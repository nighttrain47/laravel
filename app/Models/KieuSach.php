<?php

// App/Models/KieuSach.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KieuSach extends Model
{
    protected $table = 'kieu_sach';
    protected $primaryKey = 'ma_kieu_sach';
    
    protected $fillable = [
        'ten_kieu_sach',
        'ma_kieu_sach'
    ];

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'sach_kieu_sach', 'ma_kieu_sach', 'ma_sach');
    }
}
