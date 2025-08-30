<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user tier
            $user = Auth::user();
            if ($user->tier === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->tier === 'paid') {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'subscription_tier' => 'required|in:user,paid,admin',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company' => $request->company,
            'phone' => $request->phone,
            'tier' => $request->subscription_tier,
            'email_verified_at' => now(), // Auto-verify for demo
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user tier
        if ($user->tier === 'admin') {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/dashboard');
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the dashboard based on user tier
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->tier === 'admin') {
            return redirect('/admin/dashboard');
        }
        
        return view('dashboard', compact('user'));
    }

    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        
        if ($user->tier !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $users = User::where('tier', '!=', 'admin')->get();
        
        return view('admin.dashboard', compact('user', 'users'));
    }
}
