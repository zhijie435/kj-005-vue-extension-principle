<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class AdminController extends Controller
{
    protected string $viewNamespace = 'admin';

    protected string $panelName = '平台管理后台';

    protected string $panelPrefix = 'admin';

    protected string $userName = '平台管理员';

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
