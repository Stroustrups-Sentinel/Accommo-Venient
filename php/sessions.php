<!--
    -- #filename       : sessions.php     | developed using PHP Version 7.3.3       
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- contains session management procedures
-->
<?php
#start session if not started
if( session_status() == PHP_SESSION_NONE ){
    session_start();
}

#check request type
if(($_SERVER["REQUEST_METHOD"] == "POST") ){

    #sanitize post data
    if( (isset($_POST['logoutString']))){
        $rawLogoutString = trim($_POST['logoutString']);
        $cleanLogoutString = filter_var($rawLogoutString,FILTER_SANITIZE_STRING);
        if($cleanLogoutString == "logout"){

            #check for user session type and logout
            if(!(empty($_SESSION['extract'])) && ($cleanLogoutString === "logout")){
                session_destroy();    #delete session
                echo 'done';
            }elseif(!(empty($_SESSION['extractL'])) && ($cleanLogoutString === "logout")){
                session_destroy();   #delete session
                echo 'done';
            }
        }
    }
    if( (isset($_POST['checkLogin']))){

        
        $rawCheckLoginString = trim($_POST['checkLogin']);
        $cleanCheckLoginString = filter_var($rawCheckLoginString,FILTER_SANITIZE_STRING);
        #check login type
        if($cleanCheckLoginString == "check"){

            
            #check for user session type and redirect
            if(!(empty($_SESSION['extract'])) && ($cleanCheckLoginString === "check")){
                
                echo '<script>
                    window.location = "dashboard-regular.html";
                </script>';  
               
            }elseif(!(empty($_SESSION['extractL'])) && ($cleanCheckLoginString === "check")){
                echo '<script>
                    window.location = "dashboard-landlord.html";
                </script>';  
                 
            }
        }
    
    }


    

    

}

?>