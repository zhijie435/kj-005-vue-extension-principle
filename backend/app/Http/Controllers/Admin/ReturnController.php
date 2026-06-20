<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends AdminController
{
    public function index(Request $request): View
    {
        $returns = [
            ['id' => 'RET001', 'order_id' => 'ORD002', 'customer' => '李四', 'reason' => '商品破损', 'amount' => 596.00, 'status' => '待审核', 'created_at' => '2026-06-21 09:00'],
            ['id' => 'RET002', 'order_id' => 'ORD003', 'customer' => '王五', 'reason' => '不想要了', 'amount' => 199.00, 'status' => '已退款', 'created_at' => '2026-06-20 18:00'],
        ];

        return $this->view('returns.index', [
            'pageTitle' => '退货管理',
            'returns' => $returns,
        ]);
    }

    public function show(string $id): View
    {
        return $this->view('returns.show', [
            'pageTitle' => '退货详情',
            'returnId' => $id,
        ]);
    }

    public function edit(string $id): View
    {
        return $this->view('returns.edit', [
            'pageTitle' => '审核退货',
            'returnId' => $id,
        ]);
    }
}
