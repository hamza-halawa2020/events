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
            // 1. Check registration date limits
            $now = now();
            if ($event->registration_start_date && $now->lt($event->registration_start_date)) {
                throw new Exception('Registration has not started yet. Registration starts on: ' . $event->registration_start_date->format('Y-m-d H:i'));
            }
            if ($event->registration_end_date && $now->gt($event->registration_end_date)) {
                throw new Exception('Registration has ended. Registration ended on: ' . $event->registration_end_date->format('Y-m-d H:i'));
            }

            // 2. Check for duplicate email registration for the same event
            $existingRegistration = Registration::where('event_id', $event->id)
                ->where('email', $data['attendee_email'])
                ->first();
            if ($existingRegistration) {
                throw new Exception('This email is already registered for this event.');
            }

            $ticketType = TicketType::where('id', $data['ticket_type_id'])
                ->where('event_id', $event->id)
                ->firstOrFail();

            // 3. Check ticket availability
            if ($ticketType->available_quantity !== null && $ticketType->available_quantity <= 0) {
                throw new Exception('Selected ticket category is sold out.');
            }

            // 4. Generate Ticket details
            $ticketCode = TicketService::generateTicketCode();
            $qrToken = TicketService::generateQrToken($ticketCode);

            // 5. Create Registration
            $registration = Registration::create([
                'event_id'           => $event->id,
                'ticket_type_id'     => $ticketType->id,
                'name'               => $data['attendee_name'],
                'email'              => $data['attendee_email'],
                'ticket_code'        => $ticketCode,
                'qr_code'            => $qrToken,
                'status'             => 'confirmed',
                'custom_fields_data' => $data['custom_fields_data'] ?? null,
            ]);

            // 6. Decrement available capacity
            if ($ticketType->available_quantity !== null) {
                $ticketType->decrement('available_quantity');
            }

            // 7. Send Email Notification
            try {
                Mail::to($registration->email)->send(new TicketConfirmationMail($registration));
            } catch (\Throwable $e) {
                logger()->error('Failed sending ticket email: ' . $e->getMessage());
            }

            return $registration;
        });
    }
}
