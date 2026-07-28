<x-filament-panels::page>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
    .ep-card {
        background: var(--fi-bg, #fff);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.25rem;
    }
    .dark .ep-card {
        background: #111827;
        border-color: #1f2937;
    }
    .ep-event-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 0.875rem;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        text-decoration: none;
        transition: all 0.15s;
        cursor: pointer;
    }
    .dark .ep-event-row {
        background: #111827;
        border-color: #1f2937;
    }
    .ep-event-row:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99,102,241,0.12);
    }
    .ep-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }
    .ep-badge-green { background:#dcfce7; color:#15803d; }
    .ep-badge-amber { background:#fef9c3; color:#a16207; }
    .dark .ep-badge-green { background:#14532d40; color:#4ade80; }
    .dark .ep-badge-amber { background:#78350f40; color:#fbbf24; }

    /* Scanner overlay */
    #ep-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(6px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    #ep-overlay.show { display: flex; }
    #ep-modal {
        width: 100%;
        max-width: 22rem;
        border-radius: 1.5rem;
        padding: 2rem 1.75rem;
        text-align: center;
        box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        border: 2px solid transparent;
        background: #fff;
    }
    .dark #ep-modal { background: #111827; }
    #ep-modal.green { border-color: #34d399; }
    #ep-modal.amber { border-color: #fbbf24; }
    #ep-modal.red   { border-color: #f87171; }

    .ep-icon-circle {
        width: 5rem;
        height: 5rem;
        border-radius: 9999px;
        margin: 0 auto 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ep-icon-circle svg { width: 2.5rem; height: 2.5rem; stroke-width: 2.5; }
    .green .ep-icon-circle { background: #dcfce7; color: #16a34a; }
    .amber .ep-icon-circle { background: #fef9c3; color: #d97706; }
    .red   .ep-icon-circle { background: #fee2e2; color: #dc2626; }
    .dark .green .ep-icon-circle { background: #14532d40; color: #4ade80; }
    .dark .amber .ep-icon-circle { background: #78350f40; color: #fbbf24; }
    .dark .red   .ep-icon-circle { background: #7f1d1d40; color: #f87171; }

    #ep-modal-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.35rem; }
    .green #ep-modal-title { color: #16a34a; }
    .amber #ep-modal-title { color: #d97706; }
    .red   #ep-modal-title { color: #dc2626; }
    .dark .green #ep-modal-title { color: #4ade80; }
    .dark .amber #ep-modal-title { color: #fbbf24; }
    .dark .red   #ep-modal-title { color: #f87171; }

    #ep-modal-msg { font-size: 0.85rem; color: #6b7280; margin-bottom: 1rem; }
    .dark #ep-modal-msg { color: #9ca3af; }

    #ep-attendee-box {
        display: none;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.875rem;
        overflow: hidden;
        margin-bottom: 1.25rem;
        text-align: left;
    }
    .dark #ep-attendee-box { background: #1f2937; border-color: #374151; }
    .ep-attendee-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.625rem 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.8rem;
    }
    .dark .ep-attendee-row { border-bottom-color: #374151; }
    .ep-attendee-row:last-child { border-bottom: none; }
    .ep-attendee-label { color: #9ca3af; }
    .ep-attendee-value { font-weight: 700; color: #111827; }
    .dark .ep-attendee-value { color: #f9fafb; }

    #ep-next-btn {
        width: 100%;
        padding: 0.875rem;
        border-radius: 0.875rem;
        font-weight: 700;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    #ep-next-btn:hover { opacity: 0.88; }
    .green #ep-next-btn { background: #16a34a; color: #fff; }
    .amber #ep-next-btn { background: #d97706; color: #fff; }
    .red   #ep-next-btn { background: #dc2626; color: #fff; }

    #reader { border-radius: 0.75rem; overflow: hidden; }
    #reader video { border-radius: 0.75rem !important; }
    #reader img { display: none !important; }

    .ep-manual-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 0.625rem;
        padding: 0.625rem 1rem;
        font-family: monospace;
        font-size: 0.875rem;
        background: #f9fafb;
        color: #111827;
        outline: none;
        transition: border-color 0.15s;
    }
    .dark .ep-manual-input {
        background: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }
    .ep-manual-input:focus { border-color: #6366f1; }
    .ep-verify-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 0.625rem;
        background: #6366f1;
        color: #fff;
        font-weight: 700;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: background 0.15s;
    }
    .ep-verify-btn:hover { background: #4f46e5; }
</style>

@php
    $events     = $this->getEvents();
    $event      = $this->getSelectedEvent();
    $backUrl    = \App\Filament\App\Pages\CheckinScanner::getUrl(panel: 'app', tenant: filament()->getTenant());
@endphp

{{-- ════════════════════════════════════════════ --}}
{{-- EVENT SELECTOR                               --}}
{{-- ════════════════════════════════════════════ --}}
@if(!$event)

    @if($events->isEmpty())
        <div style="text-align:center;padding:4rem 1rem;">
            <p style="font-size:1.1rem;font-weight:700;color:#6b7280;">No published events found.</p>
        </div>
    @else
        <div style="max-width:640px;margin:0 auto;">
            <p style="font-size:0.85rem;color:#6b7280;margin-bottom:1rem;">Select the event you want to scan tickets for.</p>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach($events as $e)
                    @php
                        $url = \App\Filament\App\Pages\CheckinScanner::getUrl(['event' => $e->id], panel: 'app', tenant: filament()->getTenant());
                        $isPublished = $e->status === 'Published';
                    @endphp
                    <a href="{{ $url }}" class="ep-event-row">
                        {{-- Icon --}}
                        <div style="width:2.75rem;height:2.75rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $isPublished ? '#dcfce7' : '#fef9c3' }}">
                            <svg style="width:1.4rem;height:1.4rem;color:{{ $isPublished ? '#16a34a' : '#d97706' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m8-8h-1M5 12H4m13.657-6.343l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:700;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $e->title }}</p>
                            <p style="font-size:0.75rem;color:#9ca3af;margin-top:0.2rem;">
                                {{ \Carbon\Carbon::parse($e->start_date)->format('M d, Y') }} &bull; {{ Str::limit($e->location, 35) }}
                            </p>
                        </div>

                        {{-- Badge + arrow --}}
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.4rem;flex-shrink:0;">
                            <span class="ep-badge {{ $isPublished ? 'ep-badge-green' : 'ep-badge-amber' }}">
                                {{ $e->status }}
                            </span>
                            <svg style="width:1rem;height:1rem;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

{{-- ════════════════════════════════════════════ --}}
{{-- SCANNER                                      --}}
{{-- ════════════════════════════════════════════ --}}
@else
    <div style="max-width:520px;margin:0 auto;display:flex;flex-direction:column;gap:1rem;">

        {{-- Top info bar --}}
        <div class="ep-card" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="width:2.5rem;height:2.5rem;border-radius:0.625rem;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:1.2rem;height:1.2rem;color:#6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75V16.5zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:0.7rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Active Event</p>
                    <p style="font-weight:700;font-size:0.875rem;line-height:1.3;">{{ Str::limit($event->title, 40) }}</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="text-align:right;display:none;" class="sm-block">
                    <p style="font-size:0.7rem;color:#9ca3af;">Staff</p>
                    <p style="font-size:0.8rem;font-weight:600;color:#6366f1;">{{ auth()->user()->name }}</p>
                </div>
                <a href="{{ $backUrl }}" style="display:flex;align-items:center;gap:0.35rem;padding:0.5rem 0.875rem;border-radius:0.5rem;background:#f3f4f6;font-size:0.75rem;font-weight:700;color:#374151;text-decoration:none;transition:background 0.15s;"
                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    <svg style="width:0.875rem;height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    Change
                </a>
            </div>
        </div>

        {{-- Camera --}}
        <div class="ep-card" style="padding:0.75rem;position:relative;">
            {{-- Start button shown before camera starts --}}
            <div id="ep-start-screen" style="width:100%;min-height:280px;background:#000;border-radius:0.75rem;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1rem;">
                <svg style="width:3rem;height:3rem;color:#4b5563;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                </svg>
                <button onclick="startCamera()" style="padding:0.75rem 2rem;background:#6366f1;color:#fff;border:none;border-radius:0.625rem;font-size:0.875rem;font-weight:700;cursor:pointer;">
                    Start Camera Scanner
                </button>
            </div>

            {{-- Live video feed --}}
            <div id="ep-video-container" style="display:none;width:100%;border-radius:0.75rem;overflow:hidden;background:#000;min-height:280px;"></div>

            <div id="ep-scanner-label" style="display:none;position:absolute;bottom:1.25rem;left:0;right:0;text-align:center;pointer-events:none;">
                <span style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(0,0,0,0.7);color:#d1d5db;font-size:0.75rem;font-weight:600;padding:0.4rem 1rem;border-radius:9999px;border:1px solid rgba(255,255,255,0.1);">
                    <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#4ade80;animation:pulse 1.5s infinite;"></span>
                    Scanner Active — Point at QR Code
                </span>
            </div>
        </div>

        {{-- Manual entry + Image upload --}}
        <div class="ep-card">
            <p style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem;">Manual Entry</p>
            <div style="display:flex;gap:0.5rem;margin-bottom:0.75rem;">
                <input type="text" id="manual-code-input" placeholder="EVT-2026-XXXXXX" class="ep-manual-input">
                <button onclick="submitManualCode()" class="ep-verify-btn">Verify</button>
            </div>

            {{-- Divider --}}
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                <span style="font-size:0.7rem;color:#9ca3af;font-weight:600;">OR UPLOAD QR IMAGE</span>
                <div style="flex:1;height:1px;background:#e5e7eb;"></div>
            </div>

            {{-- File upload --}}
            <label for="ep-file-input" id="ep-file-label"
                   style="display:flex;align-items:center;justify-content:center;gap:0.6rem;width:100%;padding:0.75rem;border:2px dashed #d1d5db;border-radius:0.625rem;cursor:pointer;font-size:0.8rem;font-weight:600;color:#6b7280;transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#6366f1';this.style.color='#6366f1';"
                   onmouseout="this.style.borderColor='#d1d5db';this.style.color='#6b7280';">
                <svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span id="ep-file-label-text">Upload QR Code Image</span>
            </label>
            <input type="file" id="ep-file-input" accept="image/*" style="display:none;" onchange="scanImageFile(this)">
        </div>

    </div>

    {{-- Hidden container for image scanning --}}
    <div id="ep-image-scanner-container" style="display:none;"></div>

    {{-- Result overlay --}}
    <div id="ep-overlay">
        <div id="ep-modal">
            <div class="ep-icon-circle" id="ep-icon"></div>
            <div id="ep-modal-title"></div>
            <div id="ep-modal-msg"></div>
            <div id="ep-attendee-box">
                <div class="ep-attendee-row">
                    <span class="ep-attendee-label">Attendee</span>
                    <span class="ep-attendee-value" id="ep-name"></span>
                </div>
                <div class="ep-attendee-row">
                    <span class="ep-attendee-label">Ticket Type</span>
                    <span class="ep-attendee-value" id="ep-type" style="color:#6366f1;"></span>
                </div>
                <div class="ep-attendee-row">
                    <span class="ep-attendee-label">Code</span>
                    <span class="ep-attendee-value" id="ep-code" style="font-family:monospace;font-size:0.75rem;"></span>
                </div>
            </div>
            <button id="ep-next-btn" onclick="closeOverlay()">Next Scan 📷</button>
        </div>
    </div>

    <script>
        let busy = false;
        const PROCESS_URL = "{{ route('staff.checkin.process', $event->id) }}";
        const CSRF        = "{{ csrf_token() }}";

        let html5Qrcode = null;

        function startCamera() {
            document.getElementById('ep-start-screen').style.display = 'none';
            document.getElementById('ep-video-container').style.display = 'block';
            document.getElementById('ep-scanner-label').style.display = 'block';

            html5Qrcode = new Html5Qrcode('ep-video-container');

            const onDecoded = decoded => {
                if (busy) return;
                busy = true;
                beep();
                send(decoded);
            };

            Html5Qrcode.getCameras().then(cameras => {
                if (!cameras.length) throw new Error('No cameras');
                // prefer back camera on mobile
                const cam = cameras.find(c => /back|rear|environment/i.test(c.label)) || cameras[cameras.length - 1];
                return html5Qrcode.start(cam.id, { fps:10, qrbox:{width:230,height:230} }, onDecoded);
            }).catch(() => {
                html5Qrcode.start({ facingMode:'environment' }, { fps:10, qrbox:{width:230,height:230} }, onDecoded);
            });
        }

        // Init scanner
        document.addEventListener('DOMContentLoaded', () => {});

        function submitManualCode() {
            const v = document.getElementById('manual-code-input').value.trim();
            if (!v || busy) return;
            busy = true;
            send(v);
        }

        function scanImageFile(input) {
            if (!input.files.length || busy) return;
            const file = input.files[0];
            const label = document.getElementById('ep-file-label-text');
            label.innerText = 'Scanning...';

            const scanner = new Html5Qrcode('ep-image-scanner-container');
            scanner.scanFile(file, true)
                .then(decoded => {
                    busy = true;
                    beep();
                    send(decoded);
                })
                .catch(() => {
                    showOverlay(400, { success: false, message: 'Could not read a QR code from this image. Try a clearer photo.' });
                })
                .finally(() => {
                    label.innerText = 'Upload QR Code Image';
                    input.value = '';
                    scanner.clear().catch(() => {});
                });
        }

        function send(token) {
            fetch(PROCESS_URL, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ qr_token: token })
            })
            .then(r => r.json().then(d => ({ s: r.status, d })))
            .then(({ s, d }) => showOverlay(s, d))
            .catch(() => showOverlay(500, { success:false, message:'Connection error. Try again.' }));
        }

        const ICONS = {
            success: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>',
            warning: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
            danger:  '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
        };

        function showOverlay(status, data) {
            const modal   = document.getElementById('ep-modal');
            const overlay = document.getElementById('ep-overlay');
            const icon    = document.getElementById('ep-icon');
            const title   = document.getElementById('ep-modal-title');
            const msg     = document.getElementById('ep-modal-msg');
            const box     = document.getElementById('ep-attendee-box');

            modal.className = data.success ? 'green' : (status === 409 ? 'amber' : 'red');
            icon.innerHTML  = data.success ? ICONS.success : (status === 409 ? ICONS.warning : ICONS.danger);
            title.innerText = data.success ? '✅ Access Granted' : (status === 409 ? '⚠️ Already Checked-in' : '❌ Access Denied');
            msg.innerText   = data.message ?? '';

            if (data.attendee) {
                box.style.display = 'block';
                document.getElementById('ep-name').innerText = data.attendee.name;
                document.getElementById('ep-type').innerText = data.attendee.ticket_type;
                document.getElementById('ep-code').innerText = data.attendee.ticket_code;
            } else {
                box.style.display = 'none';
            }

            overlay.classList.add('show');
        }

        function closeOverlay() {
            document.getElementById('ep-overlay').classList.remove('show');
            document.getElementById('manual-code-input').value = '';
            busy = false;
        }

        function beep() {
            try {
                const ctx  = new (window.AudioContext || window.webkitAudioContext)();
                const osc  = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.setValueAtTime(1000, ctx.currentTime);
                gain.gain.setValueAtTime(0.25, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.18);
                osc.start(); osc.stop(ctx.currentTime + 0.18);
            } catch(e) {}
        }
    </script>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
@endif

</x-filament-panels::page>
