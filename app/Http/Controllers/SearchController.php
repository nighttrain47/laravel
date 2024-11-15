<?php

namespace App\Http\Controllers;

use App\Models\Sach;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query'); // Từ khóa tìm kiếm từ người dùng

        // Truy vấn dữ liệu trong bảng `sach`
        $results = Sach::where('tieu_de', 'LIKE', "%{$query}%")
            ->orWhere('gioi_thieu', 'LIKE', "%{$query}%")
            ->get();

        // Trả về kết quả tìm kiếm cho view hoặc dưới dạng JSON
        return view('search.results', compact('results', 'query'));
    }
}
