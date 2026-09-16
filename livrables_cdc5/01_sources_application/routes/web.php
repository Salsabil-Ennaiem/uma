<?php

use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/login', '/admin/login')->name('login');

Route::middleware(['web', 'auth'])
    ->prefix('admin/rapports-etats')
    ->name('admin.rapports-etats.')
    ->group(function () {
        Route::get('/{etat}/apercu', [RapportController::class, 'apercu'])->name('apercu');
        Route::get('/{etat}/pdf', [RapportController::class, 'pdf'])->name('pdf');
    });
