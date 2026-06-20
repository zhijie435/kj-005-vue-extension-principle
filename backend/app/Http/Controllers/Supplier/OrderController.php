<?php

namespace App\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends SupplierController
{
    public function index(Request $request): View
    {
        $orders = [
            ['id' => 'ORD001', 'customer' => '张三', 'amount' => 298.00, 'status' => '待发货', 'created_at' => '2026-06-20 10:30'],
            ['id' => 'ORD004', 'customer' => '赵六', 'amount' => 504.00, 'status' => '待发货', 'created_at' => '2026-06-21 08:15'],
        ];

        return $this->view('orders.index', [
            'pageTitle' => '我的订单',
            'orders' => $orders,
        ]);
    }

    public function show(string $id): View
    {
        return $this->view('orders.show', [
            'pageTitle' => '订单详情',
            'orderId' => $id,
        ]);
    }
}
