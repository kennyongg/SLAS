function loadProfile () {
    // To load profile of searched user
    let profile = new XMLHttpRequest;
    let destinationURL = "";
    let stateType = localStorage.getItem("selectedUserAcc");
    let stateEmail = localStorage.getItem("selectedUserEmail");
    let stateActive = localStorage.getItem("selectedUserActive");
    console.log(stateActive);
    // let profileBox = document.getElementById();
    if (stateType && stateType === "student") {
        destinationURL = "searchUser2.php?accType="+stateType+"&studentEmail="+stateEmail;
    } else {
        destinationURL = "searchUser2.php?accType="+stateType+"&lecturerEmail="+stateEmail;
    }

    if (stateType !== "admin") {
        profile.open("GET","../php/"+destinationURL,true);
        profile.onload = function(){
            let res = JSON.parse(this.response);
            let resName = res.name;
            let resEmail = res.email;
            document.getElementById("profileNameData").defaultValue = resName;
            document.getElementById("profileEmailData").defaultValue = resEmail;
            document.getElementById("profileTypeData").defaultValue = stateType;
            if (stateActive == 1) {
                document.getElementById("labelActive").classList.add("focus");
                document.getElementById("labelActive").classList.add("active");
                document.getElementById("labelInactive").classList.remove("focus");
                document.getElementById("labelInactive").classList.remove("active");
            } else {
                document.getElementById("labelActive").classList.remove("focus");
                document.getElementById("labelActive").classList.remove("active");
                document.getElementById("labelInactive").classList.add("focus");
                document.getElementById("labelInactive").classList.add("active");
            }
   
            // TODO - Subjects Logic
            let resSubject = "res.subject"; // JSON array object for subject

            if (stateType && stateType === "student") {
                let resCourse = res.course;
                let resIntake = res.intake;
                document.getElementById("profileCourseData").defaultValue = resCourse;
                document.getElementById("profileIntakeData").defaultValue = resIntake;
                document.getElementById("lecturerProfile").style.display = "none";
            } else if (stateType === "lecturer") {
                 let resDepartment = res.department;
                 let resOffice = res.office_location;
                 let resPhone = res.phone;
                 document.getElementById("profileDepartData").defaultValue = resDepartment;
                 document.getElementById("profilePhoneData").defaultValue = resPhone;
                 document.getElementById("profileOfficeData").defaultValue = resOffice;
                 document.getElementById("studentProfile").style.display = "none";
            }
            document.getElementById("profileLoader").style.display = "none";
            document.getElementById("profileBody").style.display = "block";
        };
        profile.send();
    } else {
        document.getElementById("lecturerProfile").style.display = "none";
        document.getElementById("studentProfile").style.display = "none";

        document.getElementById("profileNameData").defaultValue = null;
        document.getElementById("profileEmailData").defaultValue = stateEmail;
        document.getElementById("profileTypeData").defaultValue = stateType;
        document.getElementById("profile_activeSection").style.display = "none";

        document.getElementById("profileLoader").style.display = "none";
        // document.getElementById("profile_subjectSection").style.display = "none";
        document.getElementById("profileBody").style.display = "block";
    }
}


// To submit changes to the profile
var nameRegex = /^[a-zA-Z]+[a-zA-Z.\/\s]*$/;
// var passwordRegex = /^\S([\w\W]+){3,}$/;
// var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var courseRegex = /^[a-zA-Z]((\s?)[a-zA-Z\/.(),@&-]){0,}[a-zA-Z\/.),\s]*$/;
var intakeRegex = /^[12][0-9]{3}(0[1-9]|1[0-2])$/;
var officeRegex = /^[a-zA-Z]{2}-[0-9]{1,2}-([0-9]{1,2})$/;
var phoneRegex = /^[0-9][0-9]{3}$/;

// validate changes entered
let edit = document.getElementById("editProfile_formBox");
let editProfileMessageBox = document.getElementById("editProfile_messageBox");

