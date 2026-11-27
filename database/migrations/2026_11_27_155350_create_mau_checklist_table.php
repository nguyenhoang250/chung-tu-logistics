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
    Schema::create('mau_checklist', function (Blueprint $table) {
        $table->id();
        $table->string('ten');
        $table->string('loai_lo', 20);
        $table->string('phuong_thuc_van_chuyen', 20);
        $table->boolean('mac_dinh')->default(false);
        $table->boolean('dang_su_dung')->default(true);
        $table->timestamps();
    });

    Schema::create('chi_tiet_mau_checklist', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mau_checklist_id')->constrained('mau_checklist')->cascadeOnDelete();
        $table->foreignId('loai_chung_tu_id')->constrained('loai_chung_tu')->restrictOnDelete();
        $table->boolean('bat_buoc')->default(false);
        $table->unsignedInteger('thu_tu')->default(0);
        $table->timestamps();

        $table->unique(['mau_checklist_id', 'loai_chung_tu_id'], 'ct_mau_loai_unique');
    });
}

public function down(): void
{
    Schema::dropIfExists('chi_tiet_mau_checklist');
    Schema::dropIfExists('mau_checklist');
}
};
