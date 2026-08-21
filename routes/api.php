<?php

use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for 3T App
|--------------------------------------------------------------------------
|
| Public share API endpoint for querying student memorization progress.
| URL format: /api/share/m/{share_token}
|
*/

Route::get('/share/m/{share_token}', [ShareController::class, 'apiProgress'])->name('share.api');
