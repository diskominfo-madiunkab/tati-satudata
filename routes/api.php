<?php

use App\Http\Controllers\PortalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/datasets', [PortalController::class, 'apiDatasetList'])->name('api.v1.datasets.list');
    Route::get('/datasets/{id}', [PortalController::class, 'apiDatasetDetail'])->name('api.v1.datasets.detail');
});
