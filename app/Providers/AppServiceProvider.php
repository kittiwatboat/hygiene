<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
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
        View::composer('*', function ($view) {
            $menuData = [];

            $verticalMenuPath = resource_path('menu/verticalMenu.json');

            if (File::exists($verticalMenuPath)) {
                $menuJson = File::get($verticalMenuPath);
                $decodedMenu = json_decode($menuJson);

                if (json_last_error() === JSON_ERROR_NONE && !empty($decodedMenu)) {
                    $menuData = $decodedMenu;
                }
            }

            $view->with('menuData', $menuData);
        });
    }
}
