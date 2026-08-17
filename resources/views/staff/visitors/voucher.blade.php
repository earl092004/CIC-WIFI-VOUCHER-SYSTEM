<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CIC Visitor WiFi Voucher</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <nav class="bg-blue-700 px-6 py-4 text-white">
        <div class="mx-auto max-w-6xl">
            <h1 class="font-bold">CIC Campus WiFi</h1>
            <p class="text-sm text-blue-100">Visitor Voucher</p>
        </div>
    </nav>

    <main class="mx-auto max-w-2xl p-6">

        <div class="rounded-xl bg-white p-8 shadow">

            <div class="text-center">

                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <span class="text-3xl text-green-600">✓</span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900">
                    Visitor WiFi Access Ready
                </h2>

                <p class="mt-2 text-gray-600">
                    Voucher successfully issued.
                </p>

            </div>

            <div class="mt-8 rounded-xl bg-gray-50 p-6">

                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        WiFi Voucher
                    </p>

                    <p class="mt-3 text-3xl font-bold tracking-widest text-gray-900">
                        {{ $voucher->voucher_code }}
                    </p>
                </div>

                <div class="mt-8 space-y-4">

                    <div>
                        <p class="text-sm text-gray-500">
                            Visitor
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $voucher->visitor->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Purpose
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $voucher->visitor->purpose }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Visiting Department
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $voucher->visitor->visiting_department ?? 'Not specified' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Authorized By
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $voucher->issuedBy->name }}
                        </p>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">
                            Status
                        </span>

                        <span class="font-semibold text-green-600">
                            {{ strtoupper($voucher->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">
                            Duration
                        </span>

                        <span class="font-semibold">
                            {{ $voucher->duration_minutes / 60 }} hours
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">
                            Expires
                        </span>

                        <span class="font-semibold">
                            {{ $voucher->expires_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                </div>

            </div>

            <div class="mt-6 rounded-lg bg-blue-50 p-4 text-sm text-blue-800">
                This visitor WiFi access was authorized by
                <strong>{{ $voucher->issuedBy->name }}</strong>.
            </div>

            <a
                href="{{ route('staff.dashboard') }}"
                class="mt-6 block text-center text-sm text-gray-500 hover:text-gray-700"
            >
                Return to Staff Dashboard
            </a>

        </div>

    </main>

</body>
</html>
