<?php

namespace App\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends SupplierController
{
    public function index(Request $request): View
    {
        $returns = [
            ['id' => 'RET003', 'order_id' => 'ORD006', 'customer' => '孙七', 'reason' => '质量问题', 'amount' => 168.00, 'status' => '待审核', 'created_at' => '2026-06-21 10:00'],
        ];

        return $this->view('returns.index', [
            'pageTitle' => '退货申请',
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
            'pageTitle' => '处理退货',
            'returnId' => $id,
        ]);
    }
}
