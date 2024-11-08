<?php

// App/Models/TacGia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TacGia extends Model
{
    protected $table = 'tac_gia';
    protected $primaryKey = 'ma_tac_gia';

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'sach_tacgia', 'ma_tac_gia', 'ma_sach');
    }
}
