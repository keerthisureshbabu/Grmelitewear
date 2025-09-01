(function () {
    "use strict";
    //_____Calendar Events Intialization
  
    // sample calendar events data
    var curYear = moment().format('YYYY');
    var curMonth = moment().format('MM');
    // Calendar Event Source
    var sptCalendarEvents = {
      id: 1,
      events: [{
        id: '1',
        start: curYear + '-' + curMonth + '-15',
        end: curYear + '-' + curMonth + '-17',
        title: 'Annual Tech Symposium',
        className: "bg-primary-transparent",
        description: 'An exciting symposium featuring the latest in technology and innovation.'
      }, {
        id: '2',
        start: curYear + '-' + curMonth + '-03',
        end: curYear + '-' + curMonth + '-04',
        title: 'Marketing Strategies Workshop',
        className: "bg-success-transparent",
        description: 'A workshop focused on the latest marketing strategies and trends.'
      }, {
        id: '3',
        start: curYear + '-' + curMonth + '-20',
        end: curYear + '-' + curMonth + '-22',
        title: 'Annual Company Retreat',
        className: "bg-info-transparent",
        description: 'A retreat for team-building and strategic planning.'
      }, {
        id: '4',
        start: curYear + '-' + curMonth + '-10',
        end: curYear + '-' + curMonth + '-10',
        title: 'Client Presentation Day',
        className: "bg-primary-transparent",
        description: 'A day dedicated to presenting new proposals and ideas to clients.'
      }, {
        id: '5',
        start: curYear + '-' + curMonth + '-09',
        end: curYear + '-' + curMonth + '-09',
        title: 'Summer Music Festival',
        className: "bg-secondary-transparent",
        description: 'A vibrant music festival celebrating summer with various artists.'
      }, {
        id: '6',
        start: curYear + '-' + curMonth + '-22',
        end: curYear + '-' + curMonth + '-24',
        title: 'Product Launch Event',
        className: "bg-info-transparent",
        description: 'Launch event for introducing the latest product line.'
      }]
    };

    // Birthday Events Source
    var sptBirthdayEvents = {
      id: 2,
      className: "bg-secondary-transparent",
      textColor: '#fff',
      events: [{
        id: '7',
        start: curYear + '-' + curMonth + '-12',
        end: curYear + '-' + curMonth + '-12',
        title: 'Alex’s Birthday Bash',
        description: 'Celebrating Alex’s special day with friends and family.'
      }, {
        id: '8',
        start: curYear + '-' + curMonth + '-20',
        end: curYear + '-' + curMonth + '-20',
        title: 'Sophia’s Surprise Party',
        description: 'A surprise party to celebrate Sophia’s birthday.'
      }, {
        id: '9',
        start: curYear + '-' + curMonth + '-28',
        end: curYear + '-' + curMonth + '-28',
        title: 'Jake’s Birthday Celebration',
        description: 'A fun celebration for Jake’s birthday.'
      }, {
        id: '10',
        start: curYear + '-' + 11 + '-05',
        end: curYear + '-' + 11 + '-05',
        title: 'Olivia’s Birthday Gala',
        description: 'A grand gala to celebrate Olivia’s birthday.'
      }]
    };

    // Holiday Events Source
    var sptHolidayEvents = {
      id: 3,
      className: "bg-success-transparent",
      textColor: '#fff',
      events: [{
        id: '10',
        start: curYear + '-' + curMonth + '-12',
        end: curYear + '-' + curMonth + '-14',
        title: 'Winter Wonderland Festival'
      }, {
        id: '11',
        start: curYear + '-' + curMonth + '-16',
        end: curYear + '-' + curMonth + '-16',
        title: 'Cultural Heritage Day'
      }, {
        id: '12',
        start: curYear + '-' + curMonth + '-25',
        end: curYear + '-' + curMonth + '-26',
        title: 'Holiday'
      }]
    };

    // Other Events Source
    var sptOtherEvents = {
      id: 4,
      className: "bg-teal-transparent",
      textColor: '#fff',
      events: [{
        id: '13',
        start: curYear + '-' + curMonth + '-02',
        end: curYear + '-' + curMonth + '-04',
        title: 'Personal Development Days'
      }, {
        id: '14',
        start: curYear + '-' + curMonth + '-18',
        end: curYear + '-' + curMonth + '-20',
        title: 'Extended Weekend Break'
      }]
    };
  
  
    //________ FullCalendar
    var containerEl = document.getElementById('external-events');
    new FullCalendar.Draggable(containerEl, {
      itemSelector: '.fc-event',
      eventData: function (eventEl) {
        return {
          title: eventEl.innerText.trim(),
          title: eventEl.innerText,
          className: eventEl.getAttribute('data-class')
        }
      }
    });

    var calendarEl = document.getElementById('calendar2');
    var createEventModal = new bootstrap.Modal(document.getElementById('createEventModal'));
    var deleteEventModal = new bootstrap.Modal(document.getElementById('deleteEventModal'));

    // Initialize Choices.js for the select element
    var eventColorSelect = document.getElementById('eventColor');
    var choices = new Choices(eventColorSelect);

    // Initialize Flatpickr for date range selection with format dd/mm/yyyy
    var flatpickrInstance = flatpickr("#eventDateRange", {
      mode: "range",
      minDate: "today",
      dateFormat: "d/m/Y",
    });
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      initialView: 'dayGridMonth',
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      selectable: true,
      selectMirror: true,
      droppable: true, // this allows things to be dropped onto the calendar
    
      select: function(arg) {
        // Show the create event modal
        createEventModal.show();
    
        // Clear previous input values
        document.getElementById('eventTitle').value = '';
        choices.removeActiveItems();
        choices.setChoiceByValue('bg-primary-transparent'); // Set default value
        document.getElementById('eventDateRange').value = '';
        flatpickrInstance.clear(); // Clear the Flatpickr instance
    
        // Set up a single click event listener for the Save button
        document.getElementById('saveEventButton').onclick = function() {
          var title = document.getElementById('eventTitle').value.trim();
          var className = choices.getValue(true); // Get selected class name
          var dateRange = document.getElementById('eventDateRange').value.split(' to '); // Split range into start and end dates
          
          // Clear previous validation state
          var form = document.getElementById('eventForm');
          form.classList.remove('was-validated');
          document.getElementById('eventTitle').classList.remove('is-invalid');
    
          // Validate event title only
          if (!title) {
            document.getElementById('eventTitle').classList.add('is-invalid');
          } else {
            var startDate, endDate;
    
            // Handle single date or range
            if (dateRange.length === 1) {
              // Single date case
              startDate = dateRange[0].split('/').reverse().join('-');
              endDate = startDate; // Single date, so endDate is the same as startDate
            } else if (dateRange.length === 2) {
              // Date range case
              startDate = dateRange[0].split('/').reverse().join('-');
              endDate = dateRange[1].split('/').reverse().join('-');
            }
    
            // Adjust the end date to include the entire end day
            if (endDate) {
              var endDateAdjusted = new Date(endDate);
              endDateAdjusted.setDate(endDateAdjusted.getDate() + 1);
              endDate = endDateAdjusted.toISOString().split('T')[0];
            }
    
            // Add event to the calendar
            calendar.addEvent({
              title: title,
              start: startDate,
              end: endDate, // Use adjusted end date to include the entire end day if applicable
              allDay: true, // Assuming full-day events; adjust as needed
              classNames: [className] // Apply the selected class name
            });
    
            // Hide the modal
            createEventModal.hide();
          }
        };
    
        calendar.unselect();
      },
    
      eventClick: function(arg) {
        // Show the delete event modal
        deleteEventModal.show();
    
        // Set up a single click event listener for the Delete button
        document.getElementById('deleteEventButton').onclick = function() {
          arg.event.remove();
          deleteEventModal.hide();
        };
      },
    
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      eventSources: [sptCalendarEvents, sptBirthdayEvents, sptHolidayEvents, sptOtherEvents],
    });
    
    calendar.render();

    // for activity scroll
    var myElement1 = document.getElementById('full-calendar-activity');
    new SimpleBar(myElement1, { autoHide: true });
  
  
  })();
  