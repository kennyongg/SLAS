function loadProfile () {
   // To load the profile of the user
   let profile = new XMLHttpRequest;
   let stateType = sessionStorage.getItem("userAcc");
   let stateEmail = sessionStorage.getItem("userEmail");
   let stateName = sessionStorage.getItem("userName");
   // let profileBox = document.getElementById();
   
   if (stateType !== "admin") {
      profile.open("POST","../php/profile.php",true);
      profile.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      profile.onload = function(){
         let res = JSON.parse(this.response);
         console.log(res);
         let resName = res.name;
         let resEmail = res.email;
         document.getElementById("profileNameData").innerHTML = resName;
         document.getElementById("profileEmailData").innerHTML = resEmail;
         document.getElementById("profileTypeData").innerHTML = stateType;
   
         // TODO - Subjects Logic
         let resSubject = "res.subject"; // JSON array object for subject

         if (stateType && stateType === "student") {
            let resCourse = res.course;
            let resIntake = res.intake;
            document.getElementById("profileCourseData").innerHTML = resCourse;
            document.getElementById("profileIntakeData").innerHTML = resIntake;
            document.getElementById("lecturerProfile").style.display = "none";
         } else if (stateType === "lecturer") {
               let resDepartment = res.department;
               let resOffice = res.office_location;
               let resPhone = res.phone;
               document.getElementById("profileDepartData").innerHTML = resDepartment;
               document.getElementById("profilePhoneData").innerHTML = resPhone;
               document.getElementById("profileOfficeData").innerHTML = resOffice;
               document.getElementById("studentProfile").style.display = "none";
            } 
         document.getElementById("profileLoader").style.display = "none";
         document.getElementById("profileBody").style.display = "block";
      };
      profile.send("getUserProfile");
   } else {
         document.getElementById("lecturerProfile").style.display = "none";
         document.getElementById("studentProfile").style.display = "none";

         document.getElementById("profileNameData").innerHTML = stateName;
         document.getElementById("profileEmailData").innerHTML = stateEmail;
         document.getElementById("profileTypeData").innerHTML = stateType;

         document.getElementById("profileLoader").style.display = "none";
         // document.getElementById("profile_subjectSection").style.display = "none";         
         document.getElementById("profileBody").style.display = "block";
      }
}

function changePass() {
   let passChange1 = document.getElementById("changePass").value; 
   let passMessage = document.getElementById("createSlotMessage");
   if (passChange1.length < 5) {
      event.preventDefault();
      let message = "<p class='text-warning error_message'><strong>Password is too short!</strong></p>";
      passMessage.innerHTML = message;
   } else {
      let param = "changePassword="+passChange1
      let changePass = new XMLHttpRequest;
      changePass.open("POST","../php/userManagement.php",true);
      changePass.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      changePass.onload = function() {
         let res = changePass.response;
         console.log(res);
         let message = "<p class='text-success success_message'><strong>Password change accepted!</strong></p>";
         passMessage.innerHTML = message;
         setTimeout(() => {
            window.location.href="profile.html";
         }, 1500);
      }

      changePass.send(param);
   }

   
}
   

