<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client booking</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
</head>
<body>

    <div id="calendar"></div>
    <script>

        const onEventsLoaded = data => data.map(event => ({
            title: event.client_name,
            start: event.start,
            end: event.end
        }))

        const eventsLoader = async ({startStr, endStr}, onEventsLoaded) => {
            const searchParams = new URLSearchParams({from: startStr, to: endStr})
            const response = await fetch(`/api/appointments?${searchParams.toString()}`)
            const data = await response.json()
            console.log(data)
            return data.map(event => ({
                title: event.client_name,
                start: event.start,
                end: event.end
            }))
        }

        document.addEventListener('DOMContentLoaded', function() {
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today timeGridWeek,timeGridDay'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '20:00:00',
            locale: 'hu',
            selectable: true,
            select: (data) => { console.log(data) },
            events: eventsLoader
          });
          calendar.render();
        });

      </script>
</body>
</html>
