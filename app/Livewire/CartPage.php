<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CartPage extends Component
{

    use LivewireAlert; // use the LivewireAlert trait to enable sweet alert package

    /**
     * The array of cart items.
     *
     * @var array
     */
    public $cartItems = [];

    /**
     * The total price of the cart.
     *
     * @var float
     */
    public $totalPrice = 0;

    /**
     * Mount the component.
     * It retrieves the cart items from the cookie and calculates the total price.
     *
     * @return void
     */

    public function mount()
    {
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->totalPrice = CartManagement::getCartTotalPrice($this->cartItems);
    }


    /**
     * Remove a product from the cart and update the total price.
     *
     * @param int $productId The ID of the product to remove.
     * @return void
     */
    public function removeFromCart($productId)
    {
        $this->cartItems = CartManagement::removeItemFromCart($productId);
        $this->totalPrice = CartManagement::getCartTotalPrice($this->cartItems);

        // send an event to update the cart icon in the header, the event name is 'update-cart-count'
        $this->dispatch('update-cart-count', count($this->cartItems))->to(Navbar::class);

        // show a success alert message using sweet alert package (toaster)
        $this->alert('info', 'Product has been successfully removed from the cart', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'text' => '',
        ]);
    }

    /**
     * Increment the quantity of a product in the cart and update the total price.
     *
     * @param int $productId The ID of the product to increment the quantity for.
     * @return void
     */
    public function incrementQuantity($productId)
    {
        $this->cartItems = CartManagement::increaseProductQuantity($productId);
        $this->totalPrice = CartManagement::getCartTotalPrice($this->cartItems);
    }

    /**
     * Decrement the quantity of a product in the cart and update the total price.
     *
     * @param int $productId The ID of the product to decrement the quantity for.
     * @return void
     */
    public function decrementQuantity($productId)
    {
        $this->cartItems = CartManagement::decreaseProductQuantity($productId);
        $this->totalPrice = CartManagement::getCartTotalPrice($this->cartItems);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
