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

// Import the CheckoutPage
use App\Livewire\CheckoutPage;

// Import the MyOrdersPage
use App\Livewire\MyOrdersPage;


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


// home page route
Route::get('/', HomePage::class);

// categories pages route
Route::get('/categories', CategoriesPage::class);

// cart pages routes
Route::get('/cart', CartPage::class);
Route::get('/checkout', CheckoutPage::class);

// products pages route
Route::get('/products', ProductsPage::class);
Route::get('/products/{product}', ProductDetailPage::class);

// orders pages routes
Route::get('/my-orders', MyOrdersPage::class);
Route::get('/my-orders/{order}', MyOrdersPage::class);
