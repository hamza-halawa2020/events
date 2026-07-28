<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@eventpass.com',
            'password' => Hash::make('password'),
            'role' => 'SuperAdmin',
        ]);

        // 2. Sample Company Workspace
        $company = Company::create([
            'name' => 'Acme Events Corp',
            'logo' => null,
            'branding' => [
                'primary_color' => '#4F46E5',
                'secondary_color' => '#10B981',
                'footer_text' => 'Powered by EventPass Platform',
            ],
        ]);

        // 3. Company Admin & Event Staff Users
        $companyAdmin = User::create([
            'company_id' => $company->id,
            'name' => 'Sara Ahmed (Admin)',
            'email' => 'company@acme.com',
            'password' => Hash::make('password'),
            'role' => 'CompanyAdmin',
        ]);

        $eventStaff = User::create([
            'company_id' => $company->id,
            'name' => 'Mohamed Ali (Staff)',
            'email' => 'staff@acme.com',
            'password' => Hash::make('password'),
            'role' => 'EventStaff',
        ]);

        // 4. Sample Event
        $event = Event::create([
            'company_id' => $company->id,
            'title' => 'Global Tech Summit 2026',
            'slug' => 'global-tech-summit-2026',
            'description' => 'The premier tech summit for AI, Cloud Engineering, and Smart SaaS platforms.',
            'location' => 'Cairo International Convention Center, Hall 4',
            'google_maps_url' => 'https://maps.google.com',
            'start_date' => now()->addDays(5)->setHour(9)->setMinute(0),
            'end_date' => now()->addDays(5)->setHour(18)->setMinute(0),
            'registration_start_date' => now()->subDays(10),
            'registration_end_date' => now()->addDays(4),
            'capacity' => 500,
            'event_type' => 'Physical',
            'status' => 'Published',
            'custom_fields' => [
                ['name' => 'Company Name', 'type' => 'text', 'required' => true],
                ['name' => 'Job Title', 'type' => 'text', 'required' => false],
            ],
        ]);

        // 5. Ticket Types
        $vipTicket = TicketType::create([
            'event_id' => $event->id,
            'name' => 'VIP Pass',
            'capacity' => 50,
            'price' => 150.00,
            'description' => 'Access to VIP lounge and keynote front seats',
        ]);

        $regularTicket = TicketType::create([
            'event_id' => $event->id,
            'name' => 'General Admission',
            'capacity' => 450,
            'price' => 50.00,
            'description' => 'Standard entrance pass',
        ]);

        // 6. Sample Registrations
        $attendee1 = Registration::create([
            'event_id' => $event->id,
            'ticket_type_id' => $vipTicket->id,
            'name' => 'Ahmed Mahmoud',
            'email' => 'ahmed@example.com',
            'phone' => '+201001234567',
            'custom_fields_data' => [
                'Company Name' => 'Tech Solutions Ltd',
                'Job Title' => 'CTO',
            ],
            'ticket_code' => 'EVT-2026-000001',
            'qr_code' => hash_hmac('sha256', 'EVT-2026-000001', config('app.key')),
            'status' => 'Approved',
        ]);

        $attendee2 = Registration::create([
            'event_id' => $event->id,
            'ticket_type_id' => $regularTicket->id,
            'name' => 'Mona El-Sayed',
            'email' => 'mona@example.com',
            'phone' => '+201119876543',
            'custom_fields_data' => [
                'Company Name' => 'Digital Innovations',
                'Job Title' => 'Product Designer',
            ],
            'ticket_code' => 'EVT-2026-000002',
            'qr_code' => hash_hmac('sha256', 'EVT-2026-000002', config('app.key')),
            'status' => 'Approved',
        ]);
    }
}
