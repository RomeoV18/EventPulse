<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta'); // tanggal & jam sesuai WIB
        Carbon::setLocale('id');           // nama bulan/hari dalam Bahasa Indonesia
        Paginator::useBootstrapFive();     // pagination Bootstrap 5
    }
}
