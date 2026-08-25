<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>WiFi Analytics</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-6xl p-6">
        <div class="flex items-center justify-between"><div><p class="text-xs font-semibold uppercase tracking-[0.22em] text-cic-blue">CIC Campus WiFi</p><h1 class="mt-2 text-3xl font-black text-cic-navy">WiFi Analytics</h1></div><a href="{{ route('staff.dashboard') }}" class="font-semibold text-cic-blue">Dashboard</a></div>
        <form method="GET" class="mt-8 flex items-end gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><label class="text-sm font-semibold">Date<input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="mt-1 block rounded-lg border border-slate-300 px-3 py-2"></label><button class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white">View</button></form>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ([['Student assigned', $studentAssigned], ['Visitor assigned', $visitorAssigned], ['Student available', $availableStudent], ['Visitor available', $availableVisitor], ['Expired', $expired]] as [$label, $value])<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-3xl font-black text-cic-navy">{{ $value }}</p></div>@endforeach
        </div>
    </main>
</body>
</html>
