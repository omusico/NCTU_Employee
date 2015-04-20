<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>無標題文件</title>
<style>
table{padding:0px; margin:0px; text-align:center; font-size:10pt}
.ls{margin:0px; padding:2px;}
</style>
</head>
<?php
  ini_set('memory_limit', '1024M');
  include("connectSQL.php");
  include("function.php");
  $formAction="";$OrderNo="";$keytxt="";
  $formAction=filterEvil(trim($_POST["formAction"]));
  $OrderNo=filterEvil(trim($_POST["OrderNo"]));
  $keytxt=filterEvil(trim($_POST["keytxt"]));
  //echo $formAction." ".$OrderNo." ".$keytxt;
  $page_ShowCount=50;
  $page_Show=filterEvil($_POST["BeginIndex"]);
  if($page_Show==""){$page_Show=1;}
  $page_Show_Begin=($page_Show-1)*50+1;
  $page_Show_End=$page_Show_Begin+$page_ShowCount-1;
  
  $today=date('Y-m-d H:i:s');
  if(strcmp($formAction,"applied")==0){//審核通過
	//轉到verify_apply.php中處理
	/*$strSQL="update PT_Outline set VerifyDate='".$today."',VerifyEmp='".$_SESSION['UserID']."',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='1' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	//payinfo設定為己審核
	$strSQL="update PT_PayInfo set PayStatus='1',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where Eid in (select Eid from PT_employed where SerialNo='".$OrderNo."' and RecordStatus='0')";
	//註記舊單被異動,包含PT_employed & PT_PayInfo
	$strSQL2="update PT_employed set RecordStatus='-2',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',TransformedSN='".$OrderNo."' where Eid in (select FromEid from PT_employed where SerialNo='".$OrderNo."' and RecordStatus in ('0','-3')) and RecordStatus<>'-1'";
	$strSQL3="update PT_PayInfo set PayStatus='-2',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where Eid in (select FromEid from PT_employed where SerialNo='".$OrderNo."' and RecordStatus in ('0','-3')) and PayStatus='0'";
	$db->query($strSQL) or die($strSQL);
	$db->query($strSQL3) or die($strSQL3);
	$db->query($strSQL2) or die($strSQL2);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已審核通過');</script>";*/
  }else if(strcmp($formAction,"lock")==0){//鎖定單子
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-2' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已鎖定');</script>";
  }else if(strcmp($formAction,"unlock")==0){//解鎖定單子
	$reason=filterEvil($_POST['UnLockReason_'.$OrderNo]);
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='0',UnLockReason='".$reason."' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已退回');</script>";
  }else if(strcmp($formAction,"unapplied")==0){//解除單子已審核狀態,退回鎖定狀態
	$reason=filterEvil($_POST['UnApplyReason_'.$OrderNo]);
	//先確認本單內的記錄沒有被異動的狀況
	$strSQL="select distinct SerialNo from PT_Employed where FromSN='".$OrderNo."' and RecordStatus<>'-1'";
	$result=$db->query($strSQL);
	$row=$result->fetchAll();
	if(count($row)>0){
		$result=$db->query($strSQL);
		$mod_records= array();
		while($row=$result->fetch()){
			if($row['SerialNo']!=$OrderNo){array_push($mod_records,$row['SerialNo']);}
		}
		echo "<script language='javascript'>alert('本單的內容已於異動單 ".implode(",",$mod_records)." 中異動,請先取消上述異動單再退審核');</script>";
	}
	else{
		$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-2',UnapplyReason='".$reason."' where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);
		//先回復被異動的payinfo,只有PayStatus=-3的回復為1,連結資訊清空
		$strSQL="update PT_PayInfo set PayStatus='1',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',".
				"TransformedSN=Null,TransformedEid=Null,TransformedSN2=Null,TransformedEid2=Null ".
				"where PayStatus='-3' and Eid in (select distinct FromEid from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus='0')";
		$result=$db->query($strSQL);
		//PT_PayInfo 內的記錄需刪除
		$strSQL="delete from PT_PayInfo where Eid in (select Eid from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus<>'-1')";
		$result=$db->query($strSQL);
		
		echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已取消審核狀態');</script>";
	}
  }else if(strcmp($formAction,"delete")==0){//註銷單子
	$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-1' where SerialNo='".$OrderNo."'";
	//echo $strSQL;
	$result=$db->query($strSQL);
	//需再註記PT_employed 為刪除,和刪除 PT_PayInfo 內的記錄
	$strSQL="delete from PT_PayInfo where Eid in (select Eid from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus<>'-1')";
	$result=$db->query($strSQL);
	$strSQL="update PT_Employed set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',RecordStatus='-1' where SerialNo='".$OrderNo."' and RecordStatus<>'-1'";
	$result=$db->query($strSQL);
	echo "<script language='javascript'>alert('異動單 ".$OrderNo." 已註銷');</script>";
  }
  
  //處理單子查詢
  //$strSQL="exec sp_getPTTransList ?";
  //$strSQL="select * from pt_outline";
  //echo $strSQL.$keytxt;
  //$result=$db->prepare($strSQL);
  //$result->execute(array($keytxt));
  if($_SESSION["power"]=="1"){
	  $strSQL="select count(*) as count from ".
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
			  "and p.OrderType='異動') as a ";
	  //echo $strSQL;
	  $result=$db->query($strSQL);
	  $row=$result->fetch();
	  $rowcount=$row['count'];		  
	  $strSQL="select top ".$page_Show_End." * from ".
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
  }else{
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
	  $strSQL="select count(*) as count from ".
			  "(select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where (p.SerialNo like '%'+rtrim('".$keytxt."')+'%' or p.BugNo like '%'+rtrim('".$keytxt."')+'%') ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  "union ".
			  "select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and [Name] like '%'+rtrim('".$keytxt."')+'%') ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  "union ".
			  "select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and (IdCode like '%'+rtrim('".$keytxt."')+'%' ".
			  "or Pid like '%'+rtrim('".$keytxt."')+'%')) ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  ") as a ";
	  $result=$db->query($strSQL);
	  $row=$result->fetch();
	  $rowcount=$row['count'];
	  $strSQL="select top ".$page_Show_End." * from ".
			  "(select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where (p.SerialNo like '%'+rtrim('".$keytxt."')+'%' or p.BugNo like '%'+rtrim('".$keytxt."')+'%') ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  "union ".
			  "select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and [Name] like '%'+rtrim('".$keytxt."')+'%') ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  "union ".
			  "select p.*,v.name from PT_Outline p ".
			  "left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v on v.empno=p.CreateEmp ".
			  "where p.SerialNo in (select SerialNo from PT_Employed where RecordStatus<>'-1' and (IdCode like '%'+rtrim('".$keytxt."')+'%' ".
			  "or Pid like '%'+rtrim('".$keytxt."')+'%')) ".
			  "and p.OrderType='異動' and ".$qryStr." ".
			  ") as a order by SerialNo desc";
  }
  $result=$db->query($strSQL);
