<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Address;
use App\Models\Order;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutPage extends Component
{

    public $firstName;
    public $lastName;
    public $phoneNumber;
    public $homeNumber;
    public $streetAddress;
    public $block;
    public $paymentMethod;

    /**
     * Place an order.
     *
     * This method validates the user input, sanitizes the input values, retrieves cart items from the cookie,
     * calculates the line items for the Stripe payment, creates a new order in the database, sets order details,
     * creates a new address in the database, sets address details, checks the payment method to redirect the user
     * to the payment gateway or show a success message, saves the order and address in the database, creates order
     * items in the database, clears the cart items from the cookie, and redirects the user to the payment gateway
     * or success page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phoneNumber' => 'required',
            'homeNumber' => 'required',
            'streetAddress' => 'required',
            'block' => 'required',
            'paymentMethod' => 'required',
        ]);


        // Sanitize user input
        $firstName = htmlspecialchars($this->firstName);
        $lastName = htmlspecialchars($this->lastName);
        $phoneNumber = htmlspecialchars($this->phoneNumber);
        $homeNumber = htmlspecialchars($this->homeNumber);
        $streetAddress = htmlspecialchars($this->streetAddress);
        $block = htmlspecialchars($this->block);
        $paymentMethod = htmlspecialchars($this->paymentMethod);

        // dd($paymentMethod);

        // Get cart items from cookie
        $cartItems = CartManagement::getCartItemsFromCookie();

        // stipe payment logic start here
        $lineItems = [];

        foreach ($cartItems as $item) {

            $unitPriceWithTaxAmount = $item['price'] * 0.1; // the tax is 10% of the item price
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bhd',
                    'unit_amount' => $unitPriceWithTaxAmount,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // Create a new order in the database
        $order = new Order();

        // Set order details
        $order->user_id = auth()->id();
        $order->total = (CartManagement::getCartTotalPrice($cartItems) * 0.1); // the tax is 10% of the total price
        $order->payment_method = $paymentMethod;
        $order->payment_status = 'pending'; // pending until payment is successful
        $order->status = 'pending'; // pending until the order is delivered
        $order->currency = 'bhd'; //TODO: remove hard coded currency
        $order->shipping_fee = 0; // free shipping
        $order->shipping_method = 'standard'; // standard shipping
        $order->notes = 'Order placed by' . auth()->user()->name; // order placed by the `user name`


        // Create a new address in the database
        $address = new Address();

        // Set address details
        $address->first_name = $firstName;
        $address->last_name = $lastName;
        $address->phone = $phoneNumber;
        $address->home = $homeNumber;
        $address->street = $streetAddress;
        $address->block = $block;

        // initialize the redirect url
        $redirectUrl = '';

        // Check the payment method to redirect the user to the payment gateway or show a success message
        if ($paymentMethod === 'stripe') {
            // set stripe secret key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create a new stripe session
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'], // only card payment is allowed
                'customer_email' => auth()->user()->email, // user email
                'line_items' => $lineItems, // items in the cart
                'mode' => 'payment', // payment mode
                'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}', // success url
                'cancel_url' => route('failed'), // failed url is the cancel url in the route file
            ]);

            // Redirect the user to the stripe checkout page using the stripe session url from the stripe response (built-in stripe feature)
            $redirectUrl = $sessionCheckout->url;
        } else {
            route('success'); // redirect to the success page
        }

        // Save the order and address in the database
        $order->save();
        $address->order_id = $order->id; // set the order id in the address table to link the order with the address
        $address->save();

        // create order items in the database using the relationship in the Order model
        $order->orderItems()->createMany($cartItems);

        // Clear the cart items from the cookie
        CartManagement::clearCartItemsFromCookie();

        // Redirect the user to the payment gateway or success page
        return redirect($redirectUrl);
    }


    public function render()
    {
        // Get cart items from cookie
        $cartItems = CartManagement::getCartItemsFromCookie();

        // Get total price of cart items
        $totalPrice = CartManagement::getCartTotalPrice($cartItems);

        return view('livewire.checkout-page', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ]);
    }
}
