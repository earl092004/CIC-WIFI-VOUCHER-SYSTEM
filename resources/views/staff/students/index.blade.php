<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Student Directory</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <nav class="bg-cic-navy px-6 py-4 text-white"><div class="mx-auto flex max-w-7xl items-center justify-between"><div><p class="text-xs uppercase tracking-[0.22em] text-blue-100">CIC Campus WiFi</p><h1 class="mt-1 text-xl font-black">Student Directory</h1></div><a href="{{ route('staff.dashboard') }}" class="text-sm text-blue-100">Dashboard</a></div></nav>
    <main class="mx-auto max-w-7xl p-6">
        @if (session('success'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">{{ $errors->first() }}</div>@endif
        <div class="grid gap-6 lg:grid-cols-[1fr_auto]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-lg font-bold text-cic-navy">Import roster CSV</h2><p class="mt-1 text-sm text-slate-600">Columns: course, student number, last name, first name, year level. Existing PINs are preserved.</p><form class="mt-4 flex flex-wrap items-end gap-3" method="POST" action="{{ route('staff.students.import') }}" enctype="multipart/form-data">@csrf<input type="file" name="file" accept=".csv,.txt" required class="text-sm"><button class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white">Import roster</button></form></section>
            <a href="{{ route('staff.students.create') }}" class="inline-flex h-fit rounded-lg bg-cic-navy px-4 py-2 font-semibold text-white">Add student</a>
        </div>
        <form method="GET" class="mt-6 flex gap-3"><input type="search" name="search" value="{{ request('search') }}" placeholder="Search ID, name, or course" class="w-80 rounded-lg border border-slate-300 px-3 py-2"><button class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white">Search</button></form>
        <section class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-6 py-3">Student ID</th><th class="px-6 py-3">Student name</th><th class="px-6 py-3">Course</th><th class="px-6 py-3">Year</th><th class="px-6 py-3">Status</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse ($students as $student)<tr><td class="px-6 py-3 font-semibold">{{ $student->student_id }}</td><td class="px-6 py-3">{{ $student->full_name }}</td><td class="px-6 py-3">{{ $student->course }}</td><td class="px-6 py-3">{{ $student->year_level }}</td><td class="px-6 py-3">{{ ucfirst($student->status) }}</td></tr>@empty<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No students found.</td></tr>@endforelse</tbody></table><div class="p-4">{{ $students->links() }}</div></section>
    </main>
</body>
</html>
