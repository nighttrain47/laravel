<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSachTacgiaTable extends Migration
{
    public function up()
    {
        Schema::create('sach_tacgia', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_sach'); // Đảm bảo kiểu dữ liệu khớp với bảng sach
            $table->unsignedBigInteger('ma_tac_gia'); // Tương tự với ma_tac_gia

            // Thiết lập khóa ngoại
            $table->foreign('ma_sach')->references('ma_sach')->on('sach')->onDelete('cascade');
            $table->foreign('ma_tac_gia')->references('ma_tac_gia')->on('tac_gia')->onDelete('cascade');

            // Khóa chính
            $table->primary(['ma_sach', 'ma_tac_gia']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sach_tacgia');
    }
}
