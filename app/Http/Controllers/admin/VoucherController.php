<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Http\Requests\VoucherRequest;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::all();
        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ], 200);
    }
    public function store(VoucherRequest $request)
    {
        try {
            $validated = $request->validated();
            $voucher = Voucher::create([
                'name' => $validated['name'],
                'dateStart' => $validated['dateStart'],
                'dateEnd' => $validated['dateEnd'],
                'type' => $validated['type'],
                'value' => $validated['value'],
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Tạo voucher thành công',
                'data' => $voucher,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo voucher thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher không tồn tại',
            ], 404);
        }
        $voucher->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa voucher thành công',
        ], 200);
    }
    public function update(VoucherRequest $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher không tồn tại',
            ], 404);
        }
        try {
            $validated = $request->validated();
            $voucher->update([
                'name' => $validated['name'],
                'dateStart' => $validated['dateStart'],
                'dateEnd' => $validated['dateEnd'],
                'type' => $validated['type'],
                'value' => $validated['value'],
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật voucher thành công',
                'data' => $voucher,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật voucher thất bại',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
