<div class="w-full h-screen max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    {{-- title --}}
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
        Checkout
    </h1>
    <div class="grid grid-cols-12 gap-4">
        <div class="md:col-span-12 lg:col-span-8 col-span-12">
            <!-- Card -->
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                <!-- Shipping Address -->
                <div class="mb-6">

                    {{-- card title --}}
                    <h2 class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                        Shipping Address
                    </h2>

                    {{-- form --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- first name field --}}
                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="first_name">
                                First Name
                            </label>

                            <input
                                class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                                id="first_name" type="text">
                            </input>
                        </div>

                        {{-- last name field --}}
                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="last_name">
                                Last Name
                            </label>
                            <input
                                class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                                id="last_name" type="text">
                            </input>
                        </div>
                    </div>

                    {{--  phone number field --}}
                    <div class="mt-4">
                        <label class="block text-gray-700 dark:text-white mb-1" for="phone">
                            Phone
                        </label>
                        <input
                            class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                            id="phone" type="text">
                        </input>
                    </div>

                    {{-- home & block fields --}}
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="state">
                                Home number
                            </label>
                            <input
                                class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                                id="state" type="text">
                            </input>
                        </div>

                        <div>
                            <label class="block text-gray-700 dark:text-white mb-1" for="zip">
                                Block
                            </label>
                            <input
                                class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                                id="zip" type="text">
                            </input>
                        </div>
                    </div>

                    {{-- street address field --}}
                    <div class="mt-4">
                        <label class="block text-gray-700 dark:text-white mb-1" for="address">
                            Street address
                        </label>
                        <input
                            class="w-full rounded-lg border py-2 px-3 dark:bg-gray-700 dark:text-white dark:border-none"
                            id="address" type="text">
                        </input>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="text-lg font-semibold mb-4">
                    Select Payment Method
                </div>

                {{-- payment method radio buttons --}}
                <ul class="grid w-full gap-6 md:grid-cols-2">
                    {{-- cash on delivery option --}}
                    <li>
                        <input class="hidden peer" id="hosting-small" name="hosting" required="" type="radio"
                            value="hosting-small" />
                        <label
                            class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700"
                            for="hosting-small">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">
                                    Cash on Delivery
                                </div>
                            </div>
                            <svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none"
                                viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2">
                                </path>
                            </svg>
                        </label>
                    </li>

                    {{-- stripe option --}}
                    <li>
                        <input class="hidden peer" id="hosting-big" name="hosting" type="radio" value="hosting-big">
                        <label
                            class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700"
                            for="hosting-big">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">
                                    Stripe
                                </div>
                            </div>
                            <svg aria-hidden="true" class="w-5 h-5 ms-3 rtl:rotate-180" fill="none"
                                viewbox="0 0 14 10" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 5h12m0 0L9 1m4 4L9 9" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2">
                                </path>
                            </svg>
                        </label>
                        </input>
                    </li>
                </ul>
            </div>
            <!-- End Card -->
        </div>

        <!-- Order Summary -->
        <div class="md:col-span-12 lg:col-span-4 col-span-12">
            <div class="bg-white rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                {{-- card title --}}
                <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                    ORDER SUMMARY
                </div>

                {{-- total price --}}
                <div class="flex justify-between mb-2 font-bold">
                    <span>
                        Subtotal
                    </span>
                    <span>
                        {{ Number::currency($totalPrice, 'bhd') }}
                    </span>
                </div>

                {{-- taxes price --}}
                <div class="flex justify-between mb-2 font-bold">
                    @php
                        $totalPriceAfterTax = $totalPrice + $totalPrice * 0.1;
                    @endphp
                    <span>10% taxes</span>
                    <span>
                        {{ Number::currency($totalPriceAfterTax, 'bhd') }}
                    </span>
                </div>

                {{-- shipping cost --}}
                <div class="flex justify-between mb-2 font-bold">
                    <span>
                        Shipping Cost
                    </span>
                    <span>
                        {{ Number::currency(0, 'bhd') }}
                    </span>
                </div>

                {{-- seperator --}}
                <hr class="bg-slate-400 my-4 h-1 rounded">

                {{-- total price after the tax and shipping --}}
                <div class="flex justify-between mb-2 font-bold">
                    <span>
                        Total price
                    </span>
                    @php
                        $finalTotalPrice = $totalPriceAfterTax + 0;
                    @endphp

                    <span>
                        {{ Number::currency($finalTotalPrice, 'bhd') }}
                    </span>
                </div>
                {{-- </hr> --}}
            </div>

            {{-- place order button --}}
            <button class="bg-green-500 mt-4 w-full p-3 rounded-lg text-lg text-white hover:bg-green-600">
                Place Order
            </button>

            {{-- basket summary --}}
            <div class="bg-white mt-4 rounded-xl shadow p-4 sm:p-7 dark:bg-slate-900">
                {{-- card title --}}
                <div class="text-xl font-bold underline text-gray-700 dark:text-white mb-2">
                    CART SUMMARY
                </div>

                {{-- basket items --}}
                <ul class="divide-y divide-gray-200 dark:divide-gray-700" role="list">
                    {{-- item container --}}
                    @foreach ($cartItems as $item)
                        <li class="py-3 sm:py-4" wire:key='{{ $item['product_id'] }}'>
                            <div class="flex items-center">
                                {{-- image container --}}
                                <div class="flex-shrink-0">
                                    <img src="{{ url('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                        class="w-12 h-12">
                                    </img>
                                </div>

                                {{-- product details --}}
                                <div class="flex-1 min-w-0 ms-4">
                                    {{-- product name --}}
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ $item['name'] }}
                                    </p>

                                    {{-- product quantity --}}
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        Quantity: {{ $item['quantity'] }}
                                    </p>
                                </div>

                                {{-- product price --}}
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                    {{ Number::currency($item['total'], 'bhd') }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
