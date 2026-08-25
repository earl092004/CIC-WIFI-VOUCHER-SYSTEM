<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Voucher Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <nav class="bg-cic-navy px-6 py-4 text-white shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">City of Ilagan College</p>
                <h1 class="mt-1 text-xl font-black">Visitor Voucher Management</h1>
            </div>
            <a href="{{ route('staff.dashboard') }}" class="text-sm font-semibold text-blue-100 hover:text-white">Dashboard</a>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl p-6">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4 sm:grid-cols-3">
            @foreach (['active' => 'Active', 'revoked' => 'Revoked', 'expired' => 'Expired'] as $key => $label)
                <a href="{{ route('staff.visitors.vouchers', ['status' => $key]) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-cic-blue">
                    <p class="text-sm text-slate-500">{{ $label }} visitor vouchers</p>
                    <p class="mt-2 text-3xl font-black text-cic-navy">{{ $counts[$key] }}</p>
                </a>
            @endforeach
        </div>

        <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <label class="text-sm font-medium text-slate-700">
                    Search visitor or voucher
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, purpose, or code" class="mt-1 block w-64 rounded-lg border border-slate-300 px-3 py-2">
                </label>
                <label class="text-sm font-medium text-slate-700">
                    Voucher status
                    <select name="status" class="mt-1 block rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">All statuses</option>
                        @foreach (['active' => 'Active', 'revoked' => 'Revoked', 'expired' => 'Expired'] as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-medium text-slate-700">
                    Usage
                    <select name="usage_status" class="mt-1 block rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">All usage</option>
                        @foreach (['available' => 'Available', 'on_use' => 'On use', 'used' => 'Used'] as $key => $label)
                            <option value="{{ $key }}" @selected(request('usage_status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="rounded-lg bg-cic-blue px-4 py-2 font-semibold text-white hover:bg-cic-navy">Filter</button>
                <a href="{{ route('staff.visitors.vouchers') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700">Clear</a>
            </form>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Visitor</th>
                            <th class="px-6 py-3">Purpose / department</th>
                            <th class="px-6 py-3">Voucher</th>
                            <th class="px-6 py-3">Usage</th>
                            <th class="px-6 py-3">Expires</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($vouchers as $voucher)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ $voucher->visitor?->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $voucher->visitor?->contact_number ?? 'No contact number' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>{{ $voucher->visitor?->purpose }}</div>
                                    <div class="text-xs text-slate-500">{{ $voucher->visitor?->visiting_department ?? 'No department' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('staff.visitors.voucher', $voucher) }}" class="font-mono font-bold text-cic-blue hover:text-cic-navy">{{ $voucher->voucher_code }}</a>
                                    <div class="text-xs text-slate-500">{{ $voucher->network_name ?? config('wifi.networks.visitor') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase">{{ str_replace('_', ' ', $voucher->status) }}</span>
                                    <div class="mt-2 text-xs text-slate-500">{{ str_replace('_', ' ', $voucher->usage_status) }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $voucher->expires_at?->format('M d, Y h:i A') ?? 'Not issued' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">No visitor vouchers match this filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 p-4">{{ $vouchers->links() }}</div>
        </section>

        <p class="mt-4 text-xs text-slate-500">This page shows only visitor vouchers that have been issued to a visitor. Imported unused codes are listed in Voucher Inventory.</p>
    </main>
</body>
</html>
