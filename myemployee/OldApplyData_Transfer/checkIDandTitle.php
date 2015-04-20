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
	echo "<table border='1'><tr><td></td><td>舊請核單號</td><td>舊Eid</td><td>新Eid</td><td>工/學號</td><td>身份/居留證</td>".
		 "<td>姓名</td><td>學歷</td><td>請核起日</td><td>請核訖日</td><td>審核日期</td><td>memo</td></tr>";
	//抓出2014-01-01之後審核的資料
	$strSQL="select o.old_SerialNo,o.old_Eid,p.Eid as new_Eid,p.idcode,p.pid,p.name,p.Title,p.BeginDate,p.EndDate,p.VerifyDate ".
			"from PT_Employed p ".
			"left join [dbo].[oldApplyData_Transfer] o on o.new_Eid=p.Eid ".
			"where p.RecordStatus='0' and p.VerifyDate>='2014-01-01' ".
			"order by p.VerifyDate desc";
	//echo $strSQL;
	$result=$db->query($strSQL);
	$index=0;
	while($row=$result->fetch()){
		$error="";
		$Pid=trim($row['pid']);
		$title=trim($row['Title']);
		if(strlen($Pid)<10){
			$error.="無身份證/居留證資料<br>";
		}
		if(!checkARC($Pid) && !checkIdno($Pid)){
			$error.="證號不合規定<br>";
		}
		if($title==""){
			$error.="查無學歷資料";
		}
		if($error!=""){
			$index++;
			echo "<tr>".
				 "<td>".$index."</td>".
				 "<td>".$row['old_SerialNo']."</td>".
				 "<td>".$row['old_Eid']."</td>".
				 "<td>".$row['new_Eid']."</td>".
				 "<td>".$row['idcode']."</td>".
				 "<td>".$row['pid']."</td>".
				 "<td>".$row['name']."</td>".
				 "<td>".$row['Title']."</td>".
				 "<td>".substr($row['BeginDate'],0,10)."</td>".
				 "<td>".substr($row['EndDate'],0,10)."</td>".
				 "<td>".substr($row['VerifyDate'],0,10)."</td>".
				 "<td>".$error."</td>".
				 "</tr>";
		}
	}
	echo "</table>";
	
?>
</body>
</html>