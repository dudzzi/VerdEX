const calendar = document.getElementById("calendar");
const monthYear = document.getElementById("monthYear");

const dateModal = document.getElementById("dateModal");
const addModal = document.getElementById("addModal");

const calendarForm = document.getElementById("calendarForm");

const eventType = document.getElementById("eventType");
const eventTitle = document.getElementById("eventTitle");
const eventDate = document.getElementById("eventDate");
const eventTime = document.getElementById("eventTime");
const eventAmount = document.getElementById("eventAmount");
const eventDescription = document.getElementById("eventDescription");

const amountGroup = document.getElementById("amountGroup");
const notificationOption = document.getElementById("notificationOption");

const upcomingReminders = document.getElementById("upcomingReminders");

let currentDate = new Date();
let selectedDate = null;
let records = JSON.parse(localStorage.getItem("verdexCalendarRecords")) || [];


document.addEventListener("DOMContentLoaded", function(){
    renderCalendar();
    renderReminders();
    setupForm();
    checkReminders();
});


function renderCalendar(animation = ""){
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    monthYear.textContent = currentDate.toLocaleDateString("en-US", {
        month: "long",
        year: "numeric"
    });

    calendar.innerHTML = "";

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPreviousMonth = new Date(year, month, 0).getDate();

    const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;

    for(let i = 0; i < totalCells; i++){

        const dayCell = document.createElement("div");
        dayCell.className = "calendar-day";

        let dayNumber;
        let cellDate;

        if(i < firstDay){

            dayNumber = daysInPreviousMonth - firstDay + i + 1;

            cellDate = new Date(
                year,
                month - 1,
                dayNumber
            );

            dayCell.classList.add("other-month");

        }else{

            dayNumber = i - firstDay + 1;

            cellDate = new Date(
                year,
                month,
                dayNumber
            );

            if(dayNumber > daysInMonth){

                dayCell.classList.add("other-month");

            }

        }

        const dateKey = formatDateKey(cellDate);

        const numberWrapper = document.createElement("div");
        numberWrapper.className = "day-number";

        const number = document.createElement("span");
        number.textContent = dayNumber;

        numberWrapper.appendChild(number);
        dayCell.appendChild(numberWrapper);

        if(isToday(cellDate)){
            dayCell.classList.add("today");
        }

        const eventList = document.createElement("div");
        eventList.className = "event-list";

        const dayRecords = records.filter(
            record => record.date === dateKey
        );

        dayRecords.slice(0, 3).forEach(record => {

            const event = document.createElement("div");

            event.className = "event " + record.type;

            let text = record.title;

            if(record.type === "sales" && record.amount){
                text = "₱ " + Number(record.amount).toLocaleString();
            }

            if(record.type === "stock" && record.amount){
                const prefix = record.stockDirection === "loss" ? "-" : "+";
                text = prefix + record.amount + " Stock";
            }

            event.textContent = text;

            eventList.appendChild(event);

        });


        if(dayRecords.length > 3){

            const more = document.createElement("div");

            more.className = "more-events";

            more.textContent = "+" + (dayRecords.length - 3) + " more";

            eventList.appendChild(more);

        }


        dayCell.appendChild(eventList);

        dayCell.addEventListener("click", function(){
            openDateModal(dateKey);
        });

        calendar.appendChild(dayCell);
    }

    if(animation){

        calendar.classList.remove("page-next", "page-prev");

        void calendar.offsetWidth;

        calendar.classList.add(animation);
    }
}


function previousMonth(){

    currentDate.setMonth(currentDate.getMonth() - 1);

    renderCalendar("page-prev");
}


function nextMonth(){

    currentDate.setMonth(currentDate.getMonth() + 1);

    renderCalendar("page-next");
}


function goToday(){

    currentDate = new Date();

    renderCalendar("page-prev");
}


function isToday(date){

    const today = new Date();

    return (
        date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear()
    );
}


function formatDateKey(date){

    const year = date.getFullYear();

    const month = String(
        date.getMonth() + 1
    ).padStart(2, "0");

    const day = String(
        date.getDate()
    ).padStart(2, "0");

    return `${year}-${month}-${day}`;
}


