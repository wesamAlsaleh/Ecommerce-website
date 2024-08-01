<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

// import the LivewireAlert trait
use Jantinnerezo\LivewireAlert\LivewireAlert;

// change the page title
#[Title('All products')]

class ProductsPage extends Component
{


    use LivewireAlert; // use the LivewireAlert trait to enable sweet alert package

    // use the WithPagination trait to enable pagination for the products list
    use WithPagination;

    /**
     * The selected categories for the products page.
     * by the help of wire:model.lazy, we can bind the selected categories to the input fields
     *
     * @var array
     */
    #[Url] // change the page URL
    public $selectedCategories = [];

    #[Url] // change the page URL
    public $selectedBrands = [];

    #[Url] // change the page URL
    public $inStock; // if check box is checked, it will be 1, otherwise it will be null

    #[Url] // change the page URL
    public $onSale; // if check box is checked, it will be 1, otherwise it will be null

    #[Url] // change the page URL
    public $outOfStock; // if check box is checked, it will be 1, otherwise it will be null

    #[Url] // change the page URL
    public $priceRange = 1000; // the price range for the products, also this is the default value which is the maximum price

    #[Url] // change the page URL
    public $sortBy = 'latest'; // the default sorting column for the products


    /**
     * Add a product to the cart.
     *
     * @param int $product_id The ID of the product to add to the cart.
     * @return void
     */
    public function addToCart($product_id)
    {
        // add the product to the cart and get the number of products in the cart
        $numberOfProducts = CartManagement::addItemToCart($product_id);

        // send an event to update the cart count in the navbar
        $this->dispatch('update-cart-count', $numberOfProducts)->to(Navbar::class);

        // show a success alert message using sweet alert package (toaster)
        $this->alert('success', 'Product added to cart!', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
            'text' => '',
        ]);
    }

    public function render()
    {
        // fetch all products that are active and in stock
        $productQuery = Product::query()
            ->where('is_active', true);

        // if the selected categories are not empty, then filter the fetched products by the selected categories
        if (!empty($this->selectedCategories)) {
            $productQuery->whereIn('category_id', $this->selectedCategories);
        }

        // if the selected brands are not empty, then filter the fetched products by the selected brands
        if (!empty($this->selectedBrands)) {
            $productQuery->whereIn('brand_id', $this->selectedBrands);
        }

        // if the inStock is 1, then filter the fetched products by the inStock
        if ($this->inStock) {
            $productQuery->where('in_stock', 1);
        }

        // if the outOfStock is 1, then filter the fetched products by the outOfStock
        if ($this->outOfStock) {
            $productQuery->where('in_stock', 0);
        }

        // if the onSale is 1, then filter the fetched products by the onSale
        if ($this->onSale) {
            $productQuery->where('on_sale', 1);
        }

        // get the products that are less than or equal to the price range
        if ($this->priceRange) {
            $productQuery->whereBetween('price', [0, $this->priceRange]);
        }

        // sort the fetched products by the selected sorting column (latest or price)
        if ($this->sortBy === 'latest') {
            $productQuery->latest(); // sort the products by the latest
        } else if ($this->sortBy === 'lowFirst') {
            $productQuery->orderBy('price'); // sort the products by the price (low to high)
        } else if ($this->sortBy === 'highFirst') {
            $productQuery->orderByDesc('price'); // sort the products by the price (high to low)
        }


        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9), // pass the products to the page, also enable to paginate the results to 9 per page `$products->links()`
            'brands' => Brand::where('is_active', 1)->get(), // fetch all active brands from the database
            'categories' => Category::where('is_active', 1)->get(), // fetch all active categories from the database
        ]);
    }
}
