<?php

// App/Models/TheLoai.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheLoai extends Model
{
    protected $table = 'the_loai';
    protected $primaryKey = 'ma_the_loai';

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'sach_theloai', 'ma_the_loai', 'ma_sach');
    }
}
