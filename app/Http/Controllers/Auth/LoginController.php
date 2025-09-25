<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware will be handled in routes
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // If login fails, redirect back with error
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password tidak valid.']);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Get the authenticated user before logout
        $user = Auth::user();
        
        // Perform logout
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Clear remember me cookie if exists
        if ($request->hasCookie(Auth::getRecallerName())) {
            $cookie = cookie()->forget(Auth::getRecallerName());
        }

        // Log the logout activity (optional)
        if ($user) {
            \Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);
        }

        // Handle AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Successfully logged out',
                'redirect' => route('login')
            ], 200);
        }

        // Regular redirect with additional security headers
        $response = redirect()->route('login')->with('status', 'You have been successfully logged out.');
        
        // Add security headers to prevent caching
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        
        return $response;
    }
}
