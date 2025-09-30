<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/download-file/{file_path}', function ($filePath) {
    $path = "public/" . $filePath;

    if (!Storage::exists($path)) {
        abort(404);
    }

    return Storage::download($path, basename($filePath));
})->name('download.file')->where('file_path', '.*');