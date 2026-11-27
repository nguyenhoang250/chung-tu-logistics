<?php

namespace App\Http\Requests;

use App\Enums\LoaiLo;
use App\Enums\PhuongThucVanChuyen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoHangRequest extends FormRequest
{
    public const INCOTERMS = ['EXW', 'FCA', 'FAS', 'FOB', 'CFR', 'CIF', 'CPT', 'CIP', 'DAP', 'DPU', 'DDP'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'khach_hang_id' => ['required', 'integer', Rule::exists('khach_hang', 'id')->whereNull('deleted_at')],
            'cang_di' => ['nullable', 'string', 'max:255'],
            'cang_den' => ['nullable', 'string', 'max:255'],
            'so_booking' => ['nullable', 'string', 'max:255'],
            'so_mbl' => ['nullable', 'string', 'max:255'],
            'so_hbl' => ['nullable', 'string', 'max:255'],
            'ten_tau_chuyen_bay' => ['nullable', 'string', 'max:255'],
            'etd' => ['nullable', 'date'],
            'eta' => ['nullable', 'date', 'after_or_equal:etd'],
            'incoterms' => ['nullable', Rule::in(self::INCOTERMS)],
            'nguoi_gui_hang' => ['nullable', 'string', 'max:255'],
            'nguoi_nhan_hang' => ['nullable', 'string', 'max:255'],
            'mo_ta_hang_hoa' => ['nullable', 'string', 'max:2000'],
            'han_xu_ly' => ['nullable', 'date'],
            'nhan_vien_phu_trach_id' => ['nullable', 'integer', Rule::exists('users', 'id')->whereNull('deleted_at')],
            'ghi_chu' => ['nullable', 'string', 'max:2000'],
        ];

        // Loại lô và phương thức chỉ chọn lúc tạo, vì checklist đã sinh theo hai giá trị này
        if ($this->isMethod('post')) {
            $rules['loai_lo'] = ['required', Rule::enum(LoaiLo::class)];
            $rules['phuong_thuc_van_chuyen'] = ['required', Rule::enum(PhuongThucVanChuyen::class)];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'khach_hang_id.required' => 'Hãy chọn khách hàng.',
            'khach_hang_id.exists' => 'Khách hàng không hợp lệ.',
            'loai_lo.required' => 'Hãy chọn loại lô.',
            'phuong_thuc_van_chuyen.required' => 'Hãy chọn phương thức vận chuyển.',
            'eta.after_or_equal' => 'ETA không được trước ETD.',
            'incoterms.in' => 'Incoterms không hợp lệ.',
            '*.max' => 'Không được vượt quá :max ký tự.',
            '*.date' => 'Ngày không hợp lệ.',
        ];
    }
}