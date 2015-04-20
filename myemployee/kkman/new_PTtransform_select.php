<?php 
	include("connectSQL.php");
	include("function.php");
	
	$bugetno="";$ErrMsg="";$worktype="";$can_apply=false;
	
	$worktype=$_POST['worktype'];
	$selectedStr=$_POST['selectedSN'];
	$selectedSN=explode(",", $selectedStr);
	//echo "selectedSN".$selectedStr;
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
<title>新增異動單</title>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>

</head>
<body bgcolor='#c1cfb4'>
	<form name="addPT" id="addPT" method="POST" action="new_PTtransform_select.php" target="_self">	
		<input type="hidden" name="selectedSN" value="">
		<table width="720" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" id="SciPayment" style="border-collapse: collapse">
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲異動之工作類型</td>
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
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲異動之計畫編號</td>
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
			<legend>請核資料</legend>
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
	<?}else{echo $ErrMsg;}?>
	<? if($ErrMsg=="" && $bugetno!=""){//可以繼續查請核資料 
			$strSQL="select p.*,t.TitleName,".
					"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
					"from PT_Employed p ".
					"left join title t on p.PTtitle=t.TitleCode ".
					"where p.SerialNo in (select SerialNo from PT_Outline where FormStatus='1' and CreateEmp='".$_SESSION['UserID']."') ".
					"and p.RecordStatus='0' and JobType='".$worktype."' order by p.serialno,p.eid";
			$result=$db->query($strSQL);
			//echo $strSQL;
			$index=1;
			if($result && $row=$result->fetch()){	?>
				<fieldset border: solid 10px blue;>
					<legend>計畫資料</legend>
					<table width="900"  cellspacing="1" cellpadding="4">		
						<tr height="20" align="left" bgcolor="#C9CBE0">
							<td>選取異動<br><input type="button" value="全選" onClick="javascript:selectall();"></td>
							<td>單號</td>
							<td>人員編號</td>		 		
							<td>姓名</td>
							<td>兼任職稱</td>
							<td>支領期間</td>
							<td>支領類別</td>
							<td>支領金額</td>
							<td>備註</td>
						</tr>
	<?
						$bgcolor="FFFFCC";
						echo "<tr height='20' align='left' bgcolor='FFFFCC'>";
						echo "<td><input type='checkbox' name='check".$index."' id='check".$index."' value='".$row['Eid']."'";
						for($i=0;$i<=count($selectedSN);$i++){if($selectedSN[$i]==$row['Eid']){echo " checked";}}
						echo "></td>".
							 "<td>".$row['SerialNo']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td><td>".$row['TitleName']."</td>".
							"<td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
						
						if(isset($row['MonthExpense'])){echo "<td>月薪</td>";}
						else if(isset($row['AwardUnit'])){echo "<td>獎助單元</td>";}
						else if($row['PayType']=="hr_pay"){echo "<td>時薪</td>";}
						else if($row['PayType']=="case_pay"){echo "<td>按件計酬</td>";}
						else if($row['PayType']=="day_pay"){echo "<td>日薪</td>";}
						
						echo "<td>".$row['TotalAmount']."</td>";
						echo "<td>".$row['Memo']."</td>";
						echo "</tr>";
						while($row=$result->fetch()){
							$index++;
							if($bgcolor=="FFFFCC"){$bgcolor="white";}else{$bgcolor="FFFFCC";}
							echo "<tr height='20' align='left' bgcolor='".$bgcolor."'>";
							echo "<td><input type='checkbox' name='check".$index."' id='check".$index."' value='".$row['Eid']."'";
							for($i=0;$i<=count($selectedSN);$i++){if($selectedSN[$i]==$row['Eid']){echo " checked";}}
							echo "></td>".
								 "<td>".$row['SerialNo']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td><td>".$row['TitleName']."</td>".
								"<td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
							
							if(isset($row['MonthExpense'])){echo "<td>月薪</td>";}
							else if(isset($row['AwardUnit'])){echo "<td>獎助單元</td>";}
							else if($row['PayType']=="hr_pay"){echo "<td>時薪</td>";}
							else if($row['PayType']=="case_pay"){echo "<td>按件計酬</td>";}
							else if($row['PayType']=="day_pay"){echo "<td>日薪</td>";}
							
							echo "<td>".$row['TotalAmount']."</td>";
							echo "<td>".$row['Memo']."</td>";
							echo "</tr>";
						}
	?>
					</table>
				</fieldset>
				<input type="button" value="下一步" onClick="AddApply();">
	<?		}else{echo "查無可異動資料";}
		}
	?>
	<input type="hidden" name="rowcount" id="rowcount" value="<?echo $index;?>">
	</form>
</body>
</html>
<script language="javascript">
<? if($index!=""){?>
var rowcount=<?echo $index;?>;
<? }else{?>
var rowcount=0;
<? }?>

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
	var selectedSN="";
	if(document.getElementById('worktype').value==""){
		alert("請先選擇工作類型");
	}else if(document.getElementById('bugetno').value==""){
		alert("未輸入計畫編號");
	}else{
		for(i=1;i<=rowcount;i++){
			if(document.getElementById('check'+i).checked==true){
				selectedSN=selectedSN+document.getElementById('check'+i).value+",";
			}
			
		}
		if(selectedSN!=""){
			var len=selectedSN.length;
			//alert(selectedSN.substring(0,len-1));
			document.addPT.selectedSN.value=selectedSN.substring(0,len-1);
			document.addPT.action="new_PTtransform_store.php";
			document.addPT.submit();		
		}
		if(i==rowcount){alert("至少選擇一筆才能建立異動單!!");}
	}
	return false;	
}
function selectall(){
	for(i=1;i<=rowcount;i++){
		document.getElementById('check'+i).checked=true;
	}
}
</script>
	
	
	