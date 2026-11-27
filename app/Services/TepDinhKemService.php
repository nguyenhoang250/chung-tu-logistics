<?php

namespace App\Services;

use App\Enums\TrangThaiChungTu;
use App\Models\ChungTu;
use App\Models\TepDinhKem;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class TepDinhKemService
{
    public const O_DIA = 'local';
    public const DUOI_CHO_PHEP = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'];
    public const TOI_DA_KB = 10240; // 10 MB

    public function luu(ChungTu $chungTu, UploadedFile $file, User $nguoiTai): TepDinhKem
    {
        $duoi = strtolower($file->getClientOriginalExtension());

        if (! in_array($duoi, self::DUOI_CHO_PHEP, true)) {
            throw ValidationException::withMessages([
                'file' => 'Chỉ nhận file PDF, ảnh (JPG, PNG), Word và Excel.',
            ]);
        }

        $kichThuoc = (int) $file->getSize();

        if ($kichThuoc > self::TOI_DA_KB * 1024) {
            throw ValidationException::withMessages([
                'file' => 'File vượt quá ' . (self::TOI_DA_KB / 1024) . ' MB.',
            ]);
        }

                $lo = $chungTu->loHang()->withTrashed()->first();

        if (! $lo || $lo->trashed()) {
            throw ValidationException::withMessages([
                'file' => 'Lô hàng đã bị xóa, không tải file được.',
            ]);
        }

        abort_if($lo->khoa_luc !== null, 403, 'Lô đã hoàn tất và bị khóa.');

        if ($chungTu->trang_thai->value === 'khong_ap_dung') {
            throw ValidationException::withMessages([
                'file' => 'Chứng từ đang đánh dấu "Không áp dụng", không tải file được.',
            ]);
        }

        $checksum = hash_file('sha256', $file->getRealPath());
        $loaiFile = $file->getMimeType() ?: 'application/octet-stream';
        $tenGoc = $file->getClientOriginalName();
        $thuMuc = 'chung-tu/' . $chungTu->lo_hang_id;
        $tenMoi = Str::uuid() . '.' . $duoi;
        $daLuu = null;

        try {
            return DB::transaction(function () use (
                $chungTu, $file, $nguoiTai, $checksum, $loaiFile, $tenGoc, $kichThuoc, $thuMuc, $tenMoi, &$daLuu
            ) {
                // Khóa dòng chứng từ để hai lần tải cùng lúc không trùng số phiên bản
                $ct = ChungTu::whereKey($chungTu->id)->lockForUpdate()->firstOrFail();

                // Tính cả file đã xóa mềm vì ràng buộc duy nhất (chung_tu_id, phien_ban) vẫn áp dụng
                $phienBan = (int) TepDinhKem::withTrashed()->where('chung_tu_id', $ct->id)->max('phien_ban') + 1;

                TepDinhKem::where('chung_tu_id', $ct->id)
                    ->where('la_ban_hien_hanh', true)
                    ->update(['la_ban_hien_hanh' => false]);

                $duongDan = $file->storeAs($thuMuc, $tenMoi, self::O_DIA);

                if ($duongDan === false) {
                    throw new RuntimeException('Không lưu được file.');
                }

                $daLuu = $duongDan;

                $tep = $ct->tepDinhKem()->create([
                    'ten_goc' => $tenGoc,
                    'duong_dan' => $duongDan,
                    'o_dia' => self::O_DIA,
                    'kich_thuoc' => $kichThuoc,
                    'loai_file' => $loaiFile,
                    'checksum' => $checksum,
                    'phien_ban' => $phienBan,
                    'la_ban_hien_hanh' => true,
                    'nguoi_tai_len_id' => $nguoiTai->id,
                ]);

                if ($ct->trang_thai->value === 'thieu') {
                    $ct->update(['trang_thai' => TrangThaiChungTu::from('nhap')]);
                }

                return $tep;
            });
        } catch (Throwable $e) {
            // Giao dịch lỗi thì xóa file đã ghi để không để lại file mồ côi
            if ($daLuu) {
                Storage::disk(self::O_DIA)->delete($daLuu);
            }

            throw $e;
        }
    }
}