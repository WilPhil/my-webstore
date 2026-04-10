<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home.index')->name('home');
Route::livewire('/products', 'pages::product.catalog')->name('product-catalog');
Route::livewire('/products/{product:slug}', 'pages::product.detail')->name('product-detail');
Route::livewire('/cart', 'pages::cart.list')->name('cart-list');
Route::livewire('/checkout', 'pages::checkout.index')->name('checkout');
Route::livewire('/order-confirmed/{sales_order:trx_id}', 'pages::sales-order.detail')->name('order-confirmed');
Route::livewire('/page/{page:slug}', 'pages::static-page.index')->name('static-page');

Route::webhooks('moota/callback');
