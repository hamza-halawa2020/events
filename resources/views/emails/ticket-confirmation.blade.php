<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
        .header { background: #4f46e5; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .ticket-box { background: #f8fafc; border-left: 4px solid #4f46e5; padding: 15px; margin: 20px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Registration Confirmed!</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $registration->name }}</strong>,</p>
            <p>Thank you for registering for <strong>{{ $event->title }}</strong> organized by {{ $company->name ?? 'EventPass' }}.</p>
            
            <div class="ticket-box">
                <p style="margin: 0; font-size: 14px;"><strong>Ticket Code:</strong> <span style="font-family: monospace; color: #4f46e5;">{{ $registration->ticket_code }}</span></p>
                <p style="margin: 5px 0 0 0; font-size: 14px;"><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y - g:i A') }}</p>
                <p style="margin: 5px 0 0 0; font-size: 14px;"><strong>Location:</strong> {{ $event->location }}</p>
            </div>

            <p>Your official PDF ticket with entry QR Code is attached to this email. Please save or print it for check-in at the entrance.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $company->name ?? 'EventPass' }}. All rights reserved.
        </div>
    </div>
</body>
</html>
