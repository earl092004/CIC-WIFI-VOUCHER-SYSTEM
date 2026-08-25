<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Review Voucher Import</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-4xl p-6">
        <div class="mb-4 flex items-center gap-3">
            <img src="{{ asset('images/cic-logo-cropped.jpg') }}" alt="City of Ilagan College logo" class="h-14 w-14 rounded-full bg-white object-contain p-0.5">
            <span class="font-bold text-cic-navy">City of Ilagan College</span>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-2xl font-black text-cic-navy">Review voucher import</h1>
            <p class="mt-2 text-sm text-slate-600">{{ count($codes) }} code(s) found. {{ count($existing) }} duplicate(s) will be skipped.</p>
            <div class="mt-6 grid max-h-80 grid-cols-2 gap-2 overflow-auto rounded-xl bg-slate-50 p-4 font-mono text-sm sm:grid-cols-4">
                @forelse ($codes as $code)<span>{{ $code }}</span>@empty<span class="col-span-full text-slate-500">No six-digit voucher codes found.</span>@endforelse
            </div>
            <div class="mt-6 flex gap-3">
                @if (count($codes) > count($existing))
                    <form method="POST" action="{{ route('staff.vouchers.import.confirm') }}">@csrf<button class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white">Confirm Import</button></form>
                @endif
                <a href="{{ route('staff.dashboard') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold">Cancel</a>
            </div>
        </div>
    </main>
</body>
</html>
