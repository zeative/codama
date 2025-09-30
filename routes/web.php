<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesignController;

Route::get('/', function () {
    return view('welcome');
});

// Existing general file download route
Route::get('/download-file/{file_path}', function ($filePath) {
    $path = "public/" . $filePath;

    if (!Storage::exists($path)) {
        abort(404);
    }

    return Storage::download($path, basename($filePath));
})->name('download.file')->where('file_path', '.*');

// Secure design file download route
Route::get('/designs/{id}/download', [DesignController::class, 'download'])->name('design.download');