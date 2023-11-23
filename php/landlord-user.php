<!--
    -- #filename       : landlord-user.php  | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- for the landlords dashboard and house management, security maintenance as usual security

-->

<?php
#functions
 function initializeEdit($cleanVar,string $columnName,PDO $connectDB){
    #variable needed for the query
    $setColumn = '';
    switch ($columnName) {#assgning column name
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
            echo '<br/><p class="error">error: bad information supplied for column request</p>';
            break;
    }
    #create the query
    if(strlen($setColumn)>0){
    $editQuery = "UPDATE users SET {$setColumn} = ?  WHERE first_name = ? AND email = ? AND passkey = ? ;";
        #prepare query
        if($updateInfo = $connectDB->prepare($editQuery)){
            #execution
            $updateInfo->execute([$cleanVar,$_SERVER['extractL']->first_name,$_SERVER['extractL']->email,
            $_SERVER['extractL']->passkey]);#i decided to use these because they were much safer since they came from the database
            $rowNum = $updateInfo->rowCount();
                if($rowNum > 0){#checking rows
                      echo '<br/><h4 class="anim-fade">Updating '.$columnName.' was a success, how convenient "&smile;".</h4>';
                }else{#row update failure
                      echo '<br/><p class="error">error: update was not successful "&frown;"</p>';
                }
        }else{ echo '<br/><p class="error">error: query preparation for update was not successful</p>';}

    }else{ echo '<br/><p class="error">error: column-string does not meet the requirements !</p>'; }
    #done
}

