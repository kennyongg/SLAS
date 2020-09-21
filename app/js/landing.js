// TODO - AJAX call for more button
// Increment for getting more appointment
//read more button
let account=sessionStorage.getItem('userAcc');
let username=sessionStorage.getItem('userName');
if (account==="student") {
    $(document).ready(function () {
        var appointmentCount = 2;
        var i;
        $.ajax({
            url: "../php/viewAppointment.php",
            type: "POST",
            data: {getLandingAppointmentList: appointmentCount},
            success: function (response) {
                let res = $.parseJSON(response);
                let showUsername = document.getElementById("landingWelMessage");
                showUsername.innerHTML="Welcome <br>" + username;
                var totalNumOfAppointments = res.length;
                if (totalNumOfAppointments<3){
                    document.getElementById('load-more').style.visibility='hidden';
                }
                let showAppointments = document.getElementById("appointmentNum");
                showAppointments.innerHTML = "You have " + totalNumOfAppointments + " Upcoming Appointments";
                let display = document.getElementById("appointmentContainer");
                if (totalNumOfAppointments<1){
                    display.innerHTML="There are currently no upcoming appointments";
                }
                for (i = 0; i < appointmentCount; i++) {
                    display.innerHTML += "<p>Title: " + res[i]["title"] + "<br>Lecturer: " + res[i]["name"] + "<br>Subject: " +
                        res[i]["subject"] + "<br>Date: " +
                        res[i]["start_date"] + "<br>Time: " +
                        res[i]["start_time"] + "-" +
                        res[i]["end_time"] + "<br>Venue: " +
                        res[i]["venue"] + "<br>" + "</p>"
                }
            }
        })

        $("#load-more").click(function () {
            document.getElementById('load-more').style.visibility = 'hidden';
            var j;
            appointmentCount = 5;
            $.ajax({
                url: "../php/viewAppointment.php",
                type: "POST",
                data: {getLandingAppointmentList: appointmentCount},
                success: function (response) {
                    let res = $.parseJSON(response);
                    for (j = 2; j <= appointmentCount; j++) {
                        let display = document.getElementById("appointmentContainerMore");
                        display.innerHTML += "<p>Title: " + res[j]["title"] + "<br>Lecturer: " + res[j]["name"] + "<br>Subject: " +
                            res[j]["subject"] + "<br>Date: " +
                            res[j]["start_date"] + "<br>Time: " +
                            res[j]["start_time"] + "-" +
                            res[j]["end_time"] + "<br>Venue: " +
                            res[j]["venue"] + "<br>" + "</p>"
                    }
                }
            })
        });
    });
} else if (account==="lecturer"){
    $(document).ready(function () {
        var appointmentCount = 2;
        var i;
        $.ajax({
            url: "../php/viewAppointment.php",
            type: "POST",
            data: {getLandingAppointmentList: appointmentCount},
            success: function (response) {
                let res = $.parseJSON(response);
                let showUsername = document.getElementById("landingWelMessage");
                showUsername.innerHTML="Welcome <br>" + username;
                var totalNumOfAppointments = "You have " + res.length + " Upcoming Appointments";
                if (totalNumOfAppointments<3){
                    document.getElementById('load-more').style.visibility='hidden';
                }
                let showAppointments = document.getElementById("appointmentNum");
                showAppointments.innerHTML = totalNumOfAppointments;
                let display = document.getElementById("appointmentContainer");
                if (totalNumOfAppointments<1){
                    display.innerHTML = "There are currently no upcoming appointments";
                }
                for (i = 0; i < appointmentCount; i++) {
                    display.innerHTML += "<p>Title: " + res[i]["title"] + "<br>Student: " + res[i]["name"] + "<br>Subject: " +
                        res[i]["subject"] + "<br>Date: " +
                        res[i]["start_date"] + "<br>Time: " +
                        res[i]["start_time"] + "-" +
                        res[i]["end_time"] + "<br>Venue: " +
                        res[i]["venue"] + "<br>" + "</p>"
                }
            }
        })

        $("#load-more").click(function () {
            document.getElementById('load-more').style.visibility = 'hidden';
            var j;
            appointmentCount = 5;
            $.ajax({
                url: "../php/viewAppointment.php",
                type: "POST",
                data: {getLandingAppointmentList: appointmentCount},
                success: function (response) {
                    let res = $.parseJSON(response);
                    for (j = 2; j <= appointmentCount; j++) {
                        let display = document.getElementById("appointmentContainerMore");
                        display.innerHTML += "<p>Title: " + res[j]["title"] + "<br>Student: " + res[j]["name"] + "<br>Subject: " +
                            res[j]["subject"] + "<br>Date: " +
                            res[j]["start_date"] + "<br>Time: " +
                            res[j]["start_time"] + "-" +
                            res[j]["end_time"] + "<br>Venue: " +
                            res[j]["venue"] + "<br>" + "</p>"
                    }
                }
            })
        });
    });
}

document.getElementById("landingSearch").oninput = function(e) {
    event.preventDefault();
    let input = document.getElementById("landingSearch").value;
    let resultBox = document.getElementById("landingSeachResultBox");
    
    // remove before searching
    resultBox.innerHTML="";

    let stringMatch = /[a-zA-Z]/g
    if (input.length > 1 && input.match(stringMatch)) {
        let search = new XMLHttpRequest();
        search.open("GET","../php/search.php?searchLecturer="+input,true);
        search.onload = () => {
        let res = JSON.parse(search.response);
        if (res.length > 0) {
            res.forEach(element => {
                resultBox.innerHTML += '<div class="resultLines"><strong><a href="lecturerProfile.html?email='+ element["email"] +' ">'+ element["name"] +'</a></strong></div>';
            });
        } else {
            resultBox.innerHTML = '<p class=resultLines>No results Found</p>';            
        }
    }
    search.send();
    }  
}

if (account === "lecturer") {
    document.getElementById('searchBarBody').style.display = "none";
}