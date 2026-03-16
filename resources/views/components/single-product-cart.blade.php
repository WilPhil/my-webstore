<div {{ $attributes }} class="flex items-center gap-5 pb-5 border-b border-gray-200">
    <div class="relative w-40 h-40 overflow-hidden rounded-xl">
        <img class="object-coversize-full" src="{{ $item->product()->coverUrl }}" alt="{{ $item->sku }}">
    </div>
    <div class="flex items-center">
        <div class="py-5">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                {{ $item->product()->name }}
            </h3>
            <h2 class="text-sm text-gray-800">{{ $item->product()->tags }}</h2>
            <div class="flex items-center gap-2 my-5">

                <livewire:cart.add-to-cart :product="$item->product()" />

                <p class="px-3 py-2 mt-1 text-xl font-semibold text-black dark:text-black">
                    {{ $item->product()->formattedPrice }}
                </p>
            </div>
        </div>
    </div>
</div>
