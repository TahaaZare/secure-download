<?php

use Illuminate\Support\Facades\Route;

Route::get('secure-download', [\Tahaazare\SecureDownload\Controllers\DownloadController::class, 'handle'])
    ->name('secure-download.file')
    ->middleware('signed');
