<?php

// App/Models/BoSach.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoSach extends Model
{
    protected $table = 'bo_sach';
    protected $primaryKey = 'ma_bo_sach';

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'sach_bo', 'ma_bo_sach', 'ma_sach');
    }
}

