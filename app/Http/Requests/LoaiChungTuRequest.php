<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoaiChungTuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $loai = $this->route('loaiChungTu');

        return [
            'ma' => ['required', 'string', 'max:30', Rule::unique('loai_chung_tu', 'ma')->ignore($loai)],
            'ten' => ['required', 'string', 'max:255'],
            'nhom' => ['nullable', 'string', 'max:50'],
            'mo_ta' => ['nullable', 'string', 'max:2000'],
            'thu_tu' => ['required', 'integer', 'min:0', 'max:65535'],
            'dang_su_dung' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'unique' => ':attribute đã tồn tại.',
            'max' => ':attribute không được vượt quá :max.',
            'integer' => ':attribute phải là số nguyên.',
            'min' => ':attribute không được nhỏ hơn :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ma' => 'Mã loại chứng từ',
            'ten' => 'Tên loại chứng từ',
            'nhom' => 'Nhóm',
            'mo_ta' => 'Mô tả',
            'thu_tu' => 'Thứ tự',
            'dang_su_dung' => 'Trạng thái sử dụng',
        ];
    }
}