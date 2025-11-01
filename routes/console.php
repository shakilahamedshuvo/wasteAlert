<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('app:delete-unverified-user', function () {
//     DB::table('users')->where('is_verified', 0)->delete();
//     $this->info('Unverified users deleted successfully.');
// })->everyThirtySeconds();

