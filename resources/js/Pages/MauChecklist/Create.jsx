import MauChecklistForm from '@/Components/MauChecklistForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Create({ auth, loaiChungTu }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold text-gray-800">Thêm mẫu checklist</h2>}
        >
            <Head title="Thêm mẫu checklist" />
            <div className="mx-auto max-w-4xl py-8 sm:px-6 lg:px-8">
                <div className="bg-white p-6 shadow sm:rounded-lg">
                    <MauChecklistForm
                        loaiChungTu={loaiChungTu}
                        submitUrl={route('mau-checklist.store')}
                        method="post"
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}