function openDateModal(dateKey){

    selectedDate = dateKey;

    const date = parseDate(dateKey);

    document.getElementById("selectedDateNumber").textContent =
        date.getDate();

    document.getElementById("selectedDateTitle").textContent =
        date.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            year: "numeric"
        });

    document.getElementById("selectedDateSubtitle").textContent =
        date.toLocaleDateString("en-US", {
            weekday: "long"
        });


    renderDateEvents();

    dateModal.classList.add("show");
}


function renderDateEvents(){

    const container = document.getElementById("dateEvents");

    container.innerHTML = "";

    const dayRecords = records.filter(
        record => record.date === selectedDate
    );


    if(dayRecords.length === 0){

        container.innerHTML = `
            <div class="no-events">
                No records for this date yet.
            </div>
        `;

        return;
    }


    dayRecords.forEach(record => {

        const card = document.createElement("div");

        card.className = "date-event";

        const top = document.createElement("div");

        top.className = "date-event-top";


        const left = document.createElement("div");


        const title = document.createElement("div");

        title.className = "date-event-title";

        title.textContent = record.title;


        const type = document.createElement("div");

        type.className = "date-event-type";

        type.textContent = getTypeName(record.type);


        left.appendChild(title);
        left.appendChild(type);


        const deleteButton = document.createElement("button");

        deleteButton.className = "delete-event";

        deleteButton.textContent = "×";

        deleteButton.onclick = function(){
            deleteRecord(record.id);
        };


        top.appendChild(left);
        top.appendChild(deleteButton);

        card.appendChild(top);


        if(record.time){

            const time = document.createElement("div");

            time.className = "date-event-time";

            time.textContent = formatTime(record.time);

            card.appendChild(time);
        }


        if(record.amount){

            const amount = document.createElement("div");

            amount.className = "date-event-amount";

            if(record.type === "sales"){

                amount.textContent =
                    "Sales: ₱" +
                    Number(record.amount).toLocaleString();

            }else if(record.type === "stock"){

                const prefix =
                    record.stockDirection === "loss"
                        ? "-"
                        : "+";

                amount.textContent =
                    "Stock: " +
                    prefix +
                    record.amount;

            }else{

                amount.textContent =
                    "Amount: " + record.amount;
            }

            card.appendChild(amount);
        }


        if(record.description){

            const description = document.createElement("div");

            description.className = "date-event-description";

            description.textContent = record.description;

            card.appendChild(description);
        }


        container.appendChild(card);
    });
}


function getTypeName(type){

    const names = {
        activity: "Farm Activity",
        reminder: "Reminder",
        note: "Note",
        sales: "Sales",
        stock: "Stock Update"
    };

    return names[type] || "Calendar Record";
}


function openAddModal(type = "activity"){

    closeDateModal();

    addModal.classList.add("show");

    eventType.value = type;

    if(selectedDate){

        eventDate.value = selectedDate;

    }else{

        eventDate.value = formatDateKey(new Date());
    }

    updateFormFields();

    setTimeout(function(){
        eventTitle.focus();
    }, 100);
}


function closeAddModal(){

    addModal.classList.remove("show");

    calendarForm.reset();

    updateFormFields();
}


function closeDateModal(){

    dateModal.classList.remove("show");
}


function setupForm(){

    calendarForm.addEventListener("submit", function(event){

        event.preventDefault();

        saveRecord();
    });


    eventType.addEventListener("change", updateFormFields);
}


function updateFormFields(){

    const type = eventType.value;

    if(type === "sales" || type === "stock"){

        amountGroup.style.display = "block";

    }else{

        amountGroup.style.display = "none";

        eventAmount.value = "";
    }


    if(type === "reminder"){

        notificationOption.style.display = "block";

    }else{

        notificationOption.style.display = "block";
    }
}


function saveRecord(){

    const type = eventType.value;

    const title = eventTitle.value.trim();

    const date = eventDate.value;

    const time = eventTime.value;

    const amount = eventAmount.value;

    const description = eventDescription.value.trim();

    const enableNotification =
        document.getElementById("enableNotification").checked;


    if(!title || !date){

        alert("Please enter a title and date.");

        return;
    }


    let stockDirection = null;


    if(type === "stock"){

        const direction = prompt(
            "Type GAIN for stock gained or LOSS for stock lost:"
        );

        if(!direction){

            return;
        }

        if(direction.toLowerCase() === "loss"){

            stockDirection = "loss";

        }else{

            stockDirection = "gain";
        }
    }


    const record = {

        id: Date.now(),

        type: type,

        title: title,

        date: date,

        time: time,

        amount: amount,

        description: description,

        stockDirection: stockDirection,

        notification: enableNotification,

        createdAt: new Date().toISOString()
    };


    records.push(record);

    saveRecords();


    currentDate = parseDate(date);

    renderCalendar("page-next");

    renderReminders();


    closeAddModal();

    openDateModal(date);

    showToast(
        "Record Saved",
        title + " was added to your calendar."
    );
}


