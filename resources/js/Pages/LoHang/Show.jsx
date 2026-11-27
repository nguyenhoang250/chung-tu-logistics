import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {
    LOAI_LO,
    PHUONG_THUC,
    TRANG_THAI_CHUNG_TU,
    TRANG_THAI_LO,
    dinhDangNgay,
} from '@/constants';
import { Head, Link, router, usePage } from '@inertiajs/react';

function Dong({ nhan, giaTri }) {
    return (
        <div>
            <dt className="text-xs uppercase text-gray-500">{nhan}</dt>
            <dd className="mt-0.5 text-sm text-gray-900">{giaTri || '—'}</dd>
        </div>
    );
}

export default function Show({ auth, loHang: l }) {
    const thanhCong = usePage().props.flash?.thanh_cong;
    const tt = TRANG_THAI_LO[l.trang_thai];

    const batBuoc = l.chung_tu.filter((c) => c.bat_buoc && c.trang_thai !== 'khong_ap_dung');
    const du = batBuoc.filter((c) => c.trang_thai !== 'thieu').length;
    const phanTram = batBuoc.length ? Math.round((du / batBuoc.length) * 100) : 0;

    const xoa = () => {
        if (confirm(`Xóa lô ${l.so_ho_so}?`)) router.delete(route('lo-hang.destroy', l.id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold text-gray-800">
                        {l.so_ho_so}{' '}
                        <span className={`ml-2 rounded px-2 py-0.5 text-xs font-normal ${tt?.mau}`}>{tt?.nhan}</span>
                    </h2>
                    <div className="flex items-center gap-4 text-sm">
                        <Link href={route('lo-hang.index')} className="text-gray-600 hover:underline">
                            Danh sách
                        </Link>
                        {!l.khoa_luc && (
                            <>
                                <Link href={route('lo-hang.edit', l.id)} className="text-indigo-600 hover:underline">
                                    Sửa
                                </Link>
                                <button onClick={xoa} className="text-red-600 hover:underline">
                                    Xóa
                                </button>
                            </>
                        )}
                    </div>
                </div>
            }
        >
            <Head title={l.so_ho_so} />
            <div className="mx-auto max-w-7xl space-y-6 py-8 sm:px-6 lg:px-8">
                {thanhCong && (
                    <div className="rounded-md bg-green-50 p-3 text-sm text-green-700">{thanhCong}</div>
                )}

                <div className="bg-white p-6 shadow sm:rounded-lg">
                    <dl className="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <Dong nhan="Khách hàng" giaTri={l.khach_hang?.ten} />
                        <Dong nhan="Loại lô" giaTri={`${LOAI_LO[l.loai_lo]}, ${PHUONG_THUC[l.phuong_thuc_van_chuyen]}`} />
                        <Dong nhan="Cảng đi" giaTri={l.cang_di} />
                        <Dong nhan="Cảng đến" giaTri={l.cang_den} />
                        <Dong nhan="Số booking" giaTri={l.so_booking} />
                        <Dong nhan="Số MBL" giaTri={l.so_mbl} />
                        <Dong nhan="Số HBL" giaTri={l.so_hbl} />
                        <Dong nhan="Tàu / chuyến bay" giaTri={l.ten_tau_chuyen_bay} />
                        <Dong nhan="ETD" giaTri={dinhDangNgay(l.etd)} />
                        <Dong nhan="ETA" giaTri={dinhDangNgay(l.eta)} />
                        <Dong nhan="Incoterms" giaTri={l.incoterms} />
                        <Dong nhan="Hạn xử lý" giaTri={dinhDangNgay(l.han_xu_ly)} />
                        <Dong nhan="Người gửi hàng" giaTri={l.nguoi_gui_hang} />
                        <Dong nhan="Người nhận hàng" giaTri={l.nguoi_nhan_hang} />
                        <Dong nhan="Phụ trách" giaTri={l.nhan_vien_phu_trach?.name} />
                        <Dong nhan="Người tạo" giaTri={l.nguoi_tao?.name} />
                    </dl>
                    {l.mo_ta_hang_hoa && <p className="mt-4 text-sm text-gray-700">{l.mo_ta_hang_hoa}</p>}
                    {l.ghi_chu && <p className="mt-2 text-sm italic text-gray-500">{l.ghi_chu}</p>}
                </div>

                <div className="bg-white p-6 shadow sm:rounded-lg">
                    <div className="mb-3 flex items-center justify-between">
                        <h3 className="font-semibold text-gray-800">Checklist chứng từ</h3>
                        <span className="text-sm text-gray-600">
                            Chứng từ bắt buộc: {du}/{batBuoc.length}
                        </span>
                    </div>
                    <div className="mb-4 h-2 overflow-hidden rounded bg-gray-100">
                        <div className="h-full bg-green-500" style={{ width: `${phanTram}%` }} />
                    </div>

                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="text-left text-gray-600">
                            <tr>
                                <th className="py-2 pr-4">Mã</th>
                                <th className="py-2 pr-4">Loại chứng từ</th>
                                <th className="py-2 pr-4">Bắt buộc</th>
                                <th className="py-2 pr-4">Số chứng từ</th>
                                <th className="py-2 pr-4">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {l.chung_tu.map((c) => {
                                const t = TRANG_THAI_CHUNG_TU[c.trang_thai];
                                return (
                                    <tr key={c.id}>
                                        <td className="py-2 pr-4 font-mono text-xs text-gray-500">{c.loai_chung_tu?.ma}</td>
                                        <td className="py-2 pr-4">{c.loai_chung_tu?.ten}</td>
                                        <td className="py-2 pr-4">{c.bat_buoc ? 'Có' : ''}</td>
                                        <td className="py-2 pr-4">{c.so_chung_tu}</td>
                                        <td className="py-2 pr-4">
                                            <span className={`rounded px-2 py-0.5 text-xs ${t?.mau}`}>{t?.nhan}</span>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                    <p className="mt-3 text-xs text-gray-400">Tải file vào từng dòng sẽ làm ở Mốc 3.</p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}