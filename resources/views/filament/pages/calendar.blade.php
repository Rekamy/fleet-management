<x-filament-panels::page>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <x-filament::button wire:click="previousMonth">
                    <x-heroicon-m-chevron-left class="w-5 h-5" />
                </x-filament::button>
                <span class="text-lg font-medium">
                    {{ Carbon\Carbon::parse($selectedDate)->format('F Y') }}
                </span>
                <x-filament::button wire:click="nextMonth">
                    <x-heroicon-m-chevron-right class="w-5 h-5" />
                </x-filament::button>
            </div>
        </div>

        <div
            x-data="{
                bookings: @js($bookings),
                calendar: null,
                initCalendar() {
                    this.calendar = new FullCalendar.Calendar(this.$refs.calendar, {
                        initialView: 'dayGridMonth',
                        initialDate: @js($selectedDate),
                        headerToolbar: false,
                        events: this.bookings,
                        eventClassNames: function(arg) {
                            return [
                                'cursor-pointer',
                                arg.event.extendedProps.status === 'approved' ? 'bg-success-500' :
                                arg.event.extendedProps.status === 'pending' ? 'bg-warning-500' :
                                arg.event.extendedProps.status === 'rejected' ? 'bg-danger-500' :
                                arg.event.extendedProps.status === 'completed' ? 'bg-gray-500' :
                                'bg-primary-500'
                            ];
                        },
                        eventClick: function(info) {
                            window.location = info.event.url;
                        }
                    });
                    this.calendar.render();
                }
            }"
            x-init="initCalendar"
            wire:ignore
            class="bg-white rounded-xl shadow"
        >
            <div x-ref="calendar" class="min-h-[500px] p-4"></div>
        </div>

        <div class="flex items-center justify-end space-x-4 text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-success-500"></div>
                <span>Approved</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-warning-500"></div>
                <span>Pending</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-danger-500"></div>
                <span>Rejected</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-gray-500"></div>
                <span>Completed</span>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    @endpush
</x-filament-panels::page>
