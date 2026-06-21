<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $adminViews = resource_path('views/vendor/admin');
        $supplierViews = resource_path('views/vendor/supplier');
        $distributorViews = resource_path('views/vendor/distributor');

        View::addNamespace('admin', $adminViews);

        View::addNamespace('supplier', [
            $supplierViews,
            $adminViews,
        ]);

        View::addNamespace('distributor', [
            $distributorViews,
            $supplierViews,
            $adminViews,
        ]);
    }
}
