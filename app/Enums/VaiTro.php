<?php

namespace App\Enums;

enum VaiTro: string
{
    case Admin = 'admin';
    case NhanVien = 'nhan_vien';
    // Bản 2 sẽ thêm: case TruongNhom = 'truong_nhom';

    public function nhan(): string
    {
        return match ($this) {
            self::Admin => 'Quản trị viên',
            self::NhanVien => 'Nhân viên chứng từ',
        };
    }
}