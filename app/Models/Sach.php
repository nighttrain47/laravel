<?php

// App/Models/Sach.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sach extends Model
{
    protected $table = 'sach';
    protected $primaryKey = 'ma_sach';

    public $timestamps = false;
    
    protected $fillable = [
        'tieu_de',
        'tong_so_trang',
        'danh_gia',
        'ngay_xuat_ban',
        'gia_tien',
        'so_tap',
        'gioi_thieu',
        'ma_nha_xuat_ban'
    ];

    protected $casts = [
        // 'danh_gia' => 'decimal:2',
        'gia_tien' => 'decimal:2',
        'ngay_xuat_ban' => 'date',
        'so_tap' => 'float'
    ];

    public function nhaXuatBan()
    {
        return $this->belongsTo(NhaXuatBan::class, 'ma_nha_xuat_ban');
    }

    public function kieuSachs()
    {
        return $this->belongsToMany(KieuSach::class, 'sach_kieu_sach', 'ma_sach', 'ma_kieu_sach');
    }

    public function tacGias()
    {
        return $this->belongsToMany(TacGia::class, 'sach_tac_gia', 'ma_sach', 'ma_tac_gia');
    }

    public function theLoais()
    {
        return $this->belongsToMany(TheLoai::class, 'sach_the_loai', 'ma_sach', 'ma_the_loai');
    }

    public function boSachs()
    {
        return $this->belongsToMany(BoSach::class, 'sach_bo_sach', 'ma_sach', 'ma_bo_sach');
    }
}


