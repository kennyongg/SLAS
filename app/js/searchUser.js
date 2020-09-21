//search user upon input
let searchBar = document.getElementById("search_bar");
let searchResultBox = document.getElementById("search_resultBox");

searchBar.addEventListener('keyup', function(event){
    var searchUser = searchBar.value;
    let searchResult = document.getElementById("search_result");
    if (searchResult) {
        searchResult.parentNode.removeChild(searchResult);
    }

    if (searchUser) {
        let param = "searchUser=" + searchUser;
        let userSearch = new XMLHttpRequest;
        userSearch.open("POST", "/SE_Assignment/php/searchUser2.php", true);
        userSearch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        userSearch.onload = function () {
            let res = JSON.parse(userSearch.response);
            if (res["error"]) {
                let message = "<p id='search_result' class='text-danger'>No results found.</span>";
                searchResultBox.innerHTML = message;
            } else {
                let message = "<table id='search_result'>";

                res.forEach(function(item) {
                    message += '<tr><td><a id="' + item + '" href="editProfile.html">' + item + '</a></td></tr>';
                });

                message += '</table>';
                searchResultBox.innerHTML = message;
            }
        }
        //Alert if requests fail
        userSearch.onerror = function () {
            alert("Error connecting with server");
        };
        userSearch.send(param);
    }
});


searchResultBox.addEventListener('click', function(event) {
    let resultID = event.target.id;
    let userLink =  document.getElementById(resultID);
    if (userLink) {
        var userEmail = userLink.innerHTML;

        // alert(userEmail);

        if (userEmail) {
            let param = "userEmail=" + userEmail;
            let searchProfile = new XMLHttpRequest;
            searchProfile.open("POST", "/SE_Assignment/php/searchUser2.php", true);
            searchProfile.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            searchProfile.onload = function () {
                let res = JSON.parse(searchProfile.response);
                if (! res["error"]) {
                    localStorage.setItem('selectedUserEmail', res["id"]);
                    localStorage.setItem('selectedUserAcc', res["acc_type"]);
                    localStorage.setItem('selectedUserActive', res["active"]);
                }
            }
            //Alert if requests fail
            searchProfile.onerror = function () {
                alert("Error connecting with server");
            };
            searchProfile.send(param);
        }
    }
});

