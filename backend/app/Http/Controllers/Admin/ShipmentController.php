<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends AdminController
{
    public function index(Request $request): View
    {
        $shipments = [
            ['id' => 'SHP001', 'order_id' => 'ORD001', 'carrier' => '顺丰', 'tracking_no' => 'SF123456789', 'status' => '运输中', 'shipped_at' => '2026-06-20 15:00'],
            ['id' => 'SHP002', 'order_id' => 'ORD002', 'carrier' => '圆通', 'tracking_no' => 'YT987654321', 'status' => '已签收', 'shipped_at' => '2026-06-20 16:30'],
        ];

        return $this->view('shipments.index', [
            'pageTitle' => '发货管理',
            'shipments' => $shipments,
        ]);
    }

    public function create(): View
    {
        return $this->view('shipments.create', [
            'pageTitle' => '创建发货单',
        ]);
    }

    public function show(string $id): View
    {
        return $this->view('shipments.show', [
            'pageTitle' => '发货详情',
            'shipmentId' => $id,
        ]);
    }
}
