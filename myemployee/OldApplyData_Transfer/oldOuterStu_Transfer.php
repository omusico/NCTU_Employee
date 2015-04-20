<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>計畫可追溯設定</title>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>

</head>
<body>
<?php 
	include("connectSQL.php");
	include("function.php");
	
	set_time_limit(0);
	$count=0;
	//抓出有目前轉入校外人士無學歷資料,並和[PERSONDBOLD].[兼任人員差勤資料庫].[dbo].[EmpData_OutSide]
	//比對,有資料的才做update
	$strSQL="select p.Eid as newEid,o.old_Eid as old_Eid,p.Name,p.IdCode,p.Pid,p.BeginDate,p.EndDate,e.outsideunit,e.outsidetitle,o2.titlename ".
			"FROM [兼任人員資料庫].[dbo].PT_Employed p ".
			"left join oldApplyData_Transfer o on o.new_Eid=p.Eid ".
			"left join [PERSONDBOLD].[兼任人員差勤資料庫].[dbo].[EmpData_OutSide] e on e.Eid=o.old_Eid ".
			"left join [PERSONDBOLD].[兼任人員差勤資料庫].[dbo].[OutSideTitle] o2 on o2.titlecode=e.outsidetitle ".
			"where title='' and p.[role]='O' and RecordStatus<>'-1' and o2.titlename is not null  ".
			"order by BeginDate desc";
	//echo $strSQL;
	$result=$db->query($strSQL);
	while($row=$result->fetch()){
		$count++;
		
		$new_Eid=trim($row['newEid']);
		$outsidetitle=$row['outsidetitle'];		
		$updtitle="update PT_Employed set Title='".$outsidetitle."' where Eid='".$new_Eid."'";
		echo "更新:".$count." ".$row['Name']."<br>".$updtitle."<br>";
		$db->query($updtitle) or die($updtitle);
	}
	
?>
</body>
</html>