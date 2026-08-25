<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIC Visitor WiFi Voucher</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 px-4 py-8 text-slate-900">
    <nav class="mx-auto mb-6 max-w-3xl rounded-2xl bg-blue-700 px-6 py-4 text-white shadow-lg shadow-blue-200/50">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
            City of Ilagan College
        </p>
        <h1 class="mt-1 text-2xl font-black">
            Visitor WiFi Voucher
        </h1>
    </nav>

    <main class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
        <div class="text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl text-emerald-600">
                ✓
            </div>

            <h2 class="text-3xl font-black text-slate-900">
                Visitor WiFi Access Ready
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Voucher successfully issued.
            </p>
        </div>

        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
            <div class="space-y-5">
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        Network Name
                    </p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        CIC-WiFi
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                        Voucher Code
                    </p>
                    <p class="mt-2 text-3xl font-black tracking-[0.22em] text-slate-900">
                        {{ $voucher->voucher_code }}
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Visitor
                        </p>
                        <p class="mt-2 text-lg font-bold text-slate-900">
                            {{ $voucher->visitor->name }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Status
                        </p>
                        <p class="mt-2 text-lg font-bold text-emerald-600">
                            {{ strtoupper($voucher->status) }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Purpose
                        </p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $voucher->visitor->purpose }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Duration
                        </p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $voucher->duration_minutes / 60 }} hours
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Department
                        </p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $voucher->visitor->visiting_department ?? 'Not specified' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Expires
                        </p>
                        <p class="mt-2 text-base font-semibold text-slate-900">
                            {{ $voucher->expires_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
                    <p class="font-semibold">Authorized by</p>
                    <p class="mt-1 font-bold">{{ $voucher->issuedBy?->name ?? 'Campus staff' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
            <p class="font-semibold">How to connect</p>
            <ol class="mt-2 list-decimal space-y-1 pl-5">
                <li>Connect to the <strong>CIC-WiFi</strong> network.</li>
                <li>Use the voucher code above as the access code.</li>
                <li>Enjoy your campus internet access.</li>
            </ol>
        </div>

        <div class="mt-7 flex justify-center">
            <a
                href="{{ route('staff.dashboard') }}"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700"
            >
                Return to Staff Dashboard
            </a>
        </div>
    </main>
</body>
</html>
