<?php

namespace App\Enums;

enum TrangThaiBanGoc: string
{
    case KhongCo = 'khong_co';
    case ChuaGui = 'chua_gui';
    case DaGui = 'da_gui';
    case KhachDaNhan = 'khach_da_nhan';
}