<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('lo_hang', function (Blueprint $table) {
        $table->id();
        $table->string('so_ho_so', 20)->unique();
        $table->foreignId('khach_hang_id')->constrained('khach_hang')->restrictOnDelete();
        $table->string('loai_lo', 20);
        $table->string('phuong_thuc_van_chuyen', 20);
        $table->string('cang_di')->nullable();
        $table->string('cang_den')->nullable();
        $table->string('so_booking')->nullable()->index();
        $table->string('so_mbl')->nullable()->index();
        $table->string('so_hbl')->nullable()->index();
        $table->string('ten_tau_chuyen_bay')->nullable();
        $table->date('etd')->nullable();
        $table->date('eta')->nullable();
        $table->string('incoterms', 10)->nullable();
        $table->string('nguoi_gui_hang')->nullable();
        $table->string('nguoi_nhan_hang')->nullable();
        $table->text('mo_ta_hang_hoa')->nullable();
        $table->date('han_xu_ly')->nullable();
        $table->date('ngay_thong_quan')->nullable();
        $table->date('ngay_hoan_tat')->nullable();
        $table->string('trang_thai', 30)->default('moi_nhan');
        $table->foreignId('nguoi_tao_id')->constrained('users')->restrictOnDelete();
        $table->foreignId('nhan_vien_phu_trach_id')->nullable()->constrained('users')->restrictOnDelete();
        $table->timestamp('khoa_luc')->nullable();
        $table->text('ghi_chu')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['trang_thai', 'han_xu_ly']);
    });

    Schema::create('chung_tu', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lo_hang_id')->constrained('lo_hang')->restrictOnDelete();
        $table->foreignId('loai_chung_tu_id')->constrained('loai_chung_tu')->restrictOnDelete();
        $table->string('so_chung_tu')->nullable()->index();
        $table->date('ngay_phat_hanh')->nullable();
        $table->string('ben_phat_hanh')->nullable();
        $table->boolean('bat_buoc')->default(false);
        $table->string('trang_thai', 30)->default('thieu');
        $table->text('ly_do_khong_ap_dung')->nullable();
        $table->date('han_nop')->nullable()->index();
        $table->date('ngay_het_han')->nullable();
        $table->boolean('co_ban_goc')->default(false);
        $table->string('trang_thai_ban_goc', 30)->default('khong_co');
        $table->string('vi_tri_ban_giay')->nullable();
        $table->foreignId('nguoi_tao_id')->nullable()->constrained('users')->restrictOnDelete();
        $table->text('ghi_chu')->nullable();
        $table->timestamps();
        $table->softDeletes();

        $table->index(['lo_hang_id', 'trang_thai']);
    });
}

public function down(): void
{
    Schema::dropIfExists('chung_tu');
    Schema::dropIfExists('lo_hang');
}
};
