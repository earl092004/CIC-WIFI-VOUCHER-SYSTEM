<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CIC WiFi Voucher</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-gray-900">
                City of Ilagan College
            </h1>

            <p class="text-lg text-gray-600 mt-2">
                CIC Campus WiFi
            </p>

        </div>

        @if (session('info'))
            <div class="mb-6 rounded-lg bg-blue-100 p-4 text-blue-700">
                {{ session('info') }}
            </div>
        @endif

        @if ($voucher)

            <div class="text-center mb-6">

                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <span class="text-3xl text-green-600">
                        ✓
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900">
                    WiFi Access Ready
                </h2>

                <p class="text-gray-600 mt-2">
                    {{ $student->full_name }}
                </p>

            </div>

            <div class="rounded-xl bg-gray-50 p-6">

                <p class="text-sm text-gray-500 text-center">
                    WiFi Voucher
                </p>

                <div class="mt-3 text-center">

                    <span class="text-3xl font-bold tracking-widest text-gray-900">
                        {{ $voucher->voucher_code }}
                    </span>

                </div>

                <div class="mt-6 space-y-3">

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

        @else

            <div class="rounded-lg bg-red-100 p-4 text-red-700">
                No active WiFi voucher was found.
            </div>

        @endif

        <a
            href="{{ route('kiosk.student') }}"
            class="mt-6 block text-center text-sm text-gray-500 hover:text-gray-700"
        >
            Return to Kiosk
        </a>

    </div>

</body>
</html>
