import KhachHangForm from '@/Components/KhachHangForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Create() {
    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Thêm khách hàng</h2>}>
            <Head title="Thêm khách hàng" />
            <div className="mx-auto max-w-2xl p-6">
                <KhachHangForm />
            </div>
        </AuthenticatedLayout>
    );
}