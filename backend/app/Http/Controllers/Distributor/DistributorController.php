<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class DistributorController extends Controller
{
    protected string $viewNamespace = 'distributor';

    protected string $panelName = '经销商后台';

    protected string $panelPrefix = 'distributor';

    protected string $userName = '经销商管理员';

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
