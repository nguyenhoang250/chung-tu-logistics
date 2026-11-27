<?php

namespace App\Http\Controllers;

use App\Enums\TrangThaiChungTu;
use App\Models\ChungTu;
use App\Models\LoHang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ChungTuController extends Controller
{
    private const BAN_GOC_CO = ['chua_gui', 'da_gui', 'khach_da_nhan'];

    // Thêm chứng từ ngoài mẫu: dòng mới, không bắt buộc, cho phép trùng loại
    public function store(Request $request, LoHang $loHang)
    {
        $this->chanNeuLoKhongSua($loHang);

        $data = $request->validate([
            'loai_chung_tu_id' => ['required', Rule::exists('loai_chung_tu', 'id')->where('dang_su_dung', true)],
            'so_chung_tu' => ['nullable', 'string', 'max:100'],
            'ghi_chu' => ['nullable', 'string', 'max:500'],
        ], [
            'loai_chung_tu_id.required' => 'Hãy chọn loại chứng từ.',
            'loai_chung_tu_id.exists' => 'Loại chứng từ không hợp lệ.',
        ]);

        $ct = new ChungTu();
        $ct->forceFill([
            'lo_hang_id' => $loHang->id,
            'loai_chung_tu_id' => $data['loai_chung_tu_id'],
            'so_chung_tu' => $data['so_chung_tu'] ?? null,
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'bat_buoc' => false,
            'trang_thai' => TrangThaiChungTu::from('thieu'),
            'nguoi_tao_id' => $request->user()->id,
        ])->save();

        return back()->with('thanh_cong', 'Đã thêm chứng từ ngoài mẫu.');
    }

    public function khongApDung(Request $request, ChungTu $chungTu)
    {
        $this->chanNeuChungTuKhongSua($chungTu);

        $data = $request->validate(['ly_do' => ['required', 'string', 'min:5', 'max:500']], [
            'ly_do.required' => 'Hãy nhập lý do không áp dụng.',
            'ly_do.min' => 'Lý do quá ngắn (tối thiểu 5 ký tự).',
        ]);

        if ($chungTu->tepDinhKem()->exists()) {
            throw ValidationException::withMessages([
                'ly_do' => 'Chứng từ đã có file, không thể đánh dấu "Không áp dụng".',
            ]);
        }

        $chungTu->forceFill([
            'trang_thai' => TrangThaiChungTu::from('khong_ap_dung'),
            'ly_do_khong_ap_dung' => trim($data['ly_do']),
        ])->save();

        return back()->with('thanh_cong', 'Đã đánh dấu "Không áp dụng".');
    }

    public function apDungLai(ChungTu $chungTu)
    {
        $this->chanNeuChungTuKhongSua($chungTu);

        if ($chungTu->trang_thai->value === 'khong_ap_dung') {
            $chungTu->forceFill([
                'trang_thai' => TrangThaiChungTu::from('thieu'),
                'ly_do_khong_ap_dung' => null,
            ])->save();
        }

        return back()->with('thanh_cong', 'Đã chuyển chứng từ về trạng thái cần có.');
    }

    public function banGoc(Request $request, ChungTu $chungTu)
    {
        $this->chanNeuChungTuKhongSua($chungTu);

        if ($chungTu->trang_thai->value === 'khong_ap_dung') {
            throw ValidationException::withMessages([
                'co_ban_goc' => 'Chứng từ "Không áp dụng" không có bản gốc.',
            ]);
        }

        $data = $request->validate([
            'co_ban_goc' => ['required', 'boolean'],
            'trang_thai_ban_goc' => ['nullable', Rule::in(self::BAN_GOC_CO)],
            'vi_tri_ban_giay' => ['nullable', 'string', 'max:100'],
        ], [
            'trang_thai_ban_goc.in' => 'Trạng thái bản gốc không hợp lệ.',
            'vi_tri_ban_giay.max' => 'Vị trí bản giấy tối đa 100 ký tự.',
        ]);

        $co = (bool) $data['co_ban_goc'];

        $chungTu->forceFill([
            'co_ban_goc' => $co,
            'trang_thai_ban_goc' => $co ? ($data['trang_thai_ban_goc'] ?? 'chua_gui') : 'khong_co',
            'vi_tri_ban_giay' => $co ? ($data['vi_tri_ban_giay'] ?? null) : null,
        ])->save();

        return back()->with('thanh_cong', 'Đã cập nhật bản gốc.');
    }

    // Xóa mềm, chỉ khi chưa có file
    public function destroy(ChungTu $chungTu)
    {
        $this->chanNeuChungTuKhongSua($chungTu);

        if ($chungTu->tepDinhKem()->exists()) {
            throw ValidationException::withMessages([
                'chung_tu' => 'Chứng từ đã có file, không xóa được.',
            ]);
        }

        $chungTu->delete();

        return back()->with('thanh_cong', 'Đã xóa chứng từ.');
    }

    private function chanNeuChungTuKhongSua(ChungTu $chungTu): void
    {
        $lo = $chungTu->loHang()->withTrashed()->first();
        $this->chanNeuLoKhongSua($lo);
    }

    private function chanNeuLoKhongSua(?LoHang $lo): void
    {
        if (! $lo || $lo->trashed()) {
            throw ValidationException::withMessages(['chung_tu' => 'Lô hàng đã bị xóa.']);
        }

        if ($lo->khoa_luc !== null) {
            throw ValidationException::withMessages(['chung_tu' => 'Lô đã hoàn tất và bị khóa.']);
        }
    }
}