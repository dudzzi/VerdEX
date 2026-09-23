/* =========================================================
   VERDEX CALENDAR
   ========================================================= */


let currentDate = new Date();

let events = [

    {
        id: 1,
        title: "Soil Aeration",
        date: "2026-09-23",
        start: "13:00",
        end: "14:00",
        type: "plant",
        description:
            "Loosen soil around the plants carefully without damaging the roots."
    },

    {
        id: 2,
        title: "Drip Line Inspection",
        date: "2026-09-24",
        start: "14:00",
        end: "15:00",
        type: "irrigation",
        description:
            "Check drip irrigation lines for leaks and blockages."
    },

    {
        id: 3,
        title: "Nutrient Mixing",
        date: "2026-09-25",
        start: "17:00",
        end: "18:00",
        type: "fertilizer",
        description:
            "Review and prepare the fertilizer nutrient mixture."
    },

    {
        id: 4,
        title: "Climate Sensor Calibration",
        date: "2026-09-26",
        start: "15:00",
        end: "15:30",
        type: "inspection",
        description:
            "Validate temperature and humidity sensor accuracy."
    }

];


/* =========================================================
   INITIALIZE
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        renderCalendar();

        renderUpcomingTasks();

        setupEventForm();

    }
);


/* =========================================================
   RENDER CALENDAR
   ========================================================= */

function renderCalendar() {

    const year =
        currentDate.getFullYear();

    const month =
        currentDate.getMonth();


    const monthTitle =
        document.getElementById("monthTitle");


    const calendarDays =
        document.getElementById("calendarDays");


    const monthName =
        currentDate.toLocaleString(
            "en-US",
            {
                month: "long"
            }
        );


    monthTitle.textContent =
        `${monthName} ${year}`;


    calendarDays.innerHTML = "";


    /*
     * First day of month
     */

    const firstDay =
        new Date(
            year,
            month,
            1
        ).getDay();


    /*
     * Number of days in month
     */

    const daysInMonth =
        new Date(
            year,
            month + 1,
            0
        ).getDate();


    /*
     * Previous month's days
     */

    const previousMonthDays =
        new Date(
            year,
            month,
            0
        ).getDate();


    /*
     * Total calendar cells
     */

    const totalCells =
        Math.ceil(
            (firstDay + daysInMonth) / 7
        ) * 7;


    const today =
        new Date();


    for (
        let i = 0;
        i < totalCells;
        i++
    ) {

        const dayElement =
            document.createElement("div");


        dayElement.className =
            "calendar-day";


        let dayNumber;

        let dateObject;


        /*
         * Previous month
         */

        if (i < firstDay) {

            dayNumber =
                previousMonthDays -
                firstDay +
                i +
                1;


            dateObject =
                new Date(
                    year,
                    month - 1,
                    dayNumber
                );


            dayElement.classList.add(
                "other-month"
            );

        }


        /*
         * Current month
         */

        else if (
            i <
            firstDay +
            daysInMonth
        ) {

            dayNumber =
                i -
                firstDay +
                1;


            dateObject =
                new Date(
                    year,
                    month,
                    dayNumber
                );

        }


        /*
         * Next month
         */

        else {

            dayNumber =
                i -
                firstDay -
                daysInMonth +
                1;


            dateObject =
                new Date(
                    year,
                    month + 1,
                    dayNumber
                );


            dayElement.classList.add(
                "other-month"
            );

        }


        const dateString =
            formatDateKey(dateObject);


        /*
         * Check today
         */

        if (
            dateObject.getFullYear() ===
                today.getFullYear() &&

            dateObject.getMonth() ===
                today.getMonth() &&

            dateObject.getDate() ===
                today.getDate()
        ) {

            dayElement.classList.add(
                "today"
            );

        }


        dayElement.innerHTML = `

            <div class="day-number">

                ${dayNumber}

            </div>

        `;


        /*
         * Events for this day
         */

        const dayEvents =
            events.filter(
                event =>
                    event.date === dateString
            );


        dayEvents.forEach(
            event => {

                const eventElement =
                    document.createElement("div");


                eventElement.className =
                    `calendar-event ${event.type}`;


                eventElement.textContent =
                    event.title;


                eventElement.onclick =
                    function (clickEvent) {

                        clickEvent.stopPropagation();

                        showTaskDetails(
                            event.id
                        );

                    };


                dayElement.appendChild(
                    eventElement
                );

            }
        );


        /*
         * Clicking empty day
         */

        dayElement.onclick =
            function () {

                if (
                    dayElement.classList.contains(
                        "other-month"
                    )
                ) {
                    return;
                }


                document.getElementById(
                    "eventDate"
                ).value =
                    dateString;


                openEventModal();

            };


        calendarDays.appendChild(
            dayElement
        );

    }

}


/* =========================================================
   MONTH NAVIGATION
   ========================================================= */

function previousMonth() {

    currentDate.setMonth(
        currentDate.getMonth() - 1
    );

    renderCalendar();

}


function nextMonth() {

    currentDate.setMonth(
        currentDate.getMonth() + 1
    );

    renderCalendar();

}


function goToToday() {

    currentDate =
        new Date();

    renderCalendar();

}


/* =========================================================
   DATE FORMAT
   ========================================================= */

function formatDateKey(date) {

    const year =
        date.getFullYear();


    const month =
        String(
            date.getMonth() + 1
        ).padStart(2, "0");


    const day =
        String(
            date.getDate()
        ).padStart(2, "0");


    return `${year}-${month}-${day}`;

}


