<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusanaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
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

// Route::get('/', function () {
//     return view('welcome');
// });



// Admin Login
Route::get('admin/login', [AdminController::class, 'index'])->name('admin.login');
Route::post('login-proses', [AdminController::class, 'login_proses'])->name('login.proses');
Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin Dashboard
Route::middleware(['admin.auth'])->group(function () {
    // DASHBOARD
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // PROFILE
    Route::get('/admin/profile', [AdminController::class, 'editProfile'])->name('admins.profile');
    Route::post('/admin/profile/update/{admin_id}', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // BUSANA
    Route::get('admin/busanas', [BusanaController::class, 'index'])->name('busana.index');
    Route::get('admin/busana/create', [BusanaController::class, 'create'])->name('busana.create');
    Route::post('admin/busana/store', [BusanaController::class, 'store'])->name('busana.store');
    Route::get('admin/busanas/edit/{busana_id}', [BusanaController::class, 'edit'])->name('busana.edit');
    Route::put('admin/busanas/update/{busana_id}', [BusanaController::class, 'update'])->name('busana.update');
    Route::get('admin/busanas/show/{busana_id}', [BusanaController::class, 'show'])->name('busana.show');
    Route::delete('admin/busanas/destroy/{busana_id}', [BusanaController::class, 'destroy'])->name('busana.destroy');

    // ORDERS
    Route::get('admin/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('admin/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');


    // REPORTS
    Route::get('admin/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/download/pdf', [ReportController::class, 'exportPDF'])->name('reports.download.pdf');
});





// Customer Routes
Route::get('/', [CustomerController::class, 'showHomePage'])->name('customer.home');
Route::get('/recommendation', [CustomerController::class, 'showRecommendationPage'])->name('customer.recommendation');


Route::post('/register', [CustomerController::class, 'register'])->name('customer.register');
Route::post('/login', [CustomerController::class, 'login'])->name('customer.login');
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Protected Routes
Route::middleware(['customer.auth'])->group(function () {

    Route::get('/all-busanas', [CustomerController::class, 'showAllBusanas'])->name('customer.busanas');
    Route::get('/busanas/{id}', [CustomerController::class, 'showDetail'])->name('customer.busana.detail');


    Route::get('/cart', [CustomerController::class, 'showCart'])->name('customer.cart');
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('customer.cart.add');
    Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('customer.cart.update');
    Route::post('/cart/remove', [CustomerController::class, 'removeFromCart'])->name('customer.cart.remove');

    Route::get('/checkout', [CustomerController::class, 'showCheckout'])->name('customer.checkout');
    Route::post('/checkout', [CustomerController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/orders', [CustomerController::class, 'showOrders'])->name('customer.orders');
    Route::get('/order/complete/{orderId}', [CustomerController::class, 'showCompletePage'])->name('customer.complete');


    Route::resource('busanas', BusanaController::class)->except(['destroy']);
});
