<!--
    -- #filename       : regular-user.php  | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- im gonna make sure that this will be the users access server side script
    -- security should be of main concern
    -- this time i'll be jumping into PDO style and no boomer style

-->

<?php
#functions
 function initializeEdit($cleanVar,string $columnName,PDO $connectDB){
    #variable needed for the query
    $setColumn = '';
    switch ($columnName) {#assigning column name
        case 'firstname':
            $setColumn = "first_name";break;
        case 'midname':
            $setColumn = "mid_name";break;
        case 'surname':
            $setColumn = "surname";break;
        /* case 'gender':
            # code...
            break; */
        case 'birthday':
            $setColumn = "birthdate";break;
        case 'password':
            $setColumn = "passkey";break; 
        case 'email':
            $setColumn = "email";break;
        case 'phone':
            $setColumn = "phone";break;
        case 'address':
            $setColumn = "physical_address";break;
        default:
            echo '<p class="error">error: bad information supplied for column request</p>';
            break;
    }
    #create the query
    if(strlen($setColumn)>0){
    $editQuery = "UPDATE users SET {$setColumn} = ?  WHERE first_name = ? AND email = ? AND passkey = ? ;";
        #prepare query
        if($updateInfo = $connectDB->prepare($editQuery)){
            #execution
            $updateInfo->execute([$cleanVar,$_SERVER['extract']->first_name,$_SERVER['extract']->email,
            $_SERVER['extract']->passkey]);#i decided to use these because they were much safer since they came from the database
            $rowNum = $updateInfo->rowCount();
                if($rowNum > 0){#checking rows
                      echo '<h4 class="anim-fade">Updating '.$columnName.' was a success, how convenient "&smile;".</h4>';
                }else{#row update failure
                      echo '<p class="error">error: update was not successful "&frown;"</p>';
                }
        }else{ echo '<p class="error">error: query preparation for update was not successful</p>';}

    }else{ echo '<p class="error">error: column-string does not meet the requirements !</p>'; }
    #done
}
function HtmlDisplay(){
    #displaying info for the user,  info will be extracted from the session object
            #rooting out profile picture first
            if($_SESSION['extract']->img_location == NULL ){
                $img_location = 'resources/logo3.jpg';
            }else{
                $img_location = $_SESSION['extract']->img_location;
            }

            echo '<script>
           
            var LLDetailsObj = { name :"'.$_SESSION['extract']->first_name.' .'.strtoupper($_SESSION['extract']->surname[0]).'",
                             email : "'.$_SESSION['extract']->email.'"
                          };
            $("#user-details").empty().append(`<p id="LLName">`+LLDetailsObj.name+`</p> <p id="LLEmail">`+LLDetailsObj.email+`</p>`);
                          
           </script>';        

    echo '<div class="user-details-ru">
            <div class="prof-pic-panel">
            <div class="prof-img"><img src="'.$img_location.'" alt="Profile Picture" ></div>
            <br/>
            <div class="prof-btn">
            <button class="prof-pb" id="prof-add">Add New Profile</button>
            <button class="prof-pb" id="prof-del">Remove Profile</button>
            </div>

         </div>
          <div class="prof-inf-panel">
                <div class="prof-dat">
                    <p>First Name </p> <input type="text" name="firstname" value="'.$_SESSION['extract']->first_name.'" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Mid Name(s) </p> <input type="text" name="midname" value="'.$_SESSION['extract']->mid_name.'" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Surname </p> <input type="text" name="surname" value="'.$_SESSION['extract']->surname.'" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Gender </p> <input type="text" value="'.strtoupper($_SESSION['extract']->gender[0]).'" readonly="true"/> 
                </div>
                            
                <div class="prof-dat">
                <p>Date Of Birth </p> <input type="text" value="'.date('d M Y  D',strtotime($_SESSION['extract']->birthdate)).'" readonly="true"/> 
                </div>
                            
                <div class="prof-dat">
                <p>Phone </p> <input type="text" name="phone" value="'.$_SESSION['extract']->phone.'" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Email </p> <input type="email" name="email" value="'.$_SESSION['extract']->email.'" readonly="true"/> <button class="edit">Edit</button>
                </div>
                             
                <div class="prof-dat">
                <p>Physical Address </p> <address name="address">'.$_SESSION['extract']->physical_address.'</address> <button class="edit edit-address">Edit</button>
                
                </div>
                            
                <div class="prof-dat">
                <p>Password </p> <input type="password" name="password" value="********"  readonly="true"/> <button class="edit">Edit</button>                   
                </div>
                            
                <div class="prof-dat">
                <p>Please enter password to confirm changes </p> <input class="confirm-changes" name="confirmPassword" type="password" placeholder="confirm changes Password" minlength="8" maxlength="40" required/> <button class="edit pass-btn">Confirm Changes</button>
                </div>
               
         </div>
          </div>';

}

