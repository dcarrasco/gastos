<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use App\Gastos\ParserMasivo\NullParser;
use Illuminate\Support\ServiceProvider;
use App\Gastos\ParserMasivo\GastosParser;
use App\Gastos\ParserMasivo\VisaExcelParser;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LengthAwarePaginator::defaultView('orm.components.paginator');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GastosParser::class, function () {
            $parsers = [
                '2' => VisaExcelParser::class,
            ];

            $selectedParser = Arr::get($parsers, request()->input('cuenta_id', ''), NullParser::class);

            return new $selectedParser();
        });
    }
}
