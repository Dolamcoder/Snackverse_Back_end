<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;

class BuyProductController extends Controller
{
    public function buyProduct(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);
            $price = Product::where('id', $productId)->value('price');
            if (!$price) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $price * $request->quantity,
                'shipping_address_id' => $request->address,
                'voucher_id' => $request->voucher ?? null,
            ]);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
            if (!$this->storeOrderStatusHistory($order->id)) {
                return response()->json(['success' => false, 'message' => 'Lưu lịch sử trạng thái đơn hàng thất bại'], 500);
            }
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Mua sãn phẩm thành công',
                    'order' => $order,
                    'order_item' => $orderItem
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Mua sản phẩm thất bại', 'error' => $e->getMessage()], 500);
        }
    }
    public function buyMultipleProducts(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $totalPrice = 0;
            $orderItems = [];

            // Duyệt qua từng sản phẩm
            foreach ($request->items as $item) {
                $price = Product::where('id', $item['product_id'])->value('price');

                if (!$price) {
                    return response()->json(['success' => false, 'message' => "Không tìm thấy sản phẩm ID {$item['product_id']}"], 404);
                }

                $subtotal = $price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'shipping_address_id' => $request->address,
                'voucher_id' => $request->voucher ?? null,
            ]);

            // Lưu từng sản phẩm vào order_items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            // Lưu lịch sử trạng thái
            $this->storeOrderStatusHistory($order->id);

            return response()->json([
                'success' => true,
                'message' => 'Đặt hàng thành công',
                'order' => $order,
                'items' => $orderItems
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đặt hàng thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function storeOrderStatusHistory($order_id)
    {
        try {
            OrderStatusHistory::create([
                'order_id' => $order_id,
                'status' => 'Đang xử lý',
                'changed_at' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
