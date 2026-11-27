<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tep_dinh_kem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chung_tu_id')->constrained('chung_tu');
            $table->string('ten_goc');                       // tên file người dùng tải lên, chỉ để hiển thị
            $table->string('duong_dan');                     // đường dẫn thật trong đĩa lưu trữ, tên do hệ thống sinh
            $table->string('o_dia', 20)->default('local');
            $table->unsignedBigInteger('kich_thuoc');        // byte
            $table->string('loai_file', 100);                // mime
            $table->char('checksum', 64);                    // SHA-256
            $table->unsignedInteger('phien_ban')->default(1);
            $table->boolean('la_ban_hien_hanh')->default(true);
            $table->foreignId('nguoi_tai_len_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['chung_tu_id', 'phien_ban']);
            $table->index('checksum');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tep_dinh_kem');
    }
};