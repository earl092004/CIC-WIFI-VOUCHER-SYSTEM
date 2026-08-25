<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CIC WiFi - Staff Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    <nav class="bg-cic-navy px-6 py-4 text-white shadow-lg shadow-cic-blue/20">
        <div class="mx-auto flex max-w-6xl items-center justify-between">
            <div>
                <h1 class="font-black tracking-wide">CIC Campus WiFi</h1>
                <p class="text-sm text-blue-100">Staff Portal</p>
            </div>

            <div class="rounded-full border border-white/20 bg-white/5 px-3 py-1 text-sm font-medium">
                {{ auth()->user()->name }}
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl p-6">

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <h2 class="text-2xl font-bold text-gray-900">
            Staff Dashboard
        </h2>

        <p class="mt-1 text-gray-600">
            Manage visitor WiFi access.
        </p>

        <a href="{{ route('staff.vouchers.index') }}" class="mt-4 inline-flex rounded-lg bg-cic-navy px-4 py-2 text-sm font-semibold text-white hover:bg-cic-blue">
            View Voucher Inventory
        </a>
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('staff.analytics') }}" class="ml-2 mt-4 inline-flex rounded-lg border border-cic-navy px-4 py-2 text-sm font-semibold text-cic-navy hover:bg-slate-100">View Analytics</a>
            <a href="{{ route('staff.students.index') }}" class="ml-2 mt-4 inline-flex rounded-lg border border-cic-navy px-4 py-2 text-sm font-semibold text-cic-navy hover:bg-slate-100">Manage Students</a>
        @endif

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-cic-slate bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Unused vouchers</p>
                <p class="mt-2 text-3xl font-black text-cic-navy">{{ $availableVouchers }}</p>
            </div>
            <div class="rounded-2xl border border-cic-slate bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Student requests today</p>
                <p class="mt-2 text-3xl font-black text-cic-navy">{{ $assignedToday }}</p>
            </div>
            <div class="rounded-2xl border border-cic-slate bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Daily student limit</p>
                <p class="mt-2 text-3xl font-black text-cic-navy">{{ config('wifi.student_daily_limit') }}</p>
            </div>
        </div>

        <p class="mt-3 text-sm text-slate-600">
            Student pool: <strong>{{ $availableStudentVouchers }}</strong> · Visitor pool: <strong>{{ $availableVisitorVouchers }}</strong>
        </p>

        <section class="mt-8 rounded-2xl border border-cic-slate bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-cic-navy">Import voucher list</h3>
                    <p class="mt-1 text-sm text-slate-600">Upload a CSV, TXT, or exported PDF containing six-digit voucher codes.</p>
                </div>
                    <form method="POST" action="{{ route('staff.vouchers.import.preview') }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <label class="text-sm font-medium text-slate-700">
                        File
                        <input id="voucher-file" type="file" name="file" accept=".csv,.txt,.pdf" required class="mt-1 block text-sm">
                        <span id="voucher-file-name" class="mt-1 block text-xs text-slate-500">No file selected</span>
                    </label>
                    <label class="text-sm font-medium text-slate-700">
                        Wi-Fi profile
                        <select name="voucher_type" required class="mt-1 block rounded-lg border border-slate-300 px-3 py-2">
                            <option value="student">Student · CIC-Student</option>
                            <option value="visitor">Visitor · CIC-Visitors</option>
                        </select>
                    </label>
                    <button type="submit" class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white hover:bg-cic-navy">Import</button>
                </form>
            </div>
            <p class="mt-3 text-xs text-slate-500">Choose the profile matching the Omada voucher export. Student codes cannot be assigned to visitors, and visitor codes cannot be assigned to students.</p>
        </section>

        <section class="mt-8 rounded-2xl border border-cic-slate bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-lg font-bold text-cic-navy">Student voucher requests</h3>
                <p class="mt-1 text-sm text-slate-600">Recent student requests and the voucher assigned to each student.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Student number</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Course</th>
                            <th class="px-6 py-3">Date requested</th>
                            <th class="px-6 py-3">Voucher</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($studentRequests as $voucher)
                            <tr>
                                <td class="px-6 py-3 font-semibold">{{ $voucher->student?->student_id }}</td>
                                <td class="px-6 py-3">{{ $voucher->student?->full_name }}</td>
                                <td class="px-6 py-3">{{ $voucher->student?->course }}</td>
                                <td class="px-6 py-3">{{ $voucher->issued_at?->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-3 font-mono">{{ $voucher->voucher_code }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No student requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="mt-8 grid gap-6 md:grid-cols-2">

            <a
                href="{{ route('staff.visitors.create') }}"
                class="rounded-2xl border border-cic-slate bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cic-blue/10"
            >
                <div class="mb-3 inline-flex rounded-xl bg-cic-blue-light p-2 text-cic-blue">
                    <span class="text-lg font-bold">+</span>
                </div>

                <h3 class="text-lg font-bold text-cic-navy">
                    Create Visitor WiFi Access
                </h3>

                <p class="mt-2 text-sm text-slate-600">
                    Authorize a visitor and issue a temporary WiFi voucher.
                </p>
            </a>

            <a
                href="{{ route('staff.visitors.vouchers') }}"
                class="rounded-2xl border border-cic-slate bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-cic-blue/10"
            >
                <div class="mb-3 inline-flex rounded-xl bg-emerald-50 p-2 text-cic-green">
                    <span class="text-lg font-bold">✓</span>
                </div>

                <h3 class="text-lg font-bold text-cic-navy">
                    Visitor Vouchers
                </h3>

                <p class="mt-2 text-sm text-slate-600">
                    Search visitor records and review visitor voucher expiry.
                </p>
            </a>

        </div>

    </main>

</body>
</html>
