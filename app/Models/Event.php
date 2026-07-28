<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'description',
        'location',
        'google_maps_url',
        'cover_image',
        'start_date',
        'end_date',
        'registration_start_date',
        'registration_end_date',
        'capacity',
        'event_type',
        'status',
        'custom_fields',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start_date' => 'datetime',
        'registration_end_date' => 'datetime',
        'custom_fields' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
