var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var nameRegex = /^[a-zA-Z]+[a-zA-Z.\/\s]*$/;
var courseRegex = /^[a-zA-Z]((\s?)[a-zA-Z\/.(),@&-]){0,}[a-zA-Z\/.),\s]*$/;
var intakeRegex = /^[12][0-9]{3}(0[1-9]|1[0-2])$/;
var officeRegex = /^[a-zA-Z]{2}-[0-9]{1,2}-([0-9]{1,2})$/;
var phoneRegex = /^[0-9][0-9]{3}$/;
// var passwordRegex = /^\S([\w\W]+){3,}$/;

//validate register data
let register = document.getElementById("reg_formBox");
let registerStudentMessageBox = document.getElementById("reg_student_messageBox");
let registerLecturerMessageBox = document.getElementById("reg_lecturer_messageBox");
let user = "";

register.addEventListener('submit', function(event){
    event.preventDefault();

    // if (acc_type !== "admin") {
    //     alert('You do not have admin permission.');
    //     event.preventDefault();
    // }

    let registerMessage = document.getElementById("register_message");
    if (registerMessage) {
        registerMessage.parentNode.removeChild(registerMessage);
    }

    if (document.getElementById('labelStudent').checked) {
        user = "student";
    } else if (document.getElementById('labelLecturer').checked) {
        user = "lecturer";
    }

    // Generate error message
    function displayEmptyError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>All fields are required.<p>";
        messageBoxType.innerHTML = message;
    }
    function displayNameError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid name format (e.g. John Doe)</span>";
        messageBoxType.innerHTML = message;
    }
    // function displayPasswordError(messageBoxType) {
    //     event.preventDefault();
    //     let message = "<p id='register_message' class='text-warning error_message'>Use 4 or more characters (letters, numbers & symbols)</span>";
    //     messageBoxType.innerHTML = message;
    // }
    // function displayRetypeError(messageBoxType) {
    //     event.preventDefault();
    //     let message = "<p id='register_message' class='text-warning error_message'>Those passwords do not match</span>";
    //     messageBoxType.innerHTML = message;
    // }
    function displayCourseError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid course name format (e.g. BSc (Hons) Information Systems (Business Analytics))</span>";
        messageBoxType.innerHTML = message;
    }
    function displayIntakeError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid format. Enter in YYYYMM format (e.g. 201903)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayDeptError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid department format (e.g. Department of Computing and Information Systems)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayOfficeError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid location format (e.g. AE-3-1)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayPhoneError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid phone extension format (e.g. 5501)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayEmailError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='register_message' class='text-warning error_message'>Invalid email format</span>";
        messageBoxType.innerHTML = message;
    }

    // Check if email already exists through AJAX and add account to db if it does not exist
    function checkDuplicateEmail(param, messageBoxType) {
        let emailCheck = new XMLHttpRequest;
        emailCheck.open("POST", "/SE_Assignment/php/account.php", true);
        emailCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        emailCheck.onload = function () {

            let res = JSON.parse(emailCheck.response);
            if (res["error"]) {
                event.preventDefault();
                let message = "<p id='register_message' class='text-warning error_message'>Email has already been used</span>";
                messageBoxType.innerHTML = message;
            } else if (res["success"]) {
                window.location.href = "/SE_Assignment/app/postRegistration.html";
            }
        };
        //Alert if requests fail
        emailCheck.onerror = function () {
            alert("Error connecting with server");
        };
        emailCheck.send(param);
    }

    // record data to db
    // function addAccount(messageBoxType, param_2, param_3) {
    //     let accountCreate = new XMLHttpRequest;
    //     accountCreate.open("POST", "/SE_Assignment/php/account.php", true);
    //     accountCreate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //
    //     accountCreate.onload = function () {
    //         let res = JSON.parse(accountCreate.response);
    //         if (res["error"]) {
    //             event.preventDefault();
    //             let message = "<p id='register_message' class='text-warning error_message'>Problem with creating account. Try submitting again</span>"
    //             messageBoxType.innerHTML = message;
    //         } else if (res["success"]) {
    //
    //             let accountAdd = new XMLHttpRequest;
    //             accountAdd.open("POST", "/SE_Assignment/php/account.php", true);
    //             accountAdd.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //
    //             accountAdd.onload = function () {
    //                 let res = JSON.parse(accountAdd.response);
    //                 if (res["error"]) {
    //                     event.preventDefault();
    //                     let message = "<p id='register_message' class='text-warning error_message'>Problem with adding account. Try submitting again</span>"
    //                     messageBoxType.innerHTML = message;
    //                 } else if (res["success"]) {
    //
    //                     window.location.href = "/SE_Assignment/app/postRegistration.html";
    //                 }
    //             }
    //             //Alert if requests fail
    //             accountAdd.onerror = function () {
    //                 alert("Error connecting with server");
    //             };
    //             accountAdd.send(param_3);
    //         }
    //     }
    //     //Alert if requests fail
    //     accountCreate.onerror = function () {
    //         alert("Error connecting with server");
    //     };
    //     accountCreate.send(param_2);
    // }

    // validate fields in student form
    if ( user === "student" ) {
        let name = document.getElementById("reg_student_name").value;
        let email = document.getElementById("reg_student_email").value;
        let course = document.getElementById("reg_student_course").value;
        let intake = document.getElementById("reg_student_intake").value;
        let password = document.getElementById("reg_student_pass").value;
        // let retype = document.getElementById("reg_student_retype").value;

        if (name === "" || email === "" || course === "" || intake === "") {
            displayEmptyError(registerStudentMessageBox);
        } else {
            if (!nameRegex.test(name)) {
                displayNameError(registerStudentMessageBox);
            } else {
                // if (!passwordRegex.test(password)) {
                //     displayPasswordError(registerStudentMessageBox);
                // } else {
                //     if ( retype !== password) {
                //         displayRetypeError(registerStudentMessageBox);
                //     } else {
                if (!courseRegex.test(course)) {
                    displayCourseError(registerStudentMessageBox);
                } else {
                    if ( !intakeRegex.test(intake)) {
                        displayIntakeError(registerStudentMessageBox);
                    } else {
                        if (!emailRegex.test(email)) {
                            displayEmailError(registerStudentMessageBox);
                        } else {
                            let param2 = "registerEmail="+ email + "&password=" + password + "&acc_type=" + user + "&name=" + name + "&course=" + course + "&intake=" + intake + "&acc_type=" + user;
                            checkDuplicateEmail(param2, registerStudentMessageBox);
                        }
                    }
                }
                //     }
                // }
            }
        }
    }

    // validate fields in lecturer form
    if ( user === "lecturer" ) {
        let name = document.getElementById("reg_lecturer_name").value;
        let email = document.getElementById("reg_lecturer_email").value;
        let department = document.getElementById("reg_lecturer_dept").value;
        let office = document.getElementById("reg_lecturer_office").value;
        let phone = document.getElementById("reg_lecturer_phone").value;
        let password = document.getElementById("reg_lecturer_pass").value;
        // let retype = document.getElementById("reg_lecturer_retype").value;

        if (name === "" || email === "" || department === "" || office === "" || phone === "") {
            displayEmptyError(registerLecturerMessageBox);
        } else {
            if (!nameRegex.test(name)) {
                displayNameError(registerLecturerMessageBox);
            } else {
                // if (!passwordRegex.test(password)) {
                //     displayPasswordError(registerLecturerMessageBox);
                // } else {
                //     if (retype !== password) {
                //         displayRetypeError(registerLecturerMessageBox);
                //     } else {
                if (!nameRegex.test(department)) {
                    displayDeptError(registerLecturerMessageBox);
                } else {
                    if (!officeRegex.test(office)) {
                        displayOfficeError(registerLecturerMessageBox);
                    } else {
                        if (!phoneRegex.test(phone)) {
                            displayPhoneError(registerLecturerMessageBox);
                        } else {
                            if (!emailRegex.test(email)) {
                                displayEmailError(registerLecturerMessageBox);
                            } else {
                                let param2 = "registerEmail="+ email + "&password=" + password + "&acc_type=" + user + "&name=" + name + "&department=" + department + "&office=" + office + "&phone=" + phone + "&acc_type=" + user;
                                checkDuplicateEmail(param2, registerLecturerMessageBox);
                            }
                        }
                    }
                }
                //     }
                // }
            }
        }
    }
});


