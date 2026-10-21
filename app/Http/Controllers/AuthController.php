<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentDirectoryService;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('role') === 'admin') return redirect()->route('admin.dashboard');
        if (session()->has('student_enrollment')) return redirect()->route('syllabus');
        return view('auth.login');
    }

    public function login(Request $request, StudentDirectoryService $directory)
    {
        $request->validate(['role' => 'required|in:admin,student']);

        // --- ADMIN LOGIN ---
        if ($request->role === 'admin') {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            // Check against Google Sheet
            if ($directory->verifyAdmin($request->username, $request->password)) {
                $request->session()->regenerate();
                $request->session()->put('role', 'admin');
                return redirect()->route('admin.dashboard');
            }
            
            return back()->withErrors(['username' => 'Invalid Admin Username or Password.']);
        }

        // --- STUDENT LOGIN --- (Keep existing code)
        $request->validate(['enrollment_no' => 'required|string']);
        $enrollment = strtoupper(trim($request->enrollment_no));
        $student = $directory->findStudent($enrollment);

        if (!$student) {
            return back()->withErrors(['enrollment_no' => 'Enrollment number not found in University records.']);
        }

        $request->session()->put('student_enrollment', $enrollment);
        $request->session()->put('student_max_sem', $student['semester']);
        $request->session()->put('role', 'student');
        
        return redirect()->intended(route('syllabus'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush(); // Completely clear the session
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the session role is 'admin'
        if (session('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Admin access required.']);
        }
        return $next($request);
    }
}