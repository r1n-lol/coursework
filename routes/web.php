<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DrinkController;
use App\Http\Controllers\AdminDrinkController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', function () {
    return view('pages.index');
})->name('index');

Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/cashback', function () {
    return view('pages.cashback');
})->name('cashback');

Route::get('/menu', [DrinkController::class, 'index'])->name('menu');
Route::get('/menu/{drink:slug}', [DrinkController::class, 'show'])->name('menu.show');
Route::get('/home', [DrinkController::class, 'home'])->name('home');
Route::get('/home/{drink:slug}', [DrinkController::class, 'showHome'])->name('home.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/drinks', [AdminDrinkController::class, 'index'])->name('drinks.index');
    Route::get('/drinks/create', [AdminDrinkController::class, 'create'])->name('drinks.create');
    Route::post('/drinks', [AdminDrinkController::class, 'store'])->name('drinks.store');
    Route::get('/drinks/{drink}/edit', [AdminDrinkController::class, 'edit'])->name('drinks.edit');
    Route::put('/drinks/{drink}', [AdminDrinkController::class, 'update'])->name('drinks.update');
    Route::delete('/drinks/{drink}', [AdminDrinkController::class, 'destroy'])->name('drinks.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
