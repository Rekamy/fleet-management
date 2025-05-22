<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Filament\Resources\BookingResource;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.calendar';

    public $selectedDate;
    public $bookings = [];

    public function mount(): void
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadBookings();
    }

    public function loadBookings(): void
    {
        $startOfMonth = Carbon::parse($this->selectedDate)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedDate)->endOfMonth();

        $this->bookings = Booking::query()
            ->with(['vehicle', 'driver'])
            ->whereBetween('start_datetime', [$startOfMonth, $endOfMonth])
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => "{$booking->vehicle->name} - {$booking->driver->name}",
                    'start' => $booking->start_datetime,
                    'end' => $booking->end_datetime,
                    'status' => $booking->status,
                    'url' => BookingResource::getUrl('edit', ['record' => $booking]),
                ];
            })
            ->toArray();
    }

    public function previousMonth(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subMonth()->format('Y-m-d');
        $this->loadBookings();
    }

    public function nextMonth(): void
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->addMonth()->format('Y-m-d');
        $this->loadBookings();
    }

    protected function getViewData(): array
    {
        return [
            'bookings' => $this->bookings,
            'selectedDate' => $this->selectedDate,
        ];
    }
}
