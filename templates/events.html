{% extends "base.html" %}
{% block content %}
<header>
<!-- Calendar Dependencies -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.min.css' rel='stylesheet' media='print' />
</header>

<body>
<!--============================= EVENTS =============================-->
<section class="events">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="event-title">Acara dan Kegiatan</h2>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mt-4" id="eventTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list-view" role="tab">List View</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="calendar-tab" data-toggle="tab" href="#calendar-view" role="tab">Calendar View</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="eventTabsContent">
            <!-- List View -->
            <div class="tab-pane fade show active" id="list-view" role="tabpanel">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div id="eventAccordion">
                            {% for event in events %}
                            <div class="event-panel mb-4">
                                <div class="row">
                                    <!-- Event Date -->
                                    <div class="col-md-2">
                                        <div class="event-date">
                                            <h4>{{ event[3].strftime('%d') }}</h4>
                                            <span>{{ event[3].strftime('%b %Y') }}</span>
                                        </div>
                                        <span class="event-time">{{ event[3].strftime('%I:%M %p') }}</span>
                                    </div>
                                    
                                    <!-- Event Content -->
                                    <div class="col-md-10">
                                        <div class="event-heading">
                                            <h3>{{ event[1] }}</h3>
                                            <p>{{ event[2] }}</p>
                                        </div>
                                        
                                        <!-- Collapsible Content -->
                                        <div class="collapse" id="collapse{{ event[0] }}">
                                            <div class="event-details">
                                                <div class="event-highlights">
                                                    <h5 class="mb-4">Event Highlights</h5>
                                                    <div class="row">
                                                        <!-- Event Images -->
                                                        <div class="col-md-4">
                                                            <img src="{{ url_for('static', filename='images/events/' + event[4]) if event[4] else 'images/events/default-event.jpg' }}" 
                                                                 class="img-fluid mb-3" 
                                                                 alt="Event image 1"
                                                                 onerror="this.src='{{ url_for('static', filename='images/events/default-event.jpg') }}'">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img src="{{ url_for('static', filename='images/events/' + event[6]) if event[6] else 'images/events/default-event.jpg' }}" 
                                                                 class="img-fluid mb-3" 
                                                                 alt="Event image 2"
                                                                 onerror="this.src='{{ url_for('static', filename='images/events/default-event.jpg') }}'">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <img src="{{ url_for('static', filename='images/events/' + event[7]) if event[7] else 'images/events/default-event.jpg' }}" 
                                                                 class="img-fluid mb-3" 
                                                                 alt="Event image 3"
                                                                 onerror="this.src='{{ url_for('static', filename='images/events/default-event.jpg') }}'">
                                                        </div>
                                                    </div>

                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="event-description">
                                                                {{ event[5]|safe }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Toggle Button -->
                                        <div class="event-toggle-wrap">
                                            <a href="#collapse{{ event[0] }}"
                                               class="event-toggle collapsed"
                                               data-toggle="collapse" 
                                               data-target="#collapse{{ event[0] }}" 
                                               aria-expanded="false">
                                                Show Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {% endfor %}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="tab-pane fade" id="calendar-view" role="tabpanel">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Calendar Scripts -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>

<script>
$(document).ready(function() {
    // Toggle button text
    $('.event-toggle').click(function() {
        $(this).text(function(i, text) {
            return text === "Show Details" ? "Hide Details" : "Show Details";
        });
    });

    // Initialize calendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: [
            {% for event in events %}
            {
                id: {{ event[0] }},
                title: '{{ event[1] }}',
                start: '{{ event[3].strftime("%Y-%m-%d") }}',
                description: '{{ event[2] }}',
                url: '#collapse{{ event[0] }}'  // Link to collapse element
            }{% if not loop.last %},{% endif %}
            {% endfor %}
        ],
        eventClick: function(calEvent, jsEvent, view) {
            // Switch to list view tab
            $('#list-tab').tab('show');
            
            // Wait for tab transition
            setTimeout(function() {
                // Find and open the corresponding collapse
                $(calEvent.url).collapse('show');
                
                // Scroll to the event
                $('html, body').animate({
                    scrollTop: $(calEvent.url).offset().top - 100
                }, 500);
            }, 150);  // Small delay to ensure tab switch completes
            
            return false; // Prevent URL navigation
        },
        eventRender: function(event, element) {
         element.attr('title', event.description);
        }
    });
});
</script>

<style>
#calendar {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.fc-event {
    cursor: pointer;
    background-color: #3366CC;
    border: 1px solid #2952a3;
}

.fc-today {
    background: #f7f7f7 !important;
}

.event-description {
    white-space: pre-line;
    line-height: 1.6;
}
</style>
</body>

{% endblock %}