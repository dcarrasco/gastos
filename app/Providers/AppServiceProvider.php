<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use App\Models\Gastos\ParserMasivo\NullParser;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Gastos\ParserMasivo\GastosParser;
use App\Models\Gastos\ParserMasivo\VisaExcelParser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LengthAwarePaginator::defaultView('components.paginator.paginator');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
