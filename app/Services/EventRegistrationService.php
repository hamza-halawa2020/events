<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use App\Mail\TicketConfirmationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;

class EventRegistrationService
{
    /**
     * Register an attendee for an event cleanly in service layer
     */
    public function registerAttendee(Event $event, array $data): Registration
    {
        return DB::transaction(function () use ($event, $data) {
            $ticketType = TicketType::where('id', $data['ticket_type_id'])
                ->where('event_id', $event->id)
                ->firstOrFail();

            // 1. Check ticket availability
            if ($ticketType->available_quantity !== null && $ticketType->available_quantity <= 0) {
                throw new Exception('Selected ticket category is sold out.');
            }

            // 2. Generate Ticket details
            $ticketCode = TicketService::generateTicketCode();
            $qrToken = TicketService::generateQrToken($ticketCode);

            // 3. Create Registration
            $registration = Registration::create([
                'company_id' => $event->company_id,
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'attendee_name' => $data['attendee_name'],
                'attendee_email' => $data['attendee_email'],
                'ticket_code' => $ticketCode,
                'qr_code_token' => $qrToken,
                'status' => 'confirmed',
                'registered_at' => now(),
            ]);

            // 4. Decrement available capacity
            if ($ticketType->available_quantity !== null) {
                $ticketType->decrement('available_quantity');
            }

            // 5. Send Email Notification
            try {
                Mail::to($registration->attendee_email)->send(new TicketConfirmationMail($registration));
            } catch (\Throwable $e) {
                logger()->error('Failed sending ticket email: ' . $e->getMessage());
            }

            return $registration;
        });
    }
}
