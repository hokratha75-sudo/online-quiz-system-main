@extends('layouts.admin')

@section('content')
<style>
    /* Calendar Customization */
.fc {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.fc .fc-toolbar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
}

.fc .fc-button-primary {
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #475569;
    font-weight: 500;
    text-transform: capitalize;
    box-shadow: none;
}

.fc .fc-button-primary:hover {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #4f46e5;
    border-color: #4f46e5;
    color: white;
}

/* Today styling */
.fc .fc-daygrid-day.fc-day-today {
    background-color: transparent !important;
}

.fc-day-today .fc-daygrid-day-number {
    background: #4f46e5 !important;
    color: white !important;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    margin: 4px 6px 0 auto;
    box-shadow: 0 2px 8px rgba(79,70,229,.3);
}

.fc .fc-daygrid-day-number {
    font-size: .875rem;
    font-weight: 500;
    color: #475569;
}

/* Day cells */
.fc .fc-daygrid-day {
    border-color: #e9eef3;
    transition: all .2s;
}

.fc .fc-daygrid-day:hover {
    background: #fafbff;
}

/* EVENT FIX */
.fc-daygrid-event-harness {
    width: 100% !important;
}

.fc-daygrid-block-event {
    width: calc(100% - 8px) !important;
    margin: 2px auto !important;
    border-radius: 8px;
}

