<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class SupplierController extends Controller
{
    protected string $viewNamespace = 'supplier';

    protected string $panelName = '供应商后台';

    protected string $panelPrefix = 'supplier';

    protected string $userName = '供应商管理员';

    protected function view(string $view, array $data = [], array $mergeData = []): View
    {
        $defaultData = [
            'panelName' => $this->panelName,
            'panelPrefix' => $this->panelPrefix,
            'userName' => $this->userName,
        ];

        return view("{$this->viewNamespace}::{$view}", array_merge($defaultData, $data), $mergeData);
    }
}
