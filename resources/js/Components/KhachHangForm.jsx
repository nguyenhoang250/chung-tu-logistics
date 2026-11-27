import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm } from '@inertiajs/react';

const CAC_TRUONG = [
    ['ma_khach_hang', 'Mã khách hàng *'],
    ['ten', 'Tên khách hàng *'],
    ['ma_so_thue', 'Mã số thuế'],
    ['dia_chi', 'Địa chỉ'],
    ['nguoi_lien_he', 'Người liên hệ'],
    ['email', 'Email'],
    ['so_dien_thoai', 'Số điện thoại'],
];

export default function KhachHangForm({ khachHang = null }) {
    const { data, setData, post, put, processing, errors } = useForm({
        ma_khach_hang: khachHang?.ma_khach_hang ?? '',
        ten: khachHang?.ten ?? '',
        ma_so_thue: khachHang?.ma_so_thue ?? '',
        dia_chi: khachHang?.dia_chi ?? '',
        nguoi_lien_he: khachHang?.nguoi_lien_he ?? '',
        email: khachHang?.email ?? '',
        so_dien_thoai: khachHang?.so_dien_thoai ?? '',
        ghi_chu: khachHang?.ghi_chu ?? '',
    });

    const submit = (e) => {
        e.preventDefault();
        if (khachHang) {
            put(route('khach-hang.update', khachHang.id));
        } else {
            post(route('khach-hang.store'));
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
                <InputLabel htmlFor="ghi_chu" value="Ghi chú" />
                <textarea
                    id="ghi_chu"
                    rows="3"
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    value={data.ghi_chu}
                    onChange={(e) => setData('ghi_chu', e.target.value)}
                />
                <InputError message={errors.ghi_chu} className="mt-1" />
            </div>
            <div className="flex items-center gap-4">
                <PrimaryButton disabled={processing}>Lưu</PrimaryButton>
                <Link href={route('khach-hang.index')} className="text-sm text-gray-600 underline">
                    Hủy
                </Link>
            </div>
        </form>
    );
}