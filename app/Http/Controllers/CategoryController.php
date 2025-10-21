<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(){
        $data = Category::all();
        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục nào',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách danh mục thành công',
            'data' => $data,
        ], 200);
    }
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images/category', $imageName, 'public');
            }
            $category = Category::create([
                'name' => $validated['name'],
                'image' => $path ?? null,
                'slug' => Str::slug($validated['name']),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Tạo danh mục thành công',
                'data' => $category,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo danh mục thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại',
            ], 404);
        }
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công',
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không tồn tại',
            ], 404);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images/category', $imageName, 'public');
            $category->image = $path;
        }
        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->save();
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật danh mục thành công',
            'data' => $category,
        ], 200);
    }
    public function show($id)
    {
        try {
            $category = Category::find($id);
            $data = $category->products;
            return response()->json([
                'success' => true,
                'message' => 'Lấy sản phẩm theo danh mục thành công',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lấy sản phẩm theo danh mục thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
