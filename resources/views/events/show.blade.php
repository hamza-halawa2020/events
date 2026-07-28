<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - EventPass Registration</title>
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
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-500 flex items-center justify-center font-black text-xl text-white shadow-lg shadow-indigo-500/30">
                    EP
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Event<span class="text-indigo-400">Pass</span></span>
            </div>
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest bg-slate-800 px-3 py-1.5 rounded-full">
                {{ $event->company->name ?? 'Verified Host' }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 py-12 flex-1 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Event Details Column -->
        <div class="lg:col-span-7 space-y-8">
            <div class="space-y-4">
                @php
                    $now2 = now();
                    $badgeNotStarted = $event->registration_start_date && $now2->lt($event->registration_start_date);
                    $badgeEnded      = $event->registration_end_date && $now2->gt($event->registration_end_date);
                @endphp
                @if($badgeNotStarted)
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-md bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> Registration Opens Soon
                    </span>
                @elseif($badgeEnded)
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-md bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <span class="w-2 h-2 rounded-full bg-rose-400"></span> Registration Closed
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-md bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span> Registration Open
                    </span>
                @endif
                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    {{ $event->title }}
                </h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-800 flex items-start space-x-3">
                    <div class="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Date & Time</p>
                        <p class="text-sm font-semibold text-white mt-0.5">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y • g:i A') }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-800/50 border border-slate-800 flex items-start space-x-3">
                    <div class="p-2.5 rounded-xl bg-violet-500/10 text-violet-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Location</p>
                        <p class="text-sm font-semibold text-white mt-0.5">{{ $event->location ?? 'Venue TBA' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="text-lg font-bold text-white">About Event</h3>
                <div class="text-slate-300 leading-relaxed text-sm bg-slate-800/30 p-6 rounded-2xl border border-slate-800/60">
                    {{ $event->description ?? 'Join us for an exclusive event experience powered by EventPass.' }}
                </div>
            </div>
        </div>

        <!-- Registration Form Column -->
        <div class="lg:col-span-5 bg-slate-800/80 border border-slate-700/60 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
            @php
                $now = now();
                $regNotStarted = $event->registration_start_date && $now->lt($event->registration_start_date);
                $regEnded      = $event->registration_end_date && $now->gt($event->registration_end_date);
                $regClosed     = $regNotStarted || $regEnded;
            @endphp

            @if($regNotStarted)
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">Registration Not Open Yet</h2>
                    <p class="text-sm text-slate-400">Registration opens on</p>
                    <p class="text-base font-semibold text-amber-400 mt-1">{{ \Carbon\Carbon::parse($event->registration_start_date)->format('M d, Y • g:i A') }}</p>
                </div>
            @elseif($regEnded)
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-500/10 flex items-center justify-center">
                        <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">Registration Closed</h2>
                    <p class="text-sm text-slate-400">Registration ended on</p>
                    <p class="text-base font-semibold text-rose-400 mt-1">{{ \Carbon\Carbon::parse($event->registration_end_date)->format('M d, Y • g:i A') }}</p>
                </div>
            @else
                <h2 class="text-2xl font-bold text-white mb-2">Claim Your Spot</h2>
                <p class="text-xs text-slate-400 mb-6">Fill in your information to receive your digital QR entry ticket.</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('public.event.register', $event->slug) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="attendee_name" required value="{{ old('attendee_name') }}" placeholder="John Doe" class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="attendee_email" required value="{{ old('attendee_email') }}" placeholder="john@example.com" class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    {{-- Custom dynamic fields --}}
                    @if(!empty($event->custom_fields))
                        @foreach($event->custom_fields as $field)
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                                    {{ $field['name'] }}{{ !empty($field['required']) ? ' *' : '' }}
                                </label>
                                @if($field['type'] === 'textarea')
                                    <textarea name="custom_fields[{{ $field['name'] }}]"
                                        rows="3"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                        class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">{{ old('custom_fields.'.$field['name']) }}</textarea>
                                @elseif($field['type'] === 'select' && !empty($field['options']))
                                    <select name="custom_fields[{{ $field['name'] }}]"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                        class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                        <option value="">-- Select --</option>
                                        @foreach(explode(',', $field['options']) as $opt)
                                            @php $opt = trim($opt); @endphp
                                            <option value="{{ $opt }}" {{ old('custom_fields.'.$field['name']) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text"
                                        name="custom_fields[{{ $field['name'] }}]"
                                        value="{{ old('custom_fields.'.$field['name']) }}"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                        class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Select Ticket Category</label>
                        <div class="space-y-2.5">
                            @foreach ($event->ticketTypes as $type)
                                <label class="flex items-center justify-between p-3.5 rounded-xl border border-slate-700 bg-slate-900/50 hover:border-indigo-500 cursor-pointer transition">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="ticket_type_id" value="{{ $type->id }}" required class="text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <p class="text-sm font-bold text-white">{{ $type->name }}</p>
                                            <p class="text-xs text-slate-400">${{ number_format($type->price, 2) }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 bg-slate-800 px-2.5 py-1 rounded-lg">
                                        {{ $type->available_quantity !== null ? $type->available_quantity . ' left' : 'Available' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 text-white font-bold text-base shadow-lg shadow-indigo-500/25 transition transform active:scale-95">
                        Complete Registration 🎟️
                    </button>
                </form>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} EventPass Smart QR Platform. All rights reserved.
    </footer>
</body>
</html>
