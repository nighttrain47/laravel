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
    // yeu cau truy van toan bo sach
    public function index(Request $request)
    {
        // cac bang lien quan
        try {
            $query = Sach::with([
                'tacGias',
                'theLoais',
                'nhaXuatBan',
                'kieuSachs',
                'boSachs'
            ]);

            // loc sach theo tieu de
            if ($request->has('tieu_de')) {
                $query->where('tieu_de', 'like', '%' . $request->tieu_de . '%');
            }

            // loc sach theo nha xuat ban
            if ($request->has('the_loai')) {
                $query->whereHas('theLoais', function($q) use ($request) {
                    $q->where('ma_the_loai', $request->the_loai);
                });
            }
            
            // loc sach theo tac gia
            if ($request->has('tac_gia')) {
                $query->whereHas('tacGias', function($q) use ($request) {
                    $q->where('ma_tac_gia', $request->tac_gia);
                });
            }

            // sap xep
            $sortField = $request->get('sort_by', 'tieu_de');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortField, $sortDirection);

            // phan trang
            $perPage = $request->get('per_page', 10);
            $books = $query->paginate($perPage);

            // tra ve ket qua
            return response()->json([
                'status' => 'success',
                'data' => $books
            ]);
            // bat loi neu co
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
    public function updateBook(Request $request, $id)
    {
        try {
            $book = Sach::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'tieu_de' => 'sometimes|string|max:255',
                'tong_so_trang' => 'sometimes|integer',
                'danh_gia' => 'nullable|numeric|between:0,5',
                'ngay_xuat_ban' => 'sometimes|date',
                'gia_tien' => 'sometimes|numeric',
                'so_tap' => 'nullable|numeric',
                'gioi_thieu' => 'nullable|string',
                'ma_nha_xuat_ban' => 'sometimes|exists:nha_xuat_ban,ma_nha_xuat_ban',
                'tac_gias' => 'sometimes|array',
                'tac_gias.*' => 'exists:tac_gia,ma_tac_gia',
                'the_loais' => 'sometimes|array',
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

            $book->update($request->except(['tac_gias', 'the_loais', 'kieu_sachs', 'bo_sachs']));

            // Sync relationships if provided
            if ($request->has('tac_gias')) {
                $book->tacGias()->sync($request->tac_gias);
            }
            if ($request->has('the_loais')) {
                $book->theLoais()->sync($request->the_loais);
            }
            if ($request->has('kieu_sachs')) {
                $book->kieuSachs()->sync($request->kieu_sachs);
            }
            if ($request->has('bo_sachs')) {
                $book->boSachs()->sync($request->bo_sachs);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Book updated successfully',
                'data' => $book->load(['tacGias', 'theLoais', 'nhaXuatBan', 'kieuSachs', 'boSachs'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating book: ' . $e->getMessage()
            ], 500);
        }
    }

    // yeu cau xoa sach
    public function deleteBook($id)
    {
        try {
            $book = Sach::findOrFail($id);
            
            DB::beginTransaction();
            
            // Delete related records in pivot tables
            $book->tacGias()->detach();
            $book->theLoais()->detach();
            $book->kieuSachs()->detach();
            $book->boSachs()->detach();
            
            $book->delete();
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Book deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting book: ' . $e->getMessage()
            ], 500);
        }
    }

    // them tac gia moi
    public function addNewAuthor(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ten_tac_gia' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $author = TacGia::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm tác giả thành công',
                'data' => $author
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm tác giả: ' . $e->getMessage()
            ], 500);
        }
    }

    // them the loai moi
    public function addNewCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ten_the_loai' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $category = TheLoai::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm thể loại thành công',
                'data' => $category
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm thể loại'
            ], 500);
        }
    }

    // yeu cau them sach moi
    public function addNewBook(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tieu_de' => 'required|string|max:255|unique:sach,tieu_de',
                'tong_so_trang' => 'nullable|integer|min:1',
                'danh_gia' => 'nullable|numeric|between:0,5',
                'ngay_xuat_ban' => 'required|date|before_or_equal:today',
                'gia_tien' => 'required|numeric|min:0',
                'so_tap' => 'nullable|numeric|min:1',
                'gioi_thieu' => 'nullable|string|max:1000',
                'ma_nha_xuat_ban' => 'required|exists:nha_xuat_ban,ma_nha_xuat_ban',
                'tac_gias' => 'required|array|min:1',
                'tac_gias.*' => 'exists:tac_gia,ma_tac_gia',
                'the_loais' => 'required|array|min:1',
                'the_loais.*' => 'exists:the_loai,ma_the_loai'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error', 
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $validatedData = $validator->validated();
            $book = Sach::create($request->except(['tac_gias', 'the_loais']));

            $book->tacGias()->attach($validatedData['tac_gias']);
            $book->theLoais()->attach($validatedData['the_loais']);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm sách thành công',
                'data' => $book->load(['tacGias', 'theLoais', 'nhaXuatBan'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi thêm sách: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi thêm sách'
            ], 500);
        }
    }
}