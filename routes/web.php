<?php
use Illuminate\Support\Facades\Route;




// Catch-All Route
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->name('home');
