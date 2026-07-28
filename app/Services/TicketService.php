<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    /**
     * Generate unique ticket code: EVT-YEAR-RANDOM
     */
    public static function generateTicketCode(): string
    {
        do {
            $code = 'EVT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));
        } while (Registration::where('ticket_code', $code)->exists());

        return $code;
    }

    /**
     * Generate HMAC signed token for QR verification
     */
    public static function generateQrToken(string $ticketCode): string
    {
        $secret = config('app.key', 'EventPassSecretKey');
        return hash_hmac('sha256', $ticketCode, $secret);
    }

    /**
     * Generate SVG QR Code string for given token
     */
    public static function generateQrSvg(string $qrToken): string
    {
        return QrCode::size(160)
            ->format('svg')
            ->errorCorrection('H')
            ->generate($qrToken);
    }

    /**
     * Generate PDF Ticket stream / output
     */
    public static function generatePdf(Registration $registration)
    {
        $registration->loadMissing(['event.company', 'ticketType']);
        
        $qrToken = $registration->qr_code ?? self::generateQrToken($registration->ticket_code);
        $qrSvg = self::generateQrSvg($qrToken);

        $pdf = Pdf::loadView('pdf.ticket', [
            'registration' => $registration,
            'event' => $registration->event,
            'company' => $registration->event->company,
            'ticketType' => $registration->ticketType,
            'qrSvg' => $qrSvg,
        ]);

        return $pdf;
    }
}
