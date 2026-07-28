<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'capacity',
        'price',
        'description',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Company::class,
            Event::class,
            'id',        // events.id
            'id',        // companies.id
            'event_id',  // ticket_types.event_id
            'company_id' // events.company_id
        );
    }
}
