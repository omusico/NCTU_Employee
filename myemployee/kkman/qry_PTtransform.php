<?php
  include("connectSQL.php");
  include("function.php");
  $formAction="";$OrderNo="";$keytxt="";
  $formAction=filterEvil(trim($_POST["formAction"]));
  $OrderNo=filterEvil(trim($_POST["OrderNo"]));
  $keytxt=filterEvil(trim($_POST["keytxt"]));
  //echo $formAction." ".$OrderNo." ".$keytxt;
  
  $today=date('Y-m-d H:i:s');
  if(strcmp($formAction,"applied")==0){//審核通過
	$strSQL="update PT_Outline set VerifyDate='".$today."',VerifyEmp='".$_SESSION['UserID']."',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='1' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已審核通過');</script>";
  }else if(strcmp($formAction,"lock")==0){//鎖定單子
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-2' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已鎖定');</script>";
  }else if(strcmp($formAction,"unlock")==0){//解鎖定單子
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='0' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已退回');</script>";
  }else if(strcmp($formAction,"unapplied")==0){//解除單子已審核狀態
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='0' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已取消審核狀態');</script>";
  }else if(strcmp($formAction,"delete")==0){//註銷單子
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-1' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	//需再註記PT_employed 和 PT_PayInfo 內的記錄為刪除
	$strSQL="update PT_Employed set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',RecordStatus='-1' where SerialNo='".$OrderNo."'";
	$result=$db->query($strSQL);
	$strSQL="update PT_PayInfo set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',PayStatus='-1' where EidSerialNo='".$OrderNo."'";
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已註銷');</script>";
  }
  
  //處理單子查詢
  //$strSQL="exec sp_getPTTransList ?";
  //$strSQL="select * from pt_outline";
  //echo $strSQL.$keytxt;
  //$result=$db->prepare($strSQL);
  //$result->execute(array($keytxt));
  $strSQL="select * from ".
		  "(select p.*,v.name from PT_Outline p ".
		  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
		  "where (p.SerialNo like '%'+rtrim('".$keytxt."')+'%' or p.BugNo like '%'+rtrim('".$keytxt."')+'%') and p.OrderType='異動' ".
		  "union ".
		  "select p.*,v.name from PT_Outline p ".
		  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
		  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and [Name] like '%'+rtrim('".$keytxt."')+'%') and p.OrderType='異動' ".
		  "union ".
		  "select p.*,v.name from PT_Outline p ".
		  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
		  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and (IdCode like '%'+rtrim('".$keytxt."')+'%' ".
		  "or Pid like '%'+rtrim('".$keytxt."')+'%')) ".
		  "and p.OrderType='異動') as a order by SerialNo desc";
  $result=$db->query($strSQL);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>無標題文件</title>
<style>
table{padding:0px; margin:0px; text-align:center; font-size:10pt}
.ls{margin:0px; padding:2px;}
</style>
</head>
<body bgcolor='#c1cfb4'>
<form name="Form1" id="Form1" method="POST" action="qry_PTapply.php" target="_self">
<input type="hidden" name="formAction" id="formAction" value="">
<input type="hidden" name="OrderNo" id="OrderNo" value="">
<input type="hidden" name="bugetno" id="bugetno" value="">
  <input name="radio" type="radio" id="radio" onClick="" value="1" checked>顯示兼任異動單
  <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="radio" name="radio" id="radio2" value="2" onClick="submit();" >顯示兼任異動單-->
  <br>&nbsp;&nbsp;&nbsp;&nbsp;輸入欲搜尋資料 <font size="-1" color="#FF0000">(姓名、表單編號、計畫編號、員工編號)</font>
  <input name="keytxt" type="text"><input name="qrybutton" type="button" value="查詢" onclick="javascript:qry();">	

	<table width="1000"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">
		<tr bgcolor="#FF8080">
			<td >建立日期</td>
			<td >表單編號</td>
			<td >承辦人</td>
			<td width="30">計畫編號</td>
			<td width="150">計畫名稱</td>
			<td >列印表單</td>		  
			<td >人事室審核狀態</td>
			<td >修改</td>
			<td >註銷該單</td>	
			<td>備註</td>
		</tr>
	<?
		if($row=$result->fetch()){
			if($_SESSION["UserID"]==trim($row['CreateEmp']) || $_SESSION["power"]=="1"){
				$bgcolor="white";
				$FormStatus=trim($row['FormStatus']);
				echo "<tr bgcolor='".$bgcolor."'>".
					 "<td width='75'>".trim($row['CreateDate'])."</td><td>".trim($row['SerialNo'])."</td><td>".trim($row['name'])."</td><td>".trim($row['BugNo'])."</td><td align='left'>".trim($row['bugname'])."</td>";
				if($FormStatus=="-1"){//註銷不可列印單子
					echo "<td>--</td>";
				}else{
					echo "<td><a href='' onClick=\"javascript:printForm('".trim($row['SerialNo'])."');\">兼任人員異動單</a></td>";
				}
				if($FormStatus=="0" || $FormStatus=="-2"){
					echo "<td>-待審- ";
					if($_SESSION["power"]=="1"){
						if($FormStatus=="-2"){
							echo "<input name=\"appliedbutton\" type=\"button\" value=\"審核通過\" onclick=\"javascript:appliedForm('".trim($row['SerialNo'])."');\">";
							echo "<input name=\"unlockbutton\" type=\"button\" value=\"退回(解鎖定)\" onclick=\"javascript:unlockForm('".trim($row['SerialNo'])."');\">";
						}else{
							echo "<input name=\"lockbutton\" type=\"button\" value=\"鎖定\" onclick=\"javascript:lockForm('".trim($row['SerialNo'])."');\">";
						}
					}else{
						if($FormStatus=="-2"){echo "鎖定中";}
					}
					echo "</td>";
				}else if($FormStatus=="1"){
					echo "<td>通過(".$row['VerifyDate'].")";
					if($_SESSION["UserID"]=="S0303"){
						echo "<input name=\"unappliedbutton\" type=\"button\" value=\"退回審核\" onclick=\"javascript:unappliedForm('".trim($row['SerialNo'])."','UnApplyReason_".trim($row['SerialNo'])."');\">";
					}
					echo "</td>";
				}else if($FormStatus=="-2"){
					echo "<td>退回(".$row['VerifyDate'].")</td>";
				}else{"<td>--</td>";}
				
				if($FormStatus=="1" || $FormStatus=="-1" || $FormStatus=="-2"){//通過/註銷/鎖定無法再修改
					echo "<td>--</td>";
				}else{
					echo "<td><input name=\"editbutton\" type=\"button\" value=\"修改\" onclick=\"javascript:editForm('".trim($row['SerialNo'])."','".trim($row['BugNo'])."');\"></td>";
				}
				if($FormStatus=="-1"){//註銷無法再註銷
					echo "<td>本單已註銷</td>";
				}else{
					if($FormStatus=="0"){
						echo "<td><input name=\"cancelbutton\" type=\"button\" value=\"註銷\" onclick=\"javascript:cancelForm('".trim($row['SerialNo'])."');\"></td>";
					}else{echo "<td>--</td>";}
				}
				if($FormStatus=="1"){
					echo "<td>";
					if($_SESSION["UserID"]=="S0303"){
						echo "<textarea name='UnApplyReason_".trim($row['SerialNo'])."' id='UnApplyReason_".trim($row['SerialNo'])."' rows='1' cols='10'>".trim($row['UnapplyReason'])."</textarea></td>";
					}else if($_SESSION["power"]=="1"){
						echo trim($row['UnapplyReason']);
					}else{
						echo "--";
					}
					echo "</td>";
				}else{
					echo "<td>";
					if($_SESSION["power"]=="1"){
						echo trim($row['UnapplyReason']);
					}else{
						echo "--";
					}
					echo "</td>";
				}
				echo "</tr>";
			}
			while($row=$result->fetch()){
				if($_SESSION["UserID"]==trim($row['CreateEmp']) || $_SESSION["power"]=="1"){
					$FormStatus=trim($row['FormStatus']);
					if($bgcolor=="white"){$bgcolor="yellow";}
					else{$bgcolor="white";}
					echo "<tr bgcolor='".$bgcolor."'>".
						 "<td>".trim($row['CreateDate'])."</td><td>".trim($row['SerialNo'])."</td><td>".trim($row['name'])."</td><td>".trim($row['BugNo'])."</td><td align='left'>".trim($row['bugname'])."</td>";
					if($FormStatus=="-1"){//註銷不可列印單子
						echo "<td>--</td>";
					}else{
						echo "<td><a href='' onClick=\"javascript:printForm('".trim($row['SerialNo'])."');\">兼任人員異動單</a></td>";
					}
					if($FormStatus=="0" || $FormStatus=="-2"){
						echo "<td>-待審- ";
						if($_SESSION["power"]=="1"){
							if($FormStatus=="-2"){
								echo "<input name=\"appliedbutton\" type=\"button\" value=\"審核通過\" onclick=\"javascript:appliedForm('".trim($row['SerialNo'])."');\">";
								echo "<input name=\"unlockbutton\" type=\"button\" value=\"退回(解鎖定)\" onclick=\"javascript:unlockForm('".trim($row['SerialNo'])."');\">";
							}else{
								echo "<input name=\"lockbutton\" type=\"button\" value=\"鎖定\" onclick=\"javascript:lockForm('".trim($row['SerialNo'])."');\">";
							}
						}else{
							if($FormStatus=="-2"){echo "鎖定中";}
						}
						echo "</td>";
					}else if($FormStatus=="1"){
						echo "<td>通過(".$row['VerifyDate'].")";
						if($_SESSION["UserID"]=="S0303"){
							echo "<input name=\"unappliedbutton\" type=\"button\" value=\"退回審核\" onclick=\"javascript:unappliedForm('".trim($row['SerialNo'])."','UnApplyReason_".trim($row['SerialNo'])."');\">";
						}
						echo "</td>";
					}else if($FormStatus=="-2"){
						echo "<td>退回(".$row['VerifyDate'].")</td>";
					}else{echo "<td>--</td>";}
					
					if($FormStatus=="1" || $FormStatus=="-1" || $FormStatus=="-2"){//通過/註銷/鎖定無法再修改
						echo "<td>--</td>";
					}else{
						echo "<td><input name=\"editbutton\" type=\"button\" value=\"修改\" onclick=\"javascript:editForm('".trim($row['SerialNo'])."','".trim($row['BugNo'])."');\"></td>";
					}
					if($FormStatus=="-1"){//註銷無法再註銷
						echo "<td>本單已註銷</td>";
					}else{
						if($FormStatus=="0"){
							echo "<td><input name=\"cancelbutton\" type=\"button\" value=\"註銷\" onclick=\"javascript:cancelForm('".trim($row['SerialNo'])."');\"></td>";
						}else{echo "<td>--</td>";}
					}
					if($FormStatus=="1"){
						echo "<td>";
						if($_SESSION["UserID"]=="S0303"){
							echo "<textarea name='UnApplyReason_".trim($row['SerialNo'])."' id='UnApplyReason_".trim($row['SerialNo'])."' rows='1' cols='10'>".trim($row['UnapplyReason'])."</textarea></td>";
						}else if($_SESSION["power"]=="1"){
							echo trim($row['UnapplyReason']);
						}else{
							echo "--";
						}
						echo "</td>";
					}else{
						echo "<td>";
						if($_SESSION["power"]=="1"){
							echo trim($row['UnapplyReason']);
						}else{
							echo "--";
						}
						echo "</td>";
					}
					echo "</tr>";
				}
			}
		}else{
			echo "查無資料";
			print_r($db->errorInfo());
		}
	?>
	</table>
</form>
</body>
</html>



<script language="javascript">
function editForm(OrderNo,bugetno){
	document.Form1.OrderNo.value=OrderNo;
	document.Form1.formAction.value="edit";
	document.Form1.bugetno.value=bugetno;
	document.Form1.action="new_PTForm.php";
	document.Form1.target="_blank";
	document.Form1.submit();
}
function appliedForm(OrderNo){
	//alert(OrderNo);
	document.Form1.OrderNo.value=OrderNo;
	document.Form1.formAction.value="applied";	
	document.Form1.method = "post";
	document.Form1.action="qry_PTapply.php";
	document.Form1.target="_self";
	//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
	document.Form1.submit();
}
function lockForm(OrderNo){
	//alert(OrderNo);
	if(confirm("是否鎖定異動單號"+OrderNo)){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="lock";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTapply.php";
		document.Form1.target="_self";
		//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
		document.Form1.submit();
	}
}
function unlockForm(OrderNo){
	//alert(OrderNo);
	if(confirm("是否退回(解除鎖定)異動單號"+OrderNo)){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="unlock";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTapply.php";
		document.Form1.target="_self";
		//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
		document.Form1.submit();
	}
}
function unappliedForm(OrderNo,reason_id){
	//alert(OrderNo);
	var reason=document.getElementById(reason_id).value;
	if(reason.trim()==""){
		alert("請填寫退回審核理由");
		return false;
	}
	if(confirm("是否取消異動單號"+OrderNo+"已審核狀態")){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="unapplied";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTapply.php";
		document.Form1.target="_self";
		//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
		document.Form1.submit();
	}
}
function cancelForm(OrderNo){
	//alrt(OrderNo);
	if(confirm("是否刪除異動單號"+OrderNo)){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="delete";
		document.Form1.method = "post";
		document.Form1.action="qry_PTapply.php";
		document.Form1.target="_self";
		document.Form1.submit();
	}
}
function printForm(OrderNo){
	document.Form1.OrderNo.value=OrderNo;
	document.Form1.action="form1.php?OrderNo="+OrderNo;
	document.Form1.target="_blank";
	document.Form1.submit();
}
function qry(){
	document.Form1.action="qry_PTapply.php";
	document.Form1.target="_self";
	document.Form1.submit();
}
</script>