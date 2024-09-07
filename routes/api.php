<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// Bearer token middleware ile korunan route grubu
Route::middleware('BearerTokenMiddleware')->group(function () {
    // Tüm haberleri listeleme
    Route::get('news', [NewsController::class, 'index']);
    
    // Yeni haber ekleme
    Route::post('news', [NewsController::class, 'store']);
    
    // Belirli bir haberi getirme
    Route::get('news/{news}', [NewsController::class, 'show']);
    
    // Belirli bir haberi güncelleme
    Route::put('news/{news}', [NewsController::class, 'update']);
    
    // Belirli bir haberi silme
    Route::delete('news/{news}', [NewsController::class, 'destroy']);
    
    // Haberleri arama
    Route::get('search', [NewsController::class, 'search']);
});
