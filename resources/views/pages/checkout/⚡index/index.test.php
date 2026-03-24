<?php

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages::checkout.index')
        ->assertStatus(200);
});

it('passes validation if shipping method exists in cache', function () {
    // 1. Arrange
    $validHash = 'valid-hash-456';
    // We manually put the hash in the cache so the Rule finds it
    Cache::put("shipping-method-{$validHash}", ['some' => 'data'], now()->addMinutes(10));

    // 2. Act & Assert
    Livewire::test('pages::checkout.index')
        ->set('user_data.full_name', 'Wilsent Philip Lo') // Fill other required fields
        ->set('user_data.email_address', 'wilsent@email.com')
        ->set('user_data.phone_number', '08123456789')
        ->set('user_data.shipping_address', 'Jl. Sudirman No. 123')
        ->set('user_data.shipping_location_code', '33.08.08.1014')
        ->set('user_data.shipping_method_hash', $validHash)
        ->call('placeAnOrder')
        ->assertHasNoErrors('user_data.shipping_method_hash');
});

it('fails validation if shipping method is missing from cache', function () {
    $fakeHash = 'inibukanhash';
    Livewire::test('pages::checkout.index')
        ->set('user_data.full_name', 'Wilsent Philip Lo') // Fill other required fields
        ->set('user_data.email_address', 'wilsent@email.com')
        ->set('user_data.phone_number', '08123456789')
        ->set('user_data.shipping_address', 'Jl. Sudirman No. 123')
        ->set('user_data.shipping_location_code', '33.08.08.1014')
        ->set('user_data.shipping_method_hash', $fakeHash)
        ->call('placeAnOrder')
        ->assertHasErrors('user_data.shipping_method_hash');
});
