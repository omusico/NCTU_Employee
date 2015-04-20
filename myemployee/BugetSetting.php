<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>計畫可追溯設定</title>
<script type="text/javascript" src="/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/JS/jquery-impromptu.js"></script>

</head>
<?php 
	include("connectSQL.php");
	include("function.php");
	$bugetno="";$ErrMsg="";$can_apply=false;$formAction="";
	
	if(isset($_POST['bugetno'])){$bugetno=mb_strtoupper(filterEvil(trim($_POST['bugetno'])));}
	if(isset($_POST['formAction'])){$formAction=filterEvil(trim($_POST['formAction']));}
	if($formAction=="set"){
		$today=date('Y-m-d H:i:s');
		$org_bug_start=$_POST['bug_org_start'];
		
		$work_set_type=$_POST['work_traceback_type'];
		$work_traceback_y=$_POST['work_traceback_y'];
		$work_traceback_m=$_POST['work_traceback_m'];
		$work_traceback_d=$_POST['work_traceback_d'];
		$work_traceback_reason=filterEvil($_POST['work_traceback_reason']);
		
		$study_set_type=$_POST['study_traceback_type'];
		$study_traceback_y=$_POST['study_traceback_y'];
		$study_traceback_m=$_POST['study_traceback_m'];
		$study_traceback_d=$_POST['study_traceback_d'];
		$study_traceback_reason=filterEvil($_POST['study_traceback_reason']);
		
		$strSQL="select * from BugetTraceBackSetting where BugNo='".$bugetno."'";
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){
			$strSQL="update BugetTraceBackSetting set work_TraceBackType='".$work_set_type."',study_TraceBackType='".$study_set_type."',".
					"UpdateEmp='".$_SESSION['UserID']."',UpdateDate='".$today."',".
					"work_Org_startdate='".$org_bug_start."',study_Org_startdate='".$org_bug_start."',".
					"work_Set_reason='".$work_traceback_reason."',study_Set_reason='".$study_traceback_reason."',";					
			if($work_set_type=="3"){
				$strSQL.="work_Set_startdate='".$work_traceback_y.addLeadingZeros($work_traceback_m,2).addLeadingZeros($work_traceback_d,2)."',";
			}else{
				$strSQL.="work_Set_startdate=null,";
			}
			if($study_set_type=="3"){
				$strSQL.="study_Set_startdate='".$study_traceback_y.addLeadingZeros($study_traceback_m,2).addLeadingZeros($study_traceback_d,2)."'";
			}else{
				$strSQL.="study_Set_startdate=null ";
			}
			$strSQL.=" where BugNo='".$bugetno."'";
		}else{
			$strSQL="insert into BugetTraceBackSetting ".
					"select '".$bugetno."','".$work_set_type."','".$org_bug_start."',".
					"'".$work_traceback_y.addLeadingZeros($work_traceback_m,2).addLeadingZeros($work_traceback_d,2)."',".
					"'".$work_traceback_reason."','".$study_set_type."','".$org_bug_start."'".
					",'".$study_traceback_y.addLeadingZeros($study_traceback_m,2).addLeadingZeros($study_traceback_d,2)."'".
					",'".$study_traceback_reason."','".$_SESSION['UserID']."','".$today."'";
		}
		//echo $strSQL;
		$result=$db->query($strSQL);
		echo "<script language='javascript'>alert('新增/更新計畫追溯設定完成');</script>";
	}
	if($bugetno!=""){
		//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";	
		/*$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
				  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
				  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
				  "where v.bugetno='".$bugetno."'";
		//echo $strSQL;
		$result=$db->query($strSQL);		*/
		$row=getBugInfo($bugetno);
		if($row!="notfount"){	
			$bugname=trim($row['bugname']);
			$leader=trim($row['leader']);
			$leaderDep=trim($row['DepName']);
			$start=trim($row['start']);
			if(strlen(trim($row['delay']))>0){
				$end=trim($row['delay']);
			}else{
				$end=trim($row['deadline']);
			}
			$giveunit=trim($row['giveunit']);
			
			$start_y=substr($start,0,strlen($start)-4);
			$start_m=substr($start,strlen($start)-4,2);
			$start_d=substr($start,strlen($start)-2,2);
			
			$end_y=substr($end,0,strlen($end)-4);
			$end_m=substr($end,strlen($end)-4,2);
			$end_d=substr($end,strlen($end)-2,2);
			
			if(trim($row['pjtclose'])!=""){
				$can_apply=false;
				$ErrMsg="本計畫已執行完畢。";
			}else{
				$can_apply=true;
				$strSQL="select * from BugetTraceBackSetting where BugNo='".$bugetno."'";
				$result=$db->query($strSQL);
				if($result && $row=$result->fetch()){
					$work_set_type=$row['work_TraceBackType'];
					$work_traceback_y=substr($row['work_Set_startdate'],0,strlen($row['work_Set_startdate'])-4);
					$work_traceback_m=substr($row['work_Set_startdate'],strlen($row['work_Set_startdate'])-4,2);
					$work_traceback_d=substr($row['work_Set_startdate'],strlen($row['work_Set_startdate'])-2,2);
					$work_traceback_reason=$row['work_Set_reason'];
					
					$study_set_type=$row['study_TraceBackType'];
					$study_traceback_y=substr($row['study_Set_startdate'],0,strlen($row['study_Set_startdate'])-4);
					$study_traceback_m=substr($row['study_Set_startdate'],strlen($row['study_Set_startdate'])-4,2);
					$study_traceback_d=substr($row['study_Set_startdate'],strlen($row['study_Set_startdate'])-2,2);
					$study_traceback_reason=$row['study_Set_reason'];
				}
			}
		}else{
			$can_apply=false;
			$ErrMsg="查無此計畫資料，請先洽詢主計室承辦人員。";
		}
	}
