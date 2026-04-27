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
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
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
        --fc-border-color: #f1f5f9;
        --fc-today-bg-color: #f8fafc;
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #e2e8f0;
        --fc-button-text-color: #475569;
        --fc-button-hover-bg-color: #f8fafc;
        --fc-button-hover-border-color: #cbd5e1;
        --fc-button-active-bg-color: #f1f5f9;
        --fc-button-active-border-color: #94a3b8;
    }
    
    /* Calendar Container */
    .fc { 
        font-family: 'Inter', sans-serif; 
        background: white;
    }
    
    /* Header Customization */
    .fc-header-toolbar {
        margin-bottom: 2.5rem !important;
        padding: 0 1rem;
    }
    
    .fc-toolbar-title { 
        font-weight: 900 !important; 
        color: #0f172a; 
        font-size: 1.25rem !important;
        letter-spacing: -0.02em;
        font-family: 'Open Sans', sans-serif !important;
    }
    
    /* Navigation Buttons */
    .fc-button { 
        border-radius: 14px !important; 
        font-weight: 700 !important; 
        font-size: 11px !important;
        letter-spacing: 0.02em !important;
        padding: 8px 16px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
        text-transform: capitalize !important;
    }
    
    .fc-button-primary:not(:disabled):active, 
    .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
    }
    
    .fc-button-group > .fc-button:not(:first-child) { margin-left: 6px !important; }
    
    /* Day Headers */
    .fc-col-header-cell { 
        padding: 20px 0 !important; 
        background: #f8fafc;
        border: none !important;
    }
    
    .fc-col-header-cell-cushion { 
        font-size: 10px !important; 
        font-weight: 800 !important; 
        text-transform: uppercase; 
        letter-spacing: 0.15em;
        color: #94a3b8;
    }
    
    /* Day Grid */
    .fc-daygrid-day { transition: background 0.3s; }
    .fc-daygrid-day:hover { background: #fbfcfe; }
    
    .fc-daygrid-day-number { 
        font-weight: 800 !important; 
        font-size: 13px !important; 
        color: #64748b; 
        padding: 12px 16px !important;
        font-family: 'Inter', sans-serif;
    }
    
    .fc-day-today { background: #f1f5f9/30 !important; }
    .fc-day-today .fc-daygrid-day-number { color: #6366f1 !important; }
    
    /* Event Styling */
    .fc-event { 
        border-radius: 12px !important; 
        padding: 4px 10px !important; 
        font-weight: 700 !important; 
        font-size: 11px !important;
        border: none !important;
        margin: 2px 6px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px -2px rgba(0,0,0,0.08) !important;
    }
    
    .fc-event:hover { 
        transform: translateY(-2px) scale(1.02); 
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
    }
    
    /* Color Variations for Events */
    .fc-daygrid-event-dot { border-width: 4px !important; }
    
    .fc-v-event { background-color: transparent !important; }
    
    /* Modal/Popups (if any) */
    .fc-popover {
        border-radius: 20px !important;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1) !important;
        border: 1px solid #f1f5f9 !important;
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
                // Custom event styling based on type
                if (info.event.extendedProps.status === 'completed') {
                    info.el.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                } else {
                    info.el.style.background = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';
                }
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
