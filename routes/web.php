<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/library', function () {
    return view('search');
})->name('library');

Route::get('/borrowing', function () {
    return view('borrowing');
})->name('borrowing');

Route::get('/success', function () {
    return view('success');
})->name('success');
