<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

// 👇 ESSA LINHA RESOLVE TUDO
Route::any('{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');