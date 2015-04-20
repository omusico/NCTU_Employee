<?php  
	include("connectSQL.php");
	include("function.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>兼任人員請核系統</title>
<script type="text/javascript" src="JS/jquery-1.8.0.js"></script>
<script type="text/javascript" src="JS/jquery.cookie.js"></script>
</head>

<frameset cols="250,*" frameborder="NO" border="0" framespacing="0">
    <frame src="Menu/Left.php" name="leftFrame" scrolling="auto" noresize>
	<? if($_SESSION["power"]=="1"){?>
		<frame src="uploadManList.php" name="mainFrame">
	<? }else{ ?>
		<frame src="blank.php" name="mainFrame">
	<? } ?>
</frameset>
</frameset>
<noframes><body>
</body></noframes>
</html>
