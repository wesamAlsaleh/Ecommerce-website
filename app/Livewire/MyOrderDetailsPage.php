<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Livewire\Component;

class MyOrderDetailsPage extends Component
{

    public $orderId;

    // get the order id from the url
    public function mount(Request $request)
    {
        $this->orderId = $request->route('orderId');
    }

    public function render()
    {
        /**
         * Retrieves the order items with their associated products for the given order ID.
         *
         * @return \Illuminate\Database\Eloquent\Collection|OrderItem[]
         */
        $orderItems = OrderItem::with('product')->where('order_id', $this->orderId)->get();

        /**
         * Retrieves the address associated with the given order ID.
         *
         * @return \Illuminate\Database\Eloquent\Model|Address|null
         */

        $address = Address::where('order_id', $this->orderId)->first();

        /**
         * Retrieves the order with the given order ID.
         *
         * @return \Illuminate\Database\Eloquent\Model|Order|null
         */
        $order = Order::where('id', $this->orderId)->first();

        return view('livewire.my-order-details-page', [
            'order' => $order, // pass the order
            'orderItems' => $orderItems, // pass the order items to the view
            'address' => $address, // pass the address
        ]);
    }
}
