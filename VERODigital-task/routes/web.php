<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('index');
});

Route::get('/check-updates', function () {
    $filePath = public_path('src/data/tasks.json'); // Use public_path() to access the public directory

    if (file_exists($filePath)) {
        $lastModified = filemtime($filePath); // Get last modified time in UNIX timestamp
        return response()->json(['last_modified' => $lastModified]);
    } else {
        return response()->json(['error' => 'File not found'], 404);
    }
});


Route::get('/test-fetch', function () {
    return \fetchAndSaveData(); // This should not give an undefined function error if everything is set up correctly.
});

Route::post('/upload', [UploadController::class, 'upload'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);


Route::get('/check-image', [ImageController::class, 'checkFolder']);
