<?php

use App\Livewire\Table;
use App\Livewire\MenuList;
use App\Livewire\MenuCategoryList;
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
})->middleware('guest');

Route::get('/admin', function () {
    return view('auth.login');
});

// Route::get('/dash')

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('dashboard');
    //table create
    Route::get('/table_create', Table::class)->name('table_create');
    //Menu Category
    Route::get('/menu_category', MenuCategoryList::class)->name('menu_category');
    // Menu Create
    Route::get('/menu_create', MenuList::class)->name('menu_create');
});
