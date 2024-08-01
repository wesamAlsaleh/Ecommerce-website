<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Class CategoriesPage - A Livewire Component for displaying all categories
 */

// #[Title('Categories')]

class CategoriesPage extends Component
{
    public function render()
    {

        // fetch all categories that are active from the database
        $categories = Category::where('is_active', 1)->get();

        return view('livewire.categories-page', [
            'categories' => $categories,
        ]);
    }
}
