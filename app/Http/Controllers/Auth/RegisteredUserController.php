<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Step 1: Validate the form, including hCaptcha response
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'h-captcha-response' => ['required'], // Ensure hCaptcha response is present
        ]);
    
        // Step 2: Verify hCaptcha with the hCaptcha API
        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => config('captcha.secret'), // This should match the key in your .env file
            'response' => $request->input('h-captcha-response'),
            'remoteip' => $request->ip(),
        ]);
    
        // Step 3: Check if hCaptcha verification was successful
        if (!optional($response->json())['success']) {
            return back()->withErrors(['captcha' => 'hCaptcha verification failed. Please try again.']);
        }
    
        // Step 4: Proceed with user registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect()->intended(url('/'));
    }
}
