<!--
    -- #filename       : filemanip.php      | developed using PHP Version 7.3.3
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    -- this is a file that will be included and contains the file manipulation functions -:: insertImage() and removeImage()
    -- using PDO
-->
<?php

function isFileTypeApproved( $fileExtension, $mime,array $allowedExtensions){
    return is_array($allowedExtensions) and isset($allowedExtensions[$mime]) and in_array($fileExtension,(array) $allowedExtensions[$mime]);
}


function insertImage(PDO $connectDB,$userID,$houseID,string $case,$imgDesc,$delPicLocation = null){
#check for image upload request
if(!( $_FILES["uploadPic"]["error"] == UPLOAD_ERR_OK )){
    echo '<br/><p class="error">error: image file is not included in upload</p>';
    return;
}echo '<br/><p class="inform">file upload successful</p>';
#listing the allowed image extensions
$allowedImageTypes =array ( 'image/png'=> 'png',
                  'image/gif'=> 'gif',
                  'image/jpeg'=> ['jpeg','jpg','jpe','jfif','pjp','pjpeg'],
                  'image/bmp'=> ['bmp','dib'],
                  'image/tiff'=> ['tif','tiff'],
                  'image/x-icon' => ['ico'],
                  'image/svg+xml' => ['svg','svgz'],
                  'image/x-xbitmap'=> ['xbm'],
                  'image/webp' => ['webp'],
                );
#check file if pic
// This array contains a list of characters not allowed in a filename 
#exctracted from PHPforProfessionals Chapter101:Security Page:457/481 and edited to suit needs
$illegalChars = array_merge(array_map('chr', range(0,31)), ["<", ">", ":", '"', "/", "\\", "|", "?","*", " "]);
$filename = str_replace($illegalChars, "-", $_FILES['uploadPic']['name']);
$pathinfo = pathinfo($filename);
#sanitizing and filtering variables
$cleanExtension = $pathinfo['extension'] ? $pathinfo['extension']:'';
$cleanFilename = $pathinfo['filename'] ? $pathinfo['filename']:'';
$cleanMime = str_replace(["<", ">", ":", '"', "\\", "|", "?","*", " "],'-',$_FILES['uploadPic']['type']);
#echo '<br>$cleanmime is : '.$cleanMime.' <br>';
#check if requirements are met
    if(!empty($cleanExtension) && !empty($cleanFilename) && !empty($cleanMime)){
       # echo '<br/> Filename is : '.$cleanFilename.', and Extension is : '.$cleanExtension.', with Mime :'.$cleanMime.' <br/>';
         #test if image works
            switch ($cleanExtension) {
                case 'jpeg':
                case 'jpg':
                case 'jpe':     #testing for the different jpeg formats
                case 'jfif':
                case 'pjp':
                case 'pjpeg':                   
                    if( $tryImg = imagecreatefromjpeg($pathinfo['basename']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                     }else{
                        echo '<br/><p class="error">error: jpeg-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                    break;# end switch case
                case 'gif':
                    if( $tryImg = imagecreatefromgif($pathinfo['basename']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                     }else{
                        echo '<p class="error">error: gif-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                     break;    
                case 'png':
                    if( $tryImg = imagecreatefrompng($_FILES['uploadPic']['tmp_name']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                     }else{
                        echo '<p class="error">error: png-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                     break;
                case 'bmp':
                case 'dib':    
                    if( $tryImg = imagecreatefrombmp($pathinfo['basename']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                     }else{
                        echo '<p class="error">error: bmp-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                     break; 
                case 'xbm':
                    if( $tryImg = imagecreatefromxbm($pathinfo['basename']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                     }else{
                        echo '<p class="error">error: xbm-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                     break; 
                case 'webp':
                    if( $tryImg = imagecreatefromwebp($pathinfo['basename']) ){
                        imagedestroy($tryImg);
                        echo '<br/><p class="success"> '.$cleanExtension.' image works </p>';
                        echo '<p class="error">error: webp-image failed to open, could be corrupt or the file contains something else.</p>';
                        return;
                        }
                        
                     break;             
                default:
                echo '<br/><p class="inform anim-fade">alert: image was not checked if working, just make sure it works...</p>';
                    break;
            }

            if( isFileTypeApproved($cleanExtension,$cleanMime,$allowedImageTypes) ){
                #give file a new name
                $newFileName = md5(uniqid().microtime());
                #debug : echo "<br>new filename is : {$newFileName} and is ".strlen($newFileName)." characters long <br/>";
            }else{
                echo '<br/><p class="error">error: file extension is not supported</p>';
                return; #end if extension is not supported
                }

    } else {
        echo '<br/><p class="error">error: uploaded file has a bad extension! and is unrecognized</p>';
        return; #end function if file is not recognized
    }
 
$dirName = "pictureDB";

#check use case : update or insert
    switch ($case) {
        case 'update':
            #debug : echo '<br>inside update<br/>';
            if(empty($delPicLocation) && strlen($delPicLocation) < 32){ echo '';return;};
            #copy file to directory
             if(!file_exists($dirName))mkdir('../'.$dirName); #create if there isn't a directory
                        $upload = move_uploaded_file($_FILES['uploadPic']['tmp_name'],'../'.$dirName.'/'.$newFileName.'.'.$cleanExtension);
                    if($upload){
                        echo '<br/><p class="inform anim-fade"> image upload was successful!</p>';
                    }else{ echo '<br/><p class="error">error: image upload failed!</p>'; return; }
            #insert filename and location into db %on failure% - delete file
                #start transaction
try{
                    # insert picture 
                     $updateQuery = 'INSERT INTO pictures (`user_id`, `img_desc`, `house_id`, `img_location`,`last_update`)
                                VALUES (? , ?, ?, ?, CURRENT_TIMESTAMP);';
                                    if( $insertImg = $connectDB->prepare($updateQuery) ){
                                        $img_location = '../'.$dirName.'/'.$newFileName.'.'.$cleanExtension;#var to contain location
                                                 #upload file
                                                 $success = $insertImg->execute([$userID,$imgDesc,$houseID,$img_location]);
                                                 if( $success ){
                                                 echo '<br/><p class="inform anim-fade">image upload complete</p>';
                                                 }else{
                                                     #delete file from dir
                                                     $completeDelete = unlink($img_location);
                                                     #check if deletion was a success
                                                        if($completeDelete){
                                                         echo '<br/><p class="error anim-fade">upload failed and file was removed</p>';
                                                    }else{ echo '<br/><p class="error">error: upload failed and file could not be removed!</p>'; }
                                             }
                                       }else{ '<p class="error">error: preparing for upload failed!</p>'; };
                    # then remove picture 
                        #prepare delete query 
                    $deleteQuery = 'DELETE pictures WHERE `user_id` = ? AND `img_desc` = ?
                     AND `house_id` = ? AND `img_location` = ?;';
                                       if($deletion = $connectDB->prepare($deleteQuery)){
                                            $deletionSuccess = $deletion->execute([$userID,$imgDesc,$houseID,$delPicLocation]);
                                                 if($deletionSuccess){
                                                     echo '<br/><p class="inform anim-fade">old image removed successfully...</p>';
                                                 }else{ echo '<br/><p class="error">error: old image could not be removed </p>'; }
                                       }else{ echo '<br/><p class="error">error: preparing for orphaned-image removal failed </p>'; }
                    #when both processes were a success                   
                    echo '<h4 class="inform anim-fade">image updated successfully</h4>';
                    $connectDB->commit(); #upload complete
}catch( PDOException $pE ){
                        echo '<p class="error">Fatal-error: uploading image incurred a difficulty : <em>'.print_r($pE).'</em></p>' ;
                        $connectDB->rollBack();
                        }
            break;
        case 'insert': 
             #echo '<br>inside insert<br/>';
                #copy file to directory
                    if(!file_exists($dirName))mkdir('../'.$dirName); #create if there isn't a directory
                        $upload = move_uploaded_file($_FILES['uploadPic']['tmp_name'],'../'.$dirName.'/'.$newFileName.'.'.$cleanExtension);
                    if($upload){
                        echo '<br/><p class="inform anim-fade"> image upload was successful!</p>';
                    }else{ echo '<br/><p class="error">error: image upload failed!</p>'; return; }
                #insert filename and location into db %on failure% - delete file   
                         #prepare query
                         $uploadQuery = 'INSERT INTO pictures (`user_id`, `img_desc`, `house_id`, `img_location`,`last_update`)
                          VALUES (? , ?, ?, ?, CURRENT_TIMESTAMP)';
                                if( $uploadImg = $connectDB->prepare($uploadQuery) ){
                                    $img_location = '../'.$dirName.'/'.$newFileName.'.'.$cleanExtension;#var to contain location
                                             #upload file
                                             $success = $uploadImg->execute([$userID,$imgDesc,$houseID,$img_location]);
                                             if( $success ){
                                                 echo '<br/><p class="inform anim-fade">image upload complete</p>';
                                             }else{
                                                 #delete file from dir
                                                 $completeDelete = unlink($img_location);
                                                 #check if deletion was a success
                                                    if($completeDelete){
                                                        echo '<br/><p class="error anim-fade">upload failed and file was removed</p>';
                                                    }else{ echo '<br/><p class="error">error: upload failed and file could not be removed!</p>'; }
                                             }
                                }else{ echo '<br/><p class="error">error: preparing for upload failed!</p>'; };
               
            break;
        default:#in case of an unknown case
                    echo '<br/><p class="error">error: bad add image scenario: -: <em>'.$case.'</em> </p>';
            break;
        };


}#end of function

function removeImage(PDO $connectDB,$userID,$houseID,$imgLoc){
#check for images' existence
$imgCheckQuery = "SELECT * FROM pictures WHERE user_id = ? AND house_id = ? AND img_location = ?;";
#prepare query
    if($imgCheck = $connectDB->prepare($imgCheckQuery)){
        #execute query
        $imgCheck->execute([$userID,$houseID,$imgLoc]);
        #check for affected rows
        $rowCount = $imgCheck->rowCount();
        #executing different scenarios
            if($rowCount == 1){
                #exactly one row was affected so we then delete the file
                #formulate Query
                $imgRemQuery = 'DELETE FROM pictures WHERE user_id = ? AND house_id = ? AND img_location = ?;';
                #prepare query
                    if($imgDel = $connectDB->prepare($imgRemQuery) ){
                        $imgDel->execute([$userID,$houseID,$imgLoc]);
                        #check for number of queries affected
                        $deletedRows = $imgDel->rowCount();
                        #informing user for the events
                            if($deletedRows == 1){
                                echo '<br/><p class="anim-fade">image has been removed successfully!</p>';
                            }elseif ($deletedRows > 1) {
                                echo '<br/><p class="error">Oops, turns out more than one image was removed</p>';
                            }else{
                                echo '<br/><p class="error">error: removal request was unsuccessful </p>';
                            }

                    }else{#if preparing failed
                        echo '<br/><p class="error">error: preparation for deletion failed</p>';
                    }

            }elseif($rowCount > 0){
                #in case of the unexpected
                 echo '<br/><p class="error">error: images with the same values are :'.$rowCount.' {multiple images cannot be deleted, contact ADMIN}</p>';
            }else{ #if the search had no results
                 echo '<br/><p class="error">error: could not find the image to remove</p>';
            }

    }else{#if preparing failed
        echo '<br/><p class="error">error: could not prepare image-check</p>';
    }
}
#test code starts here
/* #connecting to db -> pdo style
$serverName = "localhost";  $dbuser = "root"; $dbpass = ""; $dbName =  "accommo_venientdb";
$DSN = ("mysql:host={$serverName};dbname={$dbName}");
$connectDB = new PDO($DSN,$dbuser,$dbpass);

echo '<p style="color: purple; font-family: serif; font-style: bolder;">..starting image check</p>';
insertImage($connectDB,'34','12','un-set val','house-kitchen');
var_dump($_FILES);
echo '<p style="color: coral; font: monospace oblique;">..ending image check</p>';
 */
?>