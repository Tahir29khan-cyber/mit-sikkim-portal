<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('auth.dashboard', [
            'theoryOpen' => Setting::bool('theory_open'),
            'mcqOpen' => Setting::bool('mcq_open')
        ]);
    }

    public function update(Request $request)
    {
        Setting::updateOrCreate(['key' => 'theory_open'], ['value' => $request->has('theory_open')]);
        Setting::updateOrCreate(['key' => 'mcq_open'], ['value' => $request->has('mcq_open')]);

        return back()->with('status', 'Module access updated successfully!');
    }
}