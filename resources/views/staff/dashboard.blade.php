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

        <h2 class="text-2xl font-bold text-gray-900">
            Staff Dashboard
        </h2>

        <p class="mt-1 text-gray-600">
            Manage visitor WiFi access.
        </p>

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

            <div class="rounded-2xl border border-cic-slate bg-white p-6 shadow-sm">
                <div class="mb-3 inline-flex rounded-xl bg-emerald-50 p-2 text-cic-green">
                    <span class="text-lg font-bold">✓</span>
                </div>

                <h3 class="text-lg font-bold text-cic-navy">
                    Visitor Vouchers
                </h3>

                <p class="mt-2 text-sm text-slate-600">
                    Visitor voucher management will be added next.
                </p>
            </div>

        </div>

    </main>

</body>
</html>
