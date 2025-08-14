<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $student = Student::where('username', $request->username)->first();

        if (!$student) {
            return back()->withErrors(['message' => 'بيانات الدخول غير صحيحة']);
        }

        if (!Hash::check($request->password, $student->password)) {
            return back()->withErrors(['message' => 'بيانات الدخول غير صحيحة']);
        }

        session(['student_id' => $student->id]);
        return redirect()->intended('/schedule');
    }

    public function logout(Request $request) {
        // dd('logout called');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
