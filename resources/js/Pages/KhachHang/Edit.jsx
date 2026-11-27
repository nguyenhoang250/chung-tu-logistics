import KhachHangForm from '@/Components/KhachHangForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Edit({ khachHang }) {
    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Sửa khách hàng</h2>}>
            <Head title="Sửa khách hàng" />
            <div className="mx-auto max-w-2xl p-6">
                <KhachHangForm khachHang={khachHang} />
            </div>
        </AuthenticatedLayout>
    );
}