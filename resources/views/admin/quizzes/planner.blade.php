@extends('layouts.admin')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 md:p-8 font-inter">
    <!-- Header: Compact Calendar -->
    <div class="relative overflow-hidden bg-white rounded-3xl p-6 md:p-8 mb-8 shadow-sm border border-slate-100">
        <div class="absolute inset-0 opacity-40 pointer-events-none">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 blur-[80px] rounded-full translate-x-1/2 -translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <div class="inline-flex items-center gap-2 text-blue-600 text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight" style="font-family: 'Open Sans', Helvetica, Arial, sans-serif !important;">Calendar</h1>
            </div>
            
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <div class="w-8 h-8 bg-white text-blue-500 rounded-lg flex items-center justify-center text-sm shadow-sm border border-slate-100">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="text-xs font-bold text-slate-900 tabular-nums">{{ now()->format('M d, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-8 md:p-12 relative overflow-hidden">
        <div id="calendar" class="min-h-[700px]"></div>
    </div>

    <!-- Legend & Info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-50/50 backdrop-blur-sm p-6 rounded-3xl border border-indigo-100/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-3 h-3 bg-indigo-500 rounded-full ring-4 ring-indigo-500/20"></div>
                <h4 class="text-[10px] font-black text-indigo-900 uppercase tracking-widest">Active Quizzes</h4>
            </div>
            <p class="text-xs text-indigo-700/70 font-medium">Currently open or upcoming assessments.</p>
        </div>
        <div class="bg-emerald-50/50 backdrop-blur-sm p-6 rounded-3xl border border-emerald-100/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-3 h-3 bg-emerald-500 rounded-full ring-4 ring-emerald-500/20"></div>
                <h4 class="text-[10px] font-black text-emerald-900 uppercase tracking-widest">Completed</h4>
            </div>
            <p class="text-xs text-emerald-700/70 font-medium">Assessments you have already finished.</p>
        </div>
        <div class="bg-slate-50/50 backdrop-blur-sm p-6 rounded-3xl border border-slate-200/50">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-3 h-3 bg-slate-400 rounded-full ring-4 ring-slate-400/20"></div>
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest">Other Events</h4>
            </div>
            <p class="text-xs text-slate-500 font-medium">General academic dates and reminders.</p>
        </div>
    </div>
</div>

<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<style>
    :root {
        --fc-border-color: #e8edf2;
        --fc-today-bg-color: #f0f4ff;
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #e2e8f0;
        --fc-button-text-color: #475569;
        --fc-button-hover-bg-color: #f8fafc;
        --fc-button-hover-border-color: #c7d2fe;
        --fc-button-active-bg-color: #eef2ff;
        --fc-button-active-border-color: #a5b4fc;
    }

    /* ── Base ── */
    .fc {
        font-family: 'Inter', 'Open Sans', sans-serif;
        background: transparent;
    }

    /* ── Header Toolbar ── */
    .fc-header-toolbar {
        margin-bottom: 1.5rem !important;
        padding: 0 0.25rem;
    }

    .fc-toolbar-title {
        font-weight: 900 !important;
        color: #0f172a !important;
        font-size: 1.15rem !important;
        letter-spacing: -0.025em;
        font-family: 'Open Sans', sans-serif !important;
    }

    /* ── Nav Buttons ── */
    .fc-button {
        border-radius: 12px !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.03em !important;
        padding: 7px 14px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
        text-transform: capitalize !important;
    }

    .fc-button-primary {
        background: white !important;
        border-color: #e2e8f0 !important;
        color: #475569 !important;
    }

    .fc-button-primary:hover {
        background: #f0f4ff !important;
        border-color: #a5b4fc !important;
        color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1) !important;
    }

    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background: #eef2ff !important;
        border-color: #a5b4fc !important;
        color: #4338ca !important;
        font-weight: 800 !important;
    }

    .fc-button-group > .fc-button:not(:first-child) {
        margin-left: 4px !important;
        border-left: 1px solid #e2e8f0 !important;
    }

    /* ── Day Column Headers (SUN MON…) ── */
    .fc-col-header {
        background: transparent;
    }

    .fc-col-header-cell {
        padding: 14px 0 !important;
        border: none !important;
        background: transparent !important;
    }

    .fc-col-header-cell-cushion {
        font-size: 9px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.18em !important;
        color: #94a3b8 !important;
        text-decoration: none !important;
    }

    /* ── Day Grid Cells ── */
    .fc-daygrid-body {
        border-radius: 16px;
        overflow: hidden;
    }

    .fc-scrollgrid {
        border: 1px solid #e2e8f0 !important;
        border-radius: 20px !important;
        overflow: hidden;
        background: #ffffff;
    }

    .fc-scrollgrid-section-header td,
    .fc-scrollgrid-section-body td {
        border: 1px solid #f1f5f9 !important;
    }

    .fc-daygrid-day {
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 100px !important;
        position: relative;
    }

    .fc-daygrid-day:hover {
        background: #fdfdff !important;
        z-index: 5;
        box-shadow: inset 0 0 0 2px #e0e7ff, 0 8px 20px -6px rgba(0,0,0,0.05) !important;
    }

    /* ── Day Numbers ── */
    .fc-daygrid-day-top {
        padding: 8px 10px 0 !important;
        justify-content: flex-start !important;
    }

    .fc-daygrid-day-number {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #475569 !important;
        line-height: 1 !important;
        width: 30px;
        height: 30px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .fc-daygrid-day-number:hover {
        background: #e0e7ff;
        color: #4338ca !important;
    }

    /* ── Today's Date ── */
    .fc-day-today {
        background: #f0f4ff !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        background: #4f46e5 !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        box-shadow: 0 4px 12px rgba(79,70,229,0.35) !important;
    }

    /* ── Greyed out (other month) dates ── */
    .fc-day-other .fc-daygrid-day-number {
        color: #cbd5e1 !important;
        font-weight: 500 !important;
    }

    .fc-day-other {
        background: #fafbfc !important;
    }

    /* ── Events ── */
    .fc-event {
        border-radius: 8px !important;
        padding: 3px 8px !important;
        font-weight: 700 !important;
        font-size: 10.5px !important;
        border: none !important;
        margin: 2px 4px 2px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 8px -2px rgba(0,0,0,0.12) !important;
        cursor: pointer;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(0,0,0,0.15) !important;
        filter: brightness(1.05);
    }

    .fc-event-title {
        font-size: 10.5px !important;
        font-weight: 700 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-more-link {
        font-size: 9px !important;
        font-weight: 900 !important;
        color: #6366f1 !important;
        padding: 2px 8px !important;
        background: #eef2ff !important;
        border-radius: 6px !important;
        margin: 2px 4px !important;
        text-decoration: none !important;
    }

    .fc-more-link:hover {
        background: #e0e7ff !important;
    }

    /* ── Popover ── */
    .fc-popover {
        border-radius: 16px !important;
        box-shadow: 0 20px 40px -8px rgba(0,0,0,0.12) !important;
        border: 1px solid #e0e7ff !important;
        overflow: hidden;
    }

    .fc-popover-header {
        background: #f0f4ff !important;
        padding: 10px 14px !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        color: #4338ca !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            dayMaxEvents: true,
            nowIndicator: true,
            editable: false,
            selectable: true,
            events: @json($events),
            eventDidMount: function(info) {
                // High-End Pastel Aesthetic
                if (info.event.extendedProps.status === 'completed') {
                    info.el.style.background = '#f0fdf4'; // Very light green
                    info.el.style.borderLeft = '4px solid #10b981';
                } else {
                    info.el.style.background = '#f5f7ff'; // Very light blue
                    info.el.style.borderLeft = '4px solid #4f46e5';
                }
                info.el.style.color = '#0f172a'; // Month Title Color
                info.el.style.padding = '6px 12px';
                info.el.style.boxShadow = '0 1px 3px rgba(0,0,0,0.02)';
                info.el.style.fontWeight = '800';
                info.el.style.fontFamily = "'Open Sans', sans-serif";
                info.el.style.textShadow = 'none';
                info.el.style.borderTopRightRadius = '10px';
                info.el.style.borderBottomRightRadius = '10px';

                // Target internal elements specifically to force the color
                const titleEl = info.el.querySelector('.fc-event-title');
                const timeEl = info.el.querySelector('.fc-event-time');
                if (titleEl) titleEl.style.color = '#0f172a';
                if (timeEl) timeEl.style.color = '#0f172a';
            },
            eventClick: function(info) {
                if (info.event.url) {
                    window.location.href = info.event.url;
                    info.jsEvent.preventDefault();
                }
            }
        });
        calendar.render();
    });
</script>
@endsection
