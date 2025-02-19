<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // $credentials['role'] = 'admin'; // Ensure the user is an admin

        if (Auth::attempt($credentials)) {
            return redirect()->route('announcement'); // Redirect to admin dashboard
        }

        return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
    }
}
