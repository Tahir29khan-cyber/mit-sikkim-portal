<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('student_enrollment')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}