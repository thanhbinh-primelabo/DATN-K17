<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Model\ProductType;
use Cart;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $req)
    {
        View::composer(['user.master'], function ($view) {
            $data = ProductType::all();
            $view->with(['menu' => $data]);
        });
    }
}
