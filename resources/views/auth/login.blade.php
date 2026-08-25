<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CIC WiFi - Staff Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center">

    <div class="w-full max-w-md rounded-3xl border border-cic-slate bg-white p-8 shadow-xl shadow-cic-blue/10">

        <div class="mb-8 text-center">
            <img src="{{ asset('images/cic-logo-cropped.jpg') }}" alt="City of Ilagan College logo" class="mx-auto mb-5 h-24 w-24 rounded-full object-contain">

            <h1 class="text-2xl font-black text-cic-navy">
                City of Ilagan College
            </h1>

            <p class="mt-2 text-sm font-medium text-slate-600">
                CIC WiFi Staff Portal
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label
                    for="email"
                    class="block text-sm font-medium text-gray-700"
                >
                    Email
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                >
            </div>

            <div class="mt-5">
                <label
                    for="password"
                    class="block text-sm font-medium text-gray-700"
                >
                    Password
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                >
            </div>

            <button
                type="submit"
                class="mt-6 w-full rounded-xl bg-cic-blue px-4 py-3 font-semibold text-white shadow-lg shadow-cic-blue/20 transition hover:bg-cic-navy"
            >
                Sign In
            </button>
        </form>

    </div>

</body>
</html>
