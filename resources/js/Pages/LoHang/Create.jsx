import LoHangForm from '@/Components/LoHangForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Create({ auth, khachHang, nhanVien, incoterms }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold text-gray-800">Thêm lô hàng</h2>}
        >
            <Head title="Thêm lô hàng" />
            <div className="mx-auto max-w-5xl py-8 sm:px-6 lg:px-8">
                <div className="bg-white p-6 shadow sm:rounded-lg">
                    <LoHangForm khachHang={khachHang} nhanVien={nhanVien} incoterms={incoterms} />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}