?>
<body bgcolor='#c1cfb4'>
	<form name="form1" id="form1" method="POST" action="BugetSetting.php" target="_self">		
		<input type="hidden" name="bug_org_start" id="bug_org_start" value="<?echo $start;?>">
		<input type="hidden" name="bug_org_end" id="bug_org_end" value="<?echo $end;?>">
		<input type="hidden" name="formAction" id="formAction" value="">		
		<table width="720" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" id="SciPayment" style="border-collapse: collapse">
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲設定之計畫編號
				<br>==>若未特別設定之計畫
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>工作型</font>預設不能追溯
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>學習型</font>預設可以追溯,追溯起訖不設限制
			  </td>
			</tr>
			<tr>
			  <td bgcolor="#DFDFDF">
					<input type="text" name="bugetno" id="bugetno" value="<?echo $bugetno;?>"><input type="button" value="查詢" onclick="checkBugetno();">
			  </td>
			</tr>
		</table>
	<hr>
	<?if($can_apply){?>
		<fieldset border: solid 10px blue;>
			<legend>計畫資料</legend>
			<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
				<tr height="20" align="left" bgcolor="#C9CBE0">
					<td nowrap width="100" bgcolor="#C9CBE0">計畫編號</td>
					<td width="260" bgcolor="FFFFCC" align="left"><?echo $bugetno;?></td>		 		
					<td nowrap width="120" bgcolor="#C9CBE0">計畫名稱</td>
					<td width="220" bgcolor="FFFFCC" align="left"><?echo $bugname;?></td>				
				</tr>	
				<tr height="20" align="left" bgcolor="#C9CBE0">  		  		
					<td nowrap width="100" bgcolor="#C9CBE0">計畫主持人</td>
					<td  width="260" bgcolor="FFFFCC" align="left" ><?echo $leader;?></td>	
					<td nowrap width="120" bgcolor="#C9CBE0" width="120">計畫執行單位</td>
					<td bgcolor="FFFFCC" width="220" align="left"><?echo $leaderDep;?></td>
				</tr>
				<tr height="20" align="left" bgcolor="#C9CBE0"> 		
					<td nowrap width="100" bgcolor="#C9CBE0" width="120">計畫補助/委託單位</td>
					<td nowrap width="260" bgcolor="FFFFCC" align="left"><?echo $giveunit;?></td>	
					<td nowrap width="120" bgcolor="#C9CBE0">計畫執行期限</td>
					<td bgcolor="FFFFCC" width="220" align="left"><?echo $start."-".$end;?></td>	
				</tr>
			</table><br>
			<table width="950"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">
				<tr height="20" align="left" bgcolor="#C9CBE0">
					<td nowrap width="100" bgcolor="#C9CBE0">工作型</td>
					<td width="260" bgcolor="FFFFCC" align="left" colspan="3">
						追溯類型:<select name="work_traceback_type" id="work_traceback_type" onchange="javascript:check_work_input();">
							<option value="1" <?if($work_set_type=="1"){echo "selected";}?>>不可追溯</option>
							<!--<option value="2" <?if($work_set_type=="2"){echo "selected";}?>>可追溯-不設限</option>
							<option value="3" <?if($work_set_type=="3"){echo "selected";}?>>可追溯-設定可追溯起日</option>-->
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						可追溯起日:<select name="work_traceback_y" id="work_traceback_y" <?if($work_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=$start_y;$i<=$end_y;$i++){
											echo "<option value='".$i."'";
											if($work_traceback_y==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>年
								<select name="work_traceback_m" id="work_traceback_m" <?if($work_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=1;$i<=12;$i++){
											echo "<option value='".addLeadingZeros($i,2)."'";
											if($work_traceback_m==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>月
								<select name="work_traceback_d" id="work_traceback_d" <?if($work_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=1;$i<=31;$i++){
											echo "<option value='".addLeadingZeros($i,2)."'";
											if($work_traceback_d==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>日&nbsp;&nbsp;&nbsp;&nbsp;
								設定緣由:<textarea name="work_traceback_reason" id="work_traceback_reason"><?echo $work_traceback_reason;?></textarea>
					</td>					
				</tr>	
				<tr height="20" align="left" bgcolor="#C9CBE0">
					<td nowrap width="100" bgcolor="#C9CBE0">學習型</td>
					<td width="260" bgcolor="FFFFCC" align="left" colspan="3">
						追溯類型:<select name="study_traceback_type" id="study_traceback_type" onchange="javascript:check_study_input();">
							<option value="1" <?if($study_set_type=="1"){echo "selected";}?>>不可追溯</option>
							<option value="2" <?if($study_set_type=="2"){echo "selected";}?>>可追溯-不設限</option>
							<option value="3" <?if($study_set_type=="3"){echo "selected";}?>>可追溯-設定可追溯起日</option>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
						可追溯起日:<select name="study_traceback_y" id="study_traceback_y" <?if($study_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=$start_y;$i<=$end_y;$i++){
											echo "<option value='".$i."'";
											if($study_traceback_y==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>年
								<select name="study_traceback_m" id="study_traceback_m" <?if($study_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=1;$i<=12;$i++){
											echo "<option value='".addLeadingZeros($i,2)."'";
											if($study_traceback_m==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>月
								<select name="study_traceback_d" id="study_traceback_d" <?if($study_set_type!="3"){echo "disabled";}?>>
								<?
										for($i=1;$i<=31;$i++){
											echo "<option value='".addLeadingZeros($i,2)."'";
											if($study_traceback_d==$i){echo " selected";}
											echo ">",$i."</option>";
										}
								?></select>日&nbsp;&nbsp;&nbsp;&nbsp;
								設定緣由:<textarea name="study_traceback_reason" id="study_traceback_reason"><?echo $study_traceback_reason;?></textarea>
					</td>				
				</tr>	
			</table>
			<input type="button" value="確認設定" onClick="javascript:BugetSet();">
		</fieldset>
		
	<?}else{echo $ErrMsg;}?>
	</form>
</body>
</html>
<script language="javascript">
function checkBugetno(){
	if(document.getElementById('bugetno').value!=""){
		document.form1.submit();
	}else{
		alert("未輸入計畫編號");
	}
	return false;
}
function check_work_input(){
	var selected_value=document.getElementById('work_traceback_type').value;
	if(selected_value=="3"){
		document.getElementById('work_traceback_y').disabled=false;
		document.getElementById('work_traceback_m').disabled=false;
		document.getElementById('work_traceback_d').disabled=false;
	}else{
		document.getElementById('work_traceback_y').disabled=true;
		document.getElementById('work_traceback_m').disabled=true;
		document.getElementById('work_traceback_d').disabled=true;
	}
}
function check_study_input(){
	var selected_value=document.getElementById('study_traceback_type').value;
	if(selected_value=="3"){
		document.getElementById('study_traceback_y').disabled=false;
		document.getElementById('study_traceback_m').disabled=false;
		document.getElementById('study_traceback_d').disabled=false;
	}else{
		document.getElementById('study_traceback_y').disabled=true;
		document.getElementById('study_traceback_m').disabled=true;
		document.getElementById('study_traceback_d').disabled=true;
	}
}
function BugetSet(){
	//確認設定的起日是否超過計畫期間
	var work_type=document.getElementById('work_traceback_type').value;
	var study_type=document.getElementById('study_traceback_type').value;
	var org_startdate=document.getElementById('bug_org_start').value;
	var org_enddate=document.getElementById('bug_org_end').value;
	var ErrMsg="";
	
	var work_set_date_y=document.getElementById('work_traceback_y').value;
	var work_set_date_m=document.getElementById('work_traceback_m').value;
	var work_set_date_d=document.getElementById('work_traceback_d').value;
	var work_set_date=work_set_date_y+work_set_date_m+work_set_date_d;
	
	var study_set_date_y=document.getElementById('study_traceback_y').value;
	var study_set_date_m=document.getElementById('study_traceback_m').value;
	var study_set_date_d=document.getElementById('study_traceback_m').value;
	var study_set_date=study_set_date_y+study_set_date_m+study_set_date_d;
	
	if(work_type=="3"){
		if(!isDate(work_set_date_y,work_set_date_m,work_set_date_d)){
			ErrMsg=ErrMsg+"工作型追溯起日非正規日期,請確認本日期是否存在(ex.4/31不存在)\n";			
		}
		if(work_set_date<org_startdate || work_set_date>org_enddate){
			ErrMsg=ErrMsg+"工作型追溯起日,不可以超出計畫執行起訖\n";
		}
	}
	if(study_type=="3"){
		if(!isDate(study_set_date_y,study_set_date_m,study_set_date_d)){
			ErrMsg=ErrMsg+"學習型追溯起日非正規日期,請確認本日期是否存在(ex.4/31不存在)\n";			
		}
		if(study_set_date<org_startdate || study_set_date>org_enddate){
			ErrMsg=ErrMsg+"學習型追溯起日,不可以超出計畫執行起訖\n";
		}
	}
	if(ErrMsg!=""){alert(ErrMsg);}
	else{
		document.form1.formAction.value="set";
		document.form1.submit();
	}
}
function isDate(year, month, day){  
   var dateStr;  
   if (!month || !day) {  
       if (month == '') {  
           dateStr = year + "/1/1"  
       }else if (day == '') {  
           dateStr = year + '/' + month + '/1';  
       }else {  
           dateStr = year.replace(/[.-]/g, '/');  
       }  
   }else {  
       dateStr = year + '/' + month + '/' + day;  
   }  
   dateStr = dateStr.replace(/\/0+/g, '/');  
  
   var accDate = new Date(dateStr);  
   var tempDate = accDate.getFullYear() + "/";  
   tempDate += (accDate.getMonth() + 1) + "/";  
   tempDate += accDate.getDate();  
  
   if (dateStr == tempDate) {  
       return true;  
   }  
   return false;  
} 
</script>
	
	
	