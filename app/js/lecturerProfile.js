var popup1 = document.getElementById("appointmentPopup1");

var btn1 = document.getElementById("appointmentBtn1");

/*var popup2 = document.getElementById("appointmentPopup2");

var btn2 = document.getElementById("appointmentBtn2");*/

var span = document.getElementsByClassName("lecturer-popup-close")[0];
var lecturerEmail = "";
var lecturerOffice = "";
var subjects = "";
    

btn1.onclick = function() {
    popup1.style.display = "block";
}

/*btn2.onclick = function() {
    popup2.style.display = "block";
}*/

span.onclick = function() {
    popup1.style.display = "none";
}

/*span[1].onclick = function() {
    popup2.style.display = "none";
}*/

window.onclick = function(event) {
    if (event.target == popup1) {
        popup1.style.display = "none";
    }
    /*if (event.target == popup2) {
        popup2.style.display = "none";
    }*/
}

function loadLecturer () {
    let url = new URL(window.location.href);
    let lecturerParams = url.searchParams.get("email");
    lecturerEmail = url.searchParams.get("email");
    
    let subject = new XMLHttpRequest();
    subject.open("GET","../php/getEnrollment.php?enrollment",true);
    subject.onload = function(){
        let res = JSON.parse(subject.response);
        res.forEach(element => {
            if (element.lecturer == lecturerEmail) {
                subjects = element.subject;
            }
        });
    }
    subject.send();

    $(document).ready(function () {
        $.ajax({
            url: "../php/profile.php",
            type: "POST",
            data: {lecturerEmail:lecturerParams},
            success: function (response) {
                let res = $.parseJSON(response);
                console.log(res);
                let displayName = document.getElementById("lecturerName");
                let displayDepartment = document.getElementById("department");
                let displayOffice = document.getElementById("officeLocation");
                let displayPhone = document.getElementById("phone");
                let displayEmail = document.getElementById("email");

                displayName.innerHTML = res[0]["name"];
                displayDepartment.innerText = res[0]["department"];
                displayOffice.innerHTML = res[0]["office_location"];
                displayPhone.innerHTML = res[0]["phone"];
                displayEmail.innerHTML = res[0]["email"];
                lecturerOffice = res[0]["office_location"];
            }
        })
    })

    let schedules = new XMLHttpRequest();
    schedules.open("POST","../php/lecturerSchedule.php", true);
    schedules.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    schedules.onload= function () {
        let resSlot = JSON.parse(schedules.response);
        let mondaySlot = document.getElementById("listDayMonday");
        let tuesdaySlot = document.getElementById("listDayTuedday");
        let wednesdaySlot = document.getElementById("listDayWednesday");
        let thursdaySlot = document.getElementById("listDayThursday");
        let fridaySlot = document.getElementById("listDayFriday");
        let res = JSON.parse(this.response);
        console.log(resSlot);
        resSlot.forEach(element => {
            console.log(element);
            switch(element.day) {
                case "Monday":
                    mondaySlot.innerHTML += "<li class='list-item slotTime'><strong>"+ element.start_time + " - " + element.end_time + "</strong></li>"
                    break;
                case Tuesday:
                    tuesdaySlot.innerHTML += "<li class='list-item slotTime'><strong>"+ element.start_time + " - " + element.end_time + "</strong></li>"
                    break;
                case Wednesday:
                    wednesdaySlot.innerHTML += "<li class='list-item slotTime'><strong>"+ element.start_time + " - " + element.end_time + "</strong></li>"
                    break;
                case Thursday:
                    thursdaySlot.innerHTML += "<li class='list-item slotTime'><strong>"+ element.start_time + " - " + element.end_time + "</strong></li>"
                    break;
                case Friday:
                    fridaySlot.innerHTML += "<li class='list-item slotTime'><strong>"+ element.start_time + " - " + element.end_time + "</strong></li>"
                    break;
            }    
        });
    }
    schedules.send("getSchedule="+lecturerEmail);
}


let appointForm = document.getElementById("createAppointForm");
appointForm.addEventListener('submit', function(){
    event.preventDefault();
   
    let title = document.getElementById("createTitle").value;
    let date = $('#datePicker').datepicker("getDate");
    let dateDay = date.getDate();
    let dateMonth = date.getMonth() + 1;
    let dateYear = date.getFullYear();
    let choseDate = dateYear+'-'+dateMonth+'-'+dateDay;

    let startTimePick = $('#startTimePicker').timepicker('getTime', new Date);
    let startTimeHour = startTimePick.getHours();
    let startTimeMinute = startTimePick.getMinutes();
    let startTimeSecond = startTimePick.getSeconds();
    let startTime = startTimeHour+":"+startTimeMinute+":"+startTimeSecond;

    let endTimePick = $('#endTimePicker').timepicker('getTime', new Date);
    let endTimeHour = endTimePick.getHours();
    let endTimeMinute = endTimePick.getMinutes();
    let endTimeSecond = endTimePick.getSeconds();
    let endTime = endTimeHour+":"+endTimeMinute+":"+endTimeSecond;

    let desc = document.getElementById("createDesc").value;
    let appoint = new XMLHttpRequest();
    appointParam = "createAppointment="+
        "&title="+title+
        "&description="+desc+
        "&lecturerEmail="+lecturerEmail+
        "&subject="+subjects+
        "&startDate="+choseDate+
        "&startTime="+startTime+
        "&endDate="+choseDate+
        "&endTime="+endTime+
        "&venue="+lecturerOffice;
    appoint.open("POST","../php/manageAppointment.php",true);
    appoint.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    appoint.onload = function() {
        setTimeout(function(){
            window.location.href="appointmentList.html";
        }, 1500);
    }

    appoint.send(appointParam);
});