edit.addEventListener('submit', function(event){
    event.preventDefault();
    let name = document.getElementById("profileNameData").value;
    let email = document.getElementById("profileEmailData").value;
    let user = document.getElementById("profileTypeData").value;

    let labelActive = document.getElementById("labelActive");
    let labelInactive = document.getElementById("labelInactive");
    if ( labelActive.classList.contains("active") ) {
        var activeStatus = 1;
    } else {
        if ( labelInactive.classList.contains("active") ) {
            activeStatus = 0;
        }
    }

    let editMessage = document.getElementById("edit_message");
    if (editMessage) {
        editMessage.parentNode.removeChild(editMessage);
    }

    // Generate error message
    function displayEmptyError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>All fields are required.<p>";
        messageBoxType.innerHTML = message;
    }
    function displayNameError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid name format (e.g. John Doe)</span>";
        messageBoxType.innerHTML = message;
    }
    // function displayPasswordError(messageBoxType) {
    //     event.preventDefault();
    //     let message = "<p id='edit_message' class='text-warning error_message'>Use 4 or more characters (letters, numbers & symbols)</span>";
    //     messageBoxType.innerHTML = message;
    // }
    // function displayRetypeError(messageBoxType) {
    //     event.preventDefault();
    //     let message = "<p id='edit_message' class='text-warning error_message'>Those passwords do not match</span>";
    //     messageBoxType.innerHTML = message;
    // }
    function displayCourseError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid course name format (e.g. BSc (Hons) Information Systems (Business Analytics))</span>";
        messageBoxType.innerHTML = message;
    }
    function displayIntakeError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid format. Enter in YYYYMM format (e.g. 201903)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayDeptError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid department format (e.g. Department of Computing and Information Systems)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayOfficeError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid location format (e.g. AE-3-1)</span>";
        messageBoxType.innerHTML = message;
    }
    function displayPhoneError(messageBoxType) {
        event.preventDefault();
        let message = "<p id='edit_message' class='text-warning error_message'>Invalid phone extension format (e.g. 5501)</span>";
        messageBoxType.innerHTML = message;
    }
    // function displayEmailError(messageBoxType) {
    //     event.preventDefault();
    //     let message = "<p id='edit_message' class='text-warning error_message'>Invalid email format</span>";
    //     messageBoxType.innerHTML = message;
    // }

    // Check if email already exists through AJAX
    // function checkDuplicateEmail(regEmail, messageBoxType, param_2, param_3) {
    //     let param = "registerEmail=" + regEmail;
    //     let emailCheck = new XMLHttpRequest;
    //     emailCheck.open("POST", "/SE_Assignment/php/regEmailDuplicate.php", true);
    //     emailCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    //
    //     emailCheck.onload = function () {
    //         let res = JSON.parse(emailCheck.response);
    //         if (res["error"]) {
    //             event.preventDefault();
    //             let message = "<p id='edit_message' class='text-warning error_message'>Email has already been used</span>";
    //             messageBoxType.innerHTML = message;
    //         } else if (res["success"]) {
    //             editProfile(messageBoxType, param_2, param_3);
    //         }
    //     }
    //     //Alert if requests fail
    //     emailCheck.onerror = function () {
    //         alert("Error connecting with server");
    //     };
    //     emailCheck.send(param);
    // }

    // record data to db
    function editProfile(messageBoxType, param_2) {
        let profileEdit = new XMLHttpRequest;
        profileEdit.open("POST", "/SE_Assignment/php/account.php", true);
        profileEdit.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        profileEdit.onload = function () {
            let res = JSON.parse(profileEdit.response);
            if (res["error"]) {
                event.preventDefault();
                let message = "<p id='edit_message' class='text-warning error_message'>Problem with creating account. Try submitting again</span>";
                messageBoxType.innerHTML = message;
            } else if (res["success"]) {
                window.location.href = "/SE_Assignment/app/postEdit.html";
            }
        }
        //Alert if requests fail
        profileEdit.onerror = function () {
            alert("Error connecting with server");
        };
        profileEdit.send(param_2);
    }


    // validate fields in student form
    if ( user === "student" ) {
        let course = document.getElementById("profileCourseData").value;
        let intake = document.getElementById("profileIntakeData").value;

        if (name === "" || email === "" || course === "" || intake === "") {
            displayEmptyError(editProfileMessageBox);
        } else {
            if (!nameRegex.test(name)) {
                displayNameError(editProfileMessageBox);
            } else {
                if (!courseRegex.test(course)) {
                    displayCourseError(editProfileMessageBox);
                } else {
                    if (!intakeRegex.test(intake)) {
                        displayIntakeError(editProfileMessageBox);
                    } else {
                        let param2 = "email=" + email + "&acc_type=" + user + "&active=" + activeStatus + "&name=" + name + "&course=" + course + "&intake=" + intake + "&acc_type=" + user;
                        editProfile(editProfileMessageBox, param2);
                    }
                }
            }
        }
    }

    // validate fields in lecturer form
    if ( user === "lecturer" ) {
        let department = document.getElementById("profileDepartData").value;
        let office = document.getElementById("profileOfficeData").value;
        let phone = document.getElementById("profilePhoneData").value;

        if (name === "" || email === "" || department === "" || office === "" || phone === "") {
            displayEmptyError(editProfileMessageBox);
        } else {
            if (!nameRegex.test(name)) {
                displayNameError(editProfileMessageBox);
            } else {
                if (!nameRegex.test(department)) {
                    displayDeptError(editProfileMessageBox);
                } else {
                    if (!officeRegex.test(office)) {
                        displayOfficeError(editProfileMessageBox);
                    } else {
                        if (!phoneRegex.test(phone)) {
                            displayPhoneError(editProfileMessageBox);
                        } else {
                            let param2 = "email=" + email + "&acc_type=" + user + "&active=" + activeStatus + "&name=" + name + "&department=" + department + "&office=" + office + "&phone=" + phone + "&acc_type=" + user;
                            editProfile(editProfileMessageBox, param2);
                        }
                    }
                }
            }
        }
    }
});

// remove 'focus' class from the active toggle button
let activeButtons = document.getElementById("activeButtons");
activeButtons.addEventListener('click', function(event) {
    document.getElementById("labelInactive").classList.remove("focus");
    document.getElementById("labelActive").classList.remove("focus");
});