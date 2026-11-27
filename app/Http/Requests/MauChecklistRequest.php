<?php

namespace App\Http\Requests;

use App\Enums\LoaiLo;
use App\Enums\PhuongThucVanChuyen;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MauChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten' => ['required', 'string', 'max:255'],
            'loai_lo' => ['required', Rule::enum(LoaiLo::class)],
            'phuong_thuc_van_chuyen' => ['required', Rule::enum(PhuongThucVanChuyen::class)],
            'mac_dinh' => ['required', 'boolean'],
            // Mẫu mặc định thì phải đang sử dụng
            'dang_su_dung' => ['required', 'boolean', Rule::when($this->boolean('mac_dinh'), 'accepted')],
            'chi_tiet' => ['required', 'array', 'min:1'],
            'chi_tiet.*.loai_chung_tu_id' => ['required', 'integer', 'distinct', 'exists:loai_chung_tu,id'],
            'chi_tiet.*.bat_buoc' => ['required', 'boolean'],
            'chi_tiet.*.thu_tu' => ['required', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên mẫu không được để trống.',
            'ten.max' => 'Tên mẫu không được vượt quá 255 ký tự.',
            'dang_su_dung.accepted' => 'Mẫu mặc định phải đang sử dụng.',
            'chi_tiet.required' => 'Chọn ít nhất một loại chứng từ.',
            'chi_tiet.*.thu_tu.*' => 'Thứ tự phải là số nguyên từ 0 đến 65535.',
        ];
    }
}