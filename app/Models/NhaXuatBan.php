<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaXuatBan extends Model
{
    public $timestamps = false;
    protected $table = 'nha_xuat_ban';
    protected $primaryKey = 'ma_nha_xuat_ban';
    
    protected $fillable = [
        'ten_nha_xuat_ban',
        'ma_nha_xuat_ban'
    ];

    public function sachs()
    {
        return $this->hasMany(Sach::class, 'ma_nha_xuat_ban');
    }
}