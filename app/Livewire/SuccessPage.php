<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SuccessPage extends Component
{

    // get the session ID from the URL
    #[Url]
    public $sessionId;


    public function render()
    {
        // Get the latest order of the authenticated user using the Order model 'address' relationship method
        $latestOrder = Order::with('address')->where('user_id', auth()->id())->latest()->first();

        // Get the session info from Stripe using the session ID
        if ($this->sessionId) {
            Stripe::setApiKey(env('STRIPE_SECRET')); // set the stripe secret key
            $sessionInfo = Session::retrieve($this->sessionId); // get the session info from stripe by the session ID provided from the URL

            // dd($sessionInfo); // dump the session info to see the data 🔴😶

            // update the payment status of the latest order based on the payment status of the session info
            if ($sessionInfo->payment_status === 'paid') {
                // update the payment status of the latest order to 'paid'
                $latestOrder->payment_status = 'paid';

                // save the changes
                $latestOrder->save();
            } else if ($sessionInfo->payment_status != 'paid') {
                // update the payment status of the latest order to 'failed'
                $latestOrder->payment_status = 'failed';

                // save the changes
                $latestOrder->save();

                return redirect()->route('/cancel'); // redirect to the failed page
            }
        }


        return view('livewire.success-page', [
            'latestOrder' => $latestOrder, // pass the latest order to the view
        ]);
    }
}
