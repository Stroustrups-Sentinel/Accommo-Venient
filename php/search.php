<!-- 
    -- #filename       : search.php     | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- *********************************************************************************************************************************            ******************

    --PHP file to filter the search results
    -- it is connected to the two files ::index.html & ::house-list.html
    -- security should be of concern and try to avoid injection and or hacking*** ....well i had some ommissions due to complications
-->


<?php

function houseListSearch($cleanGirlsnBoys, $cleanMaxPrice, $cleanLocation, $cleanSearchName){

    #adding more filters
        $searchQuery = "SELECT *,((house.ratings_awarded/house.ratings_total)*5) AS ratings FROM house WHERE ( house.physical_address  REGEXP '$cleanSearchName' OR house.offered REGEXP '$cleanSearchName' OR house.essentials REGEXP '$cleanSearchName' ) ";
                       # echo '<br/>$cleanGirlsnBoys is :'.$cleanGirlsnBoys;#debug
        if(!($cleanGirlsnBoys == "All")){$searchQuery .= " AND house.accomm_type REGEXP '$cleanGirlsnBoys'" ;}
                      #  echo '<br/>$cleanMaxPrice is : '.$cleanMaxPrice;#debug
        if(!($cleanMaxPrice == "any")){ $searchQuery .= " AND house.rent < ('$cleanMaxPrice' + 1)";}
                       # echo '<br/>$cleanLocation is : '.$cleanLocation;
        if(!($cleanLocation == "any")){ $searchQuery .= " AND house.geo_location REGEXP '$cleanLocation' ";}
        $searchQuery .= " ;"; #close off query
    #debug: echo '<br/> the search query : '.$searchQuery;
        return $searchQuery;
}
            #-------------------------------- code starts here
//connection to db
    $serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
    $connectDB = mysqli_connect($serverName,$dbuser,$dbpass,$dbName); 

#svg vars
require('svg-resource.php');

