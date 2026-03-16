<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'pages.homepage')->name('home');

Route::livewire('/', 'pages::home.index')->name('home');
Route::livewire('/products', 'pages::product.catalog')->name('product-catalog');
Route::livewire('/products/{product:slug}', 'pages::product.detail')->name('product-detail');
Route::livewire('/cart', 'pages::cart.list')->name('cart-list');

// Route::view('/product', 'pages.product')->name('product');
// Route::view('/cart', 'pages.cart')->name('cart');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/order-confirmed', 'pages.order-confirmed')->name('order-confirmed');
Route::view('/page', 'pages.page')->name('page');
