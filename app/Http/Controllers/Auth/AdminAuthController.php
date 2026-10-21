<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentDirectoryService;

class AdminAuthController extends Controller
{
    public function login(Request $request, StudentDirectoryService $directory)
    {
        $request->validate([
            'email' => 'required|string', // Using email field for Username
            'password' => 'required|string',
        ]);

        if ($directory->verifyAdmin($request->email, $request->password)) {
            $request->session()->regenerate();
            $request->session()->put('role', 'admin');
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid Admin credentials from Google Sheet.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('admin.login');
    }
}