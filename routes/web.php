<?php

use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Route;

Route::middleware(CheckTableNumber::class)->group(function () {
    Route::get('/', HomePage::class)->name('home');
    Route::get('/food', AllFoodPage::class)->name('product.index');
    Route::get('/food/favorite', FavoritePage::class)->name('product.favorite');
    Route::get('/food/promo', PromoPage::class)->name('product.promo');
    Route::get('/food/{id}', FoodDetailPage::class)->name('product.detail');
});

Route::middleware(CheckTableNumber::class)->controller(TransactionController::class)->group(function () {
    Route::get('/cart', CartPage::class)->name('payment.cart');
    Route::get('/checkout', CheckoutPage::class)->name('payment.checkout');

    Route::middleware('throttle:10,1')->post('/payment', 'handlePayment')->name('payment');
    Route::get('/payment', function () {
        abort(404);
    });

    Route::get('/payment/status', 'paymentStatus')->name('payment.status');
    Route::get('/payment/success', PaymentSuccessPage::class)->name('payment.success');
    Route::get('/payment/failure', PaymentFailurePage::class)->name('payment.failure');
});

Route::post('/payment/webhook', [TransactionController::class, 'handleWebhook'])->name('payment.webhook');

Route::controller(QRController::class)->group(function () {
    Route::post('/store-gr-result', 'storeResult')->name('product.scan.store');
    Route::get('/scan', ScanPage::class)->name('product.scan');
    Route::get('/{tableNumber}', 'checkCode')->name('product.scan.table');
});