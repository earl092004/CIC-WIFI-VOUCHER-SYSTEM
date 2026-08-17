<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIC WiFi Kiosk</title>

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

            <p class="text-sm text-gray-500 mt-1">
                Student WiFi Voucher Kiosk
            </p>
        </div>

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('kiosk.student.verify') }}">
            @csrf

            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                Student ID
            </label>

            <input
                type="text"
                id="student_id"
                name="student_id"
                value="{{ old('student_id') }}"
                placeholder="Enter your Student ID"
                autofocus
                class="w-full rounded-lg border border-gray-300 px-4 py-3 text-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            >

            @error('student_id')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
            @enderror

            <div class="mt-5">
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-2">
                    4-Digit PIN
                </label>

                <input
                    type="password"
                    id="pin"
                    name="pin"
                    value="{{ old('pin') }}"
                    inputmode="numeric"
                    maxlength="4"
                    pattern="[0-9]*"
                    placeholder="Enter your 4-digit PIN"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                >

                @error('pin')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-3 text-lg font-semibold text-white hover:bg-blue-700"
            >
                Check Student
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-500">
            Please ask CIC MIS staff for assistance if you encounter a problem.
        </div>

    </div>

</body>
</html>
