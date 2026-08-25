<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Verified - CIC WiFi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/cic-logo-cropped.jpg') }}" alt="City of Ilagan College logo" class="mx-auto mb-5 h-24 w-24 rounded-full object-contain">
            <p class="mb-3 text-sm font-semibold text-cic-navy">City of Ilagan College</p>
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                <span class="text-3xl text-green-600">✓</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">
                Student Verified
            </h1>
        </div>

        <div class="rounded-xl bg-gray-50 p-5 space-y-4">

            <div>
                <p class="text-sm text-gray-500">Student ID</p>
                <p class="font-semibold text-gray-900">
                    {{ $student->student_id }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="font-semibold text-gray-900">
                    {{ $student->full_name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Course</p>
                <p class="font-semibold text-gray-900">
                    {{ $student->course }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Year Level</p>
                <p class="font-semibold text-gray-900">
                    {{ $student->year_level }}
                </p>
            </div>

        </div>

        <form
            method="POST"
            action="{{ route('kiosk.student.voucher.issue', $student) }}"
        >
            @csrf

            <button
                type="submit"
                class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-3 text-lg font-semibold text-white hover:bg-blue-700"
            >
                Get WiFi Voucher
            </button>
        </form>

        <a
            href="{{ route('kiosk.student') }}"
            class="mt-3 block text-center text-sm text-gray-500 hover:text-gray-700"
        >
            Use another Student ID
        </a>

    </div>

</body>
</html>
