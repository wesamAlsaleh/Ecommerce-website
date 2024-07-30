<?php

use Illuminate\Support\Facades\Route;

/**
 * Import the components from app/Livewire/*.php
 * then use them in the routes.web.php file !
 */

// Import the HomePage 
use App\Livewire\HomePage;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', HomePage::class);
