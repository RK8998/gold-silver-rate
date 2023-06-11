<?php
/*	$dbhost = "localhost";
 	$dbuser = "u314842691_dbuser";//"u314842691_goldrateindia";
 	$dbpass = "Sck@20180"; //"$cIThqX14";
 	$db = "u314842691_dbgold"; //"u314842691_goldrate";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed:");
 	
 	// Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
    else
 	 echo "Connection Successfully";*/
 	 
 	function getCon()
    {		
		$arr = array("host"=>"localhost","user"=>"root","password"=>"","database"=>"dbgold");		
			
		$con=mysqli_connect($arr["host"],$arr['user'],$arr["password"],$arr["database"])  or die("Connection Error".mysqli_error());	
		return $con;
    }
?>