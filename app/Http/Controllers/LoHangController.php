<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoHangRequest;
use App\Models\KhachHang;
use App\Models\LoHang;
use App\Models\User;
use App\Services\LoHangService;
use Inertia\Inertia;

class LoHangController extends Controller
{
    public function index()
    {
        return Inertia::render('LoHang/Index', [
            'loHang' => LoHang::with('khachHang:id,ma_khach_hang,ten')
                ->withCount([
                    'chungTu',
                    'chungTu as bat_buoc_count' => fn ($q) => $q->where('bat_buoc', true),
                ])
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('LoHang/Create', $this->duLieuForm());
    }

    public function store(LoHangRequest $request, LoHangService $service)
    {
        $lo = $service->tao($request->validated(), $request->user());

        return redirect()->route('lo-hang.show', $lo)
            ->with('thanh_cong', "Đã tạo lô {$lo->so_ho_so} và sinh checklist.");
    }

    public function show(LoHang $loHang)
    {
        $loHang->load([
            'khachHang:id,ma_khach_hang,ten',
            'nguoiTao:id,name',
            'nhanVienPhuTrach:id,name',
            'chungTu.loaiChungTu:id,ma,ten',
        ]);

        return Inertia::render('LoHang/Show', ['loHang' => $loHang]);
    }

    public function edit(LoHang $loHang)
    {
        $this->chanNeuKhoa($loHang);

        return Inertia::render('LoHang/Edit', ['loHang' => $loHang] + $this->duLieuForm());
    }

    public function update(LoHangRequest $request, LoHang $loHang)
    {
        $this->chanNeuKhoa($loHang);

        $loHang->update($request->validated());

        return redirect()->route('lo-hang.show', $loHang)->with('thanh_cong', 'Đã cập nhật lô hàng.');
    }

    // Xóa mềm
    public function destroy(LoHang $loHang)
    {
        $this->chanNeuKhoa($loHang);

        $loHang->delete();

        return redirect()->route('lo-hang.index')->with('thanh_cong', "Đã xóa lô {$loHang->so_ho_so}.");
    }

    private function chanNeuKhoa(LoHang $loHang): void
    {
        abort_if($loHang->khoa_luc !== null, 403, 'Lô đã hoàn tất và bị khóa.');
    }

    private function duLieuForm(): array
    {
        return [
            'khachHang' => KhachHang::orderBy('ten')->get(['id', 'ma_khach_hang', 'ten']),
            'nhanVien' => User::where('dang_hoat_dong', true)->orderBy('name')->get(['id', 'name']),
            'incoterms' => LoHangRequest::INCOTERMS,
        ];
    }
}