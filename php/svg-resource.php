<!--
    -- #filename       : svg-resource.php   | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- contains var svg

-->
<?php

#vars
$contactSvg = '<svg style="width:1em; height:1em;"; viewBox="0 0 24 24">
            <path d="M20 0H4v2h16V0M4 24h16v-2H4v2M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2m-8 2.75A2.25 2.25 0 0114.25 9 2.25 2.25 0 0112 11.25 2.25 2.25 0
             019.75 9 2.25 2.25 0 0112 6.75M17 17H7v-1.5c0-1.67 
             3.33-2.5 5-2.5s5 .83 5 2.5V17z" /></svg>';
$positiveStarSvg = '<svg style="height:1em; width:1em; background-color:#ff533d ; " viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22
             9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.45 4.73L5.82 21 12 17.27z" /></svg>';
$negativeStarSvg = '<svg style="height:1em; width:1em; background-color:#aaaaaa ; " viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2 9.19 8.62 2 9.24l5.45 4.73L5.82 21 12 17.27z" /></svg>';                          
$exclamationSvg = '<svg style="width:1em;height:1em; border:#ff533d solid ;"  viewBox="0 0 24 24"><path d="M10 3h4v11h-4V3m0 18v-4h4v4h-4z" /></svg>'; 
$shieldStarSvg = ' <svg  style="width:1.2em;height:1.2em;" viewBox="0 0 24 24"><path d="M21 11c0 5.55-3.84 10.74-9 12-5.16-1.26-9-6.45-9-12V5l9-4 9 4v6m-9 10c3.75-1 7-5.46 7-9.78V6.3l-7-3.12L5 6.3v4.92C5 15.54 8.25 20 12 21m3.05-5l-3.08-1.85L8.9 16l.81-3.5L7 10.16l3.58-.31 1.39-3.3 1.4 3.29 3.58.31-2.72 2.35.82 3.5" /></svg>';

#functions

function generate_stars_frac( $rating, $positiveStarSvg , $negativeStarSvg ){
    #generate star ratings
    $ratedStars = intval($rating);
    $unratedStars = intval(5 - ceil($ratedStars )) - 1;#negate by one to provide space for the fractioned star
    $fractionStar = $rating - $ratedStars;
    $fractionStarPercentage = $fractionStar * 100;
    $ratedStarsString = '';
    $unratedStarsString = '';
    
    for($a = 0; $a < $ratedStars; $a++){
      $ratedStarsString .= $positiveStarSvg;
    }
    for($b = 0; $b < $unratedStars; $b++){
      $unratedStarsString .= $negativeStarSvg;
    }
    $fractionStarString = str_replace('background-color:#ff533d', 'background: linear-gradient(90deg, #ff533d '.$fractionStarPercentage.'%, #aaaaaa 1%,#aaaaaa)',$positiveStarSvg);
    $ratingStarsString = $ratedStarsString.$fractionStarString.$unratedStarsString;
    return $ratingStarsString;
}

?>