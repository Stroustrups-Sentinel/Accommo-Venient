<!--
    -- #filename       : forgot-password.php        | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- this will aid the users on recovering their passwords and should ensure SECURITY as usual
    -- so vaidations should be perfect and nicely filtered
    -- email and sms recovery enablement
-->

<?php
    //connection to db
    $serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
    $connectDB = mysqli_connect($serverName,$dbuser,$dbpass,$dbName);

    //checking information
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #debug: echo "method is really post";
        #sanitize email
        $rawEmail = trim($_POST["recovery_email"]);
        $cleanEmail = filter_var($rawEmail,FILTER_VALIDATE_EMAIL);
        #check for email in database
        $emailSearchQuery = "SELECT first_name,surname,email,passkey FROM users WHERE users.email = '$cleanEmail';";
        $emailSearch = mysqli_query($connectDB,$emailSearchQuery);
        if($emailSearch){
            $rowCount = mysqli_num_rows($emailSearch);
            if($rowCount > 0){
                 #root out information to use
                    $name = '';$suname = '';$passkey='';$email='';
                 if($rowCount == 1){
                     $userInfoArr = mysqli_fetch_assoc($emailSearch);
                    
                     $name = $userInfoArr['first_name'];
                     $surname = $userInfoArr['surname'];
                     $passkey = $userInfoArr['passkey'];
                     $email = $userInfoArr['email'];
                 }
                 $recoveryString = $passkey;# was supposed to be converted to another more random and secure value
                echo '<br/><p class="inform">email has been found '.$cleanEmail.'</p>';
               
                #send email to user
                    $recipient = $cleanEmail;
                    $subject = 'Password Recovery';
                    $headers = implode("\r\n",[ 'From: Accommo Venient-Website <accommov@support.home>',
                    'Reply-To: no-reply@accommovenient.home','MIME-Version: 1.0','Content-Type: text/html; charset=ISO-8859-1','X-Mailer: PHP/' . PHP_VERSION]);#edited code snippet from `PHP Notes for professionals`
                    $message = '
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Recovery-Password-Email</title>
                        <style>
                            body{ background-color: #f5f5f5;
                                  font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif;
                                  padding: 2%;
                                }
                            header{ background-color: #0f1626;      font-family: Verdana, Geneva, Tahoma, sans-serif;   font-size: 1.3em;
                                font-weight: bold;      padding: 10%;        padding-bottom: 2%;     margin: -6%;        margin-bottom: unset;
                                box-shadow: #FF533D;    border-bottom: inset;       border-color: #FF533D;      justify-content: space-around;
                                image-rendering: optimizeQuality;
                            }
                            header div{ flex-flow: row;     align-content: flex-end;        flex-direction: row;        flex-wrap: wrap-reverse;
                                justify-content: center;        align-self: auto;       flex-grow: 1;
                            }
                            header div a{ margin: 5px;      padding:20px;       padding-top: 0px;
                            }
                            header :hover{ color: #FF533D;  transition-duration: 0.5s;
                            }
                            header .header-links{ margin: 1%;
                            }
                            a{ text-decoration: none;color: #F5F5F5;
                            }
                           hr{
                                width: inherit;
                                color:#FF533D;
                                border: 0.1em dashed;
                            }
                            .message-box{
                                padding: 12% 30% 35% 10%;
                                margin: 2%;
                                background-color: #aaaaaa40;
                            }
                            .inner-box{
                                background-color: whitesmoke;
                                padding: 3%;
                                border: #0f1626 dashed 0.2em;
                                text-align: justify;
                                word-wrap: inherit;
                                
                            }
                            div.mini-alert{
                                 padding-top: 2%;
                                 padding-left: 20%;
                                 color :#FF533D;
                                 font-weight: bold;
                                 font-style: oblique;
                                 display: flex;
                                 align-content: center;
                                 text-align: center;
                             }h2{
                                color: red;
                            }
                            footer{
                            background-color: #0f1626;
                            color: #F5F5F5;
                            font-family: inherit;
                            padding: 5%;
                            padding-top: 4%;
                            padding-bottom: 0%;
                            margin: -6%;
                            margin-top: unset;
                            border-top: outset ;
                            border-color: #AB987A;
                            text-align: center;
                            }
                            footer div  h3{
                                color: #FF533D;
                            }
                           
                        </style>
                    </head>
                    <body>
                            <header>
                                <div>   <a>Accommo-Venient</a><a>|  Password Recovery</a>   </div>
                            </header>
                            <div class="mini-alert">
                            <h3>&times; Please Note  &twoheadrightarrow; the &quot;recovery-code&quot;  expires in <h2>&nbsp; 5 minutes !</h2></h3>
                            </div>
                            <div class="message-box">
                                <div class = "inner-box">
                                <p>
                                    <em> Name  &nbsp;: '.$name." ".$surname.'<br/>
                                    <br> Email : '.$email.'<br/>
                                    <br> Recovery-Code :<br/><br/>
                                    <textarea  readonly style="font: inherit; width:95%;
                                     border: inherit; background-color:#AB987A50; padding: 2%; overflow:auto;">'
                                    .$passkey.'</textarea></em>
                                    <hr/>
                                    <b> please change your password to 
                                    a new one so that you can keep your account secure !.</b>
                                </p>
                                </div>
                            </div>
                            <footer>
                            <div class="foot-text">
                                Enjoy the service you deserve and experience the advantages you have at hand by simply using accommo-venient.
                                <h3>"convenient accommodation"</h3>
                                  This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S 
                            </div>
                                <div class="lower-footer">
                                 <p>&nbsp; Accommo-Venient &nbsp; Copyright &copy;2020  &nbsp; <a href="#">Top</a> </p> 
                                </div>
                            </footer>
                    </body>
                    </html>
                    ';
                    #done with needed information,now its time to send
                   if( mail($recipient,$subject,$message,$headers)){
                      echo '<br/><p class="inform"> message was sent successfully ! use the recovery-code to reclaim account</p>';
                    }{
                       echo '<br/><p class="error"> error: message not sent !';
                    }

                    }elseif($rowCount == 0){
                        echo '<br><p class="error">error: unfortunately email "<b>'.$cleanEmail.'</b>" could not be found !, [i] maybe check if you typed it correct</p>';
                    }

            }else{
                echo '<br/><p class="error">error on connection to database '.mysqli_error($connectDB).'</p>';
            }

           
    }else{
        echo '<br/><p class="error">Error: bad Information ! ,unstable security detected</p>';
    }
if(mysqli_close($connectDB))echo '<br/><p class="inform">process complete.</p>';
#debug: echo "<br/><br/>END OF PHP PASSWORD RECOVERY";

?>