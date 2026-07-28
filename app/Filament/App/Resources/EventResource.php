<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static \UnitEnum|string|null $navigationGroup = 'Events & Ticketing';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, \Filament\Schemas\Components\Utilities\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Event::class, 'slug', ignoreRecord: true),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('location')
                            ->required(),

                        Forms\Components\TextInput::make('google_maps_url')
                            ->url()
                            ->label('Google Maps Location URL'),

                        Forms\Components\FileUpload::make('cover_image')
                            ->image()
                            ->directory('event-covers'),
                    ])->columns(2),

                Section::make('Timing & Capacity')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')->required(),
                        Forms\Components\DateTimePicker::make('end_date')->required(),
                        Forms\Components\DateTimePicker::make('registration_start_date'),
                        Forms\Components\DateTimePicker::make('registration_end_date'),
                        Forms\Components\TextInput::make('capacity')
                            ->numeric()
                            ->default(100)
                            ->required(),
                        Forms\Components\Select::make('event_type')
                            ->options([
                                'Physical' => 'Physical Venue',
                                'Virtual' => 'Virtual / Online',
                                'Hybrid' => 'Hybrid Event',
                            ])
                            ->default('Physical')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Published' => 'Published',
                                'Closed' => 'Registration Closed',
                                'Finished' => 'Finished',
                            ])
                            ->default('Draft')
                            ->required(),
                    ])->columns(2),

                Section::make('Dynamic Registration Form Fields')
                    ->schema([
                        Forms\Components\Repeater::make('custom_fields')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required()->label('Field Label'),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'text' => 'Short Text',
                                        'textarea' => 'Long Text',
                                        'select' => 'Dropdown Select',
                                    ])->default('text')->required(),
                                Forms\Components\Toggle::make('required')->default(false),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('start_date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('capacity')->sortable(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registered'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Published' => 'success',
                        'Draft' => 'warning',
                        'Closed' => 'danger',
                        'Finished' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                        'Closed' => 'Closed',
                        'Finished' => 'Finished',
                    ]),
            ])
            ->actions([
                Actions\Action::make('registration_link')
                    ->label('Registration Link')
                    ->icon('heroicon-o-link')
                    ->color('success')
                    ->modalHeading(fn (Event $record) => 'Registration Link: ' . $record->title)
                    ->modalDescription('Share this link with your attendees so they can register for the event.')
                    ->modalContent(fn (Event $record) => view('filament.event-link-modal', [
                        'url' => route('public.event.show', $record->slug),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
