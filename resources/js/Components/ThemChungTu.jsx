import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function ThemChungTu({ loHangId, loai }) {
    const [mo, setMo] = useState(false);
    const [loaiId, setLoaiId] = useState('');
    const [soChungTu, setSoChungTu] = useState('');
    const [ghiChu, setGhiChu] = useState('');
    const [dang, setDang] = useState(false);
    const [loi, setLoi] = useState('');

    const luu = () =>
        router.post(
            route('lo-hang.chung-tu.store', loHangId),
            { loai_chung_tu_id: loaiId, so_chung_tu: soChungTu, ghi_chu: ghiChu },
            {
                preserveScroll: true,
                onStart: () => setDang(true),
                onFinish: () => setDang(false),
                onSuccess: () => {
                    setMo(false);
                    setLoaiId('');
                    setSoChungTu('');
                    setGhiChu('');
                    setLoi('');
                },
                onError: (e) => setLoi(Object.values(e)[0]),
            },
        );

    if (!mo) {
        return (
            <button onClick={() => setMo(true)} className="mt-4 text-sm text-indigo-600 hover:underline">
                + Thêm chứng từ ngoài mẫu
            </button>
        );
    }

    return (
        <div className="mt-4 grid gap-2 rounded border border-gray-200 p-3 text-sm sm:grid-cols-4">
            <select value={loaiId} onChange={(e) => setLoaiId(e.target.value)} className="rounded border-gray-300 text-sm">
                <option value="">-- Chọn loại chứng từ --</option>
                {loai.map((l) => (
                    <option key={l.id} value={l.id}>
                        {l.ten}
                    </option>
                ))}
            </select>
            <input
                value={soChungTu}
                onChange={(e) => setSoChungTu(e.target.value)}
                placeholder="Số chứng từ (nếu có)"
                maxLength={100}
                className="rounded border-gray-300 text-sm"
            />
            <input
                value={ghiChu}
                onChange={(e) => setGhiChu(e.target.value)}
                placeholder="Ghi chú (vd: hải quan yêu cầu kiểm dịch)"
                maxLength={500}
                className="rounded border-gray-300 text-sm"
            />
            <div className="flex items-center gap-3">
                <button onClick={luu} disabled={dang} className="text-indigo-600 hover:underline">
                    {dang ? 'Đang thêm...' : 'Thêm'}
                </button>
                <button onClick={() => setMo(false)} className="text-gray-500 hover:underline">
                    Hủy
                </button>
            </div>
            {loi && <p className="text-xs text-red-600 sm:col-span-4">{loi}</p>}
        </div>
    );
}