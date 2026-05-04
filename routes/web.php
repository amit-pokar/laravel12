<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

// Email Notification Routes
Route::prefix('notifications')->group(function () {
    Route::get('/test', [NotificationController::class, 'test'])->name('notification.test');
    Route::get('/send', [NotificationController::class, 'create'])->name('notification.create');
    Route::post('/send', [NotificationController::class, 'send'])->name('notification.send');
    Route::post('/send-html', [NotificationController::class, 'sendHtml'])->name('notification.send-html');
    Route::post('/send-bulk', [NotificationController::class, 'sendBulk'])->name('notification.send-bulk');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Blog Routes
    Route::resource('blogs', BlogController::class);
});

require __DIR__.'/auth.php';
