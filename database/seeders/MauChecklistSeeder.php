<?php

namespace Database\Seeders;

use App\Models\LoaiChungTu;
use App\Models\MauChecklist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MauChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $chuyenNganh = ['PHYTO', 'FUMIGATION', 'HEALTH_CERT', 'COA'];
        $batBuocBien = ['HOP_DONG', 'INVOICE', 'PACKING_LIST', 'BL', 'TO_KHAI'];
        $batBuocBay = ['HOP_DONG', 'INVOICE', 'PACKING_LIST', 'AWB', 'TO_KHAI'];

        // [tên, loại lô, phương thức, bắt buộc, không bắt buộc]
        $danhSach = [
            ['Nhập, đường biển', 'nhap', 'bien', $batBuocBien, ['CO', 'ARRIVAL_NOTICE', 'DO', 'CQ']],
            ['Nhập, hàng không', 'nhap', 'hang_khong', $batBuocBay, ['CO', ...$chuyenNganh]],
            ['Xuất, đường biển', 'xuat', 'bien', $batBuocBien, ['CO', 'BAO_HIEM', ...$chuyenNganh]],
            ['Xuất, hàng không', 'xuat', 'hang_khong', $batBuocBay, ['CO', ...$chuyenNganh]],
        ];

        DB::transaction(function () use ($danhSach) {
            foreach ($danhSach as [$ten, $loaiLo, $phuongThuc, $batBuoc, $tuyChon]) {
                $mau = MauChecklist::firstOrCreate(
                    ['loai_lo' => $loaiLo, 'phuong_thuc_van_chuyen' => $phuongThuc, 'mac_dinh' => true],
                    ['ten' => $ten, 'dang_su_dung' => true],
                );

                // Mẫu đã có thì giữ nguyên, tránh ghi đè chỉnh sửa của admin
                if (! $mau->wasRecentlyCreated) {
                    continue;
                }

                foreach ([...$batBuoc, ...$tuyChon] as $i => $ma) {
                    $mau->chiTiet()->create([
                        'loai_chung_tu_id' => LoaiChungTu::where('ma', $ma)->firstOrFail()->id,
                        'bat_buoc' => in_array($ma, $batBuoc),
                        'thu_tu' => ($i + 1) * 10,
                    ]);
                }
            }
        });
    }
}