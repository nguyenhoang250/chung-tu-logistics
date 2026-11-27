<?php

namespace Database\Seeders;

use App\Models\LoaiChungTu;
use Illuminate\Database\Seeder;

class LoaiChungTuSeeder extends Seeder
{
    public function run(): void
    {
        $danhSach = [
            ['HOP_DONG', 'Hợp đồng mua bán', 'thuong_mai'],
            ['INVOICE', 'Commercial Invoice', 'thuong_mai'],
            ['HOA_DON_GTGT', 'Hóa đơn GTGT', 'thuong_mai'],
            ['PACKING_LIST', 'Packing List', 'thuong_mai'],
            ['BL', 'Bill of Lading', 'van_tai'],
            ['AWB', 'Air Waybill', 'van_tai'],
            ['TO_KHAI', 'Tờ khai hải quan', 'hai_quan'],
            ['CO', 'Certificate of Origin (C/O)', 'xuat_xu'],
            ['CQ', 'Giấy chứng nhận chất lượng (C/Q)', 'chat_luong'],
            ['ARRIVAL_NOTICE', 'Arrival Notice', 'van_tai'],
            ['DO', 'Delivery Order', 'van_tai'],
            ['PHYTO', 'Phyto (kiểm dịch thực vật)', 'chuyen_nganh'],
            ['FUMIGATION', 'Fumigation (hun trùng)', 'chuyen_nganh'],
            ['HEALTH_CERT', 'Health Certificate', 'chuyen_nganh'],
            ['COA', 'COA', 'chuyen_nganh'],
            ['BAO_HIEM', 'Bảo hiểm hàng hóa', 'bao_hiem'],
        ];

        foreach ($danhSach as $i => [$ma, $ten, $nhom]) {
            LoaiChungTu::updateOrCreate(
                ['ma' => $ma],
                ['ten' => $ten, 'nhom' => $nhom, 'thu_tu' => ($i + 1) * 10, 'dang_su_dung' => true],
            );
        }
    }
}