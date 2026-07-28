<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Services\EventRegistrationService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Exception;

class PublicEventController extends Controller
{
    protected EventRegistrationService $registrationService;

    public function __construct(EventRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Display public event landing page
     */
    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->with(['company', 'ticketTypes'])
            ->firstOrFail();

        return view('events.show', compact('event'));
    }

    /**
     * Process attendee registration via Service Layer
     */
    public function register(Request $request, string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        try {
            $registration = $this->registrationService->registerAttendee($event, $validated);
            return redirect()->route('public.event.success', ['slug' => $event->slug, 'code' => $registration->ticket_code]);
        } catch (Exception $e) {
            return back()->withErrors(['ticket_type_id' => $e->getMessage()]);
        }
    }

    /**
     * Show Registration Success Page
     */
    public function success(string $slug, string $code)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $registration = Registration::where('ticket_code', $code)
            ->where('event_id', $event->id)
            ->with(['ticketType', 'event.company'])
            ->firstOrFail();

        $qrSvg = TicketService::generateQrSvg($registration->qr_code_token);

        return view('events.success', compact('event', 'registration', 'qrSvg'));
    }

    /**
     * Download PDF Ticket directly
     */
    public function downloadPdf(string $code)
    {
        $registration = Registration::where('ticket_code', $code)
            ->with(['event.company', 'ticketType'])
            ->firstOrFail();

        $pdf = TicketService::generatePdf($registration);

        return $pdf->download('Ticket-' . $registration->ticket_code . '.pdf');
    }
}
