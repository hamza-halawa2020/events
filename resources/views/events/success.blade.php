<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-black text-xl text-white shadow-lg shadow-emerald-500/30">
                    ✓
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Event<span class="text-emerald-400">Pass</span></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-xl mx-auto px-6 py-12 flex-1 w-full text-center flex flex-col justify-center items-center">
        <div class="bg-slate-800/80 border border-slate-700/60 rounded-3xl p-8 shadow-2xl backdrop-blur-xl w-full">
            <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-2">You're Registered!</h1>
            <p class="text-sm text-slate-400 mb-6">Your spot is confirmed for <strong>{{ $event->title }}</strong>. A confirmation email has been sent to <span class="text-slate-200 font-semibold">{{ $registration->attendee_email }}</span>.</p>

            <!-- Digital QR Ticket Preview Card -->
            <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 mb-6 text-center space-y-4">
                <div class="bg-white p-4 rounded-xl inline-block shadow-lg">
                    {!! $qrSvg !!}
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Ticket Code</p>
                    <p class="text-lg font-mono font-bold text-indigo-400 mt-0.5">{{ $registration->ticket_code }}</p>
                </div>
                <div class="text-xs text-slate-400 border-t border-slate-800 pt-3 flex justify-around">
                    <span><strong>Attendee:</strong> {{ $registration->attendee_name }}</span>
                    <span><strong>Type:</strong> {{ $registration->ticketType->name ?? 'Standard' }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('public.ticket.pdf', $registration->ticket_code) }}" class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-500/25 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Official PDF Ticket
                </a>

                <a href="{{ route('public.event.show', $event->slug) }}" class="block text-xs font-semibold text-slate-400 hover:text-white transition py-2">
                    ← Back to Event Page
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} EventPass Smart QR Platform. All rights reserved.
    </footer>
</body>
</html>