session_start();    

    if($_SERVER["REQUEST_METHOD"] == "GET"){ 
       /*  #session initialize
        session_start();
        #if session comes from list-destroy

        #check if var exists
            if(isset($_SESSION['redirSearch']) && $_GET['search'] == 'abc323tdf657g' ){
                echo '<p>var $_SESSION["redirSearch"] is set and is : '.$_SESSION["redirSearch"].'</p>';
                #before assign
                echo '<b>before assign $_GET["search"] is : '.$_GET['search'].'</b>';
                 $_GET['search'] = $_SESSION['redirSearch'];
                 #after assign
                 echo '<b>after assign $_GET["search"] is : '.$_GET['search'].'</b>';
            }if($_GET['search'] == 'abc323tdf657g')$_GET['search'] = '';
        #take values if it exists
        
         */

    //if $_GET['search'] is empty echo '<br>$_GET is <br>';     
    if(empty(trim($_GET['search']))) $_GET['search'] = 'a';
    $rawName = trim($_GET['search']);
    $cleanSearchName = mysqli_real_escape_string($connectDB,filter_var($rawName,FILTER_SANITIZE_STRING));
   #debug: echo "clean Search: $cleanSearchName";
        if(isset($_GET['girls_or_boys'])){
            $rawGirlsnBoys = trim($_GET['girls_or_boys']);
            $cleanGirlsnBoys = mysqli_real_escape_string($connectDB,filter_var($rawGirlsnBoys,FILTER_SANITIZE_STRING));
            #debug: echo "<br> girls and boys are {$cleanGirlsnBoys}";
        }

        if(isset($_GET['max_price'])){
            $rawMaxPrice = trim($_GET['max_price']);
          $cleanMaxPrice = mysqli_real_escape_string($connectDB,filter_var($rawMaxPrice,FILTER_SANITIZE_STRING));
         #debug: echo "<br/> maxprice = $cleanMaxPrice";
        }
        
        if(isset($_GET['location'])){
            $rawLocation = trim($_GET['location']);
            $cleanLocation = mysqli_real_escape_string($connectDB,filter_var($rawLocation,FILTER_SANITIZE_STRING));
          #debug:  echo "<br> clean location = $cleanLocation";
        }
     /*    //when request comes from ajax post-redirect script
        if(isset($_GET['redirect'])){
            echo '<br/>Yeah redirect is set and is :'.$_GET['redirect'].'<br/>';
            $rawRedirect = trim($_GET['redirect']);
            $cleanRedirect = filter_var($rawRedirect,FILTER_SANITIZE_STRING);
            goto completeRedirect;
        } */
    
        //searching through database
        $searchQuery = "";
        if(isset($_GET['girls_or_boys'])){ #if search comes from the -> house-list page
                $searchQuery = houseListSearch($cleanGirlsnBoys, $cleanMaxPrice, $cleanLocation, $cleanSearchName); # construct query


        }elseif($_GET["search"]){   #if search comes from -> index/ house-list
            $searchQuery = "SELECT *,((house.ratings_awarded/house.ratings_total)*5) AS ratings FROM house WHERE  house.physical_address  REGEXP '$cleanSearchName' OR 
                            house.offered REGEXP '$cleanSearchName' OR house.essentials REGEXP '$cleanSearchName' ORDER BY `ratings` DESC;";
                           #debug: echo "<br/>[line 55] the query is : $searchQuery";
        }
        else{#in case of the impossible error in history
            echo '<br/><p class="error"><br/> un intended occurrence &lt;!&gt;';
        }               #run query and show values
                         $searchViewQuery = mysqli_query($connectDB,$searchQuery);
                        if($searchRows = mysqli_num_rows($searchViewQuery) ){                                        # - check if query ran without error
                      #debug:  echo "<br/>[line 58] house has been found ; and num of rows is : $searchRows <br/><br/>";          # - collect values from table
                        #loop through results
                        $searchResults = '';$count = 0; $searchResultsHtml = '';
                        while($resultRow = mysqli_fetch_assoc($searchViewQuery)){
                            //get house main pic
                            if($houseMainPicQuery = mysqli_query($connectDB,'SELECT img_desc, img_location,last_update FROM pictures WHERE house_id = '.$resultRow["house_id"].' AND img_desc = "house-main" ')){
                                                                       if( $mainPicRow = mysqli_num_rows($houseMainPicQuery)){
                                                                            $houseMainPicObj = mysqli_fetch_object($houseMainPicQuery);
                                                                            #debug: var_dump($houseMainPicObj);
                                                                        }
                                                                   }
                            //append html data
                             $searchResults .= json_encode($resultRow,JSON_PRETTY_PRINT);
                            //get info and highlight it first
                            $cleanSearchNameTmp = strtolower($cleanSearchName);
                            $highlightedLocation = str_replace($cleanSearchNameTmp,'<b class="search-h">'.$cleanSearchNameTmp.'</b>', strtolower( $resultRow["geo_location"] ? $resultRow["geo_location"] : ' -- --' ) );
                            $highlightedOffered = str_replace($cleanSearchNameTmp,'<b class="search-h">'.$cleanSearchNameTmp.'</b>',strtolower($resultRow["offered"]));
                            $highlightedEssentials = str_replace($cleanSearchNameTmp,'<b class="search-h">'.$cleanSearchNameTmp.'</b>',strtolower($resultRow["essentials"]));
                             $searchResultsHtml .= '<div class="house-list-item">
                                        <div class="slide-img"  hseid="'.$resultRow["house_id"].'" nid="'.$resultRow["user_id"].'" acctype="'.$resultRow["accomm_type"].'" > 
                                                       <div class="hse-img"><img src="'. ( isset($houseMainPicObj->img_location) ? $houseMainPicObj->img_location :/*'resources/logo3.jpg'*/ 'media\pics\IMG-20200319-WA0067.jpg'  ) .'" style="background-color:#0f1629;" /></div>            
                                                       <div class="slide-info">
                                                        <p>
                                                            <b> '. ($resultRow["geo_location"] ? $resultRow["geo_location"] : ' -- --' ).'</b> <br/>
                                                            '. generate_stars_frac((number_format( (($resultRow["ratings_awarded"]/$resultRow["ratings_total"])*5),2 )),$positiveStarSvg,$negativeStarSvg).'  <button class="info-view-more" title="view more details">&raquo;</button><br/>
                                                            <b>Rated</b>: '.$resultRow["ratings_total"]/5 .'
                                                        </p>    
                                                       </div>
                                        </div>
                                        <div class="slide-extra">
                                                    <div class="extra-details">
                                                            <ul>
                                                                   <li class="location-h"><b>Location: </b> '. $highlightedLocation .'</li>
                                                                   <li><b>Accommodates: </b> '. ($resultRow["accomm_type"] == "both" ? "boys and girls" : $resultRow["accomm_type"]  ).'</li>
                                                                   <li><b>Physical Address: </b> '. $resultRow["physical_address"].'</li>
                                                                   <li><b>Rent: $</b>'. ($resultRow["rent"] ? $resultRow["rent"] : '--.--' ).' </li>
                                                                   <li><b>Rating: </b> '. generate_stars_frac((number_format( (($resultRow["ratings_awarded"]/$resultRow["ratings_total"])*5),2 )),$positiveStarSvg,$negativeStarSvg).'</li>
                                                            </ul>
                                                    </div>
                                                    <div class="extra-info">
                                                      <p><b>You will be offered</b> '.$highlightedOffered.' <br/><b> and the suggested essentials  : </b> <em>'.$highlightedEssentials.'</em>  </p>
                                                      <button class="view-more">view more &rang;</button>     
                                                    </div>          
                                        </div>
                                                    </div>';
                             ++$count;
                        }
                        #making sure the json array is satisfied
                        if($count > 1){ $jsonSearch = "[{$searchResults}]";}else{$jsonSearch = $searchResults; }
                        
                        # create page info as json
                        if( strlen($searchResults) > 0){
                        #debug:    print_r( var_dump($jsonSearch));
                         echo $searchResultsHtml; #debug : .'<hr/><br><p class="json-dat">'.$jsonSearch.'</p>';
                        }else{
                            echo '<br/><p class="inform">no result found</p>';
                        }

                        }else{#in case of error
                            $errorSearch = mysqli_error($connectDB);
                            if(strlen($errorSearch) > 0){
                                echo '<br/><p class="error"> an Error occurred on search #'.$errorSearch.'</p>';
                            }else{
                                echo '<br/><p class="warning"> Search <b style=" font-size:2em;">&telrec;</b> for `<b class="search-h">'.$cleanSearchName.'</b>` not found !, try changing search filters.</p>';
                            }
                         }                

}else{  echo '<br/><p class="error">error: request method is unrecognized </p>'; }

/* 
#this portion is for the redirect
completeRedirect:
 echo '<br>after redir<br>';
    if(! empty($cleanRedirect)){ #check if var is empty
       
        if($cleanRedirect == 'true'){ //
        #clear redirect var
        $_GET['redirect'] = '';
        #assign Var
            #before assign
            if(isset($_SESSION['redirSearch'])){
                echo '<br>session var [redirSearch] is :'.$_SESSION['redirSearch'].'<br>';
            }else{echo '<p class="error">var $_SESSION["redirSearch"] is not set yet</p>';}
            #assign
        $_SESSION['redirSearch'] = $cleanSearchName;
        echo '$cleanSearchName is '.$cleanSearchName.'<br/>$_GET["search"] is '.$_GET['search'].'<br/>' ;
            #after assign
            echo '<p>var $_SESSION["redirSearch"] is set and is now: '.$_SESSION["redirSearch"].'</p>';
        }
    }

 */
?>    
