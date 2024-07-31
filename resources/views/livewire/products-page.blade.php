<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="py-10 bg-gray-50 font-poppins dark:bg-gray-800 rounded-lg">
        <div class="px-4 py-4 mx-auto max-w-7xl lg:py-6 md:px-6">
            <div class="flex flex-wrap mb-24 -mx-3">
                {{-- products nav bar --}}
                <div class="w-full pr-2 lg:w-1/4 lg:block">
                    {{-- categories container --}}
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:border-gray-900 dark:bg-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400"> Categories</h2>

                        {{-- the red line design --}}
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>

                        <ul>
                            @foreach ($categories as $category)
                                <li class="mb-4" wire:key={{ $category->id }}>
                                    <label for="{{ $category->slug }}" class="flex items-center dark:text-gray-400">
                                        <input type="checkbox" id="{{ $category->slug }}" value="{{ $category->id }}"
                                            class="w-4 h-4 mr-2" wire:model.live="selectedCategories">
                                        <span class="text-lg">{{ $category->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>

                    </div>

                    {{-- brands container --}}
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Brand</h2>

                        {{-- the red line design --}}
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>

                        <ul>
                            @foreach ($brands as $brand)
                                <li class="mb-4" wire:key={{ $brand->id }}>
                                    <label for="{{ $brand->slug }}" class="flex items-center dark:text-gray-300">
                                        <input id="{{ $brand->slug }}" value="{{ $brand->id }}" type="checkbox"
                                            class="w-4 h-4 mr-2" wire:model.live="selectedBrands">
                                        <span class="text-lg dark:text-gray-400">{{ $brand->name }}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- products status container --}}
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        <h2 class="text-2xl font-bold dark:text-gray-400">Product Status</h2>
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>
                        <ul>
                            {{-- stock status --}}
                            <li class="mb-4">
                                <label for="inStock" class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" class="w-4 h-4 mr-2" wire:model.live='inStock'>
                                    <span class="text-lg dark:text-gray-400">In Stock</span>
                                </label>
                            </li>

                            <li class="mb-4">
                                <label for="inStock" class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" class="w-4 h-4 mr-2" wire:model.live='outOfStock'>
                                    <span class="text-lg dark:text-gray-400">Out of Stock</span>
                                </label>
                            </li>

                            {{-- sale status --}}
                            <li class="mb-4">
                                <label for="onSale" class="flex items-center dark:text-gray-300">
                                    <input type="checkbox" class="w-4 h-4 mr-2" wire:model.live='onSale'>
                                    <span class="text-lg dark:text-gray-400">On Sale</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    {{-- price range container --}}
                    <div class="p-4 mb-5 bg-white border border-gray-200 dark:bg-gray-900 dark:border-gray-900">
                        {{-- range title --}}
                        <h2 class="text-2xl font-bold dark:text-gray-400">
                            Price
                        </h2>

                        {{-- the design --}}
                        <div class="w-16 pb-2 mb-6 border-b border-rose-600 dark:border-gray-400"></div>

                        {{-- range slider --}}
                        <div>
                            {{-- the number above the range --}}
                            <p class="font-semibold mb-2">
                                {{ Number::currency(0, 'bhd') }}- {{ Number::currency($priceRange, 'bhd') }}
                            </p>

                            {{-- the range itself --}}
                            <input type="range"
                                class="w-full h-2 mb-4 mt-2 bg-blue-100 rounded appearance-none cursor-pointer"
                                min="0" max="1000" value="priceRange" step="5" id="priceRange"
                                wire:model.live='priceRange'>

                            {{-- the bar   --}}
                            <div class="flex justify-between">
                                <span class="inline-block text-lg font-bold text-blue-400">0</span>
                                <span class="inline-block text-lg font-bold text-blue-400">1000</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- products container --}}
                <div class="w-full px-3 lg:w-3/4">
                    {{-- sorting section container --}}
                    <div class="px-3 mb-4 ">
                        <div class="items-center hidden px-3 py-2 bg-gray-100 md:flex dark:bg-gray-900 ">
                            <div class="flex items-center justify-between">
                                <select wire:model.live="sortBy"
                                    class="block w-48 text-base bg-gray-100 cursor-pointer dark:text-gray-400 dark:bg-gray-900">
                                    <option value="latest">Sort by latest</option>
                                    <option value="lowFirst">Sort by Price (low to high)</option>
                                    <option value="highFirst">Sort by Price (high to low)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- the products show here --}}
                    <div class="flex flex-wrap items-center ">
                        {{-- individual product container --}}
                        @foreach ($products as $product)
                            <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3">
                                <div class="border border-gray-300 dark:border-gray-700">
                                    {{-- product image --}}
                                    <div class="relative bg-gray-200">
                                        <a href="/products/{{ $product->slug }}">
                                            <img src="{{ url('storage/' . $product->images[0]) }}"
                                                alt="{{ $product->name }}" class="object-cover w-full h-56 mx-auto">
                                        </a>
                                    </div>

                                    {{-- product details --}}
                                    <div class="p-3 ">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <h3 class="text-xl font-medium dark:text-gray-400">
                                                {{ $product->name }}
                                            </h3>
                                        </div>

                                        <p class="text-lg ">
                                            <span class="text-green-600 dark:text-green-600">
                                                {{ Number::currency($product->price, 'BHD') }}
                                            </span>
                                        </p>
                                    </div>

                                    {{-- product action buttons --}}
                                    <div class="flex justify-center p-4 border-t border-gray-300 dark:border-gray-700">
                                        <a href="#"
                                            class="text-gray-500 flex items-center space-x-2 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="w-4 h-4 bi bi-cart3 " viewBox="0 0 16 16">
                                                <path
                                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                                </path>
                                            </svg>
                                            <span>Add to Cart</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- pagination section -->
                    <div class="flex justify-end mt-6">
                        {{ $products->links() }}
                    </div>
                    <!-- pagination end -->
                </div>
            </div>
        </div>
    </section>

</div>
