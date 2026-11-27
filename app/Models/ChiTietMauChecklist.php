<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['mau_checklist_id', 'loai_chung_tu_id', 'bat_buoc', 'thu_tu'])]
class ChiTietMauChecklist extends Model
{
    protected $table = 'chi_tiet_mau_checklist';

    protected function casts(): array
    {
        return ['bat_buoc' => 'boolean'];
    }

    public function mauChecklist(): BelongsTo
    {
        return $this->belongsTo(MauChecklist::class, 'mau_checklist_id');
    }

    public function loaiChungTu(): BelongsTo
    {
        return $this->belongsTo(LoaiChungTu::class, 'loai_chung_tu_id');
    }
}