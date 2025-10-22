<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\Voucher;
class OrderController extends Controller
{
    public function index(){
        try {
            $user = auth()->user();
            $order = $user->orders;
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn nào'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'orders' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách hóa đơn',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id) {
        try {
            $order= Order::find($id); //lấy đơn hàng theo id
            $orderItems = $order->orderItems; //lấy các mục đơn hàng liên quan đến đơn hàng
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy chi tiết đơn hàng nào'
                ], 404);
            }
            $data = [];
            foreach ($orderItems as $item) {
                $data[] = [
                    'nameProduct' => $this->getNameProduct($item->product_id),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }
            return response()->json([
                'success' => true,
                'orderItems' => $data,
                'order_id' => $id,
                'address' => $this->getAddressName($order->shipping_address_id),
                'vourcher_id' => $this->getVoucherName($order->voucher_id),
                'history' => $order->orderStatusHistories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNameProduct($product_id){
        try {
            $product_name = Product::where('id', $product_id)->value('name');
            if (!$product_name) {
                return null;
            }
            return $product_name;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getAddressName($address_id){
        try {
            $address = ShippingAddress::where('id', $address_id)->value('address');
            if (!$address) {
                return null;
            }
            return $address;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getVoucherName($voucher_id){
        try {
            $voucher_code = Voucher::where('id', $voucher_id)->value('name');
            if (!$voucher_code) {
                return null;
            }
            return $voucher_code;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function destroy($id){
        try {
            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng'
                ], 404);
            }
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa đơn hàng thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
