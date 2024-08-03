<div class="w-full h-screen max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <h1 class="text-4xl font-bold text-slate-500">My Orders</h1>
    <div class="flex flex-col bg-white p-5 rounded mt-4 shadow-lg">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                {{-- <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Order Number
                                </th> --}}

                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Order Status
                                </th>

                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Payment Status
                                </th>

                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                    Order Amount
                                </th>

                                <th scope="col"
                                    class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- order container --}}
                            @foreach ($userOrders as $order)
                                <tr wire:key='{{ $order->id }}'
                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-900 dark:even:bg-slate-800">
                                    {{--   Order number  --}}
                                    {{-- <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ $order->id }}
                                    </td> --}}

                                    {{-- order date --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $order->created_at }}
                                    </td>

                                    {{-- Order Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <span class="bg-orange-500 py-1 px-3 rounded text-white shadow">
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    {{-- Payment Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        <span class="bg-slate-600 py-1 px-3 rounded text-white shadow">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>

                                    {{-- total price --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ Number::currency($order->total, 'bhd') }}
                                    </td>

                                    {{-- link to order details --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <a href="/orders/{{ $order->id }}"
                                            class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-slate-500">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- the build in pagination --}}
            <div class=" flex justify-end my-4 px-2">
                {{ $userOrders->links() }}
            </div>
        </div>
    </div>
</div>
