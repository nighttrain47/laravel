<?php

// App\Http\Controllers\DataExportController.php
namespace App\Http\Controllers;

use App\Models\Sach;
use Illuminate\Http\Request;

class DataExportController extends Controller
{
    public function exportAllData()
    {
        // Truy vấn tất cả dữ liệu từ bảng sach cùng các bảng liên quan
        $data = Sach::with(['tacGias', 'theLoais', 'boSachs', 'kieuSachs'])->get();

        // Trả về toàn bộ dữ liệu dưới dạng JSON
        return response()->json($data);
    }
}
