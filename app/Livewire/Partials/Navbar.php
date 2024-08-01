<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{

    // the cart items count initialized to 0
    public $cartCount = 0;

    /**
     * Mount is a lifecycle method that is called when the component is initialized and before the render method, its main purpose is
     * to execute code at specific points.
     * Mount is a hook that is called only once when the component is rendered for the first time.
     * This method is used to initialize the cart items count from the cookie.
     *
     */
    public function mount()
    {
        // get the cart items count from the cookie
        $this->cartCount = count(CartManagement::getCartItemsFromCookie()); // this will get the cart items count from the cookie that already stored in the browser
    }

    // listen to the event to update the cart count
    #[On('update-cart-count')]
    public function updateCartCount($count)
    {
        $this->cartCount = $count; // this will update the cart count, it will be dynamic and will be updated when the event is fired
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
