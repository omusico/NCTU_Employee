 <?
	include("connectSQL.php");
	include("function.php");
	require_once ('JSON.php');
	
	$j = new Services_JSON();
	$return_data = array();
	
	$bugnotype=filterEvil($_GET['bugnotype']);
	$PTtitle=filterEvil($_GET['PTtitle']);	
	
	//$bug_type=checkBugetTypeForPTtitle($bugno);
	
	//控制支領類別的使用
	//20150209改以paytype_mapping內的記錄為準
	$strSQL="select * from paytype_mapping where TitleCode='".$PTtitle."' and (Plan_Type='".$bugnotype."' or Plan_Type='all-others') order by priority asc";
	//array_push($return_data,$strSQL);
	$result=$db->query($strSQL);
	$row=$result->fetchAll();
	if(count($row)>0){
		$result=$db->query($strSQL);
		$row=$result->fetch();
		
		if($row['hr_pay']){array_push($return_data,"hr_pay");}
		if($row['day_pay']){array_push($return_data,"day_pay");}
		if($row['case_pay']){array_push($return_data,"case_pay");}
		if($row['award_pay']){array_push($return_data,"award_pay");}
		if($row['month_pay']){array_push($return_data,"month_pay");}
		
	}
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
