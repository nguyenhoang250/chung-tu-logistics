<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\TrangThaiBanGoc;
use App\Enums\TrangThaiChungTu;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'lo_hang_id', 'loai_chung_tu_id', 'so_chung_tu', 'ngay_phat_hanh', 'ben_phat_hanh',
    'bat_buoc', 'trang_thai', 'ly_do_khong_ap_dung', 'han_nop', 'ngay_het_han',
    'co_ban_goc', 'trang_thai_ban_goc', 'vi_tri_ban_giay', 'nguoi_tao_id', 'ghi_chu',
])]
class ChungTu extends Model
{
    use SoftDeletes;

    protected $table = 'chung_tu';

    protected function casts(): array
    {
        return [
            'bat_buoc' => 'boolean',
            'co_ban_goc' => 'boolean',
            'trang_thai' => TrangThaiChungTu::class,
            'trang_thai_ban_goc' => TrangThaiBanGoc::class,
            'ngay_phat_hanh' => 'date:Y-m-d',
            'han_nop' => 'date:Y-m-d',
            'ngay_het_han' => 'date:Y-m-d',
        ];
    }

    public function loHang(): BelongsTo
    {
        return $this->belongsTo(LoHang::class, 'lo_hang_id');
    }

    public function loaiChungTu(): BelongsTo
    {
        return $this->belongsTo(LoaiChungTu::class, 'loai_chung_tu_id');
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id');
    }
        public function tepDinhKem(): HasMany
    {
        return $this->hasMany(TepDinhKem::class, 'chung_tu_id')->orderByDesc('phien_ban');
    }

    public function tepHienHanh(): HasMany
    {
        return $this->tepDinhKem()->where('la_ban_hien_hanh', true);
    }
}