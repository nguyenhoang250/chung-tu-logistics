<?php

namespace App\Models;

use App\Enums\LoaiLo;
use App\Enums\PhuongThucVanChuyen;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['ten', 'loai_lo', 'phuong_thuc_van_chuyen', 'mac_dinh', 'dang_su_dung'])]
class MauChecklist extends Model
{
    protected $table = 'mau_checklist';

    protected function casts(): array
    {
        return [
            'loai_lo' => LoaiLo::class,
            'phuong_thuc_van_chuyen' => PhuongThucVanChuyen::class,
            'mac_dinh' => 'boolean',
            'dang_su_dung' => 'boolean',
        ];
    }

    public function chiTiet(): HasMany
    {
        return $this->hasMany(ChiTietMauChecklist::class, 'mau_checklist_id')->orderBy('thu_tu');
    }
}