function saveRecords(){

    localStorage.setItem(
        "verdexCalendarRecords",
        JSON.stringify(records)
    );
}


function deleteRecord(id){

    const confirmed = confirm(
        "Delete this calendar record?"
    );

    if(!confirmed){

        return;
    }


    records = records.filter(
        record => record.id !== id
    );

    saveRecords();

    renderCalendar();

    renderReminders();

    renderDateEvents();
}


function renderReminders(){

    upcomingReminders.innerHTML = "";

    const todayKey = formatDateKey(new Date());


    const reminders = records
        .filter(record =>
            record.type === "reminder" &&
            record.notification === true &&
            record.date >= todayKey
        )
        .sort(function(a, b){

            const dateA =
                new Date(
                    a.date + "T" + (a.time || "00:00")
                );

            const dateB =
                new Date(
                    b.date + "T" + (b.time || "00:00")
                );

            return dateA - dateB;
        });


    if(reminders.length === 0){

        upcomingReminders.innerHTML = `
            <div class="empty-reminders">
                No upcoming reminders.
                Add a reminder to keep track of your farm tasks.
            </div>
        `;

        return;
    }


    reminders.slice(0, 6).forEach(reminder => {

        const card = document.createElement("div");

        card.className = "reminder-card";


        const info = document.createElement("div");

        info.className = "reminder-info";


        const title = document.createElement("h3");

        title.textContent = reminder.title;


        const date = document.createElement("p");

        date.className = "reminder-date";

        date.textContent =
            formatDisplayDate(reminder.date) +
            (reminder.time
                ? " • " + formatTime(reminder.time)
                : "");


        info.appendChild(title);
        info.appendChild(date);


        if(reminder.description){

            const description = document.createElement("p");

            description.textContent =
                reminder.description;

            info.appendChild(description);
        }


        const deleteButton = document.createElement("button");

        deleteButton.className = "delete-reminder";

        deleteButton.textContent = "×";

        deleteButton.onclick = function(){

            deleteRecord(reminder.id);
        };


        card.appendChild(info);

        card.appendChild(deleteButton);

        upcomingReminders.appendChild(card);
    });
}


function formatDisplayDate(dateString){

    const date = parseDate(dateString);

    return date.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric"
    });
}


function formatTime(timeString){

    const parts = timeString.split(":");

    let hour = Number(parts[0]);

    const minute = parts[1];

    const period = hour >= 12
        ? "PM"
        : "AM";

    hour = hour % 12;

    if(hour === 0){
        hour = 12;
    }

    return `${hour}:${minute} ${period}`;
}


function parseDate(dateString){

    const parts = dateString.split("-");

    return new Date(
        Number(parts[0]),
        Number(parts[1]) - 1,
        Number(parts[2])
    );
}


function checkReminders(){

    const now = new Date();

    records.forEach(record => {

        if(
            record.type !== "reminder" ||
            !record.notification ||
            !record.time
        ){
            return;
        }


        const reminderDate = new Date(
            record.date +
            "T" +
            record.time
        );


        const difference =
            now.getTime() -
            reminderDate.getTime();


        if(
            difference >= 0 &&
            difference < 60000
        ){

            showToast(
                "Farm Reminder",
                record.title
            );
        }

    });
}


function showToast(title, message){

    document.getElementById("toastTitle").textContent =
        title;

    document.getElementById("toastMessage").textContent =
        message;

    document
        .getElementById("notificationToast")
        .classList.add("show");


    setTimeout(function(){

        hideToast();

    }, 6000);
}


function hideToast(){

    document
        .getElementById("notificationToast")
        .classList.remove("show");
}


window.addEventListener("click", function(event){

    if(event.target === dateModal){

        closeDateModal();
    }

    if(event.target === addModal){

        closeAddModal();
    }
});


setInterval(checkReminders, 30000);