<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Component;

class CheckoutPage extends Component
{

    public $firstName;
    public $lastName;
    public $phoneNumber;
    public $homeNumber;
    public $streetAddress;
    public $block;
    public $paymentMethod;

    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phoneNumber' => 'required',
            'homeNumber' => 'required',
            'streetAddress' => 'required',
            'block' => 'required',
            'paymentMethod' => 'required',
        ]);

        // Sanitize user input
        $firstName = htmlspecialchars($this->firstName);
        $lastName = htmlspecialchars($this->lastName);
        $phoneNumber = htmlspecialchars($this->phoneNumber);
        $homeNumber = htmlspecialchars($this->homeNumber);
        $streetAddress = htmlspecialchars($this->streetAddress);
        $block = htmlspecialchars($this->block);
        $paymentMethod = htmlspecialchars($this->paymentMethod);

        session()->flash('message', 'Order placed successfully 🎉');

        // dd($firstName, $lastName, $phoneNumber, $homeNumber, $streetAddress, $block, $paymentMethod);
    }


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