// For switching between student and lecturer
function formChange (user) {
    document.getElementById("reg_button").style.display = "block";

    let studentForm = document.getElementById("reg_student");
    let lecturerForm = document.getElementById("reg_lecturer");

    if (user === "student") {
        lecturerForm.style.display = "none";
        studentForm.style.display = "block";
        document.getElementById("addStaff_form").style.display = "none";
        document.getElementById("addStudent_form").style.display = "block";
    } else if (user === "lecturer") {
        lecturerForm.style.display = "block";
        studentForm.style.display = "none";
        document.getElementById("addStaff_form").style.display = "block";
        document.getElementById("addStudent_form").style.display = "none";
    }
}

function formShow (mode) {
    let addForm = document.getElementById("add_formBox");
    let editForm = document.getElementById("edit_formBox");
    let addButton = document.getElementById("action_add");
    let editButton = document.getElementById("action_edit");

    if (mode === "add") {
        editForm.style.display = "none";
        editButton.className = editButton.className.replace(" btnActive","");
        addForm.style.display = "flex";
        if (! addButton.className.includes(" btnActive")) {
            addButton.className += " btnActive";
        }
        document.getElementById("reg_mainTitle").style.display = "block";
        document.getElementById("reg_select").style.display = "inline-flex";
    } else if (mode === "edit") {
        addForm.style.display = "none";
        addButton.className = addButton.className.replace(" btnActive","");
        editForm.style.display = "block";
        if (! editButton.className.includes(" btnActive")) {
            editButton.className += " btnActive";
        }
        document.getElementById("reg_mainTitle").style.display = "none";
        document.getElementById("reg_select").style.display = "none";
    }
}

