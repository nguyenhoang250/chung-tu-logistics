<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoaiChungTuRequest;
use App\Models\LoaiChungTu;
use Inertia\Inertia;

class LoaiChungTuController extends Controller
{
    public function index()
    {
        return Inertia::render('LoaiChungTu/Index', [
            'loaiChungTu' => LoaiChungTu::orderBy('thu_tu')->orderBy('ten')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('LoaiChungTu/Create');
    }

    public function store(LoaiChungTuRequest $request)
    {
        LoaiChungTu::create($request->validated());

        return redirect()->route('loai-chung-tu.index')->with('thanh_cong', 'Đã thêm loại chứng từ.');
    }

    public function edit(LoaiChungTu $loaiChungTu)
    {
        return Inertia::render('LoaiChungTu/Edit', ['loaiChungTu' => $loaiChungTu]);
    }

    public function update(LoaiChungTuRequest $request, LoaiChungTu $loaiChungTu)
    {
        $loaiChungTu->update($request->validated());

        return redirect()->route('loai-chung-tu.index')->with('thanh_cong', 'Đã cập nhật loại chứng từ.');
    }
}