<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\CheckinService;
use Illuminate\Http\Request;

class CheckinScannerController extends Controller
{
    protected CheckinService $checkinService;

    public function __construct(CheckinService $checkinService)
    {
        $this->checkinService = $checkinService;
    }

    /**
     * Show Mobile QR Scanner View for Staff
     */
    public function show(int $eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('staff.checkin-scanner', compact('event'));
    }

    /**
     * Process QR Token check-in via Service Layer
     */
    public function process(Request $request, int $eventId)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $event = Event::findOrFail($eventId);
        $device = $request->header('User-Agent', 'Mobile Camera Scanner');

        $result = $this->checkinService->processCheckin(
            event: $event,
            qrToken: $request->qr_token,
            staffId: auth()->id() ?? 1,
            device: $device
        );

        return response()->json($result, $result['status_code']);
    }
}
