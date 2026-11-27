<?php

namespace App\Http\Controllers;

use App\Http\Requests\KhachHangRequest;
use App\Models\KhachHang;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KhachHangController extends Controller
{
    public function index(Request $request)
    {
        $tuKhoa = trim((string) $request->query('q'));

        $khachHang = KhachHang::query()
            ->when($tuKhoa !== '', function ($query) use ($tuKhoa) {
                $query->where(function ($q) use ($tuKhoa) {
                    $q->where('ten', 'like', "%{$tuKhoa}%")
                        ->orWhere('ma_khach_hang', 'like', "%{$tuKhoa}%")
                        ->orWhere('ma_so_thue', 'like', "%{$tuKhoa}%");
                });
            })
            ->orderBy('ma_khach_hang')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('KhachHang/Index', [
            'khachHang' => $khachHang,
            'boLoc' => ['q' => $tuKhoa],
        ]);
    }

    public function create()
    {
        return Inertia::render('KhachHang/Create');
    }

    public function store(KhachHangRequest $request)
    {
        KhachHang::create($request->validated());

        return redirect()->route('khach-hang.index')->with('thanh_cong', 'Đã thêm khách hàng.');
    }

    public function edit(KhachHang $khachHang)
    {
        return Inertia::render('KhachHang/Edit', ['khachHang' => $khachHang]);
    }

    public function update(KhachHangRequest $request, KhachHang $khachHang)
    {
        $khachHang->update($request->validated());

        return redirect()->route('khach-hang.index')->with('thanh_cong', 'Đã cập nhật khách hàng.');
    }

    public function destroy(KhachHang $khachHang)
    {
        $khachHang->delete(); // xóa mềm

        return redirect()->route('khach-hang.index')->with('thanh_cong', 'Đã xóa khách hàng.');
    }
}