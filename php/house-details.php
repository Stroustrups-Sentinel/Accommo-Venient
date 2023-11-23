<!--
    -- #filename       : house-details.php      | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- this will generate more house details onto the webpage securely
    -- using PDO
-->

<?php
#connecting to db -> pdo style
$serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
$DSN = ("mysql:host={$serverName};dbname={$dbName}");
$connectDB = new PDO($DSN,$dbuser,$dbpass);
#svg vars
require('svg-resource.php');
session_start();

#checking if data is from a get request
    if($_SERVER['REQUEST_METHOD']=='GET'){
        #extract more details to use on house details
        if(isset($_GET["houseID"])){
            $cleanHouseId = filter_var($_GET["houseID"],FILTER_VALIDATE_INT);
            $cleanUserId = filter_var($_GET["userID"],FILTER_VALIDATE_INT);
            $cleanAccommType = filter_var($_GET["accomType"],FILTER_SANITIZE_STRING);

            $houseQuery = 'SELECT *,((ratings_awarded/ratings_total)*5) AS ratings FROM house WHERE house_id = ? AND user_id = ? AND accomm_type = ?;';
            #prepare statement for the house
            if($houseData = $connectDB->prepare($houseQuery) ){
                $houseData->execute([ $cleanHouseId,$cleanUserId,$cleanAccommType]);
                $houseObj = $houseData->fetch(PDO::FETCH_OBJ);
                $_SESSION['houseObj'] = $houseObj;
               
                #prepare statement for the pictures
                if($pictureData = $connectDB->prepare("SELECT img_location FROM pictures WHERE
                 house_id = ? AND user_id = ? AND img_desc  NOT REGEXP 'profile';")){
                     #execute list search
                      $pictureData->execute([$cleanHouseId,$cleanUserId]);
                      $picHtml = '<div class="house-img">';
                     while($picInfo = $pictureData->fetch(PDO::FETCH_OBJ)){
                         #loop through while getting the data
                            $picHtml .= '<img src="'.$picInfo->img_location.'" />';
                        } $picHtml .= ' </div>'; #wrap the pics in a div-tag
                }else{ 
                    echo '<br/><p class="error">error: could not prepare house pictures</p>';
                }

            }else{ echo '<br/><p class="error">error: could not prepare house information</p>'; }
             #extract phone number,email address and name
             $landlordDetailsQuery = 'SELECT email, phone, gender, concat( left(users.first_name,1),". ",users.surname ) AS fullname FROM users WHERE users.user_id = ? AND users.user_type = "landlord" '; 
             if($landlordDetails = $connectDB->prepare($landlordDetailsQuery)){
                    $landlordDetails->execute([$cleanUserId]);
                    $landlordDetailsObject = $landlordDetails->fetch(PDO::FETCH_OBJ);
                }
             #extract landlord/lady profile-picture
             $landlordProfilePicQuery = 'SELECT img_location FROM pictures WHERE user_id = ? AND img_desc = "profile"'; 
                if($landlordProfilePic = $connectDB->prepare($landlordProfilePicQuery)){
                    $landlordProfilePic->execute([$cleanUserId]);
                    $landlordProfilePicObj = $landlordProfilePic->fetch(PDO::FETCH_OBJ);
                }
             #use details to construct the house detail 
             
             $houseDataHtml = '<br/><section id="detailed-info">
            
                <div class="p-details">
             <p> This convenient accommodation found in '.$houseObj->geo_location.' offers you <b class="offered">'.$houseObj->offered.'</b> .So make yourself at home and make sure you have a good time. All 
                that we wish for, us at <em title="convenient accommodation, made for you at your own convenience"><b>AccommoVenient</b></em> and '. ( ($landlordDetailsObject->gender == "female") ? "Ms" : "Mr" ) .' '.$landlordDetailsObject->fullname .' 
                is for you to have that feeling of being home away from home and  be
                 engaged in your academics whilst bringing the best out of yourself as you strive to be better.
                <br/>
              We also advice you bring these <b><em class="essentials">'.$houseObj->essentials.'</em></b>, '. ( ($landlordDetailsObject->gender == "female") ? "Ms" : "Mr" ) .' '.$landlordDetailsObject->fullname .'
              has offered '. ( ($landlordDetailsObject->gender == "female") ? "her" : "his" ) .' helping hand out of love and wanting the best for you
              and in making sure you have a nice stay without having to worry about leaving any essential behind. </p>
                </div>
                

        <div class="l-parent">
             <div class="l-details">
              <ul>
                <li><p class="b">Accommodates:</p> <p>'. ( $houseObj->accomm_type == "both" ? "boys and girls" : $houseObj->accomm_type ) .'</p></li>
                <li><p class="b">Students Per room:</p> <p>'.$houseObj->accommodates.'</p></li>
                <li><p class="b">Rent:</p> <p>$'.$houseObj->rent.'</p></li>
                <li><p class="b">Location:</p> <p>'.$houseObj->geo_location.'</p></li>
                <li><p class="b">Ratings:</p> <p>'.generate_stars_frac($houseObj->ratings,$positiveStarSvg,$negativeStarSvg).'</p></li>
                <li><p class="b">Rated by</p>: <p>'.$houseObj->ratings_total/5 .'</p></li>
                <li><p class="b">Rating score</p>: <p>'.$houseObj->ratings_awarded.'/'.$houseObj->ratings_total .'</p></li>
                <li><p class="b">Physical Address:</p> <p>'.$houseObj->physical_address.'</p></li>
               </ul>
             </div>
            
             
             <div class="l-contact-info">
                <ul>
                    <li class="l-heading"><u><b>Land'.( ($landlordDetailsObject->gender == "female") ? "lady" : "lord" ).' Contact details</b></u></li>
                    <li><b>'.$contactSvg.'Name:</b> '. ( ($landlordDetailsObject->gender == "female") ? "Ms" : "Mr" ) .' '.$landlordDetailsObject->fullname .'</li>
                    <li><b>&commat;Email: </b> '.$landlordDetailsObject->email.'</li>
                    <li><b> &phone;Phone:</b> '.$landlordDetailsObject->phone.'</li>
                </ul>
             </div>
             <div class="prof-pic">
                <img src="'. ( isset($landlordProfilePicObj->img_location) ? $landlordProfilePicObj->img_location: 'resources\logo1.jpg') .'" />
             </div>

        </div>     
             </section><br/>';
             #generate reviews from the db
             #prepare query
             $commentsQuery = 'SELECT * FROM comments WHERE house_id = ?;';
              if($commentData = $connectDB->prepare($commentsQuery)){
                     #execute list search
                      $commentData->execute([$cleanHouseId]);
                      $commentsHtml = '<div class="reviews">
                        <div class="mini-adv ratings">
                        <p>Reviews and Comments</p>
                        </div>';
                        #if comments exist
                        
                        $leVar = $commentData->rowCount();
                        if($commentData->rowCount() > 0 ){
                        #will be used for the comment section of the page
                        while($commentInfo = $commentData->fetch(PDO::FETCH_OBJ)){
                         #extract profile pic 
                         if($commentProfilePic = $connectDB->prepare('SELECT img_location FROM pictures WHERE user_id = ? AND img_desc = "profile"') ){
                             $commentProfilePic->execute([$commentInfo->user_id]);
                             $commentProfilePicObj = $commentProfilePic->fetch(PDO::FETCH_OBJ);
                             #assign img src
                             if(isset($commentInfo->user_alias)){
                                 $commentImgSrc = 'resources\logo.png';
                             }else{
                                 $commentImgSrc = isset($commentProfilePicObj->img_location) ? $commentProfilePicObj->img_location : 'resources\logo.png';
                             }
                         } 
                          #extract username
                          if($commentUsername = $connectDB->prepare('SELECT  concat( users.first_name," .",left(users.surname,1) ) AS fullname  FROM users WHERE user_id = ?')){
                              $commentUsername->execute([$commentInfo->user_id]);
                              $commentUsernameObj = $commentUsername->fetch(PDO::FETCH_OBJ);
                              #assign comment name
                                if(isset($commentInfo->user_alias)){
                                   $commentName = $commentInfo->user_alias;
                                }else{
                                    $commentName = $commentUsernameObj->fullname;
                                }
                          }  
                         #generate star ratings
                          $ratedStars = $commentInfo->rating_score;
                          $unratedStars = 5 - $ratedStars;
                          $ratedStarsString = '';
                          $unratedStarsString = '';
                          for($a = 0; $a < $ratedStars; $a++){
                            $ratedStarsString .= $positiveStarSvg;
                          }
                          for($b = 0; $b < $unratedStars; $b++){
                            $unratedStarsString .= $negativeStarSvg;
                          }
                           
                         #loop through while getting the data
                            $commentsHtml .= '<section class="comment"><div class="comment-prof"><div class="comment-user-prof"><img class="comment-img" src="'.$commentImgSrc.'"/><p>'.
                             $commentName .'<br/> '. $ratedStarsString . $unratedStarsString .' </p></div></div><article class="comment">                                                                                                                                                                            
                            <p>'.$commentInfo->comment_text.'</p>
                            <h5 class="comment-time">'.date("h:i A - D d M Y ",strtotime($commentInfo->comment_time)).'</h5>
                            </article> 
                            </section> 
                            <br/>';
                           
                        }
                        }else{
                            $commentsHtml .='<div class="comment-inform">
                                <p class="inform">No comments available, would you like to add your experience ?.</p>
                            </div>';
                        }
                        
                        $commentsHtml .= (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == 'student')? '<section class="comment"><div class="comment-prof"></div><article class="add-comment comment">
                        <form id="comment-form" action="php/house-details.php"  method="post" enctype="multipart/form-data" >
                        <textarea name="commentTxt" class="comment-box" required placeholder="Want to add your experience living here ? We would like to know how it was..." ></textarea>
                        <div class="comment-rating">  </div>
                        <p> <b title="In case you are not comfortable sharing your identity">Use Alias</b><input type="checkbox"  id="alias-comment">  <b title="let us know how much you  like it" id="comment-rating">Whats your rating ?</b>
                        <input type="number" placeholder="-" name="ratingScore" id="rating" min="1" max="5" required/> '.$shieldStarSvg.'</p>
                        <input type="text" id="alias" placeholder="set your Alias for privacy" name="nameAlias" >
                        <button type="submit">Add Comment</button>
                        </form>                        
                        </article>
                        </section></div>':'<p class="warn">'.$exclamationSvg.' Only logged in students can comment!</p></div>';
                }else{ echo '<br/><p class="error">error: could not prepare comments</p>';
                }
                #prepare map
                $mapHtml = '<div  id="map" class="house-location">
                    <style>
                        #map{
                         <!--   height:50%; -->
                          <!--  width:50%; -->
                        }
                    </style>
                    <script>
                        function initMap(){
                            //map Options
                                var options = {
                                    zoom: 8;
                                    center:{lat:92.3601,lng:-101.0589}
                                }
                            //New Map    
                                var map = new google.maps.Map(document.getElementById("map"), options);
                            //Add marker
                                var marker = new google.maps.Marker({ position:{lat:92.3601,lng:-101.0589} },map:map);   
                            //info window
                                var infoWindow = new google.maps.InfoWindow({content: "house Address varInfo here!"});     
                        }
                    </script>
                    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">

                    </script>
                </div>';
#now every information has been generated and now its time to display it
                        
         echo '<div class="map-and-pics">'.$mapHtml.' '.$picHtml.'</div>  '.$houseDataHtml.' '.$commentsHtml; 

        }else{
            echo '<p class="error">error: insufficient information for house</p>';
        }
       

    }elseif ($_SERVER["REQUEST_METHOD"] == 'POST') {
        #check if session data contains logged in user
            if(isset($_SESSION["usertype"])){
                # if user is regular, allow commenting
                if(isset($_POST["nameAlias"]) && isset($_POST["commmentTxt"])){
                    $rawAlias = trim($_POST["nameAlias"]);
                    $rawComment = trim($_POST["commentTxt"]);
                    $rawRating = trim($_POST["ratingScore"]);
                    #filter and sanitize
                    $cleanAlias = filter_var($rawAlias,FILTER_SANITIZE_STRING);
                    $cleanComment = filter_var($rawComment,FILTER_SANITIZE_STRING);
                    $cleanRating = filter_var($rawRating,FILTER_VALIDATE_INT);
                    #prepare String
                    $addCommentQuery = 'INSERT INTO `comments` (`user_id`, `house_id`,
                     `comment_text`, `comment_time`, `user_alias`, `rating_score`) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)';
                     if($addComment = $connectDB->prepare($addCommentQuery)){
                         
                           $commentAdded =  $addComment->execute([$_SESSION["userID"],$_SESSION["houseObj"]->house_id,$cleanComment,$cleanAlias,$cleanRating]);
                           
                            if($commentAdded){
                                echo '<br/><p class="inform anim-fade">comment added successfully</p>';
                            }else{ echo '<br/><p class="error">error: adding comment failed.</p>'; }
                     }else{ echo '<br/><p class="error">error: preparing to add query failed.</p>'; }

                }

            }else{ echo '<br/><p class="error">error: user is not logged in. denied access</p>'; }

    }
    else{
        echo '<br/><p class="error">error: bad page-request method</p>';
    }




?>