function HtmlDisplay(){
    #displaying info for the user,  info will be extractLed from the session object
            #rooting out data to be processed first
                    #profile picture
            if(empty($_SESSION['profilePic']->img_location)){
                $img_location = 'resources/logo.png';
            }else{
                $img_location = $_SESSION['profilePic']->img_location;
            }
            #add login details JS Object
    echo '<script>
           
          var LLDetailsObj = { name :"'.$_SESSION['extractL']->surname.' .'.strtoupper($_SESSION['extractL']->first_name[0]).'",
                           email : "'.$_SESSION['extractL']->email.'"
                        };
          $("#user-details").empty().append(`<p id="LLName">`+LLDetailsObj.name+`</p> <p id="LLEmail">`+LLDetailsObj.email+`</p>`);
                        
         </script>';
                #date
    echo '<div class="user-details-prof">
            <div class="prof-pic-panel">
                <div class="prof-img">
                    <img src="'.$img_location.'" alt="Profile Picture" ></div>
                    <br/>
                <div class="prof-btn">
                    <button class="prof-pb" id="prof-add">Add New Profile</button>
                    <button class="prof-pb" id="prof-del">Remove Profile</button>
                </div>

            </div>
            <div class="prof-inf-panel">
                <div class="prof-dat">
                    <p>First Name </p>  <input type="text" value="'.$_SESSION['extractL']->first_name.'" name="fname" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Mid Name(s) </p> <input type="text" value="'.$_SESSION['extractL']->mid_name.'" name="mname" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Surname </p> <input type="text" value="'.$_SESSION['extractL']->surname.'" name="sname" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Gender </p> <input type="text" value="'.strtoupper($_SESSION['extractL']->gender[0]).'" readonly="true"/> 
                </div>
                            
                <div class="prof-dat">
                <p>Date Of Birth </p> <input type="text" value="'.date('d M Y  D',strtotime($_SESSION['extractL']->birthdate)).'" readonly="true"/> 
                </div>
                            
                <div class="prof-dat">
                <p>Phone </p> <input type="tel" value="'.$_SESSION['extractL']->phone.'" name="phone" readonly="true"/> <button class="edit">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Email </p> <input type="email" value="'.$_SESSION['extractL']->email.'" name="email" readonly="true"/> <button class="edit">Edit</button>
                </div>
                             
                <div class="prof-dat">
                <p>Physical Address </p> <address name="address">'.$_SESSION['extractL']->physical_address.'</address> <button class="edit edit-address">Edit</button>
                </div>
                            
                <div class="prof-dat">
                <p>Password </p> <input type="password" value="********" name="password"  readonly="true"/> <button class="edit">Edit</button>                   
                </div>
                            
                <div class="prof-dat">
                <p>Please enter password to confirm changes </p> <input class="confirm-changes" type="password" name="confirmPassword" placeholder="confirm changes Password" minlength="8" maxlength="40" required/> <button class="edit pass-btn">Confirm Changes</button>
                </div>
                <div class="prof-dat" id="pass-status">
                <p></p>
                </div>
            </div>
        </div>'
          ;
          # print the houses of the landlord
echo '  <div class="houses">';

echo $_SESSION['houseValues'];
         # add house option
echo '<div class="house-item last-hi"><p class="void"></p><p class="void"></p>  <button class="add-house" title="click to Add another House"><b>+</b>Add new house to list</button> </div>';
          #after finishing the loop ,close the div tag
echo '</div>' ;

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
                #debug : echo '<br/><p class="inform anim-fade">user has been found</p>';
                        #load the data  for the user`s html page into an object
                        $dataQuery = 'SELECT * FROM users  WHERE first_name = ? AND email = ? AND user_type = ? ;';
                            if($dataextractL = $connectDB->prepare($dataQuery)){
                                $dataextractL->execute([$_SESSION["firstname"],$_SESSION["email"],$_SESSION["usertype"]]);
                                    if($extractL = $dataextractL->fetch(PDO::FETCH_OBJ)){
                                        #logged in already should be set to true
# should be re-activated:              $_SESSION['loggedIn'] = true;
                                           $_SESSION["extractL"] = $extractL; #extract info

                                                #extract profile picture
                                                if($profilePicQuery = $connectDB->prepare("SELECT pictures.user_id, pictures.img_location FROM pictures WHERE user_id = ? AND img_desc = 'profile'"))
                                                 {
                                                    if( $profilePicQuery->execute([$_SESSION['extractL']->user_id]) )
                                                    {
                                                        $_SESSION['profilePic'] = $profilePicQuery->fetch(PDO::FETCH_OBJ);
                                                    }else{ echo '<br><p class="error">Error: executing profile pictures query failed</p>';}
                                                   # $_SESSION['profilePic']->img_location = 'resources/logo1.jpg';//$profilePicObj->img_location;
                                                #    var_dump($_SESSION['profilePic']);
                                                }else{ echo '<br><p class="error">Error: preparing profile pictures failed</p>';}
                                                #give houses no value first
                                                 $_SESSION['houseValues'] = '<br/><p class="inform"> No houses were found.</p>';
                                                #extract house list
                                                if($housesQuery = $connectDB->prepare("SELECT * FROM house  WHERE house.user_id = ? ;")){
                                                  #debug:  echo '<br/><p class="success">Preparing for house was a success and session val is : '.$_SESSION['extractL']->user_id.'</p>';
                                                    if($val = $housesQuery->execute([$_SESSION['extractL']->user_id])){
                                                        $houseNum = $housesQuery->rowCount();
                                                        #debug: echo '<br/><p class="inform">Number of houses is : '.$houseNum.'</p>';
                                                        if( $houseNum > 0 ){
                                                           $houseValues = $housesQuery->fetchAll(PDO::FETCH_OBJ);
                                                           #debug: $_SESSION['houseValues'] = json_encode($houseValues);
                                                           #give out values
                                                           $_SESSION['houseValues'] = ' ';
                                                           for($i = 0;$i < count($houseValues);$i++) {
                                                                 $picHouseId = $houseValues[$i]->house_id;
                                                                #get house main pic
                                                                   if($houseMainPicQuery = $connectDB->prepare('SELECT img_desc, img_location,last_update FROM pictures WHERE house_id = ? AND img_desc = "house-main" ')){
                                                                       if( $houseMainPicQuery->execute([$picHouseId])){
                                                                            $houseMainPicObj = $houseMainPicQuery->fetch(PDO::FETCH_OBJ);
                                                                        }
                                                                   }
                                                                #get house pictures per house
                                                                    $housePicsHtml = ''; #generate pics
                                                                    if($housePicsQuery = $connectDB->prepare('SELECT img_desc, img_location,last_update FROM pictures WHERE house_id = ? AND NOT img_desc = "house-main" ')){
                                                                        if( $housePicsQuery->execute([$picHouseId])){
                                                                            $housePicsArr = $housePicsQuery->fetchAll(PDO::FETCH_OBJ);
    
                                                                            #loop through Values
                                                                            for($j=0;$j < count($housePicsArr);$j++){
                                                                                $housePicsHtml .= '<div class="house__pic">
                                                                                <button class="del-button" title="delete Picture">X</button>
                                                                                <img src="'.$housePicsArr[$j]->img_location.'" class="house__pic-img" alt="house-'.($j+1).'-picture" hseN="'.$picHouseId.'" hseL="'.$housePicsArr[$j]->img_location.'"/>
                                                                                <div class="house__pic-desc" ><p><b>'.$housePicsArr[$j]->img_desc.'</b> <br/> '.date("H:i  D d-M-Y",strtotime($housePicsArr[$i]->last_update)).'</p></div>
                                                                                </div>';

                                                                            }$housePicsHtml .= '<button class="house__pic picTxt_div" hseid="'.$picHouseId.'" title="click to Add more Pictures of your House"> <p class="house__picTxt"><b>+</b> <br/>Picture</p> </button>'; 
                                                                        }else{ echo '<br><p class="error">Error: getting profile pictures failed</p>';}

                                                                    }else{ echo '<br><p class="error">Error: preparing house pictures failed</p>';}
                                                                    $houseMainPic = isset($houseMainPicObj->img_location) ? $houseMainPicObj->img_location : 'resources/logo3.jpg';
                                                            $_SESSION['houseValues'] .= '<div class="house-item">
                                                                <div class="house-main-pic"> <img src="'.$houseMainPic.'" title="House Main picture" /> </div>

                                                                <div class="house-info"><p>
                                                                  <b>Location</b> : '.$houseValues[$i]->geo_location.'<br/>
                                                                  <b>Address</b> : '.$houseValues[$i]->physical_address.'<br/>
                                                                  <b>For</b> : '.$houseValues[$i]->accomm_type.'
                                                                  </p>
                                                                  <div  class="house-info-btn">
                                                                    <button class="house-edit" hseId="'.$houseValues[$i]->house_id.'" title="Edit house Information">Edit Info</button>
                                                                    <button class="house-del" hseId="'.$houseValues[$i]->house_id.'" hseimg="'.$houseMainPic.'" title="Remove House permanently">Remove House</button>
                                                                  </div>
                                                                </div>
                                                                <script>
                                                                //adding house details to object
                                                                housesObj["'.$houseValues[$i]->house_id.'"] = {
                                                                    houseid: "'.$houseValues[$i]->house_id.'",
                                                                    address: "'.$houseValues[$i]->physical_address.'",
                                                                    accomtype: "'.$houseValues[$i]->accomm_type.'",
                                                                    accommodates:"'.$houseValues[$i]->accommodates.'",
                                                                    rent:"'.$houseValues[$i]->rent.'",
                                                                    essentials:"'.$houseValues[$i]->essentials.'",
                                                                    offered:"'.$houseValues[$i]->offered.'",
                                                                    location:"'.$houseValues[$i]->geo_location.'"

                                                                };
                                                                </script>

                                                                <div class="house-pics">'.$housePicsHtml.'</div>
                                                                                        </div>';
                                                            #debug : var_dump($houseValues);
                                                           }

                                                        }
                                                    }else{
                                                        $_SESSION['houseValues'] = '<br/><p class="inform"> No houses were found.</p>';
                                                    }

                                                    #debug: echo '<br/><p class="success">$val is '.$val.'</p>';


                                                }else{ echo '<br><p class="error">Error: preparing houses failed</p>';}

                                            #debug: echo '<br><p class="success anim-fade">done loading user information...</p>';
                                            #after $extractL has value, now you can utilize and display user info
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
        #echo json code
        echo '<br/><p class="error">Could not login :<br/>unrecognized account information</p><br/><p>
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
#debug: echo '<br/><p style="color: coral; font-family: monospace;font-style: italic;"> End of landlord-user.php file</p>';
#debug: var_dump(isset($_SESSION['extractL'])? $_SESSION['extractL']:'landlord var is empty');
//session_unset(); #just kept it for some debugging  purposes
#debug: var_dump(isset($_SESSION['profilePic']) ? $_SESSION['profilePic']:'pic var is empty');

?>