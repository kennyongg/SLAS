function loadAppointmentDetails () {
    let account=sessionStorage.getItem('userAcc');
    let url = new URL(window.location.href);
    let appointmentParams = url.searchParams.get("id");

    $(document).ready(function () {
        $.ajax({
            url: "../php/viewAppointment.php",
            type: "GET",
            data: {appointmentId:appointmentParams},
            success: function (response) {
                let res = $.parseJSON(response);
                console.log(res);
                let displaySubject = document.getElementById("subject");
                let displayTopic = document.getElementById("topic");
                let displayDate = document.getElementById("date");
                let displayTime = document.getElementById("time");
                let displayVenue = document.getElementById("venue");
                let displayDescription = document.getElementById("description");
                let displayStatus = document.getElementById("status");
                let displayRejected = document.getElementById("rejectedReason");


                displaySubject.innerHTML = res.subject;
                displayTopic.innerText = res.title;
                displayDate.innerHTML = res.start_date;
                displayTime.innerHTML = res.start_time + "-" + res.end_time;
                displayVenue.innerHTML = res.venue;
                displayDescription.innerHTML = res.description;
                displayStatus.innerHTML = res.status;

                if(res.status ==="rejected" || res.status === "cancelled"){
                    displayRejected.innerHTML = res.rejected_reason;
                } else {
                    displayRejected.innerHTML = "-";
                }

                if (account === "lecturer") {
                    if (res.status === "approved") {
                        document.getElementById('accept').style.visibility = "hidden";
                        document.getElementById('reject').style.visibility = "hidden";
                    } else if (res.status === "rejected") {
                        document.getElementById('accept').style.visibility = "hidden";
                        document.getElementById('reject').style.visibility = "hidden";
                        document.getElementById('cancel').style.visibility = "hidden";
                    } else if (res.status === "completed" || res.status === "cancelled") {
                        document.getElementById('accept').style.visibility = "hidden";
                        document.getElementById('reject').style.visibility = "hidden";
                        document.getElementById('cancel').style.visibility = "hidden";
                    } else if (res.status === "pending") {
                        document.getElementById('cancel').style.visibility = "hidden";
                    }
                } else if (account === "student") {
                    if (res.status === "rejected" || res.status === "completed" || res.status === "cancelled") {
                        document.getElementById('accept').style.visibility = "hidden";
                        document.getElementById('reject').style.visibility = "hidden";
                        document.getElementById('cancel').style.visibility = "hidden";
                    } else if (res.status === "pending" || res.status === "approved") {
                        document.getElementById('accept').style.visibility = "hidden";
                        document.getElementById('reject').style.visibility = "hidden";
                    }
                }
                document.getElementById("detailLoader").style.display = "none";
                document.getElementById("detailTableBlock").style.display = "block";
            }
        })
    })
}

let url = new URL(window.location.href);
let appointID = url.searchParams.get("id");
// For lecturer accept
function acceptAppoint() {
    let confirm = new XMLHttpRequest();
    confirm.open("POST","../php/manageAppointment.php",true);
    confirm.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    confirm.onload = function() {
        let res = confirm.response;
        // let res = JSON.parse(reject.response);
        console.log(res);
        setTimeout(function(){
            window.location.href = "appointmentList.html";
        },3000);
    }
    confirm.send("response=approved&appointmentId="+appointID);
}
// For lecturer reject
function rejectAppoint() {
    event.preventDefault();
    let reason = document.getElementById("rejectReason").value;
    let rejectMessageBox = document.getElementById("rejectMessageBox");
    console.log(reason);
    if (reason.length == 0) {
        event.preventDefault();
        let message = "<p class='text-warning error_message'><strong>You must have a reason entered!</strong></p>"
        rejectMessageBox.innerHTML = message; 
    } else {
        let reject = new XMLHttpRequest();
        reject.open("POST","../php/manageAppointment.php",true);
        reject.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        reject.onload = function() {
            // let res = JSON.parse(reject.response);
            let message = "<p class='text-success success_message'><strong>Reason Accepted!</strong></p>"
            rejectMessageBox.innerHTML = message; 
            setTimeout(function(){
                window.location.href = "appointmentList.html";
            },1500);
        }
    
        reject.send("response=rejected&appointmentId="+appointID +"&rejected_reason="+reason);
    }
}
// For student
function cancelAppoint() {
    event.preventDefault();
    let reason = document.getElementById("cancelReason").value;
    let cancelMessageBox = document.getElementById("cancelMessageBox");
    console.log(reason);
    if (reason.length == 0) {
        event.preventDefault();
        let message = "<p class='text-warning error_message'><strong>You must have a reason entered!</strong></p>"
        cancelMessageBox.innerHTML = message; 
    } else {
        let cancel = new XMLHttpRequest();
        cancel.open("POST","../php/manageAppointment.php",true);
        cancel.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        cancel.onload = function() {
            let res = cancel.response;
            console.log(res);
            let message = "<p class='text-success success_message'><strong>Reason Accepted!</strong></p>"
            cancelMessageBox.innerHTML = message; 
            setTimeout(function(){
                window.location.href = "appointmentList.html";
            },1500);
        }
    
        cancel.send("response=cancelled&appointmentId="+appointID + "&rejected_reason="+reason);
    }
    
}

