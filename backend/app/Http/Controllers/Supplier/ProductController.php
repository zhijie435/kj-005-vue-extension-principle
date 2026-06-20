<?php

namespace App\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends SupplierController
{
    public function index(Request $request): View
    {
        $products = [
            ['id' => 1, 'name' => '供应商A商品-1', 'sku' => 'SUP-A-001', 'price' => 88.00, 'stock' => 200, 'status' => '上架'],
            ['id' => 2, 'name' => '供应商A商品-2', 'sku' => 'SUP-A-002', 'price' => 168.00, 'stock' => 80, 'status' => '上架'],
            ['id' => 3, 'name' => '供应商A商品-3', 'sku' => 'SUP-A-003', 'price' => 258.00, 'stock' => 15, 'status' => '上架'],
        ];

        return $this->view('products.index', [
            'pageTitle' => '我的产品',
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return $this->view('products.create', [
            'pageTitle' => '添加新产品',
        ]);
    }

    public function show(int $id): View
    {
        return $this->view('products.show', [
            'pageTitle' => '产品详情',
            'productId' => $id,
        ]);
    }

    public function edit(int $id): View
    {
        return $this->view('products.edit', [
            'pageTitle' => '编辑产品',
            'productId' => $id,
        ]);
    }
}
