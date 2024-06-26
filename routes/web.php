<?php
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\UserController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::get('/', function() {
    return view('login');
})->name('login');

Route::get('/error-permission', function() {
    return view('errors.permission');
})->name('error.permission');

Route::get('/logout', [UserController::class, 'logout']) ->name('logout');
Route::post('/login', [UserController::class, 'loginAuth']) ->name('login.auth');


Route::middleware(['IsLogin', 'IsAdmin'])->group(function(){ 

    Route::prefix('/medicine')->name('medicine.')->group(function() {
        Route::get('/create', [MedicineController::class, 'create'])->name('create');
        Route::post('/store', [MedicineController::class, 'store'])->name('store');
        Route::get('/', [MedicineController::class, 'index'])->name('home');
        Route::get('/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [MedicineController::class, 'update'])->name('update'); //untuk mengedit  dan update
        Route::delete('/{id}', [MedicineController::class, 'destroy'])->name('delete');
        Route::get('/data/stock', [MedicineController::class, 'stock'])->name('stock');
        Route::get('/data/stock/{id}', [MedicineController::class, 'stockEdit'])->name('stock.edit');
        Route::patch('/data/stock/{id}', [MedicineController::class, 'stockUpdate'])->name('stock.update');
    
    });
    
    Route::prefix('/akun')->name('akun.')->group(function() {
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/', [UserController::class, 'index'])->name('index');
    });
    Route::prefix('/order')->name('order.')->group(function(){
        Route::get('/data', [OrderController::class, 'data'])->name('data');
        Route::get('/export-excel', [OrderController::class, 'exportExcel'])->name('export-excel');
    });
});
Route::middleware(['IsLogin', 'IsKasir'])->group(function () {
    Route::prefix('/kasir')->name('kasir.')->group(function () {
        Route::prefix('/order') ->name('order.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/store', [OrderController::class, 'store'])->name('store');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download');
        });
    });
});

Route::middleware('IsGuest')->group(function(){
    Route::get('/', function(){
        return view('login');
    })->name('login');
    Route::post('/', [UserController::class, 'loginAuth'])->name('login.auth');
});
    
Route::middleware(['IsLogin'])->group(function (){
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/home', function(){
        return view('home');
    })->name('home.page');
});

