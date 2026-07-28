<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Event;
use App\Models\Registration;
use Carbon\Carbon;
use Exception;

class CheckinService
{
    /**
     * Process staff QR ticket check-in verification logic
     */
    public function processCheckin(Event $event, string $qrToken, ?int $staffId = null, ?string $device = null): array
    {
        $qrToken = trim($qrToken);

        // 1. Find registration by QR token or Ticket Code
        $registration = Registration::where('event_id', $event->id)
            ->where(function ($q) use ($qrToken) {
                $q->where('qr_code_token', $qrToken)
                  ->orWhere('ticket_code', $qrToken);
            })
            ->with('ticketType')
            ->first();

        if (!$registration) {
            return [
                'success' => false,
                'status_code' => 404,
                'status' => 'INVALID_TICKET',
                'message' => '❌ Invalid ticket or does not belong to this event.',
            ];
        }

        // 2. Verify HMAC Token integrity
        $expectedToken = TicketService::generateQrToken($registration->ticket_code);
        if ($registration->qr_code_token && $registration->qr_code_token !== $qrToken && $expectedToken !== $qrToken) {
            return [
                'success' => false,
                'status_code' => 403,
                'status' => 'TAMPERED_QR',
                'message' => '⚠️ Security Warning: QR code token has been tampered with!',
            ];
        }

        // 3. Check double check-in
        if ($registration->checked_in_at !== null) {
            return [
                'success' => false,
                'status_code' => 409,
                'status' => 'ALREADY_CHECKED_IN',
                'message' => '⚠️ Attendee already checked in on ' . Carbon::parse($registration->checked_in_at)->format('g:i:s A'),
                'attendee' => [
                    'name' => $registration->attendee_name,
                    'ticket_code' => $registration->ticket_code,
                    'ticket_type' => $registration->ticketType->name ?? 'Standard',
                    'checked_in_at' => $registration->checked_in_at,
                ],
            ];
        }

        // 4. Record successful check-in
        $now = now();
        $registration->update([
            'checked_in_at' => $now,
            'status' => 'checked_in',
        ]);

        Checkin::create([
            'company_id' => $event->company_id,
            'event_id' => $event->id,
            'registration_id' => $registration->id,
            'staff_id' => $staffId ?? auth()->id() ?? 1,
            'scanned_at' => $now,
            'status' => 'success',
            'device' => $device ?? 'Mobile Camera Scanner',
        ]);

        return [
            'success' => true,
            'status_code' => 200,
            'status' => 'CHECKIN_SUCCESS',
            'message' => '✅ Verified! Welcome, ' . $registration->attendee_name,
            'attendee' => [
                'name' => $registration->attendee_name,
                'email' => $registration->attendee_email,
                'ticket_code' => $registration->ticket_code,
                'ticket_type' => $registration->ticketType->name ?? 'Standard',
                'checked_in_at' => $now->format('g:i:s A'),
            ],
        ];
    }
}
