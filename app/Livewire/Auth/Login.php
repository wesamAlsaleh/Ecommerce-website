<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Login extends Component
{

    public $email;
    public $password;

    /**
     * Handle the login functionality.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function login()
    {
        // validate the email and password fields
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        // if the credentials are invalid then show an error message otherwise log the user in
        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials');
            return;
        }

        // redirect to the home page
        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