.fc-h-event {
    overflow: hidden !important;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.fc-event-main-frame {
    width: 100%;
}

.fc-event {
    border-radius: 8px;
    padding: 4px 8px;
    border: none;
    font-size: .75rem;
    font-weight: 600;
    margin: 2px;
    transition: all .2s ease;
}

.fc-event:hover {
    transform: scale(1.02);
    opacity: .95;
}
</style>

<div class="container-fluid mx-auto px-4 py-6 font-sans text-slate-700">
    <!-- Breadcrumb Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800 tracking-tight">Academic Calendar</h1>
            <p class="text-sm text-slate-500 mt-1">Manage quizzes, exams, and important deadlines</p>
        </div>
        <nav class="text-sm text-slate-500 mt-2 md:mt-0">
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Dashboard</a>
            <span class="mx-2 text-slate-400">/</span>
            <span class="text-slate-600">Calendar</span>
        </nav>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Left Sidebar - Events Panel -->
        <div class="col-span-12 lg:col-span-3 space-y-5">
            <!-- Draggable Events Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-indigo-500"></i>
                        Drag & Drop Events
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Drag any item to the calendar</p>
                </div>
                
                <div class="p-3 max-h-[380px] overflow-y-auto custom-scrollbar" id="external-events">
                    <div class="fc-event bg-red-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-red-500">
                        <i class="bi bi-calendar-exclamation mr-2"></i>Final Exam
                    </div>
                    <div class="fc-event bg-orange-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-orange-500">
                        <i class="bi bi-clock-history mr-2"></i>Quiz Deadline
                    </div>
                    <div class="fc-event bg-amber-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-amber-500">
                        <i class="bi bi-pencil-square mr-2"></i>Midterm Quiz
                    </div>
                    <div class="fc-event bg-blue-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-blue-500">
                        <i class="bi bi-journal-check mr-2"></i>Class Assessment
                    </div>
                    <div class="fc-event bg-emerald-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-emerald-500">
                        <i class="bi bi-trophy mr-2"></i>Practice Test
                    </div>
                    <div class="fc-event bg-indigo-600 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-indigo-600">
                        <i class="bi bi-megaphone mr-2"></i>Result Release
                    </div>
                    <div class="fc-event bg-teal-500 text-white mb-2.5 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all" data-class="bg-teal-500">
                        <i class="bi bi-chat-dots mr-2"></i>Student Review
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="drop-remove" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs text-slate-600">Remove event after dropping</span>
                    </label>
                </div>
            </div>

            <!-- Create Custom Event Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-plus-circle text-indigo-500"></i>
                        Quick Event
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Create and drag your own event</p>
                </div>
                
                <div class="p-4 space-y-4">
                    <!-- Color Picker -->
                    <div>
                        <label class="text-xs font-medium text-slate-600 mb-2 block">Event Color</label>
                        <div class="flex flex-wrap gap-2" id="color-chooser">
                            <div class="w-8 h-8 rounded-lg bg-blue-500 cursor-pointer shadow-sm hover:scale-110 transition-transform ring-2 ring-offset-2 ring-indigo-400" data-color="#3b82f6"></div>
                            <div class="w-8 h-8 rounded-lg bg-amber-500 cursor-pointer shadow-sm hover:scale-110 transition-transform" data-color="#f59e0b"></div>
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 cursor-pointer shadow-sm hover:scale-110 transition-transform" data-color="#10b981"></div>
                            <div class="w-8 h-8 rounded-lg bg-red-500 cursor-pointer shadow-sm hover:scale-110 transition-transform" data-color="#ef4444"></div>
                            <div class="w-8 h-8 rounded-lg bg-slate-500 cursor-pointer shadow-sm hover:scale-110 transition-transform" data-color="#64748b"></div>
                            <div class="w-8 h-8 rounded-lg bg-purple-500 cursor-pointer shadow-sm hover:scale-110 transition-transform" data-color="#8b5cf6"></div>
                        </div>
                    </div>
                    
                    <!-- Event Input -->
                    <div>
                        <label class="text-xs font-medium text-slate-600 mb-2 block">Event Title</label>
                        <div class="flex">
                            <input type="text" id="new-event" 
                                   class="flex-1 px-3 py-2 text-sm border border-slate-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="e.g., Team Meeting">
                            <button type="button" id="add-new-event"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-r-lg transition-all">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Info Tip -->
            <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100">
                <div class="flex items-start gap-2">
                    <i class="bi bi-info-circle-fill text-indigo-500 text-sm mt-0.5"></i>
                    <div>
                        <p class="text-[11px] font-medium text-indigo-800">💡 Quick Tips</p>
                        <p class="text-[10px] text-indigo-600 mt-1">• Drag events to reschedule<br>• Click quiz events to start<br>• Click custom events to delete</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Calendar Section -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@5.11.3/main.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;

        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');

        // Initialize draggable external events
        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText.trim(),
                    backgroundColor: window.getComputedStyle(eventEl).getPropertyValue('background-color'),
                    borderColor: window.getComputedStyle(eventEl).getPropertyValue('background-color'),
                    textColor: '#ffffff'
                };
            }
        });

        // Initialize Calendar
        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            editable: true,
            droppable: true,
            nowIndicator: true,
            handleWindowResize: true,
            events: @json($events ?? []),
            eventDidMount: function(info) {
                // Add tooltip for deletion
                if (info.event.extendedProps.type === 'planner_event') {
                    info.el.title = "Click to delete this event";
                    info.el.style.cursor = "pointer";
                }
                if (info.event.extendedProps.type === 'quiz') {
                    info.el.style.cursor = "pointer";
                }
                
                // Style improvement for multi-day events
                if (!info.event.allDay && info.event.end && (info.event.end - info.event.start) > 86400000) {
                    info.el.style.borderLeftWidth = '4px';
                }
            },
            // Handle external event drop
            eventReceive: function(info) {
                var eventData = {
                    title: info.event.title,
                    start: info.event.startStr,
                    end: info.event.endStr,
                    allDay: info.event.allDay,
                    background_color: info.event.backgroundColor,
                    border_color: info.event.borderColor,
                    _token: '{{ csrf_token() }}'
                };

                fetch("{{ route('planner.store') }}", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json", 
                        "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                    },
                    body: JSON.stringify(eventData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        info.event.setProp('id', data.id);
                        info.event.setExtendedProp('type', 'planner_event');
                        showToast('Event added successfully', 'success');
                    } else {
                        showToast('Failed to save event', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to save event', 'error');
                });
            },
            // Handle event drag/drop update
            eventDrop: function(info) {
                if (info.event.extendedProps.type !== 'planner_event') {
                    info.revert();
                    return;
                }
                updateEvent(info.event);
                showToast('Event moved', 'success');
            },
            // Handle event resize
            eventResize: function(info) {
                updateEvent(info.event);
                showToast('Event resized', 'success');
            },
            // Handle event click
            eventClick: function(info) {
                if (info.event.extendedProps.type === 'quiz') {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                } else if (info.event.extendedProps.type === 'planner_event') {
                    if (confirm("Delete this event?")) {
                        fetch("{{ url('/planner/destroy') }}/" + info.event.id, {
                            method: "DELETE",
                            headers: { 
                                "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.remove();
                                showToast('Event deleted', 'info');
                            }
                        });
                    }
                }
            },
            // Handle drop completion
            drop: function(info) {
                if (checkbox.checked) {
                    info.draggedEl.remove();
                }
            }
        });

        // Function to update event on server
        function updateEvent(event) {
            var updateData = {
                id: event.id,
                start: event.startStr,
                end: event.endStr,
                allDay: event.allDay
            };

            fetch("{{ route('planner.update') }}", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify(updateData)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.warn('Update failed');
                }
            });
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const existingToasts = document.querySelectorAll('.toast-notification');
            existingToasts.forEach(toast => toast.remove());
            
            const toast = document.createElement('div');
            toast.className = `toast-notification fixed bottom-4 right-4 px-4 py-2.5 rounded-lg text-white text-sm font-medium z-50 shadow-lg ${
                type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-500' : 'bg-indigo-500'
            }`;
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle';
            toast.innerHTML = `<i class="bi bi-${icon} mr-2"></i>${message}`;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        calendar.render();

        /* Custom Event Creator */
        var currColor = '#3b82f6';
        var colorChooser = document.getElementById('color-chooser');
        var addButton = document.getElementById('add-new-event');
        
        addButton.style.backgroundColor = currColor;
        
        colorChooser.addEventListener('click', function(e) {
            if (e.target.dataset.color) {
                currColor = e.target.dataset.color;
                addButton.style.backgroundColor = currColor;
                
                colorChooser.querySelectorAll('div').forEach(el => {
                    el.classList.remove('ring-2', 'ring-offset-2', 'ring-indigo-400');
                });
                e.target.classList.add('ring-2', 'ring-offset-2', 'ring-indigo-400');
            }
        });

        document.getElementById('add-new-event').addEventListener('click', function(e) {
            e.preventDefault();
            var input = document.getElementById('new-event');
            var val = input.value.trim();
            if (val.length === 0) {
                showToast('Please enter an event title', 'error');
                return;
            }

            var event = document.createElement('div');
            event.style.backgroundColor = currColor;
            event.style.borderColor = currColor;
            event.style.color = '#fff';
            event.className = 'fc-event mb-2 p-2.5 rounded-lg cursor-pointer text-sm font-medium shadow-sm hover:shadow transition-all';
            event.innerHTML = `<i class="bi bi-calendar-plus mr-2"></i>${escapeHtml(val)}`;
            
            var container = document.getElementById('external-events');
            container.appendChild(event);
            
            input.value = '';
            showToast('Event created! Drag it to calendar', 'success');
        });
        
        function escapeHtml(text) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
        
        document.getElementById('new-event').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('add-new-event').click();
            }
        });
    });
</script>
@endsection