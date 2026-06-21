<?php

namespace App\Http\Controllers\Distributor;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends DistributorController
{
    public function index(Request $request): View
    {
        $returns = [
            ['id' => 'RET001', 'order_id' => 'ORD003', 'customer' => '王五', 'reason' => '商品损坏', 'amount' => 299.00, 'status' => '处理中', 'created_at' => '2026-06-20 14:00'],
            ['id' => 'RET002', 'order_id' => 'ORD004', 'customer' => '赵六', 'reason' => '规格不符', 'amount' => 199.00, 'status' => '已退款', 'created_at' => '2026-06-19 11:20'],
        ];

        return $this->view('returns.index', [
            'pageTitle' => '退货记录',
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
}
