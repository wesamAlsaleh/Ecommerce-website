<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPage extends Component
{

    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Reset password link sent successfully.');
        } else {
            session()->flash('error', 'Unable to send reset password link.');
        }
    }


    public function render()
    {
        return view('livewire.auth.forgot-page');
    }
}
