<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KhachHangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $khachHang = $this->route('khachHang');

        return [
            'ma_khach_hang' => ['required', 'string', 'max:30', Rule::unique('khach_hang', 'ma_khach_hang')->ignore($khachHang)],
            'ten' => ['required', 'string', 'max:255'],
            'ma_so_thue' => ['nullable', 'string', 'max:20', Rule::unique('khach_hang', 'ma_so_thue')->ignore($khachHang)],
            'dia_chi' => ['nullable', 'string', 'max:255'],
            'nguoi_lien_he' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'so_dien_thoai' => ['nullable', 'string', 'max:20'],
            'ghi_chu' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'unique' => ':attribute đã tồn tại.',
            'max' => ':attribute không được dài quá :max ký tự.',
            'email' => ':attribute không đúng định dạng.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ma_khach_hang' => 'Mã khách hàng',
            'ten' => 'Tên khách hàng',
            'ma_so_thue' => 'Mã số thuế',
            'dia_chi' => 'Địa chỉ',
            'nguoi_lien_he' => 'Người liên hệ',
            'email' => 'Email',
            'so_dien_thoai' => 'Số điện thoại',
            'ghi_chu' => 'Ghi chú',
        ];
    }
}