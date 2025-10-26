<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect by tier; guest/user/paid go to dashboard, admin to admin dashboard.
            $user = Auth::user();
            if ($user->tier === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/dashboard');
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
     *
     * NOTE:
     * - New users ALWAYS start with tier = 'guest'
     * - The plan selection is ONLY used to set redirect_to on the frontend (not tier here)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8|confirmed',
            'company'               => 'nullable|string|max:255',
            'phone'                 => 'nullable|string|max:32',
            // no subscription_tier/plan_choice here on purpose
            'redirect_to'           => 'nullable|string', // set by the form to /dashboard or /book
        ]);

        // Create user. If your User model has ['password' => 'hashed'] cast,
        // this will be hashed automatically.
        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => $validated['password'],
            'company'           => $validated['company'] ?? null,
            'phone'             => $validated['phone'] ?? null,
            'tier'              => 'user',               // <- everyone starts as guest
            'email_verified_at' => now(),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // Respect redirect_to posted by the form; default to /dashboard
        $to = $request->input('redirect_to', '/dashboard');
        return redirect()->intended($to);
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
