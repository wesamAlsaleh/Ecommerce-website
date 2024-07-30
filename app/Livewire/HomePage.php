<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * The HomePage component
 * Here we can define the logic of the HomePage component
 */

/**
 * @param php attribute for $title
 * @return mixed the title of the component will be displayed in the title of the browser tab
 */
#[Title('My shop')]

class HomePage extends Component
{
    public function render()
    {
        // Get all the brands that are active from the database
        $brands = Brand::where('is_active', 1)->get();

        // Render the view file from resources/views/livewire/home-page.blade.php
        return view('livewire.home-page', [
            'brands' => $brands, // Pass the brands to the view
        ]);
    }
}
