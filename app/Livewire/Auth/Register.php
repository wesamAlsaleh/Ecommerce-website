<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $password;

    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        // Validate user input
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        // Create new user record in the database
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Authenticate the user and log them in
        auth()->login($user);

        // Redirect to the previous page which is the home page
        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
