<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail')]

class ProductDetailPage extends Component
{

    public $slug; // property to store the slug

    /**
     * Mount the component with the slug from the URL
     */
    public function mount($slug)
    {
        $this->slug = $slug; // set the slug to the property
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(), // fetch the product by the slug
        ]);
    }
}