// prevent submitting form upon pressing enter
let searchInput = document.getElementById("search_bar");
if (searchInput) {
    searchInput.addEventListener('keypress', function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
}

// import students data
document.getElementById("studentFileToUpload").addEventListener('submit', function(event) {

    let formData = new FormData();
    formData.append("studentCSV", document.getElementById("studentCSV").files[0]);

    let importStudent = new XMLHttpRequest;
    importStudent.open("POST", "/SE_Assignment/php/import.php", true);
    importStudent.setRequestHeader("Content-type", "multipart/form-data");
    importStudent.onload = function () {
        let res = JSON.parse(importStudent.response);
        if (res["success"]) {
            window.location.href = "/SE_Assignment/app/postImport.html";
        } else {
            alert('Data import unsuccessful.');
            event.preventDefault();
        }
    };
    //Alert if requests fail
    importStudent.onerror = function () {
        alert("Error connecting with server");
    };
    importStudent.send(formData);
});

// import lecturers data
document.getElementById("staffFileToUpload").addEventListener('submit', function(event) {
    let formData = new FormData();
    formData.append("staffCSV", document.getElementById("staffCSV").files[0]);

    let importStaff = new XMLHttpRequest;
    importStaff.open("POST", "/SE_Assignment/php/import.php", true);
    importStaff.setRequestHeader("Content-type", "multipart/form-data");

    importStaff.onload = function () {
        let res = JSON.parse(importStaff.response);
        if (res["success"]) {
            window.location.href = "/SE_Assignment/app/postImport.html";
        } else {
            alert('Data import unsuccessful.');
            event.preventDefault();
        }
    };
    //Alert if requests fail
    importStaff.onerror = function () {
        alert("Error connecting with server");
    };
    importStaff.send(formData);
});

// import subjects data
document.getElementById("subjectFileToUpload").addEventListener('submit', function(event) {
    let formData = new FormData();
    formData.append("subjectCSV", document.getElementById("subjectCSV").files[0]);

    let importSubject = new XMLHttpRequest;
    importSubject.open("POST", "/SE_Assignment/php/import.php", true);
    importSubject.setRequestHeader("Content-type", "multipart/form-data");

    importSubject.onload = function () {
        let res = JSON.parse(importSubject.response);
        if (res["success"]) {
            window.location.href = "/SE_Assignment/app/postImport.html";
        } else {
            alert('Data import unsuccessful.');
            event.preventDefault();
        }
    };
    //Alert if requests fail
    importSubject.onerror = function () {
        alert("Error connecting with server");
    };
    importSubject.send(formData);
});

// import enrollments data
document.getElementById("enrollmentFileToUpload").addEventListener('submit', function(event) {
    let formData = new FormData();
    formData.append("enrollmentCSV", document.getElementById("enrollmentCSV").files[0]);

    let importEnrollment = new XMLHttpRequest;
    importEnrollment.open("POST", "/SE_Assignment/php/import.php", true);
    importEnrollment.setRequestHeader("Content-type", "multipart/form-data");

    importEnrollment.onload = function () {
        let res = JSON.parse(importEnrollment.response);
        if (res["success"]) {
            window.location.href = "../postImport.html";
        } else {
            alert('Data import unsuccessful.');
            event.preventDefault();
        }
    };
    //Alert if requests fail
    importEnrollment.onerror = function () {
        alert("Error connecting with server");
    };
    importEnrollment.send(formData);
});



// allow import form to submit only if a file is chosen
document.getElementById("studentCSV").addEventListener('change', function(event) {
    if( document.getElementById("studentCSV").files.length == 0 ){
        document.getElementById("importStudentButton").disabled = true;
    } else {
        document.getElementById("importStudentButton").disabled = false;
    }
});


document.getElementById("staffCSV").addEventListener('change', function(event) {
    if( document.getElementById("staffCSV").files.length == 0 ){
        document.getElementById("importStaffButton").disabled = true;
    } else {
        document.getElementById("importStaffButton").disabled = false;
    }
});

document.getElementById("subjectCSV").addEventListener('change', function(event) {
    if( document.getElementById("subjectCSV").files.length == 0 ){
        document.getElementById("importSubjectButton").disabled = true;
    } else {
        document.getElementById("importSubjectButton").disabled = false;
    }
});

document.getElementById("enrollmentCSV").addEventListener('change', function(event) {
    if( document.getElementById("enrollmentCSV").files.length == 0 ){
        document.getElementById("importEnrollmentButton").disabled = true;
    } else {
        document.getElementById("importEnrollmentButton").disabled = false;
    }
});