?>
<body bgcolor='#c1cfb4'>
<form name="Form1" id="Form1" method="POST" action="qry_PTtransform.php" target="_self">
<input type="hidden" name="formAction" id="formAction" value="">
<input type="hidden" name="OrderNo" id="OrderNo" value="">
<input type="hidden" name="bugetno" id="bugetno" value="">
<input type="hidden" name="BeginIndex" id="BeginIndex" value="<?echo $page_Show;?>">
  &nbsp;&nbsp;&nbsp;&nbsp;顯示兼任異動單
  <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="radio" name="radio" id="radio2" value="2" onClick="submit();" >顯示兼任異動單-->
  <br>&nbsp;&nbsp;&nbsp;&nbsp;輸入欲搜尋資料 <font size="-1" color="#FF0000">(姓名、表單編號、計畫編號、員工編號)</font>
  <input name="keytxt" type="text"><input name="qrybutton" type="button" value="查詢" onclick="javascript:qry();">	

	<table width="1000"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">
		<tr bgcolor="#FF8080">
			<td>&nbsp;</td>
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
		if($rowcount>0){
			$result=$db->query($strSQL);
			$rowindex=0;
			while($row=$result->fetch()){
				$rowindex++;
				if($rowindex>=$page_Show_Begin && $rowindex<=$page_Show_End){
					if($_SESSION["UserID"]==trim($row['CreateEmp']) || $_SESSION["power"]=="1"){
						$FormStatus=trim($row['FormStatus']);
						if($bgcolor=="white"){$bgcolor="yellow";}
						else{$bgcolor="white";}
						echo "<tr bgcolor='".$bgcolor."'><td>".$rowindex."</td>".
							 "<td>".trim($row['CreateDate'])."</td><td>".trim($row['SerialNo'])."</td><td>".trim($row['name'])."</td><td>".trim($row['BugNo'])."</td><td align='left'>".mb_substr(trim($row['bugname']),0,50,"utf-8")."</td>";
						if($FormStatus=="-1"){//註銷不可列印單子
							echo "<td>--</td>";
						}else{
							echo "<td><a href='https://Receipt-test.nctu.edu.tw/test/phpio_new.php?OrderNo=".trim($row['SerialNo'])."&action=PTform2' target='_blank' >兼任人員異動單</a></td>";							
						}
						if($FormStatus=="0" || $FormStatus=="-2"){
							echo "<td>-待審- ";
							if($_SESSION["power"]=="1"){
								if($FormStatus=="-2"){
									//20150407要求審核按鈕別放在此頁面
									//echo "<input name=\"appliedbutton\" type=\"button\" value=\"審核\" onclick=\"javascript:appliedForm('".trim($row['SerialNo'])."');\">";
									echo "<input name=\"unlockbutton\" type=\"button\" value=\"退回(解鎖定)\" onclick=\"javascript:unlockForm('".trim($row['SerialNo'])."','UnLockReason_".trim($row['SerialNo'])."');\">";
								}else{
									echo "<input name=\"lockbutton\" type=\"button\" value=\"鎖定\" onclick=\"javascript:lockForm('".trim($row['SerialNo'])."');\">";
								}
							}else{
								if($FormStatus=="-2"){echo "鎖定中";}
							}
							echo "</td>";
						}else if($FormStatus=="1"){
							echo "<td>通過(".$row['VerifyDate'].")";
							if($_SESSION["UserID"]=="S0303" || $_SESSION["UserID"]=="S0005"){
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
							if($_SESSION["UserID"]=="S0303" || $_SESSION["UserID"]=="S0005"){
								echo "<td align='left'>";
								echo "填寫退回審核理由:<br><textarea name='UnApplyReason_".trim($row['SerialNo'])."' id='UnApplyReason_".trim($row['SerialNo'])."' rows='1' cols='10'>".trim($row['UnapplyReason'])."</textarea></td>";
							}else if($_SESSION["power"]=="1" && trim($row['UnapplyReason'])!=""){
								echo "<td align='left'>";
								echo "退回審核理由:<br>".trim($row['UnapplyReason']);
							}else{
								echo "<td>";
								echo "--";
							}
							echo "</td>";
						}else if($FormStatus=="-2"){
							if($_SESSION["power"]=="1"){
								echo "<td align='left'>";
								echo "填寫解鎖定理由:<br><textarea name='UnLockReason_".trim($row['SerialNo'])."' id='UnLockReason_".trim($row['SerialNo'])."' rows='1' cols='10'>".trim($row['UnLockReason'])."</textarea></td>";
							}else{
								echo "<td>";
								echo "--";
							}
							echo "</td>";
						}else{
							$msg="";
							if(trim($row['UnapplyReason'])!=""){
								$msg.= "已審核單退回理由:<br>".trim($row['UnapplyReason']);
							}
							if(trim($row['UnLockReason'])!=""){
								if(strlen($msg)!=0){$msg.="<br><br>";}
								$msg.= "請核單退回理由:<br>".trim($row['UnLockReason']);
							}
							if($msg!=""){
								echo "<td align='left'>";
								echo $msg;
							}else{
								echo "<td>";
								echo "--";
							}
							echo "</td>";
						}
						echo "</tr>";
					}
				}elseif($rowindex>$page_Show_End){break;}
			}
		}else{
			echo "查無資料";
			//print_r($db->errorInfo());
		}
	//產生頁索引
	echo "<tr>".
		 "<td colspan='11'>";
	echo "<a href=\"#\" onClick=\"javascript:changePage('1');\">第一頁</a>&nbsp;&nbsp;&nbsp;";
	if($page_Show>1){
		"<a href=\"#\" onClick=\"javascript:changePage('".($page_Show-1)."');\">上一頁</a>&nbsp;&nbsp;&nbsp;";
	}
	if(($page_Show-5)>0){$firstpage=$page_Show-5;}
	else{$firstpage=1;}
	
	$lastpage=ceil($rowcount/$page_ShowCount);
	
	if(($page_Show+5)<=$lastpage){
		if(((int)$page_Show-5)<0){$endpage=$page_Show+5+(5-$page_Show);}
		else{$endpage=$page_Show+5;}
	}else{$endpage=$lastpage;}
	
	for($pageindex=$firstpage;$pageindex<=$endpage;$pageindex++){
		if($pageindex==$page_Show){echo $pageindex;}
		else{
			echo "<a href=\"#\" onClick=\"javascript:changePage('".$pageindex."');\">".$pageindex."</a>";
		}
		echo "&nbsp;&nbsp;&nbsp;";
	}
	if($page_Show<$lastpage){
		echo "<a href=\"#\" onClick=\"javascript:changePage('".($page_Show+1)."');\">下一頁</a>&nbsp;&nbsp;&nbsp;";
	}
	echo "<a href=\"#\" onClick=\"javascript:changePage('".$lastpage."');\">最末頁</a>&nbsp;&nbsp;&nbsp;";
	echo "</td></tr>";
	?>
	</table>
</form>
</body>
</html>



<script language="javascript">
function changePage(pageindex){
	document.Form1.BeginIndex.value=pageindex;
	//document.getElementById("BeginIndex").value=pageindex;
	document.Form1.action="qry_PTtransform.php";
	document.Form1.target="_self";
	document.Form1.submit();
}
function editForm(OrderNo,bugetno){
	document.Form1.OrderNo.value=OrderNo;
	document.Form1.formAction.value="edit";
	document.Form1.bugetno.value=bugetno;
	document.Form1.action="mod_PTtransform_select.php";
	document.Form1.target="_blank";
	document.Form1.submit();
}
function appliedForm(OrderNo){
	//alert(OrderNo);
	document.Form1.OrderNo.value=OrderNo;
	document.Form1.formAction.value="";	
	document.Form1.method = "post";
	document.Form1.action="verify_apply.php";
	document.Form1.target="_blank";
	//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
	document.Form1.submit();
}
function lockForm(OrderNo){
	//alert(OrderNo);
	if(confirm("是否鎖定異動單號"+OrderNo)){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="lock";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTtransform.php";
		document.Form1.target="_self";
		//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
		document.Form1.submit();
	}
}
function unlockForm(OrderNo,reason_id){
	//alert(OrderNo);
	var reason=String(document.getElementById(reason_id).value);
	if(reason==""){
		alert("請填寫退回(解除鎖定)理由");
		return false;
	}
	if(confirm("是否退回(解除鎖定)異動單號"+OrderNo)){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="unlock";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTtransform.php";
		document.Form1.target="_self";
		//alert(document.Form1.OrderNo.value+document.Form1.formAction.value);
		document.Form1.submit();
	}
}
function unappliedForm(OrderNo,reason_id){
	//alert(OrderNo);
	var reason=String(document.getElementById(reason_id).value);
	if(reason==""){
		alert("請填寫退回審核理由");
		return false;
	}
	if(confirm("是否取消異動單號"+OrderNo+"已審核狀態")){
		document.Form1.OrderNo.value=OrderNo;
		document.Form1.formAction.value="unapplied";	
		document.Form1.method = "post";
		document.Form1.action="qry_PTtransform.php";
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
		document.Form1.action="qry_PTtransform.php";
		document.Form1.target="_self";
		document.Form1.submit();
	}
}
function qry(){
	document.Form1.action="qry_PTtransform.php";
	document.Form1.target="_self";
	document.Form1.submit();
}
</script>