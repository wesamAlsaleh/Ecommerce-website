<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Component;

class CheckoutPage extends Component
{
    public function render()
    {

        // Get cart items from cookie
        $cartItems = CartManagement::getCartItemsFromCookie();

        // Get total price of cart items
        $totalPrice = CartManagement::getCartTotalPrice($cartItems);

        return view('livewire.checkout-page', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }
}
