import { Calendar } from '@fullcalendar/core';

import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

import frLocale from '@fullcalendar/core/locales/fr';

import '../styles/full_calendar.css';

const calendarEl = document.getElementById('calendar');

const calendar = new Calendar(calendarEl, {
    events: calendarEl.getAttribute('data-url'),
    plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
    },
    navLinks: true,
    dayMaxEvents: true,
    eventStartEditable: false,
    locales: [frLocale],
    locale: window.locale ? window.locale : 'fr',
});

calendar.render();
