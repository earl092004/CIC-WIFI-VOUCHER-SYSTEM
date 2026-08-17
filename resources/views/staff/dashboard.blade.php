<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CIC WiFi - Staff Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <nav class="bg-blue-700 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between">
            <div>
                <h1 class="font-bold">CIC Campus WiFi</h1>
                <p class="text-sm text-blue-100">Staff Portal</p>
            </div>

            <div class="text-sm">
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
                class="rounded-xl bg-white p-6 shadow hover:shadow-md"
            >
                <h3 class="text-lg font-semibold text-gray-900">
                    Create Visitor WiFi Access
                </h3>

                <p class="mt-2 text-sm text-gray-600">
                    Authorize a visitor and issue a temporary WiFi voucher.
                </p>
            </a>

            <div class="rounded-xl bg-white p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-900">
                    Visitor Vouchers
                </h3>

                <p class="mt-2 text-sm text-gray-600">
                    Visitor voucher management will be added next.
                </p>
            </div>

        </div>

    </main>

</body>
</html>
