<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentDirectoryService;

class StudentAuthController extends Controller
{
    public function login(Request $request, StudentDirectoryService $directory)
    {
        $request->validate(['enrollment' => 'required|string']);
        $enrollment = strtoupper(trim($request->enrollment));

        $student = $directory->findStudent($enrollment);

        if (!$student) {
            return back()->withErrors(['enrollment' => 'Enrollment number not found in records.']);
        }

        $request->session()->put('student_enrollment', $enrollment);
        $request->session()->put('student_semester', $student['semester']);
        $request->session()->put('role', 'student');
        
        return redirect()->intended(route('syllabus'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}