<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $record->title }}</h1>
        <p class="text-gray-600 mt-1">
            {{ $record->start_date->format('F j, Y - g:i A') }} to {{ $record->end_date->format('g:i A') }}
        </p>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checked In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($registrations as $registration)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $registration->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $registration->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $registration->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $registration->ticketType->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($registration->checked_in_at)
                                <span class="text-green-600">✓ Yes</span>
                            @else
                                <span class="text-red-600">✗ No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $registration->checked_in_at?->format('M j, Y g:i A') ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($registration->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($registration->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($registration->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
