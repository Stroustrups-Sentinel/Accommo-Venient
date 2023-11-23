<!--
    -- #filename       : forgot-password.php        | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- this will aid the users on recovering their passwords and should ensure SECURITY as usual
    -- so vaidations should be perfect and nicely filtered
    -- email has been recieved and its now time to login back
       ************* and ive decided to leave the boomer style setting,
       ************* so, for now on i'll be embracing the object oriented mysqli style and have 
       ************* some rest on the procedural style of php
-->

<?php
    //connection to db
    $serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
    $connectDB = new mysqli($serverName,$dbuser,$dbpass,$dbName);


        if($_SERVER["REQUEST_METHOD"] == "POST"){
                $rawEmail = trim($_POST["email"]);
                $rawRecoveryCode = trim($_POST["recoveryString"]);
                $rawPassword = $_POST["newPassword"];
                #sanitizing and validating the php code
                $cleanEmail = filter_var($rawEmail,FILTER_VALIDATE_EMAIL);
                $cleanRecoveryCode = filter_var($rawRecoveryCode,FILTER_SANITIZE_STRING);
                $securePassword = md5($rawPassword);
            if($connectDB->connect_errno > 0){
                    trigger_error($connectDB->connect_error);
                    echo '<br><p class="error" >Connection to DB server Error!</p>';
            }else{

                  #look for row with user
                  $rowFound = false;

                  $searchQuery = 'SELECT user_id,first_name,email FROM users WHERE `email` = ? AND `passkey` = ? ;';
                  $successOnSearch = $connectDB->prepare($searchQuery) ;
                  #debug: echo "<br/>The variables are  EMAIL -: {{$cleanEmail}} ,<br/> RECOVERY CODE -: {{$cleanRecoveryCode}} <br/> PASSWORD -: {{$rawPassword}}<br/><br/>";
                  if($successOnSearch){
                        $successOnSearch->bind_param('ss',$cleanEmail,$cleanRecoveryCode);
                        $successOnSearch->execute();
                        
                            $successOnSearch->bind_result($num,$varName,$varEmail);
                            $successOnSearch->fetch();
                            if($num > 0){
                                 $rowFound = true;
                                 echo '<br> <p class="inform anim-fade"> The details offered have been verified correct !</p>';     
                            }else{ echo '<br><p class="warn"> Details offered seem to be incorrect,[<em>i</em>] please check if there was a typing error. </p>';}
                          $successOnSearch->close(); 
                                               
                  }else{
                        echo '<br><h3 class="error"> Error on searching for users` existence</h3>';
                  }

              if($rowFound){
                      #fetching and changing user password in database
                      $changePasswordQuery = 'UPDATE users SET users.passkey = ? WHERE users.email = ? AND users.passkey = ? ;';
                      if( $changePass = $connectDB->prepare($changePasswordQuery) ){
                          $changePass->bind_param('sss',$securePassword,$cleanEmail,$cleanRecoveryCode);
                          $changePass->execute();
                          echo '<br><p class="success"> password has been successfully changed...</p>';
                          $changePass->close();
                            #redirecting the user to the login page
                            echo '<br><a href="login.html" style="color:#ff533d;" >Changing password was a success, you can now login to your account</a>';
                        }else{
                            echo "<br/>Connection error ".$changePass->error;
                        }
                }

            }

        }else{
                echo '<br><p class="error">Bad Request method!<p>';
            }

       #debug:     echo '<h2 style="color: coral; font-family: monospace;font-style: italic;"> End of activation file</h2>';

    ?>