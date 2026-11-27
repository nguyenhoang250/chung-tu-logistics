import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { LOAI_LO, PHUONG_THUC } from '@/constants';
import { Link, useForm } from '@inertiajs/react';

const selectClass =
    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500';

function Truong({ nhan, loi, children }) {
    return (
        <div>
            <InputLabel value={nhan} />
            {children}
            <InputError message={loi} className="mt-2" />
        </div>
    );
}

export default function LoHangForm({ lo, khachHang, nhanVien, incoterms }) {
    const sua = !!lo;

    const { data, setData, post, put, processing, errors } = useForm({
        ...(sua ? {} : { loai_lo: 'nhap', phuong_thuc_van_chuyen: 'bien' }),
        khach_hang_id: lo?.khach_hang_id ?? '',
        cang_di: lo?.cang_di ?? '',
        cang_den: lo?.cang_den ?? '',
        so_booking: lo?.so_booking ?? '',
        so_mbl: lo?.so_mbl ?? '',
        so_hbl: lo?.so_hbl ?? '',
        ten_tau_chuyen_bay: lo?.ten_tau_chuyen_bay ?? '',
        etd: lo?.etd ?? '',
        eta: lo?.eta ?? '',
        incoterms: lo?.incoterms ?? '',
        nguoi_gui_hang: lo?.nguoi_gui_hang ?? '',
        nguoi_nhan_hang: lo?.nguoi_nhan_hang ?? '',
        mo_ta_hang_hoa: lo?.mo_ta_hang_hoa ?? '',
        han_xu_ly: lo?.han_xu_ly ?? '',
        nhan_vien_phu_trach_id: lo?.nhan_vien_phu_trach_id ?? '',
        ghi_chu: lo?.ghi_chu ?? '',
    });

    const submit = (e) => {
        e.preventDefault();
        if (sua) put(route('lo-hang.update', lo.id));
        else post(route('lo-hang.store'));
    };

    const o = (ten) => ({
        value: data[ten],
        onChange: (e) => setData(ten, e.target.value),
    });

    const text = (ten, nhan, kieu = 'text') => (
        <Truong nhan={nhan} loi={errors[ten]}>
            <TextInput type={kieu} className="mt-1 block w-full" {...o(ten)} />
        </Truong>
    );

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <Truong nhan="Khách hàng" loi={errors.khach_hang_id}>
                    <select className={selectClass} {...o('khach_hang_id')}>
                        <option value="">-- Chọn khách hàng --</option>
                        {khachHang.map((k) => (
                            <option key={k.id} value={k.id}>
                                {k.ma_khach_hang} - {k.ten}
                            </option>
                        ))}
                    </select>
                </Truong>

                {sua ? (
                    <>
                        <Truong nhan="Loại lô">
                            <p className="mt-2 text-sm text-gray-700">{LOAI_LO[lo.loai_lo]}</p>
                        </Truong>
                        <Truong nhan="Phương thức vận chuyển">
                            <p className="mt-2 text-sm text-gray-700">{PHUONG_THUC[lo.phuong_thuc_van_chuyen]}</p>
                        </Truong>
                    </>
                ) : (
                    <>
                        <Truong nhan="Loại lô" loi={errors.loai_lo}>
                            <select className={selectClass} {...o('loai_lo')}>
                                {Object.entries(LOAI_LO).map(([v, n]) => (
                                    <option key={v} value={v}>{n}</option>
                                ))}
                            </select>
                        </Truong>
                        <Truong nhan="Phương thức vận chuyển" loi={errors.phuong_thuc_van_chuyen}>
                            <select className={selectClass} {...o('phuong_thuc_van_chuyen')}>
                                {Object.entries(PHUONG_THUC).map(([v, n]) => (
                                    <option key={v} value={v}>{n}</option>
                                ))}
                            </select>
                        </Truong>
                    </>
                )}
            </div>

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-3">
                {text('cang_di', 'Cảng đi')}
                {text('cang_den', 'Cảng đến')}
                {text('ten_tau_chuyen_bay', 'Tàu / chuyến bay')}
                {text('so_booking', 'Số booking')}
                {text('so_mbl', 'Số MBL')}
                {text('so_hbl', 'Số HBL')}
                {text('etd', 'ETD', 'date')}
                {text('eta', 'ETA', 'date')}
                <Truong nhan="Incoterms" loi={errors.incoterms}>
                    <select className={selectClass} {...o('incoterms')}>
                        <option value="">--</option>
                        {incoterms.map((i) => (
                            <option key={i} value={i}>{i}</option>
                        ))}
                    </select>
                </Truong>
                {text('nguoi_gui_hang', 'Người gửi hàng')}
                {text('nguoi_nhan_hang', 'Người nhận hàng')}
                {text('han_xu_ly', 'Hạn xử lý', 'date')}
                <Truong nhan="Nhân viên phụ trách" loi={errors.nhan_vien_phu_trach_id}>
                    <select className={selectClass} {...o('nhan_vien_phu_trach_id')}>
                        <option value="">--</option>
                        {nhanVien.map((n) => (
                            <option key={n.id} value={n.id}>{n.name}</option>
                        ))}
                    </select>
                </Truong>
            </div>

            <Truong nhan="Mô tả hàng hóa" loi={errors.mo_ta_hang_hoa}>
                <textarea rows="3" className={selectClass} {...o('mo_ta_hang_hoa')} />
            </Truong>
            <Truong nhan="Ghi chú" loi={errors.ghi_chu}>
                <textarea rows="3" className={selectClass} {...o('ghi_chu')} />
            </Truong>

            <div className="flex items-center gap-4">
                <PrimaryButton disabled={processing}>{sua ? 'Lưu thay đổi' : 'Tạo lô và sinh checklist'}</PrimaryButton>
                <Link
                    href={sua ? route('lo-hang.show', lo.id) : route('lo-hang.index')}
                    className="text-sm text-gray-600 underline"
                >
                    Hủy
                </Link>
            </div>
        </form>
    );
}