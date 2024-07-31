<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

// change the page title
#[Title('All products')]

class ProductsPage extends Component
{

    // use the WithPagination trait to enable pagination for the products list
    use WithPagination;

    public function render()
    {
        // fetch all products that are active and in stock
        $productQuery = Product::query()
            ->where('is_active', true);

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9), // pass the products to the page, also enable to paginate the results to 9 per page `$products->links()`
            'brands' => Brand::where('is_active', 1)->get(), // fetch all active brands from the database
            'categories' => Category::where('is_active', 1)->get(), // fetch all active categories from the database
        ]);
    }
}
