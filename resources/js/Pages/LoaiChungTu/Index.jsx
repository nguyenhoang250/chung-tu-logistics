import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Index({ loaiChungTu }) {
    const { flash } = usePage().props;

    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Loại chứng từ</h2>}>
            <Head title="Loại chứng từ" />
            <div className="mx-auto max-w-5xl space-y-4 p-6">
                {flash?.thanh_cong && (
                    <div className="rounded bg-green-100 p-3 text-green-800">{flash.thanh_cong}</div>
                )}
                <div className="flex justify-end">
                    <Link
                        href={route('loai-chung-tu.create')}
                        className="rounded bg-indigo-600 px-4 py-2 text-white"
                    >
                        Thêm loại chứng từ
                    </Link>
                </div>
                <div className="overflow-x-auto rounded bg-white shadow">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-gray-50 text-gray-600">
                            <tr>
                                <th className="p-3">Thứ tự</th>
                                <th className="p-3">Mã</th>
                                <th className="p-3">Tên</th>
                                <th className="p-3">Nhóm</th>
                                <th className="p-3">Trạng thái</th>
                                <th className="p-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {loaiChungTu.map((l) => (
                                <tr key={l.id} className="border-t">
                                    <td className="p-3">{l.thu_tu}</td>
                                    <td className="p-3 font-medium">{l.ma}</td>
                                    <td className="p-3">{l.ten}</td>
                                    <td className="p-3">{l.nhom}</td>
                                    <td className="p-3">
                                        {l.dang_su_dung ? (
                                            <span className="text-green-700">Đang dùng</span>
                                        ) : (
                                            <span className="text-gray-400">Đã ẩn</span>
                                        )}
                                    </td>
                                    <td className="p-3 text-right">
                                        <Link
                                            href={route('loai-chung-tu.edit', l.id)}
                                            className="text-indigo-600"
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