<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/pos/auth.php';
require __DIR__.'/pos/dashboard.php';
require __DIR__.'/pos/backend/hrms.php';
require __DIR__.'/pos/backend/crm.php';
require __DIR__.'/pos/backend/inventory.php';
