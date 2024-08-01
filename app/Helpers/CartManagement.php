<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

/**
 * FILEPATH: /C:/xampp/htdocs/playgroung/my-shop/app/Helpers/CartManagement.php
 *
 * The CartManagement class provides methods for managing a shopping cart using cookies.
 * The cart items are stored as an array of objects in the cookie.
 */
class CartManagement
{
    /**
     * @ignore Cookie is a small piece of data stored in the user's browser. It is used to store user-specific information.
     * in this class we will use cookies to store the cart items, the cart items are stored as an array of objects in the cookie
     */


    /**
     * Adds an item to the cart.
     *
     * This method adds a product to the cart by either increasing the quantity of an existing item or adding a new item to the cart.
     *
     * @param int $product_id The ID of the product to be added to the cart.
     * @return int The total number of products in the cart after adding the item.
     */
    static public function addItemToCart($product_id)
    {
        // get all cart items from the cookie to check if the product is already in the cart
        $cartItems = self::getCartItemsFromCookie(); // expects an array of cart items or an empty array if no cart items found

        /**
         * this is how the cartItems array looks like `cartItems as index => object`
         *  [
         *      0 =>  ['product_id' => some product id, 'name' => 'product name', 'quantity' => number, 'price' => number, 'total' => number],
         *      1 => ['product_id' => some product id, 'name' => 'product name', 'quantity' => number, 'price' => number, 'total' => number],
         *      2 => ['product_id' => some product id, 'name' => 'product name', 'quantity' => number, 'price' => number, 'total' => number],
         *  ]
         * the foreach loop below will loop through the cartItems array and check if the product is already in the cart
         * the key is product_id and the item is quantity
         */

        // check if the product is already in the cart
        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            // if the product is already in the cart, if the some product id is equal to the product id that we want to add to the cart then set the existingItem to the key of the item
            if ($item['product_id'] == $product_id) {
                $existingItem = $key; // store the key of the existing item, the key is the index of the item in the cartItems array
                break;
            }
        }

        // if the product is already in the cart then increase the quantity else add the product to the cart
        if ($existingItem !== null) {
            $cartItems[$existingItem]['quantity']++; // increase the quantity of the product
            $cartItems[$existingItem]['total'] =  $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['price']; // calculate the total price of the product by multiplying the quantity by the price
        } else {
            // if the product is not in the cart then add the product to the cart
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']); // get the product from the database by product_id

            // if the product is found in the database then add the product to the cart
            if ($product) {
                $cartItems[] = [
                    'product_id' => $product_id, // product id of the product that we want to add to the cart
                    'name' => $product->name,
                    'images' => $product->images[0], // get the first image of the product
                    'quantity' => 1,
                    'price' => $product->price,
                    'total' => $product->price // total price of the product is the price of the product only since the quantity is 1
                ]; // ex of the cart item: 0 => ['product_id' => $product_id, 'name' => 'product name', 'quantity' => 1, 'price' => 100, 'total' => 100]
            }
        }

        // add the cart items to the cookie
        self::addCartItemsToCookie($cartItems);

