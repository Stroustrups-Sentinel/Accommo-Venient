<!--
    -- #filename       : login.php      | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- i gotta say , although other pages may be of SECURITY concern....but This Right here is the MONEYBANK
    --  ...if it gets wrong here, then its all pain, pain and pain
-->

<?php

function initializeSession($userTypeQuery){
    session_start();
    #prepare statement
   if( $sessionInfoArr = mysqli_fetch_row($userTypeQuery)){
    #first_name,email,user_type
    $_SESSION["firstname"] = $sessionInfoArr[0];
    $_SESSION["email"] = $sessionInfoArr[1];
    $_SESSION["usertype"] = $sessionInfoArr[2];
    $_SESSION["userID"] = $sessionInfoArr[3];
   }else{
       echo '<br><h2 class="error">Error on starting session please retry login.</h2>';
   }
}

#-*-*-**-*-**--------*-*-*-*-*-**-*-*-*-*-*-*-*-*-*-*-*-
//connection to db
    $serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
    $connectDB = mysqli_connect($serverName,$dbuser,$dbpass,$dbName);


if($_SERVER["REQUEST_METHOD"] == "POST" ){

   

        //distinguish between the Registering user and the Logging in user
        //debug: echo "login.php here ";
        // -- LOGING-IN USER ------------------------------------******
        if(isset($_POST['email_login'])){
                 #GET RAW VALUES
                $rawEmail_lgin = strtolower( trim($_POST["email_login"]) );
                 #clean the values
            
                $cleanEmail_lgin = mysqli_real_escape_string($connectDB,filter_var($rawEmail_lgin,FILTER_VALIDATE_EMAIL));
                $securePasswrd_lgin = md5( $_POST["password_login"] );
                #login 
                $lgin_query = 'SELECT *
                               FROM users 
                               WHERE users.email ="'.$cleanEmail_lgin.' " '. 
                              'AND  users.passkey ="'.$securePasswrd_lgin.'" ';
                $login = mysqli_query($connectDB,$lgin_query);
                if ( mysqli_num_rows($login) > 0 ){
                    echo '<br><p class="success"> User has been found </p>';
                    //redirecting user to their dashboard
                    $type = "landlord"; 
                    $userTypeQuery = mysqli_query($connectDB,'SELECT first_name,email,user_type,user_id FROM users WHERE users.email ="'.$cleanEmail_lgin.'"'.' AND users.user_type="'.$type.'";');
                            #landlady | landlord
                            if(mysqli_num_rows($userTypeQuery) > 0){
                                echo '<br/><p class="success">landlord user has been found</p>';
                                initializeSession($userTypeQuery);
                                if(mysqli_close($connectDB)){echo '<br/><p class="inform">DB connection has been closed</p>';};
                                #debug : header("Location: ../dashboard-landlord.html");
                                 echo '<script> window.location = "dashboard-landlord.html"; </script>';
                            }else{
                                echo '<br/><p class="error"> Error occurred on searching through <b>Land Owners</b></p>';
                            }
                   $userTypeQuery = mysqli_query($connectDB,'SELECT first_name,email,user_type,user_id FROM users WHERE users.email ="'.$cleanEmail_lgin.'"'.' AND users.user_type="student";');
                             #student
                              if(mysqli_num_rows($userTypeQuery)>0){
                                echo '<br/><p class="inform">student user has been found</p>';
                                initializeSession($userTypeQuery);
                                if(mysqli_close($connectDB)){echo '<br/><p class="inform">DB connection has been closed</p>';};
                                #debug : header("Location: ../dashboard-regular.html");
                                echo '<script> window.location = "dashboard-regular.html"; </script>';
                            }else{
                                echo '<br/><p class="error"> Error occurred on searching through <b>Students</b></p>';
                            }
                    
                }else{
                    echo '<br/><p class="error">Error: wrong email or password !.</p>';
                         #remove this --------------------------------------------
                } 
            
           //debug: echo '<br/><p class="success">done with the logging in !</p>';
           mysqli_close($connectDB);

}elseif (isset($_POST['name'])) {
             // -- REGISTER USER ------------------------------------******
            echo "\n >> registering !"; //remove here
            #GET RAW VALUES
                $rawName = trim($_POST["name"]);
                $rawMidnames = trim($_POST["midnames"]);
                $rawSurname = trim($_POST["surname"]);
                $rawEmail = strtolower(trim($_POST["email"]));
                $rawuserType = trim($_POST["Landlord_or_student"]);
                $rawPhone = trim($_POST["phone"]);
                $rawDOB = trim($_POST["dob"]);
                $rawGender = trim($_POST["male_or_female"]);
                $rawAddress = trim($_POST["address"]);
                
                $securePasswrd_reg = md5($_POST["password"]);
                
             #Filtering, Sanitizing and Validating
                $cleanName = filter_var($rawName,FILTER_SANITIZE_STRING);
                    if(!(!($rawMidnames) or $rawMidnames == ' ')){
                        $cleanMidnames =  filter_var($rawMidnames,FILTER_SANITIZE_STRING);}
                    else{
                        $cleanMidnames = "NULL";
                    }
                $cleanSurname = filter_var($rawSurname,FILTER_SANITIZE_STRING);
                $cleanEmail = filter_var($rawEmail,FILTER_VALIDATE_EMAIL);
                $cleanUserType = filter_var($rawuserType,FILTER_SANITIZE_STRING);
                $cleanPhone = filter_var($rawPhone,FILTER_SANITIZE_STRING);
                $cleanDOB  = filter_var($rawDOB,FILTER_SANITIZE_STRING);   
                $cleanGender = filter_var($rawGender,FILTER_SANITIZE_STRING);     
                $cleanAddress = filter_var($rawAddress,FILTER_SANITIZE_STRING);
                $userId = "NULL";
                    #register
                    $checkEmailQuery = 'SELECT * FROM users WHERE users.email ="'.$cleanEmail.'";';
                    $checkEmailExists = mysqli_query($connectDB,$checkEmailQuery);
                    if( mysqli_num_rows( $checkEmailExists ) > 0 ){
                        echo '<br/><p class="warn">email already exists, try using another one.</p>';
                    }else{
                        $reg_query = 'INSERT INTO users( user_id, user_type, first_name, mid_name, surname, gender, birthdate, email, passkey, phone, physical_address)
                                      VALUES ('.$userId.',"'.$cleanUserType.'","'.$cleanName.'",'.$cleanMidnames.',"'.$cleanSurname.'","'.$cleanGender.
                                      '","'.$cleanDOB.'","'.$cleanEmail.'","'.$securePasswrd_reg.'","'.$cleanPhone.'","'.$cleanAddress.'")';

                        $register = mysqli_query($connectDB,$reg_query);

                            if($register){
                                echo '<br/><p class="success"> User has been Successfully added !.</p>';
                                }
                    }
                    mysqli_close($connectDB);
        }
    }else{
        echo "unexpected error !";
    }
?>