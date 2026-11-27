<?php

namespace App\Http\Controllers;

use App\Models\ChungTu;
use App\Models\TepDinhKem;
use App\Services\TepDinhKemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TepDinhKemController extends Controller
{
    // Chỉ các loại này mới mở trực tiếp trong trình duyệt, còn lại luôn tải xuống
    private const XEM_TRUC_TIEP = ['application/pdf', 'image/jpeg', 'image/png'];

    public function store(Request $request, ChungTu $chungTu, TepDinhKemService $service)
    {
        $request->validate(['file' => ['required', 'file']], [
            'file.required' => 'Hãy chọn file để tải lên (hoặc file vượt giới hạn của máy chủ).',
            'file.file' => 'Không tải lên được file.',
            'file.uploaded' => 'Tải file thất bại, file có thể vượt giới hạn của máy chủ.',
        ]);

        $tep = $service->luu($chungTu, $request->file('file'), $request->user());

        return back()->with('thanh_cong', "Đã tải lên {$tep->ten_goc} (phiên bản {$tep->phien_ban}).");
    }

    public function xem(TepDinhKem $tepDinhKem)
    {
        $disk = $this->odia($tepDinhKem);

        if (! in_array($tepDinhKem->loai_file, self::XEM_TRUC_TIEP, true)) {
            return $this->tai($tepDinhKem);
        }

        return $disk->response($tepDinhKem->duong_dan, $tepDinhKem->ten_goc, [
            'Content-Type' => $tepDinhKem->loai_file,
            'X-Content-Type-Options' => 'nosniff',
        ], 'inline');
    }

    public function tai(TepDinhKem $tepDinhKem)
    {
        $disk = $this->odia($tepDinhKem);

        return $disk->download($tepDinhKem->duong_dan, $tepDinhKem->ten_goc);
    }

    private function odia(TepDinhKem $tep)
    {
        $disk = Storage::disk($tep->o_dia);
        abort_unless($disk->exists($tep->duong_dan), 404, 'Không tìm thấy file trên máy chủ.');

        return $disk;
    }
}