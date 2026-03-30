<div class="container mx-auto w-full max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-5 md:grid-cols-2 md:gap-20">
        <div class="p-10">
            <!-- User Section -->
            <div
                class="border-t border-gray-200 py-6 first:border-transparent first:pt-0 last:pb-0 dark:border-neutral-700 dark:first:border-transparent">
                <label for="af-payment-billing-contact" class="inline-block text-sm font-medium dark:text-white">
                    Billing contact
                </label>

                <div class="mt-2 grid grid-cols-2 gap-3">
                    <div class="col-span-2">
                        <input wire:model="user_data.full_name" id="af-payment-billing-contact" type="text"
                            class="@error('user_data.full_name') border-red-600 @enderror shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Full Name">
                        @error('user_data.full_name')
                            <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <input wire:model="user_data.email_address" type="text"
                            class="@error('user_data.email_address') border-red-600 @enderror shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Email">
                        @error('user_data.email_address')
                            <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <input wire:model="user_data.phone_number" type="text"
                            class="@error('user_data.phone_number') border-red-600 @enderror shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Phone Number">
                        @error('user_data.phone_number')
                            <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div
                class="mt-3 border-y border-gray-200 py-6 first:border-transparent first:pt-0 last:pb-0 dark:border-neutral-700 dark:first:border-transparent">
                <label for="af-payment-billing-address" class="inline-block hidden text-sm font-medium dark:text-white">
                    Billing address
                </label>

                <div class="mt-2 space-y-3">
                    <input wire:model="user_data.shipping_address" id="af-payment-billing-address" type="text"
                        class="@error('user_data.shipping_address') border-red-600 @enderror shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                        placeholder="Street Address">
                    @error('user_data.shipping_address')
                        <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                            {{ $message }}
                        </p>
                    @enderror
                    <div>
                        <div x-data="{ open: false }" class="relative w-full">
                            <div class="relative">
                                <input wire:model.live.debounce.500ms="location_data.keyword" type="text"
                                    @focus="open = true" @click.outside="open = false"
                                    class="@error('user_data.shipping_location_code') border-red-500 @enderror shadow-2xs peer block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Cari Lokasi" />

                                <div class="not-peer-data-loading:hidden border-3 text-primary absolute right-3 top-3 size-4 animate-spin rounded-[999px] border-current border-t-transparent"
                                    role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            @error('user_data.shipping_location_code')
                                <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                                    {{ $message }}
                                </p>
                            @enderror

                            @if ($this->locations->toCollection()->isNotEmpty())
                                <ul class="absolute z-10 mt-1 max-h-60 w-full overflow-y-auto rounded-b-lg  bg-white"
                                    x-show="open">
                                    @foreach ($this->locations() as $location)
                                        <li
                                            class="bg-layer border-layer-line text-layer-foreground -mt-px inline-flex w-full items-center gap-x-2 border px-4 py-3 text-sm font-medium first:mt-0 first:rounded-t-lg last:rounded-b-lg hover:bg-select-item-hover focus:outline-hidden focus:bg-select-item-focus cursor-pointer">
                                            <div class="relative flex w-full items-center cursor-pointer">
                                                <div class="flex h-5 items-center">
                                                    <input wire:key="{{ $location->code }}"
                                                        wire:model.live="location_data.selected_location_code"
                                                        id="hs-list-group-item-radio-{{ $location->code }}"
                                                        value="{{ $location->code }}" name="hs-list-group-item-radio"
                                                        type="radio" class="sr-only" checked>
                                                </div>
                                                <label for="hs-list-group-item-radio-{{ $location->code }}"
                                                    class="text-muted-foreground-2 mx-3 block w-full text-sm cursor-pointer">
                                                    {{ $location->label }}
                                                </label>
                                                @if ($this->user_data['shipping_location_code'] === $location->code)
                                                    <svg class="h-5 w-5 text-primary-600" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($this->location_data['selected_location_code'])
                                <p class="mt-2 text-sm text-gray-600">
                                    Lokasi Dipilih
                                    <strong>{{ $this->location->label }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Section -->
                @if ($this->location_data['selected_location_code'])
                    <label for="af-shipping-method" class="mt-5 inline-block text-sm font-medium dark:text-white">
                        Shipping Method
                    </label>
                @endif

                <div wire:loading wire:target="location_data.selected_location_code"
                    class="mt-3 w-full animate-pulse space-y-3">
                    <p class="bg-surface-1 h-4 rounded-full" style="width: 20%;"></p>
                    <ul class="mt-2 space-y-3">
                        <li class="bg-surface-1 h-4 w-full rounded-full"></li>
                    </ul>
                    <p class="bg-surface-1 h-4 rounded-full" style="width: 20%;"></p>
                    <ul class="mt-2 space-y-3">
                        <li class="bg-surface-1 h-4 w-full rounded-full"></li>
                    </ul>
                </div>

                <div wire:loading.remove wire:target="location_data.selected_location_code" class="mt-2 space-y-3">
                    <div class="grid space-y-2">
                        @foreach ($this->shippingMethods as $shipping_group => $shipping_method_service)
                            <div class="text-xs font-bold">
                                {{ $shipping_group }}
                            </div>
                            @foreach ($shipping_method_service as $method)
                                <label for="shipping_method_{{ $method->hash }}"
                                    class="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white p-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                                    <div class="flex items-center justify-start gap-2">
                                        <input wire:key="{{ $method->hash }}"
                                            wire:model.live="shipping_data.shipping_method_hash" type="radio"
                                            value="{{ $method->hash }}"
                                            class="mt-0.5 shrink-0 rounded-full border-gray-200 text-blue-600 checked:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                            id="shipping_method_{{ $method->hash }}">
                                        @if ($method->logo_url)
                                            <img src="{{ $method->logo_url }}" class="h-5" />
                                        @endif
                                        <span
                                            class="ms-3 text-sm text-gray-500 dark:text-neutral-400">{{ $method->label }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-800">
                                        {{ $method->cost_formatted }}
                                    </span>
                                </label>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                @error('user_data.shipping_method_hash')
                    <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <label for="af-payment-method" class="mt-5 inline-block text-sm font-medium dark:text-white">
                Payment Method
            </label>
            <div class="mt-2 space-y-3">
                <div class="grid space-y-2">
                    @foreach ($this->payment_methods as $key => $payment_method)
                        <label for="payment_method_{{ $key }}"
                            class="flex w-full rounded-lg border border-gray-200 bg-white p-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                            <input wire:key="payment-method-{{ $payment_method->hash }}"
                                wire:model.live="payment_data.payment_method_hash" type="radio"
                                class="mt-0.5 shrink-0 rounded-full border-gray-200 text-blue-600 checked:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                value="{{ $payment_method->hash }}" id="payment_method_{{ $key }}">
                            <span
                                class="ms-3 text-sm text-gray-500 dark:text-neutral-400">{{ $payment_method->label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @error('user_data.payment_method_hash')
                <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="p-10">
            <h1 class="mb-5 text-2xl font-light">Order Summary</h1>
            <div>
                @foreach ($this->items as $item)
                    <x-single-product-list :item="$item" />
                @endforeach
            </div>
            <div class="grid gap-5">
                <!-- List Group -->
                <ul class="mt-3 flex flex-col">
                    <li
                        class="-mt-px inline-flex items-center gap-x-2 border border-gray-200 px-4 py-3 text-sm text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                        <div class="flex w-full items-center justify-between">
                            <span>Sub Total</span>
                            <span>{{ $this->cart_summaries['sub_total_formatted'] }}</span>
                        </div>
                    </li>
                    <li
                        class="-mt-px inline-flex items-center gap-x-2 border border-gray-200 px-4 py-3 text-sm text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                        <div wire:loading.remove wire:target="shipping_data.shipping_method_hash"
                            class="flex w-full items-center justify-between">
                            <span class="flex flex-col">
                                <span>{{ $this->shippingMethod?->label ?? '-' }}</span>
                                <span class="text-xs">{{ $this->shippingMethod?->weight ?? '0' }} gram</span>
                            </span>
                            <span>{{ $this->shippingMethod?->cost_formatted ?? 'IDR 0.00' }}</span>
                        </div>

                        <div wire:loading.flex wire:target="shipping_data.shipping_method_hash"
                            class="flex w-full items-center justify-between">
                            <span class="flex flex-col w-full gap-2">
                                <span class="bg-surface-1 h-4 rounded-full" style="width: 30%;"></span>
                                <span class="bg-surface-1 h-4 rounded-full" style="width: 10%;"></span>
                            </span>
                            <span class="bg-surface-1 h-4 rounded-full" style="width: 20%;"></span>
                        </div>
                    </li>
                    <li
                        class="-mt-px inline-flex items-center gap-x-2 border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-800 first:mt-0 first:rounded-t-lg last:rounded-b-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                        <div class="flex w-full items-center justify-between">
                            <span>Total</span>
                            <span>{{ $this->cart_summaries['grand_total_formatted'] }}</span>
                        </div>
                    </li>
                </ul>
                <!-- End List Group -->
                <button wire:click="placeAnOrder" type="button"
                    class="focus:outline-hidden inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 disabled:pointer-events-none disabled:opacity-50 cursor-pointer"
                    wire:loading.attr="disabled" wire:target="placeAnOrder">
                    Place an Order
                    <div class="not-in-data-loading:hidden animate-spin inline-block size-4 border-3 border-current border-t-transparent rounded-[999px] text-white"
                        role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
