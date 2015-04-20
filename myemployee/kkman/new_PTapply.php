<?php 
	include("connectSQL.php");
	include("function.php");
	
	$bugetno="";$ErrMsg="";$worktype="";$can_apply=false;
	
	$worktype=$_POST['worktype'];
	if(isset($_POST['bugetno'])){$bugetno=mb_strtoupper(filterEvil(trim($_POST['bugetno'])));}
	if($bugetno!=""){
		//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";	
		$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
				  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
				  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
				  "where v.bugetno='".$bugetno."'";
		//echo $strSQL;
		$result=$db->query($strSQL);		
		if($result && $row=$result->fetch()){
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
			
			if($worktype=="work"){//工作型不可追朔
				$now_y=date('Y')-1911;
				$now_m=date('m');
				$now_d=date('d');		
				$now=$now_y.$now_m.$now_d;
				//echo $now;
				if((int)$now>(int)$end){//計畫訖日小於當天,已不可以再請核
					$can_apply=false;
					$ErrMsg="已過了本計畫可以請核的時間!!";
				}else{
					$can_apply=true;
				}				
			}else{
				$can_apply=true;
			}
		}else{
			$can_apply=false;
			$ErrMsg="查無此計畫資料，請先洽詢主計室承辦人員。";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>

</head>
<body bgcolor='#c1cfb4'>
	<form name="addPT" id="addPT" method="POST" action="new_PTapply.php" target="_self">
		<input type="hidden" name="formAction" id="formAction" value="">
		<table width="720" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" id="SciPayment" style="border-collapse: collapse">
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲申請之工作類型</td>
			</tr>
			<tr>
			  <td bgcolor="#DFDFDF">
					<select name="worktype" id="worktype">
						<option value="">&nbsp;</option>
						<option value="work" <?if($worktype=="work"){echo " selected";}?>>工作型</option>
						<option value="study" <?if($worktype=="study"){echo " selected";}?>>學習型</option>
					</select>
			  </td>
			</tr>
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲申請之計畫編號</td>
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
			</table>
		</fieldset>
		<input type="button" value="確認請核" onClick="AddApply();">
	<?}else{echo $ErrMsg;}?>
	</form>
</body>
</html>
<script language="javascript">
function checkBugetno(){
	if(document.getElementById('worktype').value==""){
		alert("請先選擇工作類型");
	}
	if(document.getElementById('bugetno').value!=""){
		document.addPT.submit();
	}else{
		alert("未輸入計畫編號");
	}
	return false;
}
function AddApply(){
	//仍要先確認輸入
	if(document.getElementById('worktype').value==""){
		alert("請先選擇工作類型");
	}else if(document.getElementById('bugetno').value==""){
		alert("未輸入計畫編號");
	}else{
		document.addPT.formAction.value="newPTapply";
		document.addPT.action="new_PTForm.php";
		document.addPT.submit();		
	}
	return false;
}
</script>
	
	
	