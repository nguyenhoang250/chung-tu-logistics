<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'chung_tu_id', 'ten_goc', 'duong_dan', 'o_dia', 'kich_thuoc', 'loai_file',
    'checksum', 'phien_ban', 'la_ban_hien_hanh', 'nguoi_tai_len_id',
])]
class TepDinhKem extends Model
{
    use SoftDeletes;

    protected $table = 'tep_dinh_kem';

    protected function casts(): array
    {
        return [
            'kich_thuoc' => 'integer',
            'phien_ban' => 'integer',
            'la_ban_hien_hanh' => 'boolean',
        ];
    }

    public function chungTu(): BelongsTo
    {
        return $this->belongsTo(ChungTu::class, 'chung_tu_id');
    }

    public function nguoiTaiLen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tai_len_id');
    }
}