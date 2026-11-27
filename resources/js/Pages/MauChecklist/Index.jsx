import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

const LOAI_LO = { nhap: 'Nhập', xuat: 'Xuất' };
const PHUONG_THUC = { bien: 'Đường biển', hang_khong: 'Hàng không' };

export default function Index({ auth, mauChecklist }) {
    const thanhCong = usePage().props.flash?.thanh_cong;

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold text-gray-800">Mẫu checklist</h2>}
        >
            <Head title="Mẫu checklist" />
            <div className="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
                {thanhCong && (
                    <div className="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">{thanhCong}</div>
                )}

                <div className="mb-4 flex justify-end">
                    <Link
                        href={route('mau-checklist.create')}
                        className="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                    >
                        Thêm mẫu checklist
                    </Link>
                </div>

                <div className="overflow-x-auto bg-white shadow sm:rounded-lg">
                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="bg-gray-50 text-left text-gray-600">
                            <tr>
                                <th className="px-4 py-3">Tên mẫu</th>
                                <th className="px-4 py-3">Loại lô</th>
                                <th className="px-4 py-3">Phương thức</th>
                                <th className="px-4 py-3">Chứng từ (bắt buộc / tổng)</th>
                                <th className="px-4 py-3">Trạng thái</th>
                                <th className="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {mauChecklist.map((m) => (
                                <tr key={m.id}>
                                    <td className="px-4 py-3 font-medium">{m.ten}</td>
                                    <td className="px-4 py-3">{LOAI_LO[m.loai_lo] ?? m.loai_lo}</td>
                                    <td className="px-4 py-3">
                                        {PHUONG_THUC[m.phuong_thuc_van_chuyen] ?? m.phuong_thuc_van_chuyen}
                                    </td>
                                    <td className="px-4 py-3">
                                        {m.bat_buoc_count} / {m.chi_tiet_count}
                                    </td>
                                    <td className="space-x-2 px-4 py-3">
                                        {m.mac_dinh && (
                                            <span className="rounded bg-indigo-100 px-2 py-0.5 text-xs text-indigo-700">
                                                Mặc định
                                            </span>
                                        )}
                                        {!m.dang_su_dung && (
                                            <span className="rounded bg-gray-200 px-2 py-0.5 text-xs text-gray-600">
                                                Đã ẩn
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-4 py-3 text-right">
                                        <Link
                                            href={route('mau-checklist.edit', m.id)}
                                            className="text-indigo-600 hover:underline"
                                        >
                                            Sửa
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}