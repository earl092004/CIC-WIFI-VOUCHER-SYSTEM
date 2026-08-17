<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentKioskController extends Controller
{
    public function index()
    {
        return view('kiosk.student');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:50'],
            'pin' => ['required', 'string', 'size:4', 'regex:/^\d{4}$/'],
        ]);

        $student = Student::where('student_id', $validated['student_id'])
            ->first();

        if (! $student) {
            return back()
                ->withInput()
                ->with('error', 'Student ID was not found.');
        }

        if ($student->status !== 'active') {
            return back()
                ->withInput()
                ->with('error', 'This student account is currently inactive.');
        }

        if ($student->locked_until && $student->locked_until->isFuture()) {
            return back()
                ->withInput()
                ->with('error', 'This student account is temporarily locked. Please try again later.');
        }

        if (! Hash::check($validated['pin'], $student->pin_hash ?? '')) {
            $student->failed_attempts = ($student->failed_attempts ?? 0) + 1;

            if ($student->failed_attempts >= 3) {
                $student->locked_until = now()->addMinutes(5);
                $student->failed_attempts = 0;
            }

            $student->save();

            return back()
                ->withInput()
                ->with('error', 'Invalid student PIN.');
        }

        $student->failed_attempts = 0;
        $student->locked_until = null;
        $student->save();

        return view('kiosk.student-result', compact('student'));
    }
}
