<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::all();
        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sãn phẩm nào',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $data,
        ], 200);
    }
    public function store(ProductRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images/product', $imageName, 'public');
            }
            $product = Product::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'], 
                'quantity' => $validated['quantity'],
                'slug' => Str::slug($validated['name']),
                'image' => $path
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Tạo sản phẩm thành công',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo sản phẩm thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
    {
        // Tìm sản phẩm theo id
        $product = Product::find($id);

        // Nếu không có sản phẩm
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm',
            ], 404);
        }
        // Trả về dữ liệu sản phẩm
        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin sản phẩm thành công',
            'data' => $product,
        ], 200);
    }

    public function update(ProductRequest $request)
    {
        try {
            $validated = $request->validated();
            $product = Product::findOrFail($request->id);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images', $imageName, 'public');
                $product->image = $path;
            }
            $product->name = $validated['name'];
            $product->Category_id = $validated['Category_id'];
            $product->description = $validated['description'] ?? null;
            $product->price = $validated['price'];
            $product->quantity = $validated['quantity'];
            $product->slug = Str::slug($validated['name']);
            $product->save();
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật sản phẩm thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa sản phẩm thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
