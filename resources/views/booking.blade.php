<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client booking</title>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body>

    <div id="calendar"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const onEventsLoaded = data => data.map(event => ({
                title: event.client_name,
                start: event.start,
                end: event.end
            }))

            const eventsLoader = async ({startStr, endStr}, onEventsLoaded) => {
                const searchParams = new URLSearchParams({from: startStr, to: endStr})
                const response = await fetch(`/api/appointments?${searchParams.toString()}`)
                return await response.json()
            }

            const addAppointment = async (selectedEvent) => {
                const clientName = window.prompt()
                const {start, end} = selectedEvent
                const notification = Toastify({duration: 5000, position: 'center'})
                try {
                    const response = await fetch('/api/appointments', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            clientName,
                            start,
                            end
                        })
                    })
                    const responseData = await response.json()
                    if (!response.ok) {
                        throw new Error(responseData.message ?? 'Ismeretlen hiba!');
                    }
                    notification.options.text = 'Sikeres hozzaadas'
                } catch (error) {
                    notification.options.text = error.message
                } finally {
                    await calendar.refetchEvents()
                    notification.showToast()
                }
            }
          var calendarEl = document.getElementById('calendar');
          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            timeZone: 'Europe/Budapest',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'today dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '21:00:00',
            locale: 'hu',
            firstDay: 1,
            selectable: true,
            select: addAppointment,
            events: eventsLoader
          });
          calendar.render();
        });

      </script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
