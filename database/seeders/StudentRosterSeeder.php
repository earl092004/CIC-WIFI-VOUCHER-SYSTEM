<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentRosterSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('roster.csv');
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            $this->command->error('roster.csv could not be opened.');

            return;
        }

        fgetcsv($handle);
        $imported = 0;
        $duplicates = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5) {
                continue;
            }

            [$course, $studentId, $lastName, $firstName, $yearLevel] = array_map(
                fn ($value) => trim((string) $value),
                array_slice($row, 0, 5)
            );

            if ($studentId === '') {
                continue;
            }

            $student = Student::firstOrNew(['student_id' => $studentId]);
            if ($student->exists && $student->course !== $course) {
                $duplicates[] = $studentId;
            }

            $student->fill([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'course' => $course,
                'year_level' => $yearLevel ?: '1st Year',
                'status' => 'active',
            ]);

            if (! $student->exists) {
                $student->pin_hash = Hash::make('1234');
            }

            $student->save();
            $imported++;
        }

        fclose($handle);

        $this->command->info("Imported {$imported} roster row(s).");
        if ($duplicates !== []) {
            $this->command->warn('Duplicate student IDs updated: '.implode(', ', array_unique($duplicates)));
        }
    }
}
