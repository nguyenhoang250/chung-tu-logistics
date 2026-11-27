<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['ma_khach_hang', 'ten', 'ma_so_thue', 'dia_chi', 'nguoi_lien_he', 'email', 'so_dien_thoai', 'ghi_chu'])]
class KhachHang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'khach_hang';
}