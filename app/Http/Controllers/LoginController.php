<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
 
class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('logins.form');
    }
 
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended(route('items.list'));
        }
 
        return redirect()
            ->back()
            ->withInput($request->only('email'))
            ->withErrors(['alert' => 'The email or password is incorrect.']);
    }
 
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect()->route('login');
    }
}
 