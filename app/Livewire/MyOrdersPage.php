<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrdersPage extends Component
{

    // Use the WithPagination trait to paginate the orders
    use WithPagination;

    public function render()
    {
        $userOrders = Order::where('user_id', auth()->id())->latest()->paginate(10); // Get the user's orders and paginate them with 10 orders per page

        return view('livewire.my-orders-page', [
            'userOrders' => $userOrders,
        ]);
    }
}
