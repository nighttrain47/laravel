<?php

namespace App\Http\Controllers;

use App\Models\Sach;
use App\Models\TacGia;
use App\Models\TheLoai;
use App\Models\NhaXuatBan;
use App\Models\KieuSach;
use App\Models\BoSach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



// chay lenh ./vendor/bin/sail artisan serve
// truy cap http://localhost/api/books

class BookController extends Controller
{
    //truy van sach cho store
    public function store_view()
    {
        try {
            $books = Sach::with([
                'theLoais:ma_the_loai,ten_the_loai'  // type only
            ])
            ->select([
                'ma_sach',      // needed for relationships
                'tieu_de',      // name
                'gia_tien'      // price
            ])
            ->where('so_luong', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    
            return response()->json([
                'status' => 'success',
                'data' => $books,
                'message' => 'Store books retrieved successfully'
            ], 200);
    
        } catch (\Exception $e) {
            Log::error('Store view error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving store books'
            ], 500);
        }
    }

    // yeu cau truy van toan bo sach
    public function getAllBooks(Request $request)
    {
        try {
            $query = Sach::with([
                'tacGias',
                'theLoais',
                'nhaXuatBan',
                'kieuSachs',
                'boSachs'
            ]);

            if ($request->has('tieu_de')) {
                $query->where('tieu_de', 'like', '%' . $request->tieu_de . '%');
            }

            if ($request->has('the_loai')) {
                $query->whereHas('theLoais', function($q) use ($request) {
                    $q->where('ma_the_loai', $request->the_loai);
                });
            }
            
            if ($request->has('tac_gia')) {
                $query->whereHas('tacGias', function($q) use ($request) {
                    $q->where('ma_tac_gia', $request->tac_gia);
                });
            }

            $sortField = $request->get('sort_by', 'tieu_de');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortField, $sortDirection);

            $books = $query->get()->map(function ($book) {
                $book->tieu_de = $book->so_tap 
                    ? $book->tieu_de . ' - Tập ' . $book->so_tap
                    : $book->tieu_de;
                return $book;
            });

            return response()->json([
                'status' => 'success',
                'data' => $books
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Error fetching books: ' . $e->getMessage()
            ], 500);
        }
    }
    // yeu cau truy van sach theo id
    public function getBookByID($id)
    {
        try {
            $book = Sach::with([
                'tacGias',
                'theLoais',
                'nhaXuatBan',
                'kieuSachs',
                'boSachs'
            ])->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $book
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }
    }

    // yeu cau tao don hang moi
    public function newOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tieu_de' => 'required|string|max:255',
                'tong_so_trang' => 'required|integer',
                'danh_gia' => 'nullable|numeric|between:0,5',
                'ngay_xuat_ban' => 'required|date',
                'gia_tien' => 'required|numeric',
                'so_tap' => 'nullable|numeric',
                'gioi_thieu' => 'nullable|string',
                'ma_nha_xuat_ban' => 'required|exists:nha_xuat_ban,ma_nha_xuat_ban',
                'tac_gias' => 'required|array',
                'tac_gias.*' => 'exists:tac_gia,ma_tac_gia',
                'the_loais' => 'required|array',
                'the_loais.*' => 'exists:the_loai,ma_the_loai',
                'kieu_sachs' => 'nullable|array',
                'kieu_sachs.*' => 'exists:kieu_sach,ma_kieu_sach',
                'bo_sachs' => 'nullable|array',
                'bo_sachs.*' => 'exists:bo_sach,ma_bo_sach'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $book = Sach::create($request->except(['tac_gias', 'the_loais', 'kieu_sachs', 'bo_sachs']));

            // Attach relationships
            if ($request->has('tac_gias')) {
                $book->tacGias()->attach($request->tac_gias);
            }
            if ($request->has('the_loais')) {
                $book->theLoais()->attach($request->the_loais);
            }
            if ($request->has('kieu_sachs')) {
                $book->kieuSachs()->attach($request->kieu_sachs);
            }
            if ($request->has('bo_sachs')) {
                $book->boSachs()->attach($request->bo_sachs);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Book created successfully',
                'data' => $book->load(['tacGias', 'theLoais', 'nhaXuatBan', 'kieuSachs', 'boSachs'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating book: ' . $e->getMessage()
            ], 500);
        }
    }

    // yeu cau cap nhat thong tin sach
    
}