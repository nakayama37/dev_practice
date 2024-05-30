import flatpickr from "flatpickr";
import { Japanese } from "flatpickr/dist/l10n/ja.js";

flatpickr("#event_date", {
  "locale": Japanese,
   minDate: "today",
   maxDate: new Date().fp_incr(180) // 180 days from now
});

const setting = {
    "locale": Japanese,
     minDate: "today",
     maxDate: new Date().fp_incr(180), // 180 days from now
     enableTime: true,
     noCalendar: true,
     dateFormat: "H:i",
     time_24hr: true,
     minTime: "6:00",
     maxTime: "22:00"
}

flatpickr("#start_at", setting);
flatpickr("#end_at", setting);