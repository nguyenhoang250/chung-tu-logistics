import MauChecklistForm from '@/Components/MauChecklistForm';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Edit({ auth, mauChecklist, loaiChungTu }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="text-xl font-semibold text-gray-800">Sửa mẫu checklist</h2>}
        >
            <Head title="Sửa mẫu checklist" />
            <div className="mx-auto max-w-4xl py-8 sm:px-6 lg:px-8">
                <div className="bg-white p-6 shadow sm:rounded-lg">
                    <MauChecklistForm
                        mau={mauChecklist}
                        loaiChungTu={loaiChungTu}
                        submitUrl={route('mau-checklist.update', mauChecklist.id)}
                        method="put"
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}