<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>

</head>
<?
	include("function.php");
	include("connectSQL.php");
	$IdCode="S0002";
	//$IdCode="H0122";
	$start_y="2014";
	$start_m="11";
	$start_d="26";
	$end_y="2014";
	$end_m="12";
	$end_d="31";
	
	echo "<pre>".print_r(checkIdentity($IdCode,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d))."</pre>";

?>