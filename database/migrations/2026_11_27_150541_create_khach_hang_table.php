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
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ma_khach_hang', 30)->unique();
            $table->string('ten');
            $table->string('ma_so_thue', 20)->nullable()->unique();
            $table->string('dia_chi')->nullable();
            $table->string('nguoi_lien_he')->nullable();
            $table->string('email')->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};
