<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800">
        <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">
            <div class="flex flex-wrap -mx-4">

                {{-- images container --}}
                <div class="w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ url('storage/' . $product->images[0]) }}' }">
                    <div class="top-0 z-50 overflow-hidden">
                        {{-- the main image --}}
                        <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">
                            <img x-bind:src="mainImage" alt="{{ $product->name }}"
                                class="object-cover w-full lg:h-full ">
                        </div>

                        {{-- the other images --}}
                        <div class="flex-wrap hidden md:flex ">
                            @foreach ($product->images as $image)
                                <div class="w-1/2 p-2 sm:w-1/4" {{-- on click, change the main image --}}
                                    x-on:click="mainImage='{{ url('storage/' . $image) }}'">
                                    <img src="{{ url('storage/' . $image) }}" alt="{{ $product->name }}"
                                        class="object-cover w-full lg:h-20 cursor-pointer hover:border hover:border-blue-500">
                                </div>
                            @endforeach
                        </div>

                        {{-- free shipping icon --}}
                        <div class="px-6 pb-6 mt-6 border-t border-gray-300 dark:border-gray-400">
                            <div class="flex flex-wrap items-center mt-6">
                                <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="w-4 h-4 text-gray-700 dark:text-gray-400 bi bi-truck"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                        </path>
                                    </svg>
                                </span>
                                <h2 class="text-lg font-bold text-gray-700 dark:text-gray-400">Free Shipping</h2>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- details container --}}
                <div class="w-full px-4 md:w-1/2 ">
                    <div class="lg:pl-20">
                        <div class="mb-8 [&>ul]:list-disc [&>ul]:ml-4">
                            {{-- product name --}}
                            <h2 class="max-w-xl mb-6  dark:text-gray-400 md:text-4xl">
                                <span class="text-4xl font-bold">{{ $product->name }}</span>
                            </h2>

                            {{-- product price details --}}
                            @php
                                $stockStatus = $product->in_stock > 0 ? 'In Stock' : 'Out of Stock';
                                $disabeldAddToCartButton = $product->in_stock > 0 ? false : true;
                            @endphp

                            <div class="inline-block mb-6">
                                {{-- product price --}}
                                <p class="text-2xl font-bold text-gray-700 dark:text-gray-400">
                                    {{ Number::currency($product->price, 'BHD') }}
                                </p>

                                {{-- product availability --}}
                                <p class="text-lg">
                                    <span
                                        class={{ $product->in_stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}>
                                        {{ $stockStatus }}
                                    </span>
                                </p>
                            </div>

                            {{-- product description --}}
                            <div class="max-w-md text-sm  dark:text-gray-400">
                                {{-- {{ $product->description }} --}}
                                {{-- Convert Markdown to HTML --}}
                                {!! Str::markdown($product->description) !!}
                            </div>
                        </div>

                        {{-- quantity section --}}
                        <div class="w-32 mb-8 ">
                            {{-- the label and the underline design --}}
                            <label
                                for=""class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400">
                                Quantity
                            </label>

                            {{-- the quantity counter --}}
                            <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                                {{-- - button --}}
                                <button wire:click="decrementQuantity"
                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">-</span>
                                </button>

                                {{-- the counter --}}
                                <input type="number" readonly
                                    class="flex items-center w-full font-bold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                                    placeholder="1" wire:model='quantity'>

                                {{-- + button --}}
                                <button wire:click="incrementQuantity"
                                    class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                    <span class="m-auto text-2xl font-thin">+</span>
                                </button>
                            </div>
                        </div>


                        {{-- add to cart button --}}
                        <div class="flex flex-wrap items-center gap-4">
                            @if ($product->in_stock > 0)
                                <button wire:click="addToCart({{ $product->id }})"
                                    class="w-full p-4 bg-blue-500 rounded-md lg:w-2/5 dark:text-gray-200 text-sm text-gray-50 hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-700 font-bold">

                                    {{-- add to cart text --}}
                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                        Add to cart
                                    </span>

                                    {{-- add to cart loading animation --}}
                                    <span wire:loading wire:target="addToCart({{ $product->id }})"
                                        class="text-sm flex items-center space-x-2">
                                        {{-- the circle animation --}}
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l1.737-1.032zm10-5.291a7.962 7.962 0 01-3 5.291l1.737 1.032C18.865 17.824 20 15.042 20 12h-4zm-2-5.291V4.062A7.962 7.962 0 0116 12h4c0-6.627-5.373-12-12-12z">
                                            </path>
                                        </svg>
                                        {{-- <span>Adding to cart</span> --}}
                                    </span>
                                </button>
                            @else
                                <button
                                    class="w-full p-4 bg-gray-300 rounded-md lg:w-2/5 dark:text-gray-200 text-sm text-gray-50 font-bold cursor-not-allowed">
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
