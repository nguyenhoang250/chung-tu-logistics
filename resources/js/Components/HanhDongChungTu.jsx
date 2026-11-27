import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function HanhDongChungTu({ chungTu, khoa }) {
    const [mo, setMo] = useState(false);
    const [lyDo, setLyDo] = useState('');
    const [loi, setLoi] = useState('');

    if (khoa) return null;

    const khongApDung = chungTu.trang_thai === 'khong_ap_dung';
    const coFile = (chungTu.tep_dinh_kem_count ?? 0) > 0;
    const onError = (e) => setLoi(Object.values(e)[0]);

    const luuKhongApDung = () =>
        router.patch(
            route('chung-tu.khong-ap-dung', chungTu.id),
            { ly_do: lyDo },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setMo(false);
                    setLyDo('');
                    setLoi('');
                },
                onError,
            },
        );

    const xoa = () => {
        if (confirm('Xóa chứng từ này?')) {
            router.delete(route('chung-tu.destroy', chungTu.id), { preserveScroll: true, onError });
        }
    };

    return (
        <div className="min-w-[12rem] text-xs">
            {mo ? (
                <div className="space-y-1">
                    <input
                        value={lyDo}
                        onChange={(e) => setLyDo(e.target.value)}
                        placeholder="Lý do (vd: lô này không cần C/O)"
                        maxLength={500}
                        className="w-full rounded border-gray-300 py-1 text-xs"
                    />
                    <div className="flex gap-2">
                        <button onClick={luuKhongApDung} className="text-indigo-600 hover:underline">
                            Lưu
                        </button>
                        <button onClick={() => setMo(false)} className="text-gray-500 hover:underline">
                            Hủy
                        </button>
                    </div>
                </div>
            ) : (
                <div className="flex gap-3">
                    {khongApDung ? (
                        <button
                            onClick={() => router.patch(route('chung-tu.ap-dung-lai', chungTu.id), {}, { preserveScroll: true, onError })}
                            className="text-indigo-600 hover:underline"
                        >
                            Áp dụng lại
                        </button>
                    ) : (
                        !coFile && (
                            <button onClick={() => setMo(true)} className="text-indigo-600 hover:underline">
                                Không áp dụng
                            </button>
                        )
                    )}
                    {!coFile && (
                        <button onClick={xoa} className="text-red-600 hover:underline">
                            Xóa
                        </button>
                    )}
                </div>
            )}
            {loi && <p className="mt-1 text-red-600">{loi}</p>}
        </div>
    );
}