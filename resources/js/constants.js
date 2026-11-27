export const LOAI_LO = { nhap: 'Nhập', xuat: 'Xuất' };

export const PHUONG_THUC = { bien: 'Đường biển', hang_khong: 'Hàng không' };

export const TRANG_THAI_LO = {
    moi_nhan: { nhan: 'Mới nhận', mau: 'bg-gray-100 text-gray-700' },
    dang_lam_chung_tu: { nhan: 'Đang làm chứng từ', mau: 'bg-blue-100 text-blue-700' },
    da_khai_hai_quan: { nhan: 'Đã khai hải quan', mau: 'bg-amber-100 text-amber-700' },
    thong_quan: { nhan: 'Thông quan', mau: 'bg-teal-100 text-teal-700' },
    hoan_tat: { nhan: 'Hoàn tất', mau: 'bg-green-100 text-green-700' },
};

export const TRANG_THAI_CHUNG_TU = {
    thieu: { nhan: 'Thiếu', mau: 'bg-red-100 text-red-700' },
    nhap: { nhan: 'Nháp', mau: 'bg-gray-100 text-gray-700' },
    cho_duyet: { nhan: 'Chờ duyệt', mau: 'bg-amber-100 text-amber-700' },
    da_duyet: { nhan: 'Đã duyệt', mau: 'bg-green-100 text-green-700' },
    can_bo_sung: { nhan: 'Cần bổ sung', mau: 'bg-orange-100 text-orange-700' },
    khong_ap_dung: { nhan: 'Không áp dụng', mau: 'bg-slate-100 text-slate-600' },
};

// 2026-11-27 thành 27/11/2026
export function dinhDangNgay(s) {
    if (!s) return '';
    const [y, m, d] = s.slice(0, 10).split('-');
    return `${d}/${m}/${y}`;
}