<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * The HomePage component
 * Here we can define the logic of the HomePage component
 */

class HomePage extends Component
{
    public function render()
    {
        // Render the view file from resources/views/livewire/home-page.blade.php
        return view('livewire.home-page');
    }
}
