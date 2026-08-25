<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Visitor WiFi Access</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    <nav class="bg-cic-navy px-6 py-4 text-white shadow-lg shadow-cic-blue/20">
        <div class="mx-auto max-w-6xl">
            <h1 class="font-black tracking-wide">CIC Campus WiFi</h1>
            <p class="text-sm text-blue-100">Visitor Authorization</p>
        </div>
    </nav>

    <main class="mx-auto max-w-2xl p-6">

        <div class="mb-6">
            <a
                href="{{ route('staff.dashboard') }}"
                class="text-sm font-medium text-cic-blue hover:text-cic-navy hover:underline"
            >
                ← Back to Dashboard
            </a>
        </div>

        <div class="rounded-3xl border border-cic-slate bg-white p-6 shadow-xl shadow-cic-blue/10">

            <h2 class="text-2xl font-black text-cic-navy">
                Create Visitor WiFi Access
            </h2>

            <p class="mt-2 text-sm text-slate-600">
                This visitor will receive a temporary CIC WiFi voucher.
            </p>

            <form
                method="POST"
                 action="{{ route('staff.visitors.store') }}"
                class="mt-8 space-y-6"
                >
                @csrf

                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Visitor Name
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                    >
                </div>

                <div>
                    <label
                        for="purpose"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Purpose of Visit
                    </label>

                    <input
                        id="purpose"
                        name="purpose"
                        type="text"
                        required
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                        placeholder="Meeting, seminar, maintenance, etc."
                    >
                </div>

                <div>
                    <label
                        for="visiting_department"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Visiting Department
                    </label>

                    <input
                        id="visiting_department"
                        name="visiting_department"
                        type="text"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                        placeholder="MIS, Registrar, Faculty, etc."
                    >
                </div>

                <div>
                    <label
                        for="contact_number"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Contact Number
                    </label>

                    <input
                        id="contact_number"
                        name="contact_number"
                        type="text"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                    >
                </div>

                <div>
                    <label
                        for="duration"
                        class="block text-sm font-medium text-gray-700"
                    >
                        WiFi Duration
                    </label>

                    <select
                        id="duration"
                        name="duration"
                        class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3"
                    >
                        <option value="120">2 Hours</option>
                        <option value="240">4 Hours</option>
                        <option value="480">8 Hours</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-cic-blue px-4 py-3 font-semibold text-white shadow-lg shadow-cic-blue/20 transition hover:bg-cic-navy"
                >
                    Authorize & Issue WiFi Voucher
                </button>

            </form>

        </div>

    </main>

</body>
</html>
