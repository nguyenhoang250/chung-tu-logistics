import { DUOI_CHO_PHEP, TOI_DA_MB, dinhDangDungLuong } from '@/constants';
import { router } from '@inertiajs/react';
import { useRef, useState } from 'react';

export default function OTaiFile({ chungTu, khoa }) {
    const input = useRef(null);
    const [keo, setKeo] = useState(false);
    const [dangTai, setDangTai] = useState(false);
    const [loi, setLoi] = useState('');

    const tep = chungTu.tep_hien_hanh?.[0];
    const khongApDung = chungTu.trang_thai === 'khong_ap_dung';

    const tai = (file) => {
        if (!file) return;

        // Kiểm tra nhanh ở trình duyệt cho đỡ phải chờ, server vẫn kiểm tra lại
        const duoi = file.name.split('.').pop().toLowerCase();
        if (!DUOI_CHO_PHEP.includes(duoi)) {
            setLoi('Chỉ nhận file PDF, ảnh (JPG, PNG), Word và Excel.');
            return;
        }
        if (file.size > TOI_DA_MB * 1024 * 1024) {
            setLoi(`File vượt quá ${TOI_DA_MB} MB.`);
            return;
        }

        setLoi('');
        router.post(
            route('chung-tu.tep.store', chungTu.id),
            { file },
            {
                forceFormData: true,
                preserveScroll: true,
                onStart: () => setDangTai(true),
                onFinish: () => setDangTai(false),
                onError: (e) => setLoi(e.file || 'Không tải lên được file.'),
            },
        );
    };

    return (
        <div className="min-w-[16rem]">
            {tep && (
                <div className="mb-1 text-xs">
                    <span className="font-medium text-gray-800">{tep.ten_goc}</span>{' '}
                    <span className="text-gray-400">
                        ({dinhDangDungLuong(tep.kich_thuoc)}, v{tep.phien_ban})
                    </span>
                    <a
                        href={route('tep.xem', tep.id)}
                        target="_blank"
                        rel="noreferrer"
                        className="ml-2 text-indigo-600 hover:underline"
                    >
                        Xem
                    </a>
                    <a href={route('tep.tai', tep.id)} className="ml-2 text-indigo-600 hover:underline">
                        Tải xuống
                    </a>
                </div>
            )}

            {!khoa && !khongApDung && (
                <div
                    role="button"
                    tabIndex={0}
                    onClick={() => input.current?.click()}
                    onKeyDown={(e) => e.key === 'Enter' && input.current?.click()}
                    onDragOver={(e) => {
                        e.preventDefault();
                        setKeo(true);
                    }}
                    onDragLeave={() => setKeo(false)}
                    onDrop={(e) => {
                        e.preventDefault();
                        setKeo(false);
                        tai(e.dataTransfer.files[0]);
                    }}
                    className={`cursor-pointer rounded border border-dashed px-3 py-2 text-center text-xs ${
                        keo ? 'border-indigo-400 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-500 hover:bg-gray-50'
                    }`}
                >
                    {dangTai
                        ? 'Đang tải lên...'
                        : tep
                          ? 'Kéo file mới vào để thay (tạo phiên bản mới)'
                          : 'Kéo file vào đây hoặc bấm để chọn'}
                    <input
                        ref={input}
                        type="file"
                        className="hidden"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                        onChange={(e) => {
                            tai(e.target.files[0]);
                            e.target.value = '';
                        }}
                    />
                </div>
            )}

            {loi && <p className="mt-1 text-xs text-red-600">{loi}</p>}
        </div>
    );
}