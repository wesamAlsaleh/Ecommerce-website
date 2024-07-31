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

    /**
     * The selected brands for the products page.
     * by the help of wire:model.lazy, we can bind the selected brands to the input fields
     *
     * @var array
     */

    #[Url] // change the page URL
    public $selectedBrands = [];



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

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9), // pass the products to the page, also enable to paginate the results to 9 per page `$products->links()`
            'brands' => Brand::where('is_active', 1)->get(), // fetch all active brands from the database
            'categories' => Category::where('is_active', 1)->get(), // fetch all active categories from the database
        ]);
    }
}
