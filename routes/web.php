<?php

use Illuminate\Support\Facades\Route;

/**
 * Import the components from app/Livewire/*.php
 * then use them in the routes.web.php file !
 */

// Import the HomePage 
use App\Livewire\HomePage;

// Import the CategoriesPage
use App\Livewire\CategoriesPage;

// Import the ProductsPage
use App\Livewire\ProductsPage;

// Import the CartPage
use App\Livewire\CartPage;

// Import the ProductDetailPage
use App\Livewire\ProductDetailPage;

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


// home page routes
Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/products', ProductsPage::class);
Route::get('/cart', CartPage::class);

// product detail page route
Route::get('/products/{product}', ProductDetailPage::class);