#*-*-****-*-*-*-*-*-*-*-*-----------------********------*****-----------*-*-*-
//connect.php
$serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
$DSN = ("mysql:host={$serverName};dbname={$dbName}");
#debug: var_dump($DSN);
$connectDB = new PDO($DSN,$dbuser,$dbpass);

#start session
session_start();  #first_name,email,usertype,loggedIn

#required file(s)
require('sessions.php');


        #check if user has not logged in
if(!(isset($_SESSION["loggedIn"]))){        
        #check session data if it exists
    if(isset($_SESSION["firstname"])){

        #debug : var_dump($_SESSION);
        
        #check if session data is real
        $verifyQuery = 'SELECT COUNT(*) FROM users WHERE first_name = ? AND email = ? AND user_type = ? ;';
        if($verifySession = $connectDB->prepare($verifyQuery)){
                $verifySession->execute([$_SESSION["firstname"],$_SESSION["email"],$_SESSION["usertype"]]);
                $numOfRows = $verifySession->rowCount();
                #load user account data and keep session data
                    if($numOfRows == 1){
                        #debug: echo "<br/>user has been found";
                        #load the data  for the user`s html page into an object
                        $dataQuery = 'SELECT first_name,mid_name,surname,gender,birthdate,phone,email,physical_address,img_location FROM users LEFT JOIN pictures ON users.user_id = pictures.user_id  WHERE first_name = ? AND email = ? AND user_type = ? ;';
                            if($dataExtract = $connectDB->prepare($dataQuery)){
                                $dataExtract->execute([$_SESSION["firstname"],$_SESSION["email"],$_SESSION["usertype"]]);
                                    if($extract = $dataExtract->fetch(PDO::FETCH_OBJ)){
                                        #logged in already should be set to true
                                        $_SESSION['loggedIn'] = true;
                                           $_SESSION["extract"] = $extract; // the global but later made a session variable had no use before
                                           //since my var had the scope i wanted it to have but after a reload it was something else
                                            #debug: echo '<br><h4 class="anim-fade"> loading user information...</h4>';
                                            #after $extract has value, now you can utilize and display user info
                                            #echo json code
                                            HtmlDisplay();

                                    }else{  echo '<br><p class="error"> Error on getting login information</p>'; }
                                
                            }else{
                                echo '<br><p class="error"> Error on preparing login information</p>';
                            }
                            

                    }elseif($numOfRows > 0 && $numOfRows != 1) {#if the most unlikely event happens
                        echo '<br><p class="error"> user seems to have more than one account, they are :'.$numOfRows.' in total</p>';
                    }else{ echo '<br><p class="error"> User seems not to exist</p>';}#if the user doesn't exists, might be a hack

               
        }else{ echo '<br><p class="error">Error on checking for user</p>';}
        
   
    }else{
         echo '<br/><p class="error">Could not login :<br/>unrecognized account information</p>
         <script>
        window.location = "login.html";
        </script>';
    }
}elseif($_SESSION["loggedIn"]){ #if user has already logged in and is having data processing
        #checking form submission type
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            #process info
            #echo the results after processing
            HtmlDisplay();

        }else{#for other request methods
            echo '<br/><p class="error">error: bad request method </p>';
        }
}else{
    echo '<br/><p class="error">an extreme error occurred: `user could not be checked for login state`</p>';
}
#debug: echo '<p style="color: coral; font-family: monospace;font-style: italic;"> End of regular-user.php file</p>';
#debug: var_dump(isset($_SESSION['extract'])?$_SESSION['extract']: 'extract has no data');
//session_unset(); #just kept it for some debugging  purposes


?>