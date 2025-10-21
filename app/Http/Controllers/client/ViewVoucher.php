<?php

namespace App\Http\Controllers\client;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;

class ViewVoucher extends Controller
{
    public function index()
    {
        // Lấy ngày hiện tại
        $today = Carbon::now();
        // Lấy tất cả voucher đang hoạt động
        $vouchers = Voucher::where('DateStart', '<=', $today)
            ->where('DateEnd', '>=', $today)
            ->get();
        if($vouchers->isEmpty()){
            return response()->json([
                'success' => false,
                'message' => 'Hiện tại không có voucher nào hoạt động',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ]);
    }
}
