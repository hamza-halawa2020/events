<?php

namespace App\Filament\App\Pages;

use App\Models\Event;
use Filament\Pages\Page;

class CheckinScanner extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Check-in Scanner';
    protected static \UnitEnum|string|null $navigationGroup = 'Events & Ticketing';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.pages.checkin-scanner';
    public ?int $selectedEventId = null;

    public function mount(): void
    {
        $this->selectedEventId = request()->query('event');
    }

    public function getEvents()
    {
        return Event::where('company_id', filament()->getTenant()?->id)
            ->whereIn('status', ['Published'])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->get();
    }

    public function getSelectedEvent(): ?Event
    {
        if (!$this->selectedEventId) return null;
        return Event::where('id', $this->selectedEventId)
            ->where('company_id', filament()->getTenant()?->id)
            ->first();
    }

    public function getTitle(): string
    {
        return 'QR Check-in Scanner';
    }
}