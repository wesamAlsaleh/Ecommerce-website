<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

// #[Title('Product Detail')]

class ProductDetailPage extends Component
{
    use LivewireAlert; // use the LivewireAlert trait to enable sweet alert package

    public $slug; // property to store the slug

    public $quantity = 1; // property to store the quantity, its passed to the view to be used in the input field in `wire:model="quantity"`

    /**
     * Mount the component with the slug from the URL
     */
    public function mount($slug)
    {
        $this->slug = $slug; // set the slug to the property
    }

    /**
     * Increment the quantity by 1.
     */
    public function incrementQuantity()
    {
        $this->quantity++;
    }

    /**
     * Decrement the quantity by 1, but make sure it doesn't go below 1.
     */
    public function decrementQuantity()
    {
        $this->quantity = $this->quantity > 1 ? $this->quantity - 1 : 1;
    }

    /**
     * Add a product to the cart.
     *
     * @param int $product_id The ID of the product to add to the cart.
     * @return void
     */
    public function addToCart($product_id)
    {
        // add the product to the cart
        $numberOfProducts = CartManagement::addItemToCart($product_id);

        // send an event to update the cart count in the navbar
        $this->dispatch('update-cart-count', $numberOfProducts)->to(Navbar::class);

        // show a success alert message using sweet alert package
        $this->alert('success', 'Product added to cart!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'text' => '',
        ]);
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(), // fetch the product by the slug
        ]);
    }
}
