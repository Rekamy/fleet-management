<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Bookings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                        $set('driver_id', null)
                    ),
                Forms\Components\Select::make('driver_id')
                    ->relationship('driver', 'name', function ($query, $get) {
                        $query->where('status', 'available');
                        if ($get('start_datetime') && $get('end_datetime')) {
                            $query->whereDoesntHave('bookings', function ($query) use ($get) {
                                $query->where(function ($query) use ($get) {
                                    $query->whereBetween('start_datetime', [
                                        $get('start_datetime'),
                                        $get('end_datetime'),
                                    ])->orWhereBetween('end_datetime', [
                                        $get('start_datetime'),
                                        $get('end_datetime'),
                                    ]);
                                });
                            });
                        }
                    })
                    ->required()
                    ->preload()
                    ->searchable(),
                Forms\Components\DateTimePicker::make('start_datetime')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                        $set('driver_id', null)
                    ),
                Forms\Components\DateTimePicker::make('end_datetime')
                    ->required()
                    ->live()
                    ->after('start_datetime')
                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                        $set('driver_id', null)
                    ),
                Forms\Components\Textarea::make('purpose')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        Booking::STATUS_PENDING => 'Pending',
                        Booking::STATUS_APPROVED => 'Approved',
                        Booking::STATUS_REJECTED => 'Rejected',
                        Booking::STATUS_COMPLETED => 'Completed',
                        Booking::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->required()
                    ->default(Booking::STATUS_PENDING)
                    ->disabled(fn (string $operation) => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_datetime')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_datetime')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        Booking::STATUS_PENDING => 'Pending',
                        Booking::STATUS_APPROVED => 'Approved',
                        Booking::STATUS_REJECTED => 'Rejected',
                        Booking::STATUS_COMPLETED => 'Completed',
                        Booking::STATUS_CANCELLED => 'Cancelled',
                    ])
                    ->sortable(),
            ])
            ->defaultSort('start_datetime', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Booking::STATUS_PENDING => 'Pending',
                        Booking::STATUS_APPROVED => 'Approved',
                        Booking::STATUS_REJECTED => 'Rejected',
                        Booking::STATUS_COMPLETED => 'Completed',
                        Booking::STATUS_CANCELLED => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('vehicle')
                    ->relationship('vehicle', 'name'),
                Tables\Filters\SelectFilter::make('driver')
                    ->relationship('driver', 'name'),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_datetime', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_datetime', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
