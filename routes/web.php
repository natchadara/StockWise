<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// ทุกหน้าห้าม browser เก็บ cache (กด back หลัง logout แล้วจะไม่เห็นหน้าเดิม)
Route::middleware('cache.headers:no_store;no_cache;must_revalidate;max_age=0')
    ->group(static function (): void {

        // หน้าแรก
        Route::get('/', function () {
            return view('home');
        })->name('home');

        // Login / Logout
        Route::controller(LoginController::class)
            ->prefix('/auth')
            ->group(static function (): void {
                Route::get('/login', 'showForm')->middleware('guest')->name('login');
                Route::post('/login', 'authenticate')->middleware('guest')->name('authentication');
                Route::post('/logout', 'logout')->middleware('auth')->name('logout');
            });

        // Items: ดูได้ทุกคน แต่เพิ่ม/แก้/ลบ ต้อง login
        Route::controller(ItemController::class)
            ->prefix('/items')
            ->name('items.')
            ->group(static function (): void {
                Route::get('', 'list')->name('list');

                Route::middleware('auth')->group(static function (): void {
                    Route::get('/create', 'showCreateForm')->name('create-form');
                    Route::post('/create', 'create')->name('create');
                    Route::get('/{item}/update', 'showUpdateForm')->name('update-form');
                    Route::post('/{item}/update', 'update')->name('update');
                    Route::post('/{item}/delete', 'delete')->name('delete');
                });

                // ต้องอยู่หลัง /create ไม่งั้นคำว่า create จะถูกมองเป็น {item}
                Route::get('/{item}', 'view')->name('view');
            });

        // Categories: ดูได้ทุกคน แต่เพิ่ม/แก้/ลบ ต้อง login
        Route::controller(CategoryController::class)
            ->prefix('/categories')
            ->name('categories.')
            ->group(static function (): void {
                Route::get('', 'list')->name('list');

                Route::middleware('auth')->group(static function (): void {
                    Route::get('/create', 'showCreateForm')->name('create-form');
                    Route::post('/create', 'create')->name('create');
                    Route::get('/{category}/update', 'showUpdateForm')->name('update-form');
                    Route::post('/{category}/update', 'update')->name('update');
                    Route::post('/{category}/delete', 'delete')->name('delete');
                });

                Route::get('/{category}', 'view')->name('view');
            });

        // Suppliers: ต้อง login ทุกหน้า
        Route::controller(SupplierController::class)
            ->prefix('/suppliers')
            ->name('suppliers.')
            ->middleware('auth')
            ->group(static function (): void {
                Route::get('', 'list')->name('list');
                Route::get('/create', 'showCreateForm')->name('create-form');
                Route::post('/create', 'create')->name('create');
                Route::get('/{supplier}/update', 'showUpdateForm')->name('update-form');
                Route::post('/{supplier}/update', 'update')->name('update');
                Route::post('/{supplier}/delete', 'delete')->name('delete');
                Route::get('/{supplier}', 'view')->name('view');
            });

        // รายงานสินค้าใกล้หมด + Export PDF: ต้อง login
        Route::controller(ReportController::class)
            ->prefix('/reports')
            ->name('reports.')
            ->middleware('auth')
            ->group(static function (): void {
                Route::get('/low-stock', 'lowStock')->name('low-stock');
                Route::get('/low-stock/pdf', 'lowStockPdf')->name('low-stock.pdf');
            });
    });
