<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use PhpParser\Node\Stmt\TryCatch;

class ForgotPage extends Component
{

    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        try {
            // dd($this->email);
            $credentials = ['email' => $this->email];

            $status = Password::sendResetLink($credentials);

            if ($status === Password::RESET_LINK_SENT) {
                session()->flash('success', 'Reset password link sent successfully.');
                $this->email = '';
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Unable to send reset password link.');
        }
    }


    public function render()
    {
        return view('livewire.auth.forgot-page');
    }
}
