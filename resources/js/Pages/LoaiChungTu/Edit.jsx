import LoaiChungTuForm from '@/Components/LoaiChungTuForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Edit({ loaiChungTu }) {
    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Sửa loại chứng từ</h2>}>
            <Head title="Sửa loại chứng từ" />
            <div className="mx-auto max-w-2xl p-6">
                <LoaiChungTuForm loaiChungTu={loaiChungTu} />
            </div>
        </AuthenticatedLayout>
    );
}