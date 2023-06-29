<?php

use Illuminate\Support\Facades\Route;
use Szhorvath\GetAddress\Http\Controllers\Api\AddressLookupController;

Route::prefix('address')->group(function () {
    Route::get('/lookup/{postcode}', [AddressLookupController::class, 'lookup'])->name('address.lookup');
    Route::get('/fetch/{id}', [AddressLookupController::class, 'fetch'])->name('address.fetch');
});
