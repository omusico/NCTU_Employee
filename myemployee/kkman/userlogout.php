<?php
    include("connectSQL.php");
	$_SESSION["UserID"]="";
	session_unset();
	session_destroy();
	echo "<meta http-equiv=refresh content=0;url=http://employ.nctu.edu.tw>";
?>