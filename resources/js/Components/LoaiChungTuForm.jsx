import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm } from '@inertiajs/react';

const CAC_TRUONG = [
    ['ma', 'Mã *'],
    ['ten', 'Tên *'],
    ['nhom', 'Nhóm'],
    ['thu_tu', 'Thứ tự *'],
];

export default function LoaiChungTuForm({ loaiChungTu = null }) {
    const { data, setData, post, put, processing, errors } = useForm({
        ma: loaiChungTu?.ma ?? '',
        ten: loaiChungTu?.ten ?? '',
        nhom: loaiChungTu?.nhom ?? '',
        mo_ta: loaiChungTu?.mo_ta ?? '',
        thu_tu: loaiChungTu?.thu_tu ?? 0,
        dang_su_dung: loaiChungTu?.dang_su_dung ?? true,
    });

    const submit = (e) => {
        e.preventDefault();
        if (loaiChungTu) {
            put(route('loai-chung-tu.update', loaiChungTu.id));
        } else {
            post(route('loai-chung-tu.store'));
        }
    };

    return (
        <form onSubmit={submit} className="space-y-4">
            {CAC_TRUONG.map(([ten, nhan]) => (
                <div key={ten}>
                    <InputLabel htmlFor={ten} value={nhan} />
                    <TextInput
                        id={ten}
                        className="mt-1 block w-full"
                        value={data[ten]}
                        onChange={(e) => setData(ten, e.target.value)}
                    />
                    <InputError message={errors[ten]} className="mt-1" />
                </div>
            ))}
            <div>
                <InputLabel htmlFor="mo_ta" value="Mô tả" />
                <textarea
                    id="mo_ta"
                    rows="3"
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    value={data.mo_ta}
                    onChange={(e) => setData('mo_ta', e.target.value)}
                />
                <InputError message={errors.mo_ta} className="mt-1" />
            </div>
            <label className="flex items-center gap-2 text-sm">
                <input
                    type="checkbox"
                    checked={data.dang_su_dung}
                    onChange={(e) => setData('dang_su_dung', e.target.checked)}
                    className="rounded border-gray-300"
                />
                Đang sử dụng
            </label>
            <div className="flex items-center gap-4">
                <PrimaryButton disabled={processing}>Lưu</PrimaryButton>
                <Link href={route('loai-chung-tu.index')} className="text-sm text-gray-600 underline">
                    Hủy
                </Link>
            </div>
        </form>
    );
}