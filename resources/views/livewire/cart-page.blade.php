<div class="w-screen h-screen max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        {{-- title --}}
        <h1 class="text-2xl font-semibold mb-4">
            Shopping Cart
        </h1>

        {{-- the tables container --}}
        <div class="flex flex-col md:flex-row gap-4">
            {{-- the items table container --}}
            <div class="md:w-3/4">
                <div class="bg-white h-full overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    {{-- the table itself --}}
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left font-semibold">Product</th>
                                <th class="text-left font-semibold">Price</th>
                                <th class="text-left font-semibold">Quantity</th>
                                <th class="text-left font-semibold">Total</th>
                                <th class="text-left font-semibold">Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- format of $item ['product_id' => id number, 'name' => 'product name', 'quantity' => 1, 'price' => 100, 'total' => 100, 'image' => first image] --}}
                            @forelse ($cartItems as $item)
                                <tr wire:key='{{ $item['product_id'] }}'>
                                    {{-- product details --}}
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img class="h-16 w-16 mr-4" src="{{ url('storage/' . $item['image']) }}"
                                                alt="{{ $item['name'] }}">

                                            <span class="font-semibold">{{ $item['name'] }}</span>
                                        </div>
                                    </td>

                                    {{-- product price --}}
                                    <td class="py-4">{{ Number::currency($item['price'], 'bhd') }}</td>

                                    {{-- product quantity --}}
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            {{-- decreament btn --}}
                                            <button class="border rounded-md py-2 px-4 mr-2"
                                                wire:click="decrementQuantity({{ $item['product_id'] }})">
                                                -
                                            </button>

                                            {{-- quantity --}}
                                            <span class="text-center w-8">{{ $item['quantity'] }}</span>

                                            {{-- increment btn --}}
                                            <button class="border rounded-md py-2 px-4 ml-2"
                                                wire:click="incrementQuantity({{ $item['product_id'] }})">
                                                +
                                            </button>
                                        </div>
                                    </td>

                                    {{-- the total price --}}
                                    <td class="py-4">{{ Number::currency($item['total'], 'bhd') }}</td>

                                    {{-- remove the product from the cart --}}
                                    <td>
                                        <button wire:click="removeFromCart({{ $item['product_id'] }})"
                                            class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700 relative">
                                            <span wire:loading.remove
                                                wire:target="removeFromCart({{ $item['product_id'] }})">
                                                Remove
                                            </span>

                                            {{-- remove animation  --}}
                                            <span wire:loading wire:target="removeFromCart({{ $item['product_id'] }})">
                                                Removing...
                                            </span>
                                        </button>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-500 text-2xl font-semibold">
                                        <p>No items in the cart</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- the total price summary container --}}
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    {{-- text --}}
                    <h2 class="text-lg font-semibold mb-4">
                        Summary
                    </h2>

                    {{-- the summary details --}}
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($totalPrice, 'bhd') }}</span>
                    </div>

                    {{-- tax amount --}}
                    <div class="flex justify-between mb-2">
                        <span>10% taxes</span>
                        @php
                            $totalPriceAfterTax = $totalPrice + $totalPrice * 0.1;
                        @endphp
                        <span>{{ Number::currency($totalPriceAfterTax, 'bhd') }}</span>
                    </div>

                    {{-- shipping amount --}}
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>{{ Number::currency(0, 'bhd') }}</span>
                    </div>

                    <hr class="my-2">

                    {{-- total price --}}
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{ Number::currency($totalPriceAfterTax, 'bhd') }}</span>
                    </div>

                    {{-- if there is cart item then show the checkout button --}}
                    @if ($cartItems)
                        {{-- checkout button --}}
                        <a href="/checkout" class="bg-blue-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</a>
                    @else
                        {{-- empty cart message --}}
                        <p class="text-center text-lg font-semibold mt-4 text-red-500">Your cart is empty</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
