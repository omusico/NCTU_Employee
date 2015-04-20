 <?
	include("connectSQL.php");
	include("function.php");
	require_once ('JSON.php');
	
	$j = new Services_JSON();
	$return_data = array();
	
	$start=filterEvil($_GET['start']);
	$end=filterEvil($_GET['end']);
	$IdNo=filterEvil($_GET['IdNo']);
	$identity=filterEvil($_GET['identity']);
	
	$start_y=(substr($start,0,strlen($start)-4))+1911;
	$start_m=substr($start,strlen($start)-4,2);
	$start_d=substr($start,strlen($start)-2,2);
	
	$end_y=(substr($end,0,strlen($end)-4))+1911;
	$end_m=substr($end,strlen($end)-4,2);
	$end_d=substr($end,strlen($end)-2,2);
	
	$return_data=checkWorkingPeriod($IdNo,$identity,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
	/*echo '<pre>';
	print_r($return_data);
	echo '</pre>';
	/*$return_data[0]=$PNo;
	$return_data[1]=$start_y.$start_m.$start_d;
	$return_data[2]=$end_y.$end_m.$end_d;*/
	
	//print_r($return_data);
	$jsonString = $j->encode($return_data); 
	echo $jsonString;
	exit;
?>
