// AJAX call to laod appointment list
function loadAppointmentList() {
    let account = sessionStorage.getItem('userAcc')
    if (account === "student") {
        $(document).ready(function () {
            document.getElementById("lecOrStudent").innerHTML = "Lecturer";
            var appointmentCount = 100;
            var i;

            $.ajax({
                url: "../php/viewAppointment.php",
                type: "POST",
                data: {getAppointmentList: appointmentCount},
                success: function (response) {
                    console.log(response);
                    let res = $.parseJSON(response);
                    let displayRow = document.getElementById("appointmentTable");

                    for (i = 0; i <= appointmentCount; i++) {
                        var counter = i + 1;
                        let addNewRow = '<tr> ' +
                            '<td id="id">' + counter.toString() + '</td>' +
                            '<td id="subject">' + res[i]["subject"] + '</td>' +
                            '<td id="name">' + res[i]["name"] + '</td>' +
                            '<td id="date">' + res[i]["start_date"] + '</td>' +
                            '<td id="time">' + res[i]["start_time"] + '-' + res[i]["end_time"] + '</td>' +
                            '<td id="venue">' + res[i]["venue"] + '</td>' +
                            '<td id="status">' + res[i]["status"] + '</td>' +
                            '<td><a href="appointmentDetails.html?id=' + res[i]["id"] + '">Details</a></td></tr>';
                        displayRow.innerHTML += addNewRow;
                    }
                    // document.getElementById("listLoader").style.display = "none";
                    // document.getElementById("listTableBlock").style.display = "block";
                }
            })
        });
    } else if (account === "lecturer") {
        $(document).ready(function () {
            document.getElementById("lecOrStudent").innerHTML = "Student";
            var appointmentCount = 100;
            var i;

            $.ajax({
                url: "../php/viewAppointment.php",
                type: "POST",
                data: {getAppointmentList: appointmentCount},
                success: function (response) {
                    console.log(response);
                    let res = $.parseJSON(response);
                    let displayRow = document.getElementById("appointmentTable");

                    for (i = 0; i <= appointmentCount; i++) {
                        var counter = i + 1;
                        let addNewRow = '<tr> ' +
                            '<td id="id">' + counter.toString() + '</td>' +
                            '<td id="subject">' + res[i]["subject"] + '</td>' +
                            '<td id="name">' + res[i]["name"] + '</td>' +
                            '<td id="date">' + res[i]["start_date"] + '</td>' +
                            '<td id="time">' + res[i]["start_time"] + '-' + res[i]["end_time"] + '</td>' +
                            '<td id="venue">' + res[i]["venue"] + '</td>' +
                            '<td id="status">' + res[i]["status"] + '</td>' +
                            '<td><a href="appointmentDetails.html?id=' + res[i]["id"] + '">Details</a></td></tr>';
                        displayRow.innerHTML += addNewRow;
                    }
                    document.getElementById("listLoader").style.display = "none";
                    document.getElementById("listTableBlock").style.display = "block";
                }
            })
        });
    }
}
