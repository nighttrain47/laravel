<?php

// App/Models/Sach.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sach extends Model
{
    protected $table = 'sach';
    protected $primaryKey = 'ma_sach';

    public function tacGias()
    {
        return $this->belongsToMany(TacGia::class, 'sach_tacgia', 'ma_sach', 'ma_tac_gia');
    }

    public function theLoais()
    {
        return $this->belongsToMany(TheLoai::class, 'sach_theloai', 'ma_sach', 'ma_the_loai');
    }

    public function boSachs()
    {
        return $this->belongsToMany(BoSach::class, 'sach_bo', 'ma_sach', 'ma_bo_sach');
    }

    public function kieuSachs()
    {
        return $this->belongsToMany(KieuSach::class, 'sach_kieu', 'ma_sach', 'ma_kieu_sach');
    }
}


