<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>修改異動單</title>
<script type="text/javascript" src="/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/JS/jquery-impromptu.js"></script>

</head>
<?php 
	include("connectSQL.php");
	include("function.php");
	
	$bugetno="";$ErrMsg="";$worktype="";$can_apply=false;
	
	$OrderNo=$_POST['OrderNo'];
	
	if($OrderNo!=""){//點修改進來時,worktype沒有初值,確認目前單子是否可以編輯,若鎖定就不行
		$strSQL="select * from PT_Outline where SerialNo='".trim($OrderNo)."'";
		$result=$db->query($strSQL) or die($strSQL);
		$row=$result->fetch();
		$FormStatus=trim($row['FormStatus']);
	}
	if($FormStatus=="-2"){
		echo "<script language='javascript'>alert('".$OrderNo."的表單已被鎖定,目前無法編輯,即將關閉本視窗!!');";
		echo "window.close();";
		echo "</script>";
	}
	
	$selectedStr="";
	if(!isset($_POST['selectedSN'])){
		$strSQL="select distinct FromEid,JobType from PT_employed where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);
		while($row=$result->fetch()){
			$selectedStr.=$row['FromEid'].",";
			$worktype=$row['JobType'];
		}		
	}else{
		$selectedStr=$_POST['selectedSN'];
		$worktype=$_POST['worktype'];
	}
	$selectedSN=explode(",", $selectedStr);
	//echo "selectedSN".$selectedStr;
	if(isset($_POST['bugetno'])){$bugetno=mb_strtoupper(filterEvil(trim($_POST['bugetno'])));}
	
	if($bugetno!=""){
		//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";	
		//$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
		//		  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
		//		  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
		//		  "where v.bugetno='".$bugetno."'";
		//echo $strSQL;
		$row=getBugInfo($bugetno);
		if($row!="notfound"){	
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

<body bgcolor='#c1cfb4'>
	<form name="addPT" id="addPT" method="POST" action="mod_PTtransform_select.php" target="_self">	
		<input type="hidden" name="selectedSN" value="">
		<input type="hidden" name="OrderNo" value="<?echo $OrderNo;?>">
		<table width="720" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" id="SciPayment" style="border-collapse: collapse">
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲異動之工作類型</td>
			</tr>
			<tr>
			  <td bgcolor="#DFDFDF">
					<select name="worktype" id="worktype">
						<?if($worktype=="work"){?>
						<!--<option value="work">工作型</option>-->
						<?}else{?>
						<option value="study">學習型</option>
						<?}?>
					</select>
			  </td>
			</tr>
			<tr>
			  <td bgcolor="#FFCC00" height="25px" colspan="3">請選擇欲異動之計畫編號</td>
			</tr>
			<tr>
			  <td bgcolor="#DFDFDF">
					<input type="text" name="bugetno" id="bugetno" value="<?echo $bugetno;?>" readonly>
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
	<? if($ErrMsg=="" && $bugetno!=""){
			$appiedSN=array();
			$str_agent="select applied_SerialNo as SN from PT_Outline_Agent where Agent_EmpNo='".$_SESSION["UserID"]."'";
			$result_agent=$db->query($str_agent) or die($str_agent);
			$row_agent=$result_agent->fetchAll();
			if(count($row_agent)>0){
				$result_agent=$db->query($str_agent) or die($str_agent);
				while($row_agent=$result_agent->fetch()){
					array_push($appiedSN,trim($row_agent['SN']));
				}
			}
			if(count($appiedSN)>0){
				$qryStr="(CreateEmp='".$_SESSION["UserID"]."' or SerialNo in ('".implode("','",$appiedSN)."'))";
			}else{
				$qryStr="CreateEmp='".$_SESSION["UserID"]."'";
			}
			
			//可以繼續查可以被異動的請核資料,需至少有一筆payinfo.paystatus=0的資料才行
			$strSQL="select p.*,t.TitleName,".
					"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
					"from PT_Employed p ".
					"left join title t on p.PTtitle=t.TitleCode ".
					"where p.SerialNo in (select SerialNo from PT_Outline where FormStatus='1'";
			if($_SESSION['power']!="1"){$strSQL.=" and ".$qryStr;}		
			$strSQL.=" and BugNo='".$bugetno."') ".
					"and p.RecordStatus='0' and exists (select * from PT_PayInfo where Eid=p.Eid and PayStatus='1')".
					"and JobType='".$worktype."' order by p.serialno,p.eid";
			$result=$db->query($strSQL);
			//echo $strSQL;
			$index=1;
			$row=$result->fetchAll();
			if(count($row)>0){	?>
				<fieldset border: solid 10px blue;>
					<legend>計畫資料</legend>
					<table width="900"  cellspacing="1" cellpadding="4">		
						<tr height="20" align="left" bgcolor="#C9CBE0">
							<td>選取異動<br><input type="button" value="全選" onClick="javascript:selectall();"></td>
							<td>單號</td>
							<td>人員編號</td>		 		
							<td>姓名</td>
							<td>兼任職稱</td>
							<td>可異動支領期間</td>
							<td>支領類別</td>
							<td>支領金額</td>
							<td>備註</td>
						</tr>
	<?
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							if($bgcolor=="FFFFCC"){$bgcolor="white";}else{$bgcolor="FFFFCC";}
							echo "<tr height='20' align='left' bgcolor='".$bgcolor."'>";
							//$strSQL="select * from PT_Employed where FromEid='".$row['Eid']."' and RecordStatus<>'-1' order by Eid desc";
							$strSQL="select distinct SerialNo from PT_Employed where FromEid='".$row['Eid']."' and RecordStatus in ('0','-2') and SerialNo in (select SerialNo from PT_Outline where FormStatus='0' and CreateEmp='".$_SESSION[UserID]."' and SerialNo<>'".$OrderNo."')";
							//echo $strSQL;
							$result2=$db->query($strSQL);
							$row2=$result2->fetchAll();
							if(count($row2)>0){
								$transformed=true;
								if(trim($row2['SerialNo'])==trim($OrderNo)){
									$sameSN=true;
								}else{
									$sameSN=false;
									$transSN=array();
									$result2=$db->query($strSQL);
									while($row2=$result2->fetch()){array_push($transSN,trim($row2['SerialNo']));}
								}
							}else{
								$transformed=false;
								$sameSN=false;
							}
							if(!$transformed){
								echo "<td><input type='checkbox' name='check".$index."' id='check".$index."' value='".$row['Eid']."'";
								for($i=0;$i<=count($selectedSN);$i++){if($selectedSN[$i]==$row['Eid']){echo " checked";}}
								echo "></td>";
							}else{
								if($sameSN){
									echo "<td><input type='checkbox' name='check".$index."' id='check".$index."' value='".$row['Eid']."'";
									for($i=0;$i<=count($selectedSN);$i++){if($selectedSN[$i]==$row['Eid']){echo " checked";}}
									echo "></td>";
								}else{
									echo "<input type='hidden' name='check".$index."' id='check".$index."' value='".$row['Eid']."'>";
									echo "<td>異動中(單號:".implode(",",$transSN).")</td>";
								}
							}
							echo "<td>".$row['SerialNo']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td><td>".$row['TitleName']."</td>";
							$periods=getCanTransformPeriod($row['Eid']);
							
							if($periods['num']==0){echo "<td>無可異動期間</td>";}
							else{
								$str="";
								for($i=1;$i<=$periods['num'];$i++){
									if($i==1){$str.=$periods[$i][1]."-".$periods[$i][2];}
									else{$str.="<br>".$periods[$i][1]."-".$periods[$i][2];}
								}
								echo "<td>".$str."</td>";
							}
							
							if(isset($row['MonthExpense'])){echo "<td>月薪</td>";}
							else if(isset($row['AwardUnit'])){echo "<td>獎助單元</td>";}
							else if($row['PayType']=="hr_pay"){echo "<td>時薪</td>";}
							else if($row['PayType']=="case_pay"){echo "<td>按件計酬</td>";}
							else if($row['PayType']=="day_pay"){echo "<td>日薪</td>";}
							
							echo "<td>".$row['TotalAmount']."</td>";
							echo "<td>".$row['Memo']."</td>";
							echo "</tr>";
							$index++;
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
		for(i=1;i<rowcount;i++){
			if(document.getElementById('check'+i).checked==true){
				selectedSN=selectedSN+document.getElementById('check'+i).value+",";
			}
			
		}
		if(selectedSN!=""){
			var len=selectedSN.length;
			//alert(selectedSN.substring(0,len-1));
			document.addPT.selectedSN.value=selectedSN.substring(0,len-1);
			document.addPT.action="mod_PTtransform_store.php";
			document.addPT.submit();		
		}else{alert("至少選擇一筆才能更新異動單!!");}
	}
	return false;	
}
function selectall(){
	for(i=1;i<rowcount;i++){
		document.getElementById('check'+i).checked=true;
	}
}
</script>
	
	
	