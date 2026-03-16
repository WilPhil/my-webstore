<div class="container mx-auto max-w-[85rem] w-full px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid gap-10 md:grid-cols-10">
        <div class="md:col-span-7">
            <h1 class="mb-5 text-2xl font-light">Shopping Bag</h1>
            <div class="grid gap-5">
                @forelse ($this->items as $item)
                    <div class="flex items-center gap-5 pb-5 border-b border-gray-200">
                        <div class="relative w-40 h-40 overflow-hidden rounded-xl">
                            <img class="object-cover size-full" src="{{ $item->product()->coverUrl }}"
                                alt="{{ $item->sku }}">
                        </div>
                        <div class="w-full flex items-center">
                            <div class="w-full py-5">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                            {{ $item->product()->name }}
                                        </h3>
                                        <h2 class="text-sm text-gray-800">{{ $item->product()->tags }}</h2>
                                    </div>
                                    <livewire:cart.remove-product :product="$item->product()" />
                                </div>
                                <p class="py-2 text-xl font-semibold text-black dark:text-black">
                                    {{ $item->product()->formattedPrice }}
                                </p>
                                <div class="flex items-center gap-2 ">
                                    <livewire:cart.add-to-cart wire:key='add-to-cart-{{ $item->sku }}'
                                        :product="$item->product()" />
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        Cart is empty.
                    </div>
                @endforelse
            </div>
        </div>
        <div class="md:col-span-3">
            <h1 class="mb-5 text-2xl font-light">Order Summary</h1>
            <div class="grid gap-5">
                <!-- List Group -->
                <ul class="flex flex-col mt-3">
                    <li
                        class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                        <div class="flex items-center justify-between w-full">
                            <span>Sub Total</span>
                            <span>{{ $this->subTotal }}</span>
                        </div>
                    </li>
                    <li
                        class="inline-flex items-center px-4 py-3 -mt-px text-sm text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                        <div class="flex items-center justify-between w-full">
                            <span>Shipping</span>
                            <span>{{ $this->shipping }}</span>
                        </div>
                    </li>
                    <li
                        class="inline-flex items-center px-4 py-3 -mt-px text-sm font-semibold text-gray-800 border border-gray-200 gap-x-2 first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                        <div class="flex items-center justify-between w-full">
                            <span>Total</span>
                            <span>{{ $this->total }}</span>
                        </div>
                    </li>
                </ul>
                <!-- End List Group -->
                <button type="button" wire:click='checkout' wire:loading.attr='disabled'
                    class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg cursor-pointer gap-x-2 hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <span wire:loading.remove> Checkout Now</span>

                    <div wire:loading
                        class="animate-spin inline-block size-4 border-3 border-current border-t-transparent rounded-[999px] text-primary"
                        role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
