<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ma', 'ten', 'nhom', 'mo_ta', 'thu_tu', 'dang_su_dung'])]
class LoaiChungTu extends Model
{
    protected $table = 'loai_chung_tu';

    protected function casts(): array
    {
        return ['dang_su_dung' => 'boolean'];
    }
}