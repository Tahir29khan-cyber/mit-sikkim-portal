<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginPageController extends Controller
{
    public function student()
    {
        return view('auth.login', ['role' => 'student']);
    }

    public function admin()
    {
        return view('auth.login', ['role' => 'admin']);
    }
}