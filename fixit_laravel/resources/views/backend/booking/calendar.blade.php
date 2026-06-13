@use('App\Helpers\Helpers')

@extends('backend.layouts.master')

@section('title', __('static.booking.calendar'))

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/main.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/main.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.15/main.min.css">
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        padding: 1rem;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; }
    .fc .fc-button-primary { background-color: var(--primary-color, #5465FF); border-color: var(--primary-color, #5465FF); }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active { background-color: #4353d9; border-color: #4353d9; }
    .fc .fc-button-primary:hover { background-color: #4353d9; border-color: #4353d9; }
    .fc .fc-daygrid-event { border-radius: 4px; padding: 1px 4px; font-size: .75rem; cursor: pointer; }
    .fc .fc-list-event:hover td { background-color: #f0f2ff; }
    .fc .fc-list-event-title a { color: inherit; text-decoration: none; }
    .calendar-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem; }
    .calendar-header h4 { margin: 0; font-weight: 600; }
    .calendar-header .btn-group .btn { font-size: .8125rem; }
    .calendar-header .btn-group .btn.active { background-color: var(--primary-color, #5465FF); border-color: var(--primary-color, #5465FF); color: #fff; }
    .fc .fc-multimonth { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
    .fc .fc-multimonth-title { font-size: 1rem; font-weight: 600; padding: .5rem; background: #f9fafb; }
    .fc .fc-multimonth-header { background: #f3f4f6; }
    @media (max-width: 768px) {
        .fc .fc-toolbar { flex-direction: column; gap: .5rem; }
        .fc .fc-toolbar-chunk { display: flex; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="calendar-header">
                        <h4>{{ __('static.booking.calendar') }}</h4>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline active" data-view="dayGridMonth">{{ __('Month') }}</button>
                            <button type="button" class="btn btn-outline" data-view="timeGridWeek">{{ __('Week') }}</button>
                            <button type="button" class="btn btn-outline" data-view="timeGridDay">{{ __('Day') }}</button>
                            <button type="button" class="btn btn-outline" data-view="listMonth">{{ __('List') }}</button>
                            <button type="button" class="btn btn-outline" data-view="multiMonthYear">{{ __('Year') }}</button>
                        </div>
                        <button type="button" class="btn btn-primary" id="refreshCalendar">
                            <i data-feather="refresh-cw"></i> {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let currentView = 'dayGridMonth';

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: currentView,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        height: 'auto',
        firstDay: 1,
        nowIndicator: true,
        editable: false,
        selectable: false,
        dayMaxEvents: true,
        eventSources: [{
            url: '{{ route("backend.booking.calendar.events") }}',
            method: 'GET',
            extraParams: function() {
                return { view: currentView };
            },
            failure: function() {
                toastr.error('{{ __("Failed to load calendar events") }}');
            }
        }],
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.open(info.event.url, '_self');
            }
        },
        datesSet: function() {
            feather.replace();
        },
        loading: function(isLoading) {
            if (!isLoading) {
                feather.replace();
            }
        }
    });

    calendar.render();

    document.querySelectorAll('.btn-group .btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-group .btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            currentView = this.dataset.view;
            calendar.changeView(currentView);
        });
    });

    document.getElementById('refreshCalendar').addEventListener('click', function() {
        calendar.refetchEvents();
        toastr.success('{{ __("Calendar refreshed") }}');
    });
});
</script>
@endpush
