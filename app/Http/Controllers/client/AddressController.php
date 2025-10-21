<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Http\Requests\AddressRquest;

class AddressController extends Controller
{
    public function getAddressByUserId()
    {
        $user = auth()->user();
        $data = $user->getAddress;
        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ nào',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách địa chỉ thành công',
            'data' => $data,
        ], 200);
    }
    public function store(AddressRquest $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại',
                ], 404);
            }
            $validated = $request->validated();
            $data = ShippingAddress::create([
                'user_id' => $user->id,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Thêm địa chỉ thành công',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm địa chỉ: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $address = ShippingAddress::find($id);
            if(!$address){
                return response()->json([
                    'success' => false,
                    'message' => 'Địa chỉ không tồn tại',
                ],404);
            }
            $address->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa địa chỉ thành công',
            ],200); 
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa địa chỉ: '.$e->getMessage(),
            ],500);
        }
    }
}
