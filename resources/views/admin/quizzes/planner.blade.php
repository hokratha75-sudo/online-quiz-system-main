@extends('layouts.admin')

@section('content')
<div class="container-fluid mx-auto p-4 font-sans text-slate-700">
    <!-- Breadcrumb Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 px-2">
        <h1 class="text-2xl font-normal text-slate-800">Calendar</h1>
        <nav class="text-sm text-slate-500 mt-2 md:mt-0">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">Home</a>
            <span class="mx-2">/</span>
            <span>Calendar</span>
        </nav>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Left Sidebar -->
        <div class="col-span-12 lg:col-span-3 space-y-5">
            <!-- Draggable Events Card -->
            <div class="bg-white rounded shadow-sm border border-slate-200 w-full h-[71vh] flex flex-col">

    <!-- Header -->
    <div class="p-3 border-b border-slate-200 sticky top-0 bg-slate-50 z-10">
        <h3 class="text-md font-normal">Draggable Events</h3>
    </div>

    <!-- Scrollable Content -->
    <div class="p-3 flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar" id="external-events">
        <div class="fc-event bg-red-600 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-red-600">Final Exam</div>
        <div class="fc-event bg-orange-500 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-orange-500">Quiz Deadline</div>
        <div class="fc-event bg-yellow-500 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-yellow-500">Midterm Quiz</div>
        <div class="fc-event bg-blue-500 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-blue-500">Class Assessment</div>
        <div class="fc-event bg-emerald-500 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-emerald-500">Practice Test</div>
        <div class="fc-event bg-indigo-600 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-indigo-600">Result Release</div>
        <div class="fc-event bg-teal-500 text-white mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm" data-class="bg-teal-500">Student Review</div>
    </div>

    <!-- Footer -->
    <div class="p-3 border-t border-slate-200 bg-slate-50">
        <div class="flex items-center">
            <input type="checkbox" id="drop-remove"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
            <label for="drop-remove" class="ml-2 text-sm text-slate-600">
                remove after drop
            </label>
        </div>
    </div>

</div>
            <!-- Create Event Card -->
            <div class="bg-white rounded shadow-sm border border-slate-200 p-3 w-full h-[26%]">
                <div class=" border-b border-slate-200">
                    <h3 class="text-md font-normal">Create Event</h3>
                </div>
                <div class="py-3">
                    <div class="flex flex-wrap gap-2 mb-4" id="color-chooser">
                        <div class="w-8 h-8 rounded bg-blue-500 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" data-color="#3b82f6"></div>
                        <div class="w-8 h-8 rounded bg-yellow-500 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" data-color="#f59e0b"></div>
                        <div class="w-8 h-8 rounded bg-emerald-500 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" data-color="#10b981"></div>
                        <div class="w-8 h-8 rounded bg-red-500 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" data-color="#ef4444"></div>
                        <div class="w-8 h-8 rounded bg-slate-500 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" data-color="#64748b"></div>
                    </div>
                    <div class="flex w-full overflow-hidden h-full">
    
                        <input 
                            type="text"
                            id="new-event"
                            class="flex-1 px-2 py-2 text-sm outline-none rounded-l border border-gray-100"
                            placeholder="Event Title"
                        >

                        <button 
                            type="button"
                            id="add-new-event"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-500 hover:bg-blue-600 transition border-none rounded-r"
                        >
                            Add
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <!-- Main Calendar -->
        <div class="col-span-12 lg:col-span-9">
            <div class="bg-white rounded shadow-sm border border-slate-200 p-4">
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

<style>
    /* Custom Scrollbar for sleek aesthetic */
    .custom-scrollbar::-webkit-scrollbar { width: 7px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #4f46e5; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
    [x-cloak] { display: none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;

        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');

        // initialize the external events
        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText,
                    backgroundColor: window.getComputedStyle(eventEl).getPropertyValue('background-color'),
                    borderColor: window.getComputedStyle(eventEl).getPropertyValue('background-color'),
                    textColor: '#fff'
                };
            }
        });

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
            events: @json($events),
            eventDidMount: function(info) {
                if (info.event.allDay || (info.event.end && (info.event.end - info.event.start) > 86400000)) {
                    info.el.style.padding = '2px 6px';
                    info.el.style.borderRadius = '4px';
                    info.el.style.fontWeight = '600';
                }
                
                // Add tooltip or hint for deletion
                if (info.event.extendedProps.type === 'planner_event') {
                    info.el.title = "Click to delete this event";
                }
            },
            // Save when an external item is dropped
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
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify(eventData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        info.event.setProp('id', data.id);
                        info.event.setExtendedProp('type', 'planner_event');
                    }
                });
            },
            // Update when moved
            eventDrop: function(info) {
                if (info.event.extendedProps.type !== 'planner_event') {
                    info.revert();
                    return;
                }
                updateEvent(info.event);
            },
            // Update when resized
            eventResize: function(info) {
                updateEvent(info.event);
            },
            eventClick: function(info) {
                if (info.event.extendedProps.type === 'quiz') {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                } else if (info.event.extendedProps.type === 'planner_event') {
                    if (confirm("Do you want to delete this event?")) {
                        fetch("{{ url('/planner/destroy') }}/" + info.event.id, {
                            method: "DELETE",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.remove();
                            }
                        });
                    }
                }
            },
            drop: function(info) {
                if (document.getElementById('drop-remove').checked) {
                    info.draggedEl.remove();
                }
            }
        });

        function updateEvent(event) {
            var updateData = {
                id: event.id,
                start: event.startStr,
                end: event.endStr,
                allDay: event.allDay
            };

            fetch("{{ route('planner.update') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify(updateData)
            });
        }

        calendar.render();

        /* ADDING EVENTS */
        var currColor = '#3b82f6';
        var colorChooser = document.getElementById('color-chooser');
        
        colorChooser.addEventListener('click', function(e) {
            if (e.target.dataset.color) {
                currColor = e.target.dataset.color;
                var addButton = document.getElementById('add-new-event');
                addButton.style.backgroundColor = currColor;
                addButton.style.borderColor = currColor;
                
                // Add a small scale effect to the clicked color
                colorChooser.querySelectorAll('div').forEach(el => el.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-400'));
                e.target.classList.add('ring-2', 'ring-offset-2', 'ring-blue-400');
            }
        });

        document.getElementById('add-new-event').addEventListener('click', function(e) {
            e.preventDefault();
            var input = document.getElementById('new-event');
            var val = input.value.trim();
            if (val.length == 0) return;

            var event = document.createElement('div');
            event.style.backgroundColor = currColor;
            event.style.borderColor = currColor;
            event.style.color = '#fff';
            event.className = 'fc-event mb-2 p-2 rounded cursor-pointer text-sm font-semibold shadow-sm transition-transform hover:scale-[1.02]';
            event.innerText = val;
            
            // Insert before the checkbox container
            var container = document.getElementById('external-events');
            container.insertBefore(event, container.querySelector('.mt-4'));
            
            input.value = '';
        });
    });
</script>
@endsection
