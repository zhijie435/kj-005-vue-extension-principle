<?php

namespace App\Http\Controllers\Distributor;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends DistributorController
{
    public function index(Request $request): View
    {
        $shipments = [
            ['id' => 'SHP001', 'order_id' => 'ORD001', 'carrier' => '顺丰', 'tracking_no' => 'SF1234567890', 'status' => '已发货', 'shipped_at' => '2026-06-20 15:30'],
            ['id' => 'SHP002', 'order_id' => 'ORD002', 'carrier' => '京东物流', 'tracking_no' => 'JD9876543210', 'status' => '运输中', 'shipped_at' => '2026-06-20 18:00'],
        ];

        return $this->view('shipments.index', [
            'pageTitle' => '发货记录',
            'shipments' => $shipments,
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
