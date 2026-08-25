<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($studentQuery) use ($search) {
                $studentQuery->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%");
            });
        }

        return view('staff.students.index', [
            'students' => $query->orderBy('course')->orderBy('last_name')->paginate(50)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('staff.students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'course' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'string', 'max:30'],
            'pin' => ['required', 'digits:4'],
        ]);

        Student::updateOrCreate(
            ['student_id' => $validated['student_id']],
            [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'course' => $validated['course'],
                'year_level' => $validated['year_level'],
                'status' => 'active',
                'pin_hash' => Hash::make($validated['pin']),
            ]
        );

        return redirect()->route('staff.students.index')->with('success', 'Student record saved.');
    }

    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $handle = fopen($validated['file']->getRealPath(), 'rb');
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5 || strtolower(trim((string) $row[0])) === 'student number') {
                continue;
            }

            [$course, $studentId, $lastName, $firstName, $yearLevel] = array_map(
                fn ($value) => trim((string) $value),
                [$row[0], $row[1], $row[2], $row[3], $row[4]]
            );

            if ($studentId === '' || $firstName === '' || $lastName === '' || $course === '') {
                $skipped++;

                continue;
            }

            $student = Student::firstOrNew(['student_id' => $studentId]);
            $isNew = ! $student->exists;
            $student->fill([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'course' => $course,
                'year_level' => $yearLevel ?: '1st Year',
                'status' => 'active',
            ]);
            if ($isNew) {
                $student->pin_hash = Hash::make('1234');
            }
            $student->save();
            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Imported {$imported} student record(s); skipped {$skipped} invalid row(s). New students use temporary PIN 1234.");
    }
}
