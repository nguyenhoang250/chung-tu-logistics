import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm } from '@inertiajs/react';

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';

export default function MauChecklistForm({ mau, loaiChungTu, submitUrl, method }) {
    const { data, setData, post, put, processing, errors } = useForm({
        ten: mau?.ten ?? '',
        loai_lo: mau?.loai_lo ?? 'nhap',
        phuong_thuc_van_chuyen: mau?.phuong_thuc_van_chuyen ?? 'bien',
        mac_dinh: mau?.mac_dinh ?? false,
        dang_su_dung: mau?.dang_su_dung ?? true,
        chi_tiet: (mau?.chi_tiet ?? []).map((c) => ({
            loai_chung_tu_id: c.loai_chung_tu_id,
            bat_buoc: c.bat_buoc,
            thu_tu: c.thu_tu,
        })),
    });

    const tim = (id) => data.chi_tiet.find((c) => c.loai_chung_tu_id === id);

    const chonLoai = (id) => {
        if (tim(id)) {
            setData('chi_tiet', data.chi_tiet.filter((c) => c.loai_chung_tu_id !== id));
        } else {
            setData('chi_tiet', [
                ...data.chi_tiet,
                { loai_chung_tu_id: id, bat_buoc: false, thu_tu: (data.chi_tiet.length + 1) * 10 },
            ]);
        }
    };

    const suaDong = (id, truong, giaTri) =>
        setData(
            'chi_tiet',
            data.chi_tiet.map((c) => (c.loai_chung_tu_id === id ? { ...c, [truong]: giaTri } : c)),
        );

    const doiMacDinh = (checked) => {
        setData((d) => ({ ...d, mac_dinh: checked, dang_su_dung: checked ? true : d.dang_su_dung }));
    };

    const submit = (e) => {
        e.preventDefault();
        (method === 'put' ? put : post)(submitUrl);
    };

    const loiChiTiet = Object.entries(errors).filter(([k]) => k.startsWith('chi_tiet'));

    return (
        <form onSubmit={submit} className="space-y-6">
            <div>
                <InputLabel htmlFor="ten" value="Tên mẫu" />
                <TextInput
                    id="ten"
                    className="mt-1 block w-full"
                    value={data.ten}
                    onChange={(e) => setData('ten', e.target.value)}
                />
                <InputError message={errors.ten} className="mt-2" />
            </div>

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel htmlFor="loai_lo" value="Loại lô" />
                    <select
                        id="loai_lo"
                        className={selectClass}
                        value={data.loai_lo}
                        onChange={(e) => setData('loai_lo', e.target.value)}
                    >
                        <option value="nhap">Nhập</option>
                        <option value="xuat">Xuất</option>
                    </select>
                    <InputError message={errors.loai_lo} className="mt-2" />
                </div>
                <div>
                    <InputLabel htmlFor="phuong_thuc" value="Phương thức vận chuyển" />
                    <select
                        id="phuong_thuc"
                        className={selectClass}
                        value={data.phuong_thuc_van_chuyen}
                        onChange={(e) => setData('phuong_thuc_van_chuyen', e.target.value)}
                    >
                        <option value="bien">Đường biển</option>
                        <option value="hang_khong">Hàng không</option>
                    </select>
                    <InputError message={errors.phuong_thuc_van_chuyen} className="mt-2" />
                </div>
            </div>

            <div className="space-y-2">
                <label className="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        className="rounded border-gray-300 text-indigo-600"
                        checked={data.mac_dinh}
                        onChange={(e) => doiMacDinh(e.target.checked)}
                    />
                    Mẫu mặc định (mỗi loại lô + phương thức chỉ có một mẫu mặc định)
                </label>
                <label className="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        className="rounded border-gray-300 text-indigo-600"
                        checked={data.dang_su_dung}
                        disabled={data.mac_dinh}
                        onChange={(e) => setData('dang_su_dung', e.target.checked)}
                    />
                    Đang sử dụng
                </label>
                <InputError message={errors.dang_su_dung} />
            </div>

            <div>
                <h3 className="mb-2 text-sm font-medium text-gray-700">
                    Loại chứng từ trong mẫu ({data.chi_tiet.length} đã chọn)
                </h3>
                <div className="divide-y rounded-md border border-gray-200">
                    {loaiChungTu.map((l) => {
                        const dong = tim(l.id);
                        return (
                            <div key={l.id} className="flex flex-wrap items-center gap-4 px-4 py-2">
                                <label className="flex min-w-[16rem] flex-1 items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        className="rounded border-gray-300 text-indigo-600"
                                        checked={!!dong}
                                        onChange={() => chonLoai(l.id)}
                                    />
                                    <span className="font-mono text-xs text-gray-500">{l.ma}</span>
                                    <span>{l.ten}</span>
                                </label>
                                <label className="flex items-center gap-1 text-sm text-gray-700">
                                    <input
                                        type="checkbox"
                                        className="rounded border-gray-300 text-indigo-600 disabled:opacity-40"
                                        disabled={!dong}
                                        checked={dong?.bat_buoc ?? false}
                                        onChange={(e) => suaDong(l.id, 'bat_buoc', e.target.checked)}
                                    />
                                    Bắt buộc
                                </label>
                                <label className="flex items-center gap-1 text-sm text-gray-700">
                                    Thứ tự
                                    <input
                                        type="number"
                                        min="0"
                                        max="65535"
                                        className="w-24 rounded-md border-gray-300 text-sm disabled:opacity-40"
                                        disabled={!dong}
                                        value={dong?.thu_tu ?? ''}
                                        onChange={(e) =>
                                            suaDong(l.id, 'thu_tu', e.target.value === '' ? '' : Number(e.target.value))
                                        }
                                    />
                                </label>
                            </div>
                        );
                    })}
                </div>
                {loiChiTiet.map(([k, m]) => (
                    <InputError key={k} message={m} className="mt-2" />
                ))}
            </div>

            <div className="flex items-center gap-4">
                <PrimaryButton disabled={processing}>Lưu</PrimaryButton>
                <Link href={route('mau-checklist.index')} className="text-sm text-gray-600 underline">
                    Hủy
                </Link>
            </div>
        </form>
    );
}