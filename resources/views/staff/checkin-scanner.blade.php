<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Entry Scanner - {{ $event->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #reader video { border-radius: 1rem; object-fit: cover; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between">
    <!-- Top Header -->
    <header class="border-b border-slate-800 bg-slate-900/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-md mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                <h1 class="text-sm font-bold text-white tracking-wide uppercase">Gate Check-in</h1>
            </div>
            <span class="text-xs bg-slate-800 text-slate-300 px-3 py-1 rounded-full font-medium truncate max-w-[180px]">
                {{ $event->title }}
            </span>
        </div>
    </header>

    <!-- Main Mobile Container -->
    <main class="max-w-md mx-auto px-4 py-6 flex-1 w-full space-y-6">
        
        <!-- Live Camera Viewfinder Box -->
        <div class="relative bg-slate-900 border-2 border-indigo-500/40 rounded-3xl overflow-hidden shadow-2xl p-2">
            <div id="reader" class="w-full rounded-2xl overflow-hidden min-h-[300px] bg-slate-950"></div>
            
            <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
                <span class="bg-slate-950/80 text-indigo-400 text-xs font-semibold px-4 py-1.5 rounded-full border border-indigo-500/30 backdrop-blur">
                    Point camera at attendee QR ticket
                </span>
            </div>
        </div>

        <!-- Manual Ticket Code Fallback Input -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 space-y-3">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Manual Code Entry</label>
            <div class="flex gap-2">
                <input type="text" id="manual-code-input" placeholder="e.g. EVT-2026-A1B2C3" class="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white font-mono placeholder-slate-600 focus:outline-none focus:border-indigo-500">
                <button onclick="submitManualCode()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition">
                    Verify
                </button>
            </div>
        </div>

        <!-- Live Modal Result Overlay -->
        <div id="result-modal" class="hidden fixed inset-0 z-50 bg-slate-950/90 backdrop-blur-md flex items-center justify-center p-4">
            <div id="modal-card" class="w-full max-w-sm bg-slate-900 border-2 rounded-3xl p-6 text-center space-y-4 shadow-2xl transform transition-all scale-95">
                <div id="modal-icon-wrapper" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center text-2xl">
                    <!-- Icon injected via JS -->
                </div>

                <div class="space-y-1">
                    <h3 id="modal-title" class="text-xl font-extrabold text-white"></h3>
                    <p id="modal-message" class="text-xs text-slate-300"></p>
                </div>

                <!-- Attendee Info Card -->
                <div id="modal-attendee-box" class="hidden bg-slate-950 border border-slate-800 rounded-2xl p-4 text-left space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Name:</span>
                        <span id="modal-attendee-name" class="font-bold text-white"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Category:</span>
                        <span id="modal-ticket-type" class="font-semibold text-indigo-400"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ticket Code:</span>
                        <span id="modal-ticket-code" class="font-mono text-slate-300"></span>
                    </div>
                </div>

                <button onclick="closeModalAndResume()" class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm rounded-xl transition">
                    Next Scan 📷
                </button>
            </div>
        </div>

    </main>

    <!-- Scanner Logic & AJAX Script -->
    <script>
        let html5QrcodeScanner = null;
        let isProcessing = false;
        const eventId = {{ $event->id }};
        const csrfToken = "{{ csrf_token() }}";

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;
            
            // Audio Feedback
            playBeepSound();

            sendVerificationRequest(decodedText);
        }

        function submitManualCode() {
            const input = document.getElementById('manual-code-input');
            const code = input.value.trim();
            if (!code) return;
            isProcessing = true;
            sendVerificationRequest(code);
        }

        function sendVerificationRequest(token) {
            fetch(`/checkin/${eventId}/process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ qr_token: token })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                showResultModal(status, body);
            })
            .catch(err => {
                showResultModal(500, { success: false, message: 'Server Connection Error' });
            });
        }

        function showResultModal(statusCode, data) {
            const modal = document.getElementById('result-modal');
            const card = document.getElementById('modal-card');
            const iconWrapper = document.getElementById('modal-icon-wrapper');
            const title = document.getElementById('modal-title');
            const message = document.getElementById('modal-message');
            const attendeeBox = document.getElementById('modal-attendee-box');

            modal.classList.remove('hidden');

            if (data.success) {
                // SUCCESS
                card.className = "w-full max-w-sm bg-slate-900 border-2 border-emerald-500/50 rounded-3xl p-6 text-center space-y-4 shadow-2xl shadow-emerald-500/10";
                iconWrapper.className = "w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/40";
                iconWrapper.innerHTML = "✓";
                title.innerText = "Check-in Approved";
                title.className = "text-xl font-extrabold text-emerald-400";
                message.innerText = data.message;

                if (data.attendee) {
                    attendeeBox.classList.remove('hidden');
                    document.getElementById('modal-attendee-name').innerText = data.attendee.name;
                    document.getElementById('modal-ticket-type').innerText = data.attendee.ticket_type;
                    document.getElementById('modal-ticket-code').innerText = data.attendee.ticket_code;
                }
            } else if (statusCode === 409) {
                // ALREADY CHECKED IN
                card.className = "w-full max-w-sm bg-slate-900 border-2 border-amber-500/50 rounded-3xl p-6 text-center space-y-4 shadow-2xl shadow-amber-500/10";
                iconWrapper.className = "w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl bg-amber-500/20 text-amber-400 border border-amber-500/40";
                iconWrapper.innerHTML = "⚠️";
                title.innerText = "Already Checked-in";
                title.className = "text-xl font-extrabold text-amber-400";
                message.innerText = data.message;

                if (data.attendee) {
                    attendeeBox.classList.remove('hidden');
                    document.getElementById('modal-attendee-name').innerText = data.attendee.name;
                    document.getElementById('modal-ticket-type').innerText = data.attendee.ticket_type;
                    document.getElementById('modal-ticket-code').innerText = data.attendee.ticket_code;
                }
            } else {
                // INVALID / TAMPERED
                card.className = "w-full max-w-sm bg-slate-900 border-2 border-rose-500/50 rounded-3xl p-6 text-center space-y-4 shadow-2xl shadow-rose-500/10";
                iconWrapper.className = "w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl bg-rose-500/20 text-rose-400 border border-rose-500/40";
                iconWrapper.innerHTML = "✕";
                title.innerText = "Access Denied";
                title.className = "text-xl font-extrabold text-rose-400";
                message.innerText = data.message;
                attendeeBox.classList.add('hidden');
            }
        }

        function closeModalAndResume() {
            document.getElementById('result-modal').classList.add('hidden');
            document.getElementById('manual-code-input').value = '';
            isProcessing = false;
        }

        function playBeepSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                osc.type = "sine";
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } catch (e) {}
        }

        // Initialize Camera Scanner on Page Load
        document.addEventListener("DOMContentLoaded", function() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { fps: 10, qrbox: { width: 220, height: 220 } },
                /* verbose= */ false
            );
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</body>
</html>
