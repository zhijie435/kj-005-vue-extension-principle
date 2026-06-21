<?php

namespace App\Http\Controllers\Distributor;

use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends DistributorController
{
    public function index(Request $request): View
    {
        $orders = [
            ['id' => 'ORD001', 'customer' => '张三', 'amount' => 298.00, 'status' => '待发货', 'created_at' => '2026-06-20 10:30'],
            ['id' => 'ORD007', 'customer' => '周八', 'amount' => 1296.00, 'status' => '待确认', 'created_at' => '2026-06-21 09:30'],
        ];

        return $this->view('orders.index', [
            'pageTitle' => '订单管理',
            'orders' => $orders,
        ]);
    }

    public function create(): View
    {
        return $this->view('orders.create', [
            'pageTitle' => '创建订单',
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
