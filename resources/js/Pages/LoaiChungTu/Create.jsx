import LoaiChungTuForm from '@/Components/LoaiChungTuForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Create() {
    return (
        <AuthenticatedLayout header={<h2 className="text-xl font-semibold">Thêm loại chứng từ</h2>}>
            <Head title="Thêm loại chứng từ" />
            <div className="mx-auto max-w-2xl p-6">
                <LoaiChungTuForm />
            </div>
        </AuthenticatedLayout>
    );
}