function formatReadableDate(dateString) {

    const date =
        new Date(
            `${dateString}T00:00:00`
        );


    return date.toLocaleDateString(
        "en-US",
        {
            month: "short",
            day: "numeric",
            year: "numeric"
        }
    );

}


/* =========================================================
   UPCOMING TASKS
   ========================================================= */

function renderUpcomingTasks() {

    const container =
        document.getElementById(
            "upcomingTasks"
        );


    const today =
        formatDateKey(
            new Date()
        );


    const upcoming =
        events

            .filter(
                event =>
                    event.date >= today
            )

            .sort(
                (a, b) => {

                    const dateA =
                        `${a.date} ${a.start}`;

                    const dateB =
                        `${b.date} ${b.start}`;

                    return dateA.localeCompare(
                        dateB
                    );

                }
            )

            .slice(0, 5);


    if (upcoming.length === 0) {

        container.innerHTML = `

            <div class="no-tasks">

                No upcoming tasks.

            </div>

        `;

        return;

    }


    container.innerHTML =
        upcoming.map(
            event => `

                <div
                    class="upcoming-task"
                    onclick="showTaskDetails(${event.id})">

                    <div class="upcoming-date">

                        ${formatReadableDate(
                            event.date
                        )}

                    </div>


                    <div class="upcoming-task-title">

                        ${escapeHTML(
                            event.title
                        )}

                    </div>


                    <div class="upcoming-task-time">

                        ${formatTime(
                            event.start
                        )}

                        ${
                            event.end
                                ? ` - ${formatTime(event.end)}`
                                : ""
                        }

                    </div>


                    <span class="upcoming-type">

                        ${formatType(
                            event.type
                        )}

                    </span>

                </div>

            `
        )
        .join("");

}


/* =========================================================
   ADD EVENT
   ========================================================= */

function setupEventForm() {

    const form =
        document.getElementById(
            "eventForm"
        );


    form.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            const newEvent = {

                id:
                    Date.now(),

                title:
                    document.getElementById(
                        "eventTitle"
                    ).value.trim(),

                date:
                    document.getElementById(
                        "eventDate"
                    ).value,

                start:
                    document.getElementById(
                        "eventStart"
                    ).value,

                end:
                    document.getElementById(
                        "eventEnd"
                    ).value,

                type:
                    document.getElementById(
                        "eventType"
                    ).value,

                description:
                    document.getElementById(
                        "eventDescription"
                    ).value.trim()

            };


            events.push(
                newEvent
            );


            renderCalendar();

            renderUpcomingTasks();

            closeEventModal();

            form.reset();

        }
    );

}


/* =========================================================
   MODAL
   ========================================================= */

function openEventModal() {

    document
        .getElementById("eventModal")
        .classList.add("show");


    /*
     * Automatically set today's date
     * if no date was selected.
     */

    const dateInput =
        document.getElementById(
            "eventDate"
        );


    if (!dateInput.value) {

        dateInput.value =
            formatDateKey(
                new Date()
            );

    }

}


function closeEventModal() {

    document
        .getElementById("eventModal")
        .classList.remove("show");

}


/* =========================================================
   TASK DETAILS
   ========================================================= */

function showTaskDetails(id) {

    const event =
        events.find(
            item =>
                Number(item.id) ===
                Number(id)
        );


    if (!event) {
        return;
    }


    document.getElementById(
        "taskDetailsTitle"
    ).textContent =
        event.title;


    document.getElementById(
        "taskDetailsContent"
    ).innerHTML = `

        <span class="task-detail-type">

            ${formatType(
                event.type
            )}

        </span>


        <div class="task-detail-row">

            <strong>
                Date
            </strong>

            <span>
                ${formatReadableDate(
                    event.date
                )}
            </span>

        </div>


        <div class="task-detail-row">

            <strong>
                Time
            </strong>

            <span>

                ${formatTime(
                    event.start
                )}

                ${
                    event.end
                        ? ` - ${formatTime(event.end)}`
                        : ""
                }

            </span>

        </div>


        <div class="task-detail-row">

            <strong>
                Description
            </strong>

            <span>

                ${
                    escapeHTML(
                        event.description ||
                        "No description provided."
                    )
                }

            </span>

        </div>

    `;


    document
        .getElementById(
            "taskDetailsModal"
        )
        .classList.add("show");

}


function closeTaskDetails() {

    document
        .getElementById(
            "taskDetailsModal"
        )
        .classList.remove("show");

}


/* =========================================================
   HELPERS
   ========================================================= */

function formatTime(time) {

    if (!time) {
        return "";
    }


    const [hours, minutes] =
        time.split(":");


    const date =
        new Date();


    date.setHours(
        Number(hours)
    );


    date.setMinutes(
        Number(minutes)
    );


    return date.toLocaleTimeString(
        "en-US",
        {
            hour: "numeric",
            minute: "2-digit"
        }
    );

}


function formatType(type) {

    const types = {

        plant:
            "Plant Care",

        irrigation:
            "Irrigation",

        fertilizer:
            "Fertilizer",

        inspection:
            "Inspection",

        maintenance:
            "Maintenance",

        other:
            "Other"

    };


    return types[type] || "Other";

}


function escapeHTML(value) {

    if (value === null ||
        value === undefined) {

        return "";

    }


    return String(value)

        .replace(
            /&/g,
            "&amp;"
        )

        .replace(
            /</g,
            "&lt;"
        )

        .replace(
            />/g,
            "&gt;"
        )

        .replace(
            /"/g,
            "&quot;"
        )

        .replace(
            /'/g,
            "&#039;"
        );

}