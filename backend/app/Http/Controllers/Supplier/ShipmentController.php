<?php

namespace App\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends SupplierController
{
    public function index(Request $request): View
    {
        $shipments = [
            ['id' => 'SHP003', 'order_id' => 'ORD005', 'carrier' => '中通', 'tracking_no' => 'ZT111222333', 'status' => '待发货', 'shipped_at' => '-'],
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
