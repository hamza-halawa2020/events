<div class="space-y-3">
    <div class="flex items-center gap-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <span id="event-reg-url" class="flex-1 text-sm font-mono text-gray-800 dark:text-gray-200 break-all select-all">{{ $url }}</span>
        <button
            onclick="navigator.clipboard.writeText('{{ $url }}').then(() => { this.innerText = '✓ Copied!'; this.classList.add('bg-green-600'); setTimeout(() => { this.innerText = 'Copy'; this.classList.remove('bg-green-600'); }, 2000); })"
            class="shrink-0 px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition">
            Copy
        </button>
    </div>
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Attendees can open this link to register and receive their QR ticket automatically.
    </p>
</div>
