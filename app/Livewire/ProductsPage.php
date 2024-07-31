<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

// change the page title
#[Title('All products')]

class ProductsPage extends Component
{

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

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9), // pass the products to the page, also enable to paginate the results to 9 per page `$products->links()`
            'brands' => Brand::where('is_active', 1)->get(), // fetch all active brands from the database
            'categories' => Category::where('is_active', 1)->get(), // fetch all active categories from the database
        ]);
    }
}
