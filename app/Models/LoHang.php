<?php

namespace App\Models;

use App\Enums\LoaiLo;
use App\Enums\PhuongThucVanChuyen;
use App\Enums\TrangThaiLo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'so_ho_so', 'khach_hang_id', 'loai_lo', 'phuong_thuc_van_chuyen',
    'cang_di', 'cang_den', 'so_booking', 'so_mbl', 'so_hbl', 'ten_tau_chuyen_bay',
    'etd', 'eta', 'incoterms', 'nguoi_gui_hang', 'nguoi_nhan_hang', 'mo_ta_hang_hoa',
    'han_xu_ly', 'ngay_thong_quan', 'ngay_hoan_tat', 'trang_thai',
    'nguoi_tao_id', 'nhan_vien_phu_trach_id', 'khoa_luc', 'ghi_chu',
])]
class LoHang extends Model
{
    use SoftDeletes;

    protected $table = 'lo_hang';

    protected function casts(): array
    {
        return [
            'loai_lo' => LoaiLo::class,
            'phuong_thuc_van_chuyen' => PhuongThucVanChuyen::class,
            'trang_thai' => TrangThaiLo::class,
            'etd' => 'date:Y-m-d',
            'eta' => 'date:Y-m-d',
            'han_xu_ly' => 'date:Y-m-d',
            'ngay_thong_quan' => 'date:Y-m-d',
            'ngay_hoan_tat' => 'date:Y-m-d',
            'khoa_luc' => 'datetime',
        ];
    }

    public function khachHang(): BelongsTo
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id');
    }

    public function nhanVienPhuTrach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nhan_vien_phu_trach_id');
    }

    public function chungTu(): HasMany
    {
        return $this->hasMany(ChungTu::class, 'lo_hang_id')->orderBy('id');
    }
}