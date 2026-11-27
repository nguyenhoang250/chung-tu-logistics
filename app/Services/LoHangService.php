<?php

namespace App\Services;

use App\Enums\TrangThaiChungTu;
use App\Models\LoHang;
use App\Models\MauChecklist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoHangService
{
    public function tao(array $duLieu, User $nguoiTao): LoHang
    {
        $mau = MauChecklist::where('loai_lo', $duLieu['loai_lo'])
            ->where('phuong_thuc_van_chuyen', $duLieu['phuong_thuc_van_chuyen'])
            ->where('mac_dinh', true)
            ->where('dang_su_dung', true)
            ->with('chiTiet')
            ->first();

        if (! $mau) {
            throw ValidationException::withMessages([
                'loai_lo' => 'Chưa có mẫu checklist mặc định cho loại lô và phương thức này.',
            ]);
        }

        return DB::transaction(function () use ($duLieu, $nguoiTao, $mau) {
            $lo = new LoHang($duLieu);
            $lo->so_ho_so = $this->sinhSoHoSo();
            $lo->nguoi_tao_id = $nguoiTao->id;
            $lo->save();

            // Cờ bat_buoc được sao chép từ mẫu lúc này, đổi mẫu sau không ảnh hưởng lô cũ
            $lo->chungTu()->createMany(
                $mau->chiTiet->map(fn ($ct) => [
                    'loai_chung_tu_id' => $ct->loai_chung_tu_id,
                    'bat_buoc' => $ct->bat_buoc,
                    'trang_thai' => TrangThaiChungTu::Thieu,
                    'nguoi_tao_id' => $nguoiTao->id,
                ])->all()
            );

            return $lo;
        });
    }

    // Dạng LH-2026-00001. Phải gọi trong transaction. Tính cả lô đã xóa mềm để không dùng lại số cũ.
    private function sinhSoHoSo(): string
    {
        $tienTo = 'LH-' . now()->year . '-';

        $cuoi = LoHang::withTrashed()
            ->where('so_ho_so', 'like', $tienTo . '%')
            ->lockForUpdate()
            ->orderByDesc('so_ho_so')
            ->value('so_ho_so');

        $so = $cuoi ? ((int) substr($cuoi, strlen($tienTo))) + 1 : 1;

        return $tienTo . str_pad((string) $so, 5, '0', STR_PAD_LEFT);
    }
}