<?php

use Illuminate\Support\Facades\Route;

// página principal
Route::view('/', 'app');

// todas as rotas do Vue (SPA), exceto /api
Route::view('/{any}', 'app')->where('any', '^(?!api).*$');