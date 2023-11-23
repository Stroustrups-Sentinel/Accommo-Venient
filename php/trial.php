<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test.php</title>
</head>
<body>
    

<?php

$server = "localhost";  $user = "root"; $pass = ""; $Name =  "accomm_test";

$connect = mysqli_connect($server,$user,$pass,$Name); 


echo "hello";
$sql = 'SELECT COUNT(*) FROM users';
$run = mysqli_query($connect,$sql);
$runAssoc = mysqli_fetch_row($run);
echo "<br/> run : ".$runAssoc;

?>
</body>
</html>