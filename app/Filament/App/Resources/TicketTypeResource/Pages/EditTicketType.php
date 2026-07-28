<?php

namespace App\Filament\App\Resources\TicketTypeResource\Pages;

use App\Filament\App\Resources\TicketTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicketType extends EditRecord
{
    protected static string $resource = TicketTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
