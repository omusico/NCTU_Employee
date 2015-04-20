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
	//每個不同的Eid只建一筆,抓審核時間最新的那筆
	$strSQL_Eid="select distinct EId from Parttime_Employ ".
				"where EId not in (select distinct old_Eid from oldApplyData_Transfer) ".
				"and EId>0 ".
				"order by EId";
	$result_Eid=$db->query($strSQL_Eid) or die($strSQL_Eid."<br>");
	while($rowEid=$result_Eid->fetch()){
		$strSQL="select *,".
				"(datepart(year,EmpDate)-1911) as start_y,datepart(month,EmpDate) as start_m,datepart(day,EmpDate) as start_d,".
				"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
				"from Parttime_Employ where EId='".$rowEid['EId']."' order by PerSignTime desc";
		$result=$db->query($strSQL) or die($strSQL."<br>");
		$row=$result->fetch();//只抓最新那筆
		//兼任助理-月薪或獎助單元=0
		//或臨時工-時薪=0或無資料,視同刪除
		$delete=0;
		if(trim($row['TitleCode'])=="3"){
			$salary=trim($row['salary']);
			if($salary==""){$salary="0";}
			$awardlimit=trim($row['AwardUnit']);
			if($awardlimit==""){$awardlimit="0";}
			if($awardlimit=="0" && $salary=="0"){$delete=1;}
		}elseif(trim($row['TitleCode'])=="4"){
			$pay=trim($row['pay']);
			if($pay==""){$pay="0";}
			
			if($pay=="0"){$delete=1;}
		}
		if($delete==1){
			$old_SN=trim($row['SN']);
			if($old_SN==""){$old_SN=0;}
			$old_TSN=trim($row['Tserialno']);
			if($old_TSN==""){$old_TSN=0;}
			$old_Eid=trim($row['EId']);
			
			$str_mapping="insert into oldApplyData_Transfer select '".$old_SN."','".$old_TSN."','".$old_Eid."',null,null,'1'";
			$db->query($str_mapping) or die($str_mapping."_");
			echo "oldEid='".$old_Eid."需刪除'<br><br><br>";
		}else{
			$id_error="";
			$rule_error="";
			$title="";
			$old_SN=trim($row['SN']);
			if($old_SN==""){$old_SN=0;}
			$old_TSN=trim($row['Tserialno']);
			if($old_TSN==""){$old_TSN=0;}
			$old_Eid=trim($row['EId']);
			$empno=trim($row['EmpNo']);
			$bugno=trim($row['BugNo']);
			$dept=trim($row['DepId']);
			$idcode=trim($row['IdCode']);
			echo "oldEid='".$old_Eid."'<br>";
			if(strlen($idcode)==7){
				$Role="S";
				if(trim($row['Etitlename'])=="大學部學生"){$title="3";}
				if(trim($row['Etitlename'])=="碩班學生"){$title="2";}
				if(trim($row['Etitlename'])=="博班學生"){$title="1";}
				//確認是否為博班候選人
				$str_checkDr="select * from Parttime_Employ where IdCode='".$idcode."' and memo like '%博士%候選人%'";
				$result_checkDr=$db->query($str_checkDr) or die($str_checkDr."<br>");
				$row_checkDr=$result_checkDr->fetchAll();
				if(count($row_checkDr)>0){$title="0";}
				if($title==""){$id_error.="本校學生無學歷資料_";}
			}else if(strlen($idcode)<7){
				$Role="E";
				$title=trim($row['Etitlename']);
				if($title==""){$id_error.="本校職員無職稱資料_";}
			}else{
				$Role="O";
				if(!checkARC($idcode) && !checkIdno($idcode)){
					$id_error.="身份證或居留證號錯誤_";
				}
				if(trim($row['OutSideTitle'])=="學士生"){$title="3";}
				if(trim($row['OutSideTitle'])=="碩士生"){$title="2";}
				if(trim($row['OutSideTitle'])=="博士生"){$title="1";}
				if(trim($row['OutSideTitle'])=="博士候選人"){$title="0";}
				while ($titlename = current($outer_title)) {
					if ($titlename == trim($row['OutSideTitle'])) {
						$title=key($outer_title);
					}
					next($outer_title);
				}
				if($title==""){$id_error.="校外人士無學歷資料_";}
			}
			//確認是否此舊單已建立於新系統
			$str_checknew="select * from oldApplyData_Transfer where old_SerialNo='".$old_SN."' and new_SerialNo is not null";
			//echo $str_checknew."<br>";
			$result_checknew=$db->query($str_checknew) or die($str_checknew."<br>");
			$row_checknew=$result_checknew->fetchAll();
			//echo count($row_checknew)."<br>";
			if(count($row_checknew)<1){//不存在,先建一新單
				$today=date('Y-m-d H:i:s');
				$str_newapply="insert into PT_Outline select '請核','study','".$bugno."','".substr(trim($row['PerSignTime']),0,19)."','".$empno."',".
							  "'".substr(trim($row['PerSignTime']),0,19)."','admin','".$dept."',".
							  "'".$today."','".$empno."','1',null,null,v.bugetno,v.byear,v.bugname,v.projectno,v.start,v.deadline,v.delay,v.leaderid,v.leader,".
							  "v.leaderdep,v.depname,v.prokind,v.pjtclose,v3.updatedate,v3.accmodiuser,null,v2.Name,d.主管姓名 ".
							  "from [SALARYDB].[工作費資料庫].[dbo].[buget] v ".
							  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v3 on v3.bugetno=v.bugetno ".
							  "left join [PERSONDBOLD].[約用人員資料庫].[dbo].[DepartmentCode] d on (d.Code=v.leaderid or ".
							  "(d.Code=v.leaderdep and v.leaderid=v.leaderdep)) ".
							  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) where v.bugetno='".$bugno."'";
				//echo $str_newapply."<br>";
				$db->query($str_newapply) or die($str_newapply."<br>");
				$str_querynewapply="select * from PT_Outline where UpdateDate='".$today."'";
				$result_querynewapply=$db->query($str_querynewapply) or die($str_querynewapply."<br>");
				$row_querynewapply=$result_querynewapply->fetch();
				$new_SN=trim($row_querynewapply['SerialNo']);
				echo "新增請核單".$new_SN."<br>";
			}else{
				$result_checknew=$db->query($str_checknew) or die($str_checknew."<br>");
				$row_checknew=$result_checknew->fetch();
				$new_SN=trim($row_checknew['new_SerialNo']);
			}
			//舊請核寫入新系統
			$today=date('Y-m-d H:i:s');
			$str_newapply="insert into PT_Employed select '".$new_SN."','".substr(trim($row['IdCode']),0,10)."',".
						  "'".substr(trim($row['getidcode']),0,10)."',".
						  "'".trim($row['Cname'])."','".$title."','".$Role."','".trim($row['TitleCode'])."','study',".
						  "'".substr(trim($row['EmpDate']),0,10)."','".substr(trim($row['EndDate']),0,10)."','".trim($row['JobItemCode'])."',null,null,";
			
			$MonthlyExpenses=trim($row['MonthlyExpenses']);
			if($MonthlyExpenses==""){$MonthlyExpenses=0;}
			
			if(trim($row['pay'])!=""){
				$hours=trim($row['WorkingHours']);
				if($hours==""){$hours=0;}
				$pay=trim($row['pay']);
				if($pay==""){$pay=0;}
				$str_newapply.="'hr_pay','".$pay."','".$hours."',null,null,null,";
			}else if(trim($row['salary'])!=""){
				$salary=trim($row['salary']);
				if($salary=="" || !is_numeric($salary)){
					$salary=$MonthlyExpenses;
				}
				$str_newapply.="null,null,null,'".$salary."',null,null,";
			}else{
				$awardlimit=trim($row['AwardUnit']);
				if($awardlimit=="" || !is_numeric($awardlimit)){
					$awardlimit=(int)$MonthlyExpenses/2000;
					//echo "awardunit=".$awardunit."<br>";
					//$awardunit=round($awardunit, 1, PHP_ROUND_HALF_DOWN);
				}
				$str_newapply.="null,null,null,null,'2000','".$awardlimit."',";
			}
			$MonthlyExpenses=trim($row['MonthlyExpenses']);
			if($MonthlyExpenses==""){$MonthlyExpenses=0;}
			
			$IsAboriginal=trim($row['IsAboriginal']);
			if($IsAboriginal!="1"){$IsAboriginal="0";}
			
			$IsDisability=trim($row['IsDisability']);
			if($IsDisability!="1"){$IsDisability="0";}
			
			$BossRelation=trim($row['BossRelation']);
			if($BossRelation!="1"){$BossRelation="0";}
			
			$str_newapply.="'".$MonthlyExpenses."',null,null,'".trim($row['Memo'])."',".
						   "'".$IsAboriginal."','".$IsDisability."','".$BossRelation."',".
						   "'".substr(trim($row['PerSignTime']),0,19)."','".$empno."','".$today."','".$empno."','".substr(trim($row['PerSignTime']),0,19)."','admin',null,'0',null,null,null,null";
			$db->query($str_newapply) or die($str_newapply."<br>");
			$str_qrynewapply="select * from PT_Employed where UpdateDate='".$today."' and SerialNo='".$new_SN."' order by Eid desc";
			$result_qrynewapply=$db->query($str_qrynewapply) or die($str_qrynewapply."<br>");
			$row_qrynewapply=$result_qrynewapply->fetch();
			$new_Eid=$row_qrynewapply['Eid'];
			//寫入firstEid
			$str_upFirstEid="update PT_Employed set FirstEid='".$new_Eid."' where Eid='".$new_Eid."'";
			$db->query($str_upFirstEid) or die($str_upFirstEid."<br>");
			echo "新增一筆請核,Eid=".$new_Eid."_<br>";
			//產生支領資料
			$start_y=trim($row['start_y']);
			$start_m=trim($row['start_m']);
			$start_d=trim($row['start_d']);
			$end_y=trim($row['end_y']);
			$end_m=trim($row['end_m']);
			$end_d=trim($row['end_d']);
			$totalpay=trim($row['MonthlyExpenses']);
			$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
			//print_r($yymm);
			for($index=0;$index<sizeof($yymm);$index++){
				$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
				$strSQL="insert into PT_PayInfo select '".$bugno."','".$new_Eid."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$empno."','1','".$yymm[$index][5]."',null,null,null,null,null,null,'".$new_Eid."'";
				//echo $strSQL."<br>";
				$db->query($strSQL) or die($strSQL."<br>");
			}
			echo "新增Eid:".$new_Eid."支領資料完成<br>";
			//寫入對應表oldApplyData_Transfer
			$str_mapping="insert into oldApplyData_Transfer select '".$old_SN."','".$old_TSN."','".$old_Eid."','".$new_SN."','".$new_Eid."','0'";
			$db->query($str_mapping) or die($str_mapping."_");
			echo "新增對應表完成<br>";
			//若有問題,寫入oldApplyData_Transfer_error
			/*if($id_error!=""){
				$str_mappingerr="insert into oldApplyData_Transfer_error select '".$old_SN."','".$old_TSN."',".
								"'".$old_Eid."','".$new_SN."','".$new_Eid."','','".$id_error."'";
				$db->query($str_mappingerr) or die($str_mappingerr."<br>");
			}*/
			echo "<br><br>";
		}
	}
	
?>
</body>
</html>