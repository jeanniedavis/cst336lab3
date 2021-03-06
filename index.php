<!DOCUTYPE html>
<html>
    <head>
        <title>
            Sign Up Page
        </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href ="css/default.css" rel="stylesheet" type="text/css" />

    </head>
    <body>
        <form id="signupForm" action ="welcome.php">
            <h1> Sign Up</h1>
            
            First Name: <input type ="text" name ="fName"><br>
            Last Name: <input type ="text" name ="lName"><br>
            Gender: <input type ="radio" name ="gender" value="m">Male
                    <input type ="radio" name ="gender" value="f">Female<br>
                    
            Zip Code: <input type ="text" id="zip" name ="zip"><br>
            <span id="zipcodeError"></span><br>
            City <span id="city"></span><br>
            Latitude: <span id="latitude"></span><br>
            Longitude: <span id="longitude"></span><br>
            
            State: 
            <select id="state" name ="state">
                <option> Select One</option>
                <!--<option value="ca">California</option>-->
                <!--<option value="ny">New York</option>-->
                <!--<option value ="tx">Texas </option>-->
            </select><br>
            
            Select a County: <select id="county"></select><br>
            Desired Username: <input type ="text" id="username" name="username"><br>
            <span id="usernameError"></span><br>
            Password: <input type ="password" id="password" name="password"><br>
            Password Again: <input type ="password" id="passwordAgain"><br>
            <span id="passwordAgainError"></span><br>
            <input type ="submit" value ="Sign up!">
        </form>

    </body>
     <footer>
            <hr class="footer">
            CST 336 Internet Programming. 2020&copy; Davis<br/>
            <img src="img/csumblogo.png" alt="csumblogo"/>

        </footer>
    <script>
    var usernameAvailable = false;
    
        $(document).ready( async function () {
            let url = `https://cst336.herokuapp.com/projects/api/state_abbrAPI.php`;
            let response = await fetch (url);
            let data = await response.json();
             for (let i = 0; i<data.length; i++) {
               $("#state").append(`<option value = "${data[i].usps}"> ${data[i].state}</option>`);
            }
        });
        //Displaying City from AAPI after typing a zip code
        $("#zip").on("change", async function() {
           // alert($("#zip").val());
           let zipCode =$("#zip").val();
           let url = `https://itcdland.csumb.edu/~milara/ajax/cityInfoByZip.php?zip=${zipCode}`;
           let response = await fetch (url);
           let data = await response.json();
           console.log(data)
           if (data){
               $("#city").html(data.city);
                $("#longitude").html(data.longitude);
                $("#latitude").html(data.latitude);
           }
           else {
                $("#zipcodeError").html("Zipcode not found");
               $("#zipcodeError").css("color", "red");
           }
        
        }); //zip
        $("#state").on("change", async function(){
            //alert ($("#state").val());
            let state = $("#state").val();
            let url = `https://itcdland.csumb.edu/~milara/ajax/countyList.php?state=${state}`;
            let response = await fetch (url);
            let data =await response.json();
            //console.log(data);
        $("#county").html ("<option>Select One</option>");
            for (let i = 0; i<data.length; i++) {
                $("#county").append(`<option> ${data[i].county}</option>`);
            }
        });
        $("#username").on("change", async function() {
            //alert ($("#username").val());
            let username =$("#username").val();
            let url =`https://cst336.herokuapp.com/projects/api/usernamesAPI.php?username=${username}`;
            let response = await fetch (url);
            let data = await response.json();
           // console.log(data);
            if (data.available) {
                $("#usernameError").html("Username available!");
                $("#usernameError").css("color", "green");
                usernameAvailable = true;
                
            }
            else {
                $("#usernameError").html("Username not available!");
                $("#usernameError").css("color", "red");
                usernameAvailable = false;
            }
        }); //username
        $("#signupForm").on("submit", function(e){
            if(!isFormValid()) {
                e.preventDefault();
            }
        });
        function isFormValid(){
            isValid = true;
            if(!usernameAvailable){
                isValid = false;
            }
            if ($("#username").val().length ==0){
                isValid = false;
                $("#usernameError").html ("Username is required");
            }
            if ($("#password").val() != $("#passwordAgain").val()) {
                $("#passwordAgainError").html("Password Mismatch!");
                isValid = false;
            }
            if ($("#password").val().length<6) {
                $("#passwordAgainError").html("Sorry password must be atleast 6 characters");
            }
            return isValid;
        }
    </script>
</html>