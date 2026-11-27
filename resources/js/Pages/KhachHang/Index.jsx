import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ khachHang, boLoc }) {
    const { flash } = usePage().props;
    const [q, setQ] = useState(boLoc.q ?? '');

    const timKiem = (e) => {
        e.preventDefault();
        router.get(route('khach-hang.index'), { q }, { preserveState: true });
    };

    const xoa = (k) => {
        if (confirm(`Xóa khách hàng "${k.ten}"?`)) {
            router.delete(route('khach-hang.destroy', k.id));
        }
    };

    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Khách hàng</h2>}>
            <Head title="Khách hàng" />
            <div className="mx-auto max-w-6xl space-y-4 p-6">
                {flash?.thanh_cong && (
                    <div className="rounded bg-green-100 p-3 text-green-800">{flash.thanh_cong}</div>
                )}

                <div className="flex items-center justify-between">
                    <form onSubmit={timKiem} className="flex gap-2">
                        <input
                            value={q}
                            onChange={(e) => setQ(e.target.value)}
                            placeholder="Tìm theo mã, tên, mã số thuế"
                            className="w-72 rounded-md border-gray-300 shadow-sm"
                        />
                        <button className="rounded bg-gray-800 px-4 py-2 text-white">Tìm</button>
                    </form>
                    <Link
                        href={route('khach-hang.create')}
                        className="rounded bg-indigo-600 px-4 py-2 text-white"
                    >
                        Thêm khách hàng
                    </Link>
                </div>

                <div className="overflow-x-auto rounded bg-white shadow">
                    <table className="w-full text-left text-sm">
                        <thead className="bg-gray-50 text-gray-600">
                            <tr>
                                <th className="p-3">Mã</th>
                                <th className="p-3">Tên</th>
                                <th className="p-3">Mã số thuế</th>
                                <th className="p-3">Liên hệ</th>
                                <th className="p-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {khachHang.data.length === 0 && (
                                <tr>
                                    <td colSpan="5" className="p-6 text-center text-gray-500">
                                        Chưa có khách hàng nào.
                                    </td>
                                </tr>
                            )}
                            {khachHang.data.map((k) => (
                                <tr key={k.id} className="border-t">
                                    <td className="p-3 font-medium">{k.ma_khach_hang}</td>
                                    <td className="p-3">{k.ten}</td>
                                    <td className="p-3">{k.ma_so_thue}</td>
                                    <td className="p-3">
                                        {k.nguoi_lien_he}
                                        {k.so_dien_thoai && ` · ${k.so_dien_thoai}`}
                                    </td>
                                    <td className="space-x-3 p-3 text-right">
                                        <Link
                                            href={route('khach-hang.edit', k.id)}
                                            className="text-indigo-600"
                                        >
                                            Sửa
                                        </Link>
                                        <button onClick={() => xoa(k)} className="text-red-600">
                                            Xóa
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-wrap gap-1">
                    {khachHang.links.map((link, i) =>
                        link.url ? (
                            <Link
                                key={i}
                                href={link.url}
                                preserveState
                                className={`rounded border px-3 py-1 text-sm ${link.active ? 'bg-gray-800 text-white' : 'bg-white'}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span
                                key={i}
                                className="rounded border px-3 py-1 text-sm text-gray-400"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ),
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}