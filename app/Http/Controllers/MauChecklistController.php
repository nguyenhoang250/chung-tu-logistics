<?php

namespace App\Http\Controllers;

use App\Http\Requests\MauChecklistRequest;
use App\Models\LoaiChungTu;
use App\Models\MauChecklist;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MauChecklistController extends Controller
{
    public function index()
    {
        return Inertia::render('MauChecklist/Index', [
            'mauChecklist' => MauChecklist::withCount([
                'chiTiet',
                'chiTiet as bat_buoc_count' => fn ($q) => $q->where('bat_buoc', true),
            ])->orderBy('loai_lo')->orderBy('phuong_thuc_van_chuyen')->orderBy('ten')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('MauChecklist/Create', [
            'loaiChungTu' => $this->danhSachLoai(),
        ]);
    }

    public function store(MauChecklistRequest $request)
    {
        $this->luu(new MauChecklist, $request->validated());

        return redirect()->route('mau-checklist.index')->with('thanh_cong', 'Đã thêm mẫu checklist.');
    }

    public function edit(MauChecklist $mauChecklist)
    {
        $mauChecklist->load('chiTiet');

        return Inertia::render('MauChecklist/Edit', [
            'mauChecklist' => $mauChecklist,
            'loaiChungTu' => $this->danhSachLoai($mauChecklist->chiTiet->pluck('loai_chung_tu_id')->all()),
        ]);
    }

    public function update(MauChecklistRequest $request, MauChecklist $mauChecklist)
    {
        $this->luu($mauChecklist, $request->validated());

        return redirect()->route('mau-checklist.index')->with('thanh_cong', 'Đã cập nhật mẫu checklist.');
    }

    // Loại đang dùng, cộng thêm loại đã ẩn nhưng đang nằm trong mẫu
    private function danhSachLoai(array $daChon = [])
    {
        return LoaiChungTu::where('dang_su_dung', true)
            ->orWhereIn('id', $daChon)
            ->orderBy('thu_tu')
            ->get(['id', 'ma', 'ten']);
    }

    private function luu(MauChecklist $mau, array $duLieu): void
    {
        DB::transaction(function () use ($mau, $duLieu) {
            $mau->fill($duLieu)->save();

            // Mỗi cặp loại lô + phương thức chỉ có một mẫu mặc định
            if ($mau->mac_dinh) {
                MauChecklist::where('loai_lo', $mau->loai_lo)
                    ->where('phuong_thuc_van_chuyen', $mau->phuong_thuc_van_chuyen)
                    ->whereKeyNot($mau->id)
                    ->update(['mac_dinh' => false]);
            }

            $mau->chiTiet()->delete();
            $mau->chiTiet()->createMany($duLieu['chi_tiet']);
        });
    }
}