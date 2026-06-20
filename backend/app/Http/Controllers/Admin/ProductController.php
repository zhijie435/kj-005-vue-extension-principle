<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends AdminController
{
    public function index(Request $request): View
    {
        $products = [
            ['id' => 1, 'name' => '商品A', 'sku' => 'SKU001', 'price' => 99.00, 'stock' => 100, 'status' => '上架'],
            ['id' => 2, 'name' => '商品B', 'sku' => 'SKU002', 'price' => 199.00, 'stock' => 50, 'status' => '上架'],
            ['id' => 3, 'name' => '商品C', 'sku' => 'SKU003', 'price' => 299.00, 'stock' => 0, 'status' => '下架'],
        ];

        return $this->view('products.index', [
            'pageTitle' => '产品管理',
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return $this->view('products.create', [
            'pageTitle' => '创建产品',
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
