<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Ticket - {{ $registration->ticket_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }
        .ticket-card {
            background-color: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            max-width: 650px;
            margin: 0 auto;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            padding: 24px;
        }
        .company-name {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 6px;
        }
        .event-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .ticket-body {
            padding: 24px;
            display: table;
            width: 100%;
            box-sizing: border-box;
        }
        .info-col {
            display: table-cell;
            vertical-align: top;
            width: 65%;
            padding-right: 20px;
        }
        .qr-col {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 35%;
            border-left: 2px dashed #cbd5e1;
            padding-left: 20px;
        }
        .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 2px;
        }
        .value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #e0e7ff;
            color: #3730a3;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 12px 24px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .qr-image {
            width: 150px;
            height: 150px;
            margin-bottom: 8px;
        }
        .ticket-code {
            font-family: monospace;
            font-weight: bold;
            font-size: 13px;
            color: #4f46e5;
        }
    </style>
</head>
<body>
    <div class="ticket-card">
        <div class="header">
            <div class="company-name">{{ $company->name ?? 'EventPass' }}</div>
            <h1 class="event-title">{{ $event->title }}</h1>
        </div>

        <div class="ticket-body">
            <div class="info-col">
                <div class="label">Attendee Name</div>
                <div class="value">{{ $registration->attendee_name }}</div>

                <div class="label">Email Address</div>
                <div class="value">{{ $registration->attendee_email }}</div>

                <div class="label">Ticket Category</div>
                <div class="value">
                    <span class="badge">{{ $ticketType->name ?? 'General Admission' }}</span>
                </div>

                <div class="label">Date & Time</div>
                <div class="value">
                    {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y - g:i A') }}
                </div>

                <div class="label">Location</div>
                <div class="value">{{ $event->location ?? 'Online Event' }}</div>
            </div>

            <div class="qr-col">
                <div style="margin-bottom: 10px;">
                    {!! $qrSvg !!}
                </div>
                <div class="ticket-code">{{ $registration->ticket_code }}</div>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">Present at entrance</div>
            </div>
        </div>

        <div class="footer">
            Powered by <strong>EventPass</strong> Smart QR Event Platform | Generated on {{ date('Y-m-d H:i') }}
        </div>
    </div>
</body>
</html>
