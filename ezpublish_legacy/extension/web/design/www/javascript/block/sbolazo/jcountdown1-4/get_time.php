<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
header("Expires: Fri, 1 Jan 2010 00:00:00 GMT"); // Date in the past 
$now = time() * 1000; 
echo $now; 
?>