        // return the total number of products in the cart
        return count($cartItems);
    }



    /**
     * Adds an item to the cart with a specified quantity.
     *
     * @param int $product_id The ID of the product to add to the cart.
     * @param int $quantity The quantity of the product to add to the cart. Default is 1.
     * @return int The total number of products in the cart after adding the item.
     */
    static public function addItemToCartWithQuantity($product_id, $quantity = 1)
    {
        // get all cart items from the cookie to check if the product is already in the cart
        $cartItems = self::getCartItemsFromCookie(); // expects an array of cart items or an empty array if no cart items found

        // check if the product is already in the cart
        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            // if the product is already in the cart, if the same product id is equal to the product id that we want to add to the cart then set the existingItem to the key of the item
            if ($item['product_id'] == $product_id) {
                $existingItem = $key; // store the key of the existing item, the key is the index of the item in the cartItems array
                break;
            }
        }

        // if the product is already in the cart then increase the quantity else add the product to the cart
        if ($existingItem !== null) {
            $cartItems[$existingItem]['quantity'] = $quantity; // increase the quantity of the product
            $cartItems[$existingItem]['total'] =  $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['price']; // calculate the total price of the product by multiplying the quantity by the price
        } else {
            // if the product is not in the cart then add the product to the cart
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']); // get the product from the database by product_id

            // if the product is found in the database then add the product to the cart
            if ($product) {
                $cartItems[] = [
                    'product_id' => $product_id, // product id of the product that we want to add to the cart
                    'name' => $product->name,
                    'images' => $product->images[0], // get the first image of the product
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $product->price // total price of the product is the price of the product only since the quantity is 1
                ]; // ex of the cart item: 0 => ['product_id' => $product_id, 'name' => 'product name', 'quantity' => 1, 'price' => 100, 'total' => 100]
            }
        }

        // add the cart items to the cookie
        self::addCartItemsToCookie($cartItems);

        // return the total number of products in the cart
        return count($cartItems);
    }


    /**
     * Removes an item from the cart.
     *
     * @param int $product_id The ID of the product to be removed.
     * @return array The updated cart items after removing the specified product.
     */
    public static function removeItemFromCart($product_id)
    {
        // get all cart items from the cookie
        $cartItems = self::getCartItemsFromCookie(); // expects an array of cart items

        // loop through the cart items and remove the product we want from the cart
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cartItems[$key]);
                break;
            }
        }

        // update the cart items in the cookie
        self::addCartItemsToCookie($cartItems);

        // return the updated cart items
        return $cartItems;
    }


    /**
     * Adds cart items to the cookie.
     *
     * @param array $cartItems The cart items to be added to the cookie.
     * @return void
     */
    static public function addCartItemsToCookie($cartItems)
    {
        Cookie::queue('cartItems', json_encode($cartItems), 60 * 24 * 30); // 30 days expiration time for cookie data
    }


    /**
     * Clears the cart items from the cookie.
     *
     * @return void
     */
    static public function clearCartItemsFromCookie()
    {
        Cookie::queue(Cookie::forget('cartItems'));
    }


    /**
     * Gets the cart items from the cookie.
     *
     * @return array The cart items from the cookie, if no cart items found then an empty array is returned.
     */
    static public function getCartItemsFromCookie()
    {
        // get the cart items from the cookie
        $cartItems =  json_decode(Cookie::get('cartItems'), true);

        // if no cart items found in cookie then return an empty array to avoid null pointer exception
        if (!$cartItems) {
            $cartItems = [];
        }

        return $cartItems;
    }


    /**
     * Increases the quantity of a product in the cart and updates the total price.
     *
     * @param int $product_id The ID of the product to increase the quantity for.
     * @return array The updated cart items.
     */
    static public function increaseProductQuantity($product_id)
    {
        // get all cart items from the cookie
        $cartItems = self::getCartItemsFromCookie(); // expects an array of cart items

        // loop through the cart items and increase the quantity & total price of the product we want
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $cartItems[$key]['quantity']++;
                $cartItems[$key]['total'] =  $cartItems[$key]['quantity'] * $cartItems[$key]['price']; // calculate the total price of the product by multiplying the quantity by the price
                break;
            }
        }

        // update the cart items in the cookie
        self::addCartItemsToCookie($cartItems);

        // return the updated cart items
        return $cartItems;
    }


    /**
     * Decreases the quantity of a product in the cart.
     *
     * @param int $product_id The ID of the product to decrease the quantity for.
     * @return array The updated cart items.
     */
    static public function decreaseProductQuantity($product_id)
    {
        // get all cart items from the cookie
        $cartItems = self::getCartItemsFromCookie(); // expects an array of cart items

        // loop through the cart items and decrease the quantity & total price of the product we want
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                // if the quantity is greater than 1 then decrease the quantity else remove the product from the cart
                if ($cartItems[$key]['quantity'] > 1) {
                    $cartItems[$key]['quantity']--;
                    $cartItems[$key]['total'] =  $cartItems[$key]['quantity'] * $cartItems[$key]['price']; // calculate the total price of the product by multiplying the quantity by the price
                } else {
                    unset($cartItems[$key]); // else remove the product from the cart
                }
                break;
            }
        }

        // update the cart items in the cookie
        self::addCartItemsToCookie($cartItems);

        // return the updated cart items
        return $cartItems;
    }


    /**
     * Calculates the total value of the products in the cart.
     *
     * @param array $products An array of products in the cart.
     * @return float The total value of the products in the cart.
     */
    static public function getCartTotal($products)
    {
        /**
         * the $products array is an array of cart items that looks like this
         * [
         *  0 => ['product_id' => some product id, 'name' => 'product name', 'quantity' => number, 'price' => number, 'total' => number],
         *  1 => ['product_id' => some product id, 'name' => 'product name', 'quantity' => number, 'price' => number, 'total' => number],
         * ]
         *
         * the array_column function will return an sum value of the 'total' key from the $products array
         */
        return array_sum(array_column($products, 'total'));
    }
}
