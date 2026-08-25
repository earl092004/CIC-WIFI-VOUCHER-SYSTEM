<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIC WiFi Kiosk</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-linear-to-br from-cic-blue via-sky-500 to-violet-600 p-4 font-sans sm:p-8">
    <main class="mx-auto grid min-h-[calc(100vh-2rem)] max-w-6xl overflow-hidden rounded-2xl bg-white shadow-2xl shadow-cic-navy/30 sm:min-h-[calc(100vh-4rem)] lg:min-h-[42rem] lg:grid-cols-2">
        <section class="relative isolate overflow-hidden bg-linear-to-br from-cic-navy via-cic-blue to-cyan-400 px-7 py-8 text-white sm:px-12 sm:py-12 lg:flex lg:flex-col lg:p-14">
            <div class="absolute -bottom-56 -right-24 h-96 w-[34rem] rounded-[50%] border-[4rem] border-cyan-300/35"></div>
            <div class="absolute -bottom-72 -left-20 h-[32rem] w-[42rem] rounded-[50%] bg-blue-700/55"></div>
            <div class="absolute -bottom-64 right-8 h-[26rem] w-[38rem] rounded-[50%] bg-sky-300/25"></div>

            <div class="relative flex items-center gap-4">
                <img src="{{ asset('images/cic-logo-cropped.jpg') }}" alt="City of Ilagan College logo" class="h-24 w-24 shrink-0 rounded-full bg-white object-contain p-1 shadow-lg">
                <div>
                    <p class="text-lg font-black tracking-wide sm:text-xl">City of Ilagan College</p>
                    <p class="text-sm font-medium text-cyan-50">CIC Campus Wi-Fi</p>
                </div>
            </div>

            <div class="relative mt-14 max-w-md lg:mt-auto lg:mb-24">
                <p class="mb-3 text-sm font-bold tracking-[0.2em] text-cyan-100 uppercase">Student portal</p>
                <h1 class="text-4xl font-black leading-tight sm:text-5xl">Welcome to CIC Wi-Fi</h1>
                <p class="mt-5 max-w-sm text-base leading-relaxed text-cyan-50 sm:text-lg">
                    Securely access your student Wi-Fi voucher using your Student ID and four-digit PIN.
                </p>
            </div>

            <p class="relative mt-12 text-sm text-cyan-100 lg:mt-auto">Connect. Learn. Thrive.</p>
        </section>

        <section class="flex items-center bg-white px-7 py-10 sm:px-12 lg:px-16">
            <div class="mx-auto w-full max-w-md">
                <p class="text-sm font-bold tracking-[0.2em] text-cic-blue uppercase">Student access</p>
                <h2 class="mt-2 text-4xl font-black text-cic-navy">Login</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-500 sm:text-base">
                    Enter your school credentials to request a Wi-Fi voucher.
                </p>

                @if (session('error'))
                    <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('kiosk.student.verify') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="student_id" class="mb-2 block text-sm font-bold text-slate-700">Student ID</label>
                        <input
                            type="text"
                            id="student_id"
                            name="student_id"
                            value="{{ old('student_id') }}"
                            placeholder="Enter your Student ID"
                            autofocus
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-3.5 text-base text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-cic-blue focus:ring-4 focus:ring-cic-blue/15"
                        >

                        @error('student_id')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pin" class="mb-2 block text-sm font-bold text-slate-700">4-Digit PIN</label>
                        <input
                            type="password"
                            id="pin"
                            name="pin"
                            value="{{ old('pin') }}"
                            inputmode="numeric"
                            maxlength="4"
                            pattern="[0-9]*"
                            placeholder="Enter your 4-digit PIN"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-3.5 text-base text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-cic-blue focus:ring-4 focus:ring-cic-blue/15"
                        >

                        @error('pin')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-cic-blue px-4 py-3.5 text-base font-bold text-white shadow-lg shadow-cic-blue/25 transition hover:bg-cic-navy focus:outline-none focus:ring-4 focus:ring-cic-blue/30">
                        Check Student Account
                    </button>
                </form>

                <p class="mt-8 text-center text-sm leading-relaxed text-slate-500">
                    Need help? Please contact the CIC MIS office.
                </p>
            </div>
        </section>
    </main>
</body>
</html>
