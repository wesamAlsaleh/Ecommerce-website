<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPage extends Component
{

    public $email;

    /**
     * Handle the request for resetting the password.
     *
     * @return void
     */
    public function requestReset()
    {
        // validate the email
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // send the reset link
        $response = Password::sendResetLink(
            // $this->only('email')
            ['email' => $this->email]
        );

        // check the response if the reset link was sent
        if ($response == Password::RESET_LINK_SENT) {
            // show a success message
            session()->flash('success', 'Reset link sent successfully');

            // clear the email field
            $this->email = '';
        }

        // $this->emit('status', $response);
    }

    public function render()
    {
        return view('livewire.auth.forgot-page');
    }
}
