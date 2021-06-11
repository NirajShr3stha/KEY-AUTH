<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "hazarda1_mak", "FCdw93z4UmmSRJ", "hazarda1_main");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Print host information
// echo "Connect Successfully. Host info: " . mysqli_get_host_info($link);
?>