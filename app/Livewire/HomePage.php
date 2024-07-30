<?php

namespace App\Livewire;

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
#[Title('Home Page')]

class HomePage extends Component
{
    public function render()
    {
        // Render the view file from resources/views/livewire/home-page.blade.php
        return view('livewire.home-page');
    }
}
