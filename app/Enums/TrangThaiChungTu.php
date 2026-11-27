<?php

namespace App\Enums;

enum TrangThaiChungTu: string
{
    case Thieu = 'thieu';
    case Nhap = 'nhap';
    case ChoDuyet = 'cho_duyet';
    case DaDuyet = 'da_duyet';
    case CanBoSung = 'can_bo_sung';
    case KhongApDung = 'khong_ap_dung';
}