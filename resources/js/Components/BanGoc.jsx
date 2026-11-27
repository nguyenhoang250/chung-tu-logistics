import { TRANG_THAI_BAN_GOC } from '@/constants';
import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function BanGoc({ chungTu, khoa }) {
    const [sua, setSua] = useState(false);
    const [co, setCo] = useState(!!chungTu.co_ban_goc);
    const [tt, setTt] = useState(
        chungTu.trang_thai_ban_goc && chungTu.trang_thai_ban_goc !== 'khong_co' ? chungTu.trang_thai_ban_goc : 'chua_gui',
    );
    const [viTri, setViTri] = useState(chungTu.vi_tri_ban_giay || '');
    const [dang, setDang] = useState(false);
    const [loi, setLoi] = useState('');

    if (chungTu.trang_thai === 'khong_ap_dung') {
        return <span className="text-xs text-gray-400">—</span>;
    }

    const luu = () =>
        router.patch(
            route('chung-tu.ban-goc', chungTu.id),
            { co_ban_goc: co, trang_thai_ban_goc: co ? tt : 'khong_co', vi_tri_ban_giay: co ? viTri : '' },
            {
                preserveScroll: true,
                onStart: () => setDang(true),
                onFinish: () => setDang(false),
                onSuccess: () => {
                    setSua(false);
                    setLoi('');
                },
                onError: (e) => setLoi(Object.values(e)[0]),
            },
        );

    if (!sua) {
        return (
            <div className="whitespace-nowrap text-xs">
                {chungTu.co_ban_goc ? (
                    <>
                        <span className="font-medium text-gray-800">{TRANG_THAI_BAN_GOC[chungTu.trang_thai_ban_goc]}</span>
                        {chungTu.vi_tri_ban_giay && <span className="text-gray-500"> · {chungTu.vi_tri_ban_giay}</span>}
                    </>
                ) : (
                    <span className="text-gray-400">Không có</span>
                )}
                {!khoa && (
                    <button onClick={() => setSua(true)} className="ml-2 text-indigo-600 hover:underline">
                        Sửa
                    </button>
                )}
            </div>
        );
    }

    return (
        <div className="min-w-[13rem] space-y-1 text-xs">
            <label className="flex items-center gap-1">
                <input type="checkbox" checked={co} onChange={(e) => setCo(e.target.checked)} />
                Có bản gốc
            </label>
            {co && (
                <>
                    <select value={tt} onChange={(e) => setTt(e.target.value)} className="w-full rounded border-gray-300 py-1 text-xs">
                        {['chua_gui', 'da_gui', 'khach_da_nhan'].map((k) => (
                            <option key={k} value={k}>
                                {TRANG_THAI_BAN_GOC[k]}
                            </option>
                        ))}
                    </select>
                    <input
                        value={viTri}
                        onChange={(e) => setViTri(e.target.value)}
                        placeholder="Vị trí bản giấy (vd: tủ A2)"
                        maxLength={100}
                        className="w-full rounded border-gray-300 py-1 text-xs"
                    />
                </>
            )}
            <div className="flex gap-2">
                <button onClick={luu} disabled={dang} className="text-indigo-600 hover:underline">
                    {dang ? 'Đang lưu...' : 'Lưu'}
                </button>
                <button onClick={() => setSua(false)} className="text-gray-500 hover:underline">
                    Hủy
                </button>
            </div>
            {loi && <p className="text-red-600">{loi}</p>}
        </div>
    );
}