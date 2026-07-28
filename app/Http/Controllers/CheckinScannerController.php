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

        if (auth()->user()->company_id !== $event->company_id) {
            abort(403, 'You do not have access to this event.');
        }

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

        if (auth()->user()->company_id !== $event->company_id) {
            return response()->json([
                'success' => false,
                'status_code' => 403,
                'status' => 'UNAUTHORIZED',
                'message' => '⛔ You are not authorized to check in for this event.',
            ], 403);
        }

        $device = $request->header('User-Agent', 'Mobile Camera Scanner');

        $result = $this->checkinService->processCheckin(
            event: $event,
            qrToken: $request->qr_token,
            staffId: auth()->id(),
            device: $device
        );

        return response()->json($result, $result['status_code']);
    }
}
