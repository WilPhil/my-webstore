<div class="container mx-auto w-full max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-5 md:grid-cols-2 md:gap-20">
        <div class="p-10">
            <!-- Section -->
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
            <!-- Section -->
            <div
                class="mt-5 border-t border-gray-200 py-6 first:border-transparent first:pt-0 last:pb-0 dark:border-neutral-700 dark:first:border-transparent">
                <label for="af-payment-billing-address" class="inline-block text-sm font-medium dark:text-white">
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
                            <input wire:model.live.debounce.500ms="user_data.keyword_shipping_location" type="text"
                                @focus="open = true" @click.outside="open = false"
                                class="@error('user_data.keyword_shipping_location') border-red-500 @enderror shadow-2xs block w-full rounded-lg border-gray-200 px-3 py-1.5 pe-11 focus:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:py-2 sm:text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                placeholder="Cari Lokasi">

                            @if ($this->location_data['keyword'])
                                <ul class="absolute z-10 mt-1 max-h-60 w-full overflow-y-auto rounded-b-lg border border-gray-200 bg-white"
                                    x-show="open">
                                    @foreach ($this->locations() as $location)
                                        <div>
                                            <input wire:model.live="location_data.selected_location" type="radio"
                                                id="location-{{ $location->code }}" value="{{ $location->code }}"
                                                class="" />
                                            <label for="location-{{ $location->code }}">
                                                {{ $location->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($this->location_data['selected_location'])
                                <p class="mt-2 text-sm text-gray-600">
                                    Lokasi Dipilih
                                    <strong>{{ $this->location->label }}</strong>
                                </p>
                            @endif
                        </div>
                        @error('user_data.keyword_shipping_location')
                            <p class="mt-2 text-xs text-red-600" id="hs-validation-name-error-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- End Section -->
            <label for="af-shipping-method" class="inline-block text-sm font-medium dark:text-white">
                Shipping Method
            </label>
            <div class="mt-2 space-y-3">
                <div class="grid space-y-2">
                    <div class="text-xs font-bold">
                        Regular
                    </div>
                    @for ($i = 1; $i <= 3; $i++)
                        <label for="shipping_method_{{ $i }}"
                            class="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white p-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                            <div class="flex items-center justify-start gap-2">
                                <input type="radio" name="shipping_method" value="{{ $i }}"
                                    class="mt-0.5 shrink-0 rounded-full border-gray-200 text-blue-600 checked:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                    id="shipping_method_{{ $i }}">
                                <img src="{{ asset('images/shipping/jntexpress.svg') }}" class="h-5" />

                                <span class="ms-3 text-sm text-gray-500 dark:text-neutral-400">JNT
                                    - YES
                                    <span class="text-xs text-gray-500">(1-2 Day)</span>
                                </span>
                            </div>
                            <span class="text-sm text-gray-800">
                                Rp.123.123
                            </span>
                        </label>
                    @endfor
                    <div class="text-xs text-red-600">Fill Shipping Address First</div>
                </div>
            </div>

            <label for="af-payment-method" class="mt-5 inline-block text-sm font-medium dark:text-white">
                Payment Method
            </label>
            <div class="mt-2 space-y-3">
                <div class="grid space-y-2">
                    @php
                        $payment_methods = [
                            'Bank Transfer - BCA',
                            'Bank Transfer - BNI',
                            'Virtual Account BCA',
                            'QRIS',
                            'Dana',
                        ];
                    @endphp
                    @foreach ($payment_methods as $key => $item)
                        <label for="payment_method_{{ $key }}"
                            class="flex w-full rounded-lg border border-gray-200 bg-white p-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                            <input type="radio" name="hs-vertical-radio-in-form"
                                class="mt-0.5 shrink-0 rounded-full border-gray-200 text-blue-600 checked:border-blue-500 focus:ring-blue-500 disabled:pointer-events-none disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:checked:border-blue-500 dark:checked:bg-blue-500 dark:focus:ring-offset-gray-800"
                                id="payment_method_{{ $key }}">
                            <span class="ms-3 text-sm text-gray-500 dark:text-neutral-400">{{ $item }}</span>
                        </label>
                    @endforeach

                </div>
            </div>
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
                        <div class="flex w-full items-center justify-between">
                            <span class="flex flex-col">
                                <span>Shipping (JNT YES)</span>
                                <span class="text-xs">{{ $this->cart_summaries['weight_total'] }} gram</span>
                            </span>
                            <span>{{ $this->cart_summaries['shipping_total_formatted'] }}</span>
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
                    class="focus:outline-hidden inline-flex w-full items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 disabled:pointer-events-none disabled:opacity-50">
                    Place an Order
                </button>
            </div>
        </div>
    </div>
</div>
