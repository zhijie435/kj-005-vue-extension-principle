<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends AdminController
{
    public function index(Request $request): View
    {
        $orders = [
            ['id' => 'ORD001', 'customer' => '张三', 'amount' => 298.00, 'status' => '待发货', 'created_at' => '2026-06-20 10:30'],
            ['id' => 'ORD002', 'customer' => '李四', 'amount' => 596.00, 'status' => '已发货', 'created_at' => '2026-06-20 11:45'],
            ['id' => 'ORD003', 'customer' => '王五', 'amount' => 199.00, 'status' => '已完成', 'created_at' => '2026-06-19 14:20'],
        ];

        return $this->view('orders.index', [
            'pageTitle' => '订单管理',
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
