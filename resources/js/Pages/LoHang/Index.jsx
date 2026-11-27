import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { LOAI_LO, PHUONG_THUC, TRANG_THAI_LO, dinhDangNgay } from '@/constants';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Index({ auth, loHang }) {
    const thanhCong = usePage().props.flash?.thanh_cong;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold text-gray-800">Lô hàng</h2>}
        >
            <Head title="Lô hàng" />
            <div className="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
                {thanhCong && (
                    <div className="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{thanhCong}</div>
                )}

                <div className="mb-4 flex justify-end">
                    <Link
                        href={route('lo-hang.create')}
                        className="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                    >
                        Thêm lô hàng
                    </Link>
                </div>

                <div className="overflow-x-auto bg-white shadow sm:rounded-lg">
                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th className="px-4 py-3">Số hồ sơ</th>
                                <th className="px-4 py-3">Khách hàng</th>
                                <th className="px-4 py-3">Loại lô</th>
                                <th className="px-4 py-3">Booking / MBL</th>
                                <th className="px-4 py-3">Hạn xử lý</th>
                                <th className="px-4 py-3">Trạng thái</th>
                                <th className="px-4 py-3">Bắt buộc / tổng</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {loHang.data.length === 0 && (
                                <tr>
                                    <td colSpan="7" className="px-4 py-6 text-center text-gray-500">
                                        Chưa có lô hàng nào.
                                    </td>
                                </tr>
                            )}
                            {loHang.data.map((l) => {
                                const tt = TRANG_THAI_LO[l.trang_thai];
                                return (
                                    <tr key={l.id}>
                                        <td className="px-4 py-3 font-medium">
                                            <Link href={route('lo-hang.show', l.id)} className="text-indigo-600 hover:underline">
                                                {l.so_ho_so}
                                            </Link>
                                        </td>
                                        <td className="px-4 py-3">{l.khach_hang?.ten}</td>
                                        <td className="px-4 py-3">
                                            {LOAI_LO[l.loai_lo]}, {PHUONG_THUC[l.phuong_thuc_van_chuyen]?.toLowerCase()}
                                        </td>
                                        <td className="px-4 py-3">{l.so_booking || l.so_mbl || ''}</td>
                                        <td className="px-4 py-3">{dinhDangNgay(l.han_xu_ly)}</td>
                                        <td className="px-4 py-3">
                                            <span className={`rounded px-2 py-0.5 text-xs ${tt?.mau}`}>{tt?.nhan}</span>
                                        </td>
                                        <td className="px-4 py-3">
                                            {l.bat_buoc_count} / {l.chung_tu_count}
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>

                {loHang.links.length > 3 && (
                    <div className="mt-4 flex flex-wrap gap-1">
                        {loHang.links.map((lk, i) =>
                            lk.url ? (
                                <Link
                                    key={i}
                                    href={lk.url}
                                    className={`rounded border px-3 py-1 text-sm ${
                                        lk.active ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: lk.label }}
                                />
                            ) : (
                                <span
                                    key={i}
                                    className="rounded border px-3 py-1 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: lk.label }}
                                />
                            ),
                        )}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}