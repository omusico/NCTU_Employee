<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>�p�e�i�l���]�w</title>
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
	//�C�Ӥ��P��Eid�u�ؤ@��,��f�֮ɶ��̷s������
	$strSQL_Eid="select distinct EId from PT_Employed ".
				"where EId not in (select distinct old_Eid from oldApplyData_Transfer) ".
				"and EId>0 ".
				"order by EId";
	$result_Eid=$db->query($strSQL_Eid) or die($strSQL_Eid."<br>");
	while($rowEid=$result_Eid->fetch()){
		
	}
	
?>
</body>
</html>