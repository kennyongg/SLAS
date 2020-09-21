var self = this;    
var popup = document.getElementById("editScheduleModal");
var btn = document.getElementById("editScheduleButton");
var span = document.getElementsByClassName("popup-close")[0];
var lecturerEmail = "";
var daySlots = [];
var slotsGlobal = {
    'Monday': [],
    'Tuesday': [],
    'Wednesday': [],
    'Thursday': [],
    'Friday': []
};
var deleteArr = [];

function loadUserSchedule() {       
    // Current lecturer 
    let lecture = new XMLHttpRequest();
    lecture.open("POST","../php/profile.php",true);
    lecture.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    lecture.onload = function(){
        let res = JSON.parse(this.response);
        document.getElementById("lecturerName").innerHTML = res.name + "'s schedule";
        lecturerEmail = res.email;
        getSchedule(lecturerEmail);
    }
    lecture.send("getUserProfile"); 
}

function getSchedule(email) {
    let schedule = new XMLHttpRequest();
    schedule.open("POST","../php/lecturerSchedule.php",true);
    schedule.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    schedule.onload = function() {
        let res = JSON.parse(this.response);
        let displayMonday = document.getElementById("monday");
        let displayTuesday = document.getElementById("tuesday");
        let displayWednesday = document.getElementById("wednesday");
        let displayThursday = document.getElementById("thursday");
        let displayFriday = document.getElementById("friday");

        for (var i = 0; i < res.length; i++) {
            if (res[i]["day"] === "Monday") {
                slotsGlobal.Monday.push(res[i]["start_time"]);
                displayMonday.innerHTML += "<p>" + res[i]["start_time"] + "-" + res[i]["end_time"] + "</p>";
            } else if (res[i]["day"] === "Tuesday") {
                slotsGlobal.Tuesday.push(res[i]["start_time"]);
                displayTuesday.innerHTML += res[i]["start_time"] + "-" + res[i]["end_time"] + "</p>";
            } else if (res[i]["day"] === "Wednesday") {
                slotsGlobal.Wednesday.push(res[i]["start_time"]);
                displayWednesday.innerHTML += "<p>" + res[i]["start_time"] + "-" + res[i]["end_time"] + "</p>";
            } else if (res[i]["day"] === "Thursday") {
                slotsGlobal.Thursday.push(res[i]["start_time"]);
                displayThursday.innerHTML += "<p>" + res[i]["start_time"] + "-" + res[i]["end_time"] + "</p>>";
            } else if (res[i]["day"] === "Friday") {
                slotsGlobal.Friday.push(res[i]["start_time"]);
                displayFriday.innerHTML += "<p>" + res[i]["start_time"] + "-" + res[i]["end_time"] + "</p>";
            }
        }
    }
    schedule.send("getSchedule="+email);
}

function submitCreate(){
    console.log("submit")
    let messageBox = document.getElementById("createSlotMessage");
    messageBox.innerHTML="";
    let day = document.getElementById("createSelectedDay").value;
    let startTime = $('#createStartTime').timepicker('getTime', new Date);
    let endTime = $('#createEndTime').timepicker('getTime', new Date);
    if (startTime == null || endTime == null) {
        event.preventDefault();
        messageBox.innerHTML = "<p class='text-warning error_message'><strong>Please complete the fields!</strong></p>"
        return;
    } else {
        let proceed = true;
        let startTimeHour = startTime.getHours();
        let startTimeMinute = startTime.getMinutes();
        let startTimeSecond = startTime.getSeconds();
        
        let endTimeHour = endTime.getHours();
        let endTimeMinute = endTime.getMinutes();
        let endTimeSecond = endTime.getSeconds();
        
        if (startTimeSecond < 10) {
            startTimeSecond = '0'+startTimeSecond;
        }
        if (startTimeMinute < 10) {
            startTimeMinute = '0'+startTimeMinute;
        }
        if (startTimeHour < 10) {
            startTimeHour = '0'+startTimeHour;
        }
        
        let start_Time = startTimeHour+":"+startTimeMinute+":"+startTimeSecond;
        let end_Time = endTimeHour+":"+endTimeMinute+":"+endTimeSecond;
        
        slotsGlobal[day].forEach(element => {
            if (element == start_Time) {
                event.preventDefault();
                messageBox.innerHTML = "<p class='text-warning error_message'><strong>A slot has already been created with that time!</strong></p>"
                proceed = false;
            }
        });
        if (proceed) {
            console.log("request");
            let create = new XMLHttpRequest();
            let createParam = "addSchedule=&start_time=" + start_Time + "&end_time=" + end_Time + "&day=" + day; 
            create.open("POST","../php/lecturerSchedule.php",true);
            create.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            create.onload = function(){

                window.location.href="../app/lecturerSchedule.html";
            }
            create.send(createParam)
        } 
    }   
}

btn.onclick = function() {
    popup.style.display = "block";
    loadSchedule("Monday")
}

function loadSchedule (day) {
    let list = document.getElementById("slotList");
    list.innerHTML = "";
    let daySchedule = new XMLHttpRequest();
    daySchedule.open("POST","../php/lecturerSchedule.php",true);
    daySchedule.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    daySchedule.onload = function() {
        let res = JSON.parse(this.response);
        
        for (slots = 0; slots< res.length; slots++)
        if (res[slots]["day"] === day){
            daySlots.push([res[slots]["start_time"],res[slots]["end_time"]]);
            list.innerHTML += "<a href='#' onclick='selectSchedule("+slots+")'><li id=slot"+slots+" class='slotTime list-group-item d-flex justify-content-center'><strong>" + res[slots]["start_time"] + " - " + res[slots]["end_time"] + "</strong></li></a>";
        }
    }
    daySchedule.send("getSchedule=" + lecturerEmail);
}

function loadDaySchedule() {
    let day = document.getElementById("selectedListDay").value;
    loadSchedule(day);
}

function getDay(){
    let day = document.getElementById("selectedListDay").value;
    document.getElementById("createSelectedDay").innerHTML = "<option selected value="+day+">"+ day +"</option>";
    return day;
}

function selectSchedule(index){
    let day = getDay();
    let list = document.getElementById("slot"+index)
    let slot = daySlots[index];
    if (list.classList.contains("active")) {
        deleteArr.forEach(element => {
            if(element.slot[0] == slot[0]) {
                deleteArr.splice(deleteArr.indexOf(element),1); 
            }
        });
        list.classList.remove("active");
    } else {
        list.classList.add("active");
        deleteArr.push({day, slot});
    }
    console.log(deleteArr);
}

function deleteSlots() {
    let day = getDay();
    for (slots = 0; slots < deleteArr.length; slots++){
        let param = "deleteSchedule=&start_time=" + deleteArr[slots]["slot"][0] + "&end_time=" + deleteArr[slots]["slot"][1] + "&day=" + day ;
        let deleteSlot = new XMLHttpRequest();
        deleteSlot.open("POST","../php/lecturerSchedule.php", true);
        deleteSlot.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        if (slots == deleteArr.length -1){
            deleteSlot.onload = function(){
                window.location.href="../app/lecturerSchedule.html";
            }
        }
        deleteSlot.send(param)
    }
}

span.onclick = function() {
    popup.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}

