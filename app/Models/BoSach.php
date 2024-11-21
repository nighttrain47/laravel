<?php

// App/Models/BoSach.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoSach extends Model
{
    public $timestamps = false;
    protected $table = 'bo_sach';
    protected $primaryKey = 'ma_bo_sach';
    
    protected $fillable = [
        'ten_bo_sach',
        'ma_bo_sach'
    ];

    public function sachs()
    {
        return $this->belongsToMany(Sach::class, 'sach_bo_sach', 'ma_bo_sach', 'ma_sach');
    }
}

