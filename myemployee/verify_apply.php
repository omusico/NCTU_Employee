<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/JS/jquery-impromptu.js"></script>
<script type="text/javascript">
	$(function(){
		$("input[name='acceptfile']").click(function(){
			if(confirm("確定要通過此文件嗎?")==false)
				return false;
			var fid=$(this).attr("fid");
								
			//alert(fid+" acceptfile");
			$.ajax({

				type:"POST",
				dataType:"text",
				data:{fid:fid,action:"acceptfile"},
				url:"fileoperation.php",
				
				success:function(msg){
					if(msg=="true"){
						alert("審核通過成功");
						var opstr="#op_"+fid;
						//alert(opstr);
						$(opstr).html("審核通過");
					}else{
						alert("審核通過失敗");					
						//alert(msg);					
					}},
				error:function(){
					alert("審核操作錯誤!!");
				}	
			});
		});

		$("input[name='unacceptfile']").click(function(){
			if(confirm("確定要退回此文件嗎?")==false)
				return false;
			var fid=$(this).attr("fid");
			$.ajax({
				type:"POST",
				dataType:"text",
				data:{fid:fid,action:"unacceptfile"},
				url:"fileoperation.php",
				
				success:function(msg){
					if(msg=="true"){
						alert("退回文件成功");
						var opstr="#op_"+fid;
						//alert(opstr);
						$(opstr).html("已退回");
					}else{
						alert("退回文件失敗");					
						//alert(msg);					
					}},
				error:function(){
					alert("退回操作錯誤!!");
					
				}	
			});
		});
	});
</script>
</head>

<?php 
	include("connectSQL.php");
	include("function.php");
	
	$OrderNo=filterEvil($_POST['OrderNo']);
	$formAction=filterEvil($_POST['formAction']);
	$Eid=filterEvil($_POST['Eid']);
	
	$today=date('Y-m-d H:i:s');
	//第一次查表單狀態,供下面formAction處理使用
	if($OrderNo!=""){
		$strSQL="select * from PT_Outline where SerialNo='".$OrderNo."'";
		$result=$db->query($strSQL);
		$apply_row=$result->fetch();
		$ApplyType=trim($apply_row['OrderType']);
		$BugNo=trim($apply_row['BugNo']);
	}
	
	if(strcmp($formAction,"applied")==0){//審核通過
		if(strcmp($ApplyType,"請核")==0){//請核單較簡單,只要修改表單狀態即可
			$strSQL="update PT_Outline set VerifyDate='".$today."',VerifyEmp='".$_SESSION['UserID']."',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='1' where SerialNo='".$OrderNo."'";
			//echo $strSQL;
			$result=$db->query($strSQL);
			//產生payinfo資料
			$strSQL="select distinct Eid,BeginDate,EndDate,TotalAmount,".
					"(datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
					"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
					"from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus='0'";
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				$Eid_now=trim($row['Eid']);
				$start_y=trim($row['start_y']);
				$start_m=trim($row['start_m']);
				$start_d=trim($row['start_d']);
				$end_y=trim($row['end_y']);
				$end_m=trim($row['end_m']);
				$end_d=trim($row['end_d']);
				$totalpay=trim($row['TotalAmount']);
				$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);				
				//print_r($yymm);
				for($index=0;$index<sizeof($yymm);$index++){
					$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
					$strSQL="insert into PT_PayInfo select '".$BugNo."','".$Eid_now."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$_SESSION['UserID']."','1','".$yymm[$index][5]."',null,null,null,null,null,null,'".$Eid_now."'";
					//echo $strSQL."<br>";
					$db->query($strSQL);
				}
				while($row=$result->fetch()){
					$Eid_now=trim($row['Eid']);
					$start_y=trim($row['start_y']);
					$start_m=trim($row['start_m']);
					$start_d=trim($row['start_d']);
					$end_y=trim($row['end_y']);
					$end_m=trim($row['end_m']);
					$end_d=trim($row['end_d']);
					$totalpay=trim($row['TotalAmount']);
					$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
					//print_r($yymm);
					for($index=0;$index<sizeof($yymm);$index++){
						$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
						$strSQL="insert into PT_PayInfo select '".$BugNo."','".$Eid_now."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$_SESSION['UserID']."','1','".$yymm[$index][5]."',null,null,null,null,null,null,'".$Eid_now."'";
						//echo $strSQL."<br>";
						$db->query($strSQL);
					}
				}
			}
		}else{//異動單除改表單外,仍需要修改舊記錄,並留下連結資訊,
			  //含TransformedSN(異動單編號),TransformedEid(異動單Eid),TransformedStatus(異動前Eid狀態)
			$strSQL="update PT_Outline set VerifyDate='".$today."',VerifyEmp='".$_SESSION['UserID']."',updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='1' where SerialNo='".$OrderNo."'";
			//echo $strSQL;
			$result=$db->query($strSQL);
			//抓pt_employed的異動資料出來產生payinfo資料並註記First_Eid和雙向連結			
			$strSQL="select distinct Eid,BeginDate,EndDate,FromSN,FromEid,FirstEid,RecordStatus,TotalAmount,".
					"(datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
					"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
					"from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus in ('0','-2')";
			$pt_result=$db->query($strSQL);
			$pt_row=$pt_result->fetchAll();
			if(count($pt_row)>0){
				$pt_result=$db->query($strSQL);
				while($pt_row=$pt_result->fetch()){
					$Eid_now=trim($pt_row['Eid']);
					$start_y=trim($pt_row['start_y']);
					$start_m=trim($pt_row['start_m']);
					$start_d=trim($pt_row['start_d']);
					$end_y=trim($pt_row['end_y']);
					$end_m=trim($pt_row['end_m']);
					$end_d=trim($pt_row['end_d']);
					$totalpay=trim($pt_row['TotalAmount']);
					$FromSN=trim($pt_row['FromSN']);
					$FromEid=trim($pt_row['FromEid']);
					$FirstEid=trim($pt_row['FirstEid']);
					//若PT_employed.RecordStatus='0',則Payinfo.PayStatus='1'
					//若PT_employed.RecordStatus='-2',則Payinfo.PayStatus='-2'
					if(trim($pt_row['RecordStatus'])=="0"){$RecordStatus="1";}
					else{$RecordStatus="-2";}
					$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
					//print_r($yymm);
					//產生payinfo資料
					for($index=0;$index<sizeof($yymm);$index++){
						$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
						$strSQL="insert into PT_PayInfo select '".$BugNo."','".$Eid_now."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$_SESSION['UserID']."','".$RecordStatus."','".$yymm[$index][5]."',null,null,null,null,'".$FromSN."','".$FromEid."','".$FirstEid."'";
						//echo $strSQL."<br>";
						$db->query($strSQL);
						//舊的payinfo狀態改變,並補上連結資訊,舊payinfo.PayStatus='1'的要改成payinfo.PayStatus='-3'
						//可能會被切成2段,確認要寫的是第一transformed還是第二
						$strSQL="select * from PT_PayInfo where Eid='".$FromEid."' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
						$result=$db->query($strSQL) or die($strSQL);
						$row=$result->fetch();
						if($row['TransformedEid']==""){
							$strSQL="update PT_PayInfo set PayStatus='-3',TransformedSN='".$OrderNo."',TransformedEid='".$Eid_now."',UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where Eid='".$FromEid."' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
							$db->query($strSQL);
						}else{
							$strSQL="update PT_PayInfo set PayStatus='-3',TransformedSN2='".$OrderNo."',TransformedEid2='".$Eid_now."',UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where Eid='".$FromEid."' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
							$db->query($strSQL);
						}
					}
				}
			}
		}
		echo "<script language='javascript'>alert('".$ApplyType."單 ".$OrderNo." 已審核通過');</script>";
	  }else if(strcmp($formAction,"lock")==0){//鎖定單子
		$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-2' where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);
		echo "<script language='javascript'>alert('".$ApplyType."單 ".$OrderNo." 已鎖定');</script>";
	  }else if(strcmp($formAction,"unlock")==0){//解鎖定單子
		$reason=filterEvil($_POST['UnLockReason']);
		$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='0',UnLockReason='".$reason."' where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);
		echo "<script language='javascript'>alert('".$ApplyType."單 ".$OrderNo." 已退回(解鎖定)');</script>";
	  }else if(strcmp($formAction,"unapplied")==0){//解除單子已審核狀態
		//本處無實作解除已審核動作,在qry_PTapply.php和qry_PT_Transform.php中
		/*$reason=filterEvil($_POST['UnApplyReason_'.$OrderNo]);
		$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='0',UnapplyReason='".$reason."' where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);*/
		//echo "<script language='javascript'>alert('".$ApplyType."單 ".$OrderNo." 已取消審核狀態');</script>";
	  }else if(strcmp($formAction,"delete")==0){//註銷單子
		$strSQL="update PT_Outline set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',FormStatus='-1' where SerialNo='".$OrderNo."'";
		//echo $strSQL;
		$result=$db->query($strSQL);		
		//需再註記PT_employed 為刪除,和刪除 PT_PayInfo 內的記錄
		$strSQL="delete from PT_PayInfo where Eid in (select Eid from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus<>'-1')";
		$result=$db->query($strSQL);
		$strSQL="update PT_Employed set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',RecordStatus='-1' where SerialNo='".$OrderNo."' and RecordStatus<>'-1'";
		$result=$db->query($strSQL);
		echo "<script language='javascript'>alert('".$ApplyType."單 ".$OrderNo." 已註銷');</script>";
	  }
?>

<body bgcolor='#c1cfb4'>
	<form name="verify_app" id="verify_app" method="POST" action="verify_apply.php" target="_self">
	
	<fieldset border: solid 10px blue;>
		<legend>查詢請核/異動單</legend>
		<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<?
					//上面可能有做過表單狀態改變,再查一次
					if($OrderNo==""){$ApplyMsg="";$ApplyType="";}
					else{
						$ApplyMsg=$OrderNo;
						$strSQL="select * from PT_Outline where SerialNo='".$OrderNo."'";
						$result=$db->query($strSQL);
						$apply_row=$result->fetch();
						$ApplyType=trim($apply_row['OrderType']);
						$bugetno=trim($apply_row['BugNo']);
						$worktype=trim($apply_row['JobType']);
					}
				?>
				<input type="hidden" name="formAction" id="formAction" value=""><!--單子要做什麼動作-->
				<input type="hidden" name="Eid" id="Eid" value=""><!--記錄目前要審的Eid的Eid-->
				<input type="hidden" name="bugetno" id="bugetno" value="<?echo $bugetno;?>">
				<input type="hidden" name="worktype" id="worktype" value="<?echo $worktype;?>">
				<td nowrap width="100" bgcolor="#C9CBE0">搜尋請核/異動單號</td>
				<td width="260" bgcolor="FFFFCC" align="left">
				
					<input type="text" name="OrderNo" id="OrderNo" size="20" value="<?echo $ApplyMsg;?>">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="qry_apply" id="qry_apply" value="搜尋" onclick="javascript:query_apply();">
				</td>				
			</tr>				
		</table>
	</fieldset>
	<hr>
	<fieldset border: solid 10px blue;>
		<legend>計畫資料</legend>
		<?
			if(trim($apply_row['BugNo'])!=""){
				//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";	
				/*$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
						  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
						  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
						  "where v.bugetno='".trim($apply_row['BugNo'])."'";
				//echo $strSQL;
				$result=$db->query($strSQL);		*/
				$row=getBugInfo($apply_row['BugNo']);
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
					
				}else{
					$ErrMsg="查無此計畫資料，請先洽詢主計室承辦人員。";
				}
			}
		?>
		<table width="900"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<td nowrap width="100" bgcolor="#C9CBE0">計畫編號</td>
				<td width="260" bgcolor="FFFFCC" align="left"><?echo trim($apply_row['BugNo']);?></td>		 		
				<td nowrap width="120" bgcolor="#C9CBE0">計畫名稱</td>
				<td width="220" bgcolor="FFFFCC" align="left"><?if($ErrMsg!=""){echo $ErrMsg;}else{echo $bugname;}?></td>				
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
				<td bgcolor="FFFFCC" width="220" align="left"><?echo $start_y.$start_m.$start_d."-".$end_y.$end_m.$end_d;?></td>	
			</tr>
		</table>
	</fieldset>
	<hr>
	<fieldset>
		<legend><?echo $ApplyType;?>資料列表</legend>
		<table width="1000"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<th bgcolor="#C9CBE0">序號</th>
				<th bgcolor="#C9CBE0">人事代號</th><th bgcolor="#C9CBE0">姓名</th><th bgcolor="#C9CBE0">兼任職稱</th>
				<th bgcolor="#C9CBE0">工作型態</th><th bgcolor="#C9CBE0">支領項目</th><th bgcolor="#C9CBE0">請核期間</th>
				<th bgcolor="#C9CBE0">狀態</th>
				<th bgcolor="#C9CBE0">支領類別</th><th bgcolor="#C9CBE0">支領金額</th><th bgcolor="#C9CBE0">備註</th>
				<th bgcolor="#C9CBE0">功能</th>
			</tr>				
			<?	
				$bgcolor="";
				$selectedSN="";
				if($OrderNo!=""){//全部人員,列出清單
					$strSQL="select pt.*,".
							"(datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
							"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
							"from PT_Employed pt ".							
							"where pt.SerialNo='".$OrderNo."' and pt.RecordStatus in ('0','-2') order by eid";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							$selectedSN.=trim($row['Eid']).",";
							if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
							else{$bgcolor="#AFFEFF";}
							echo "<tr bgcolor=".$bgcolor.">".
								 "<td>".trim($row['Eid'])."</td><td>".trim($row['IdCode'])."</td><td>".trim($row['Name'])."</td><td>".$PT_title[$row['PTtitle']]."</td>";
							if(trim($row['JobType'])=="work"){echo "<td>工作型</td>";}else{echo "<td>學習型</td>";}
							$strSQL2="select top 1 * from  [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1' and SerialNo='".trim($row['JobItemCode'])."'";
							$result2=$db->query($strSQL2);
							$row2=$result2->fetch();
							echo "<td>".trim($row2['JobItem_1'])."</td>";
							echo "<td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
							
							if(trim($row['RecordStatus'])=="0"){echo "<td>有效</td>";$TransType="2";}
							else{echo "<td>取消聘期</td>";$TransType="1";}
							
							if(isset($row['MonthExpense'])){
								echo "<td>月薪</td>";$paytype="month_pay";$paytypeStr="月薪";
								$pay_unit=$row['MonthExpense'];
								$pay_limit="1";
							}else if(isset($row['AwardUnit'])){
								echo "<td>獎助單元</td>";$paytype="award_pay";$paytypeStr="獎助單元";
								$pay_unit=$row['AwardUnit'];
								$pay_limit=$row['AwardLimit'];
							}else if($row['PayType']=="hr_pay"){
								echo "<td>時薪</td>";$paytype="hr_pay";$paytypeStr="時薪";
								$pay_unit=$row['PayPerUnit'];
								$pay_limit=$row['LimitPerMonth'];
							}else if($row['PayType']=="case_pay"){
								echo "<td>按件計酬</td>";$paytype="case_pay";$paytypeStr="按件計酬";
								$pay_unit=$row['PayPerUnit'];
								$pay_limit=$row['LimitPerMonth'];
							}else if($row['PayType']=="day_pay"){
								echo "<td>日薪</td>";$paytype="day_pay";$paytypeStr="日薪";
								$pay_unit=$row['PayPerUnit'];
								$pay_limit=$row['LimitPerMonth'];
							}
							$pay_total=$row['TotalAmount'];
							
							echo "<td>".$row['TotalAmount']."</td>";
							echo "<td>".$row['Memo']."</td>";
							
							if($formAction=="loading" && $Eid==trim($row['Eid'])){echo "<td><strong>檢視中...</strong></td>";}
							else{
								echo "<td><input type='button' value='檢視細節' onClick='javascript:loadingPT(".$OrderNo.",".trim($row['Eid']).");'></td>";
							}
							echo "<input type='hidden' name='IdCode_".trim($row['Eid'])."' id='IdCode_".trim($row['Eid'])."' value='".trim($row['IdCode'])."'>".
								 "<input type='hidden' name='IdNo_".trim($row['Eid'])."' id='IdNo_".trim($row['Eid'])."' value='".trim($row['Pid'])."'>".
								 "<input type='hidden' name='PName_".trim($row['Eid'])."' id='PName_".trim($row['Eid'])."' value='".trim($row['Name'])."'>".
								 "<input type='hidden' name='Title_".trim($row['Eid'])."' id='Title_".trim($row['Eid'])."' value='".trim($row['Title'])."'>".
								 "<input type='hidden' name='Identity_".trim($row['Eid'])."' id='Identity_".trim($row['Eid'])."' value='".trim($row['Role'])."'>".
								 "<input type='hidden' name='payitem_".trim($row['Eid'])."' id='payitem_".trim($row['Eid'])."' value='".trim($row['JobItemCode'])."'>".
								 "<input type='hidden' name='PTtitle_".trim($row['Eid'])."' id='PTtitle_".trim($row['Eid'])."' value='".trim($row['PTtitle'])."'>".
								 "<input type='hidden' name='period_start_".trim($row['Eid'])."' id='period_start_".trim($row['Eid'])."' value='".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."'>".
								 "<input type='hidden' name='period_end_".trim($row['Eid'])."' id='period_end_".trim($row['Eid'])."' value='".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."'>".
								 "<input type='hidden' name='org_paytype_".trim($row['Eid'])."' id='org_paytype_".trim($row['Eid'])."' value='".$paytype."'>".
								 "<input type='hidden' name='org_payunit_".trim($row['Eid'])."' id='org_payunit_".trim($row['Eid'])."' value='".$pay_unit."'>".
								 "<input type='hidden' name='org_paylimit_".trim($row['Eid'])."' id='org_paylimit_".trim($row['Eid'])."' value='".$pay_limit."'>".
								 "<input type='hidden' name='org_paytotal_".trim($row['Eid'])."' id='org_paytotal_".trim($row['Eid'])."' value='".$pay_total."'>".
								 "<input type='hidden' name='TransType_".trim($row['Eid'])."' id='TransType_".trim($row['Eid'])."' value='".$TransType."'>";
							echo "</tr>";
						}
					}else{echo "<tr><td colspan='12'>無請核/異動資料</td></tr>";}
				}
			?>			
		</table>
		<input type="hidden" name="selectedSN" id="selectedSN" value="<?echo $selectedSN;?>">
	</fieldset>
	<table width="1000" border="0">				
		<tr height="20" align="left">
			<td align="right">
				<?if(trim($apply_row['FormStatus'])=="-2"){?>
				<input type="button" name="button_checkrule" id="button_checkrule" value="規則檢測" onClick="javascript:document.getElementById('button_checkrule').disabled=true;goCheckRules();document.getElementById('button_checkrule').disabled=false;">
				<input type="button" name="button_apply" id="button_apply" value="審核通過" onClick="javascript:appliedForm('<?echo trim($apply_row['SerialNo']);?>');" disabled>
				<input type="button" name="button_unapply" id="button_unapply" value="退回(解鎖定)" onClick="javascript:unlockForm('<?echo trim($apply_row['SerialNo']);?>');">
				<br>填寫退回理由:<br>
				<textarea name="UnLockReason" id="UnLockReason" rows="2" cols="20"><?echo trim($apply_row['UnLockReason']);?></textarea>
				<?}else if(trim($apply_row['FormStatus'])=="0"){?>
				<input type="button" name="button_lock" id="button_lock" value="鎖定" onClick="javascript:lockForm('<?echo trim($apply_row['SerialNo']);?>');">
				<input type="button" name="button_cancel" id="button_cancel" value="註銷" onClick="javascript:cancelForm('<?echo trim($apply_row['SerialNo']);?>');">
				<?}else if(trim($apply_row['FormStatus'])=="-1"){
						echo "本單已註銷";
				   }else if(trim($apply_row['FormStatus'])=="1"){
						echo "本單已通過";
				   }?>
			</td>
		</tr>							
	</table>
	<? if($Eid!=""){?>
	<hr>
	<?
		$strSQL="select *,".
				"datepart(year,BeginDate) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
				"datepart(year,EndDate) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
				"from PT_Employed where Eid='".$Eid."'";
		//echo $strSQL;
		$result=$db->query($strSQL);
		$Eid_row=$result->fetch();
		
	?>
	<fieldset>
		<legend>被<?echo $ApplyType;?>者狀態列表</legend>
		<div id='identity_status'>
			<fieldset>
				<legend>請核期間身份狀態列表</legend>
			<?	
				if($Eid_row['Role']=="E"){
					$strSQL="select d.主管姓名 as deptleader,v.全名 as deptname,v.* from ".
							"[PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
							"left join [PERSONDBOLD].[約用人員資料庫].[dbo].[DepartmentCode] d on v.服務單位代碼=d.code ".
							"where v.empno='".$Eid_row['IdCode']."'";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetch();
					$dept=$row['deptname'];
					$deptleader=$row['deptleader'];
					$con_bdate=substr($row['Con_BeginDate'],0,10);
					if($row['enddate']==""){$con_edate=substr($row['Con_EndDate'],0,10);}
					else{$con_edate=substr($row['enddate'],0,10);}
					
					$bug_leader="";
					$strSQL="select distinct v.ExpenseSourceCode as bugno,b.*,d.主管姓名 as deptleader from ".
					        "[PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource_buget] v ".
							"left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] b on b.bugetno=v.ExpenseSourceCode ".
							"left join [PERSONDBOLD].[約用人員資料庫].[dbo].[DepartmentCode] d on b.leaderid=d.code ".
							"where v.empno='".$Eid_row['IdCode']."' ".
							"and ((v.BeginDate<='".$con_bdate."' and v.Enddate>='".$con_bdate."') ".
							"or (v.BeginDate<='".$con_edate."' and v.Enddate>='".$con_edate."') ".
							"or (v.BeginDate<='".$con_bdate."' and v.Enddate>='".$con_edate."') ".
							"or (v.BeginDate>='".$con_bdate."' and v.Enddate<='".$con_edate."'))";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							$bug_leader.=$row['bugno'];
							if($row['deptleader']!=""){
								$bug_leader.="  ".$row['deptleader']."<br>";
							}else{$bug_leader.="  ".$row['leader']."<br>";}
						}
					}
					
					$strSQL="select min(BeginDate) as BeginDate from [PERSONDBOLD].[personnelcommon].dbo.vi_condate_history where empno='".$Eid_row['IdCode']."'";
					$result=$db->query($strSQL);
					$row2=$result->fetchAll();
					if(count($row2)>0){
						$result=$db->query($strSQL);
						$row2=$result->fetch();
						if($row2['BeginDate']!=""){
							$con_bdate=substr($row2['BeginDate'],0,10);
						}
					}
					echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
						 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
						 "<th>人事代號</th><th>姓名</th><th>專任職稱</th><th>專任在職期間</th>".
						 "<th>任職單位</th><th>單位主管</th><th>專任職計畫和主持人</th>".
						 "</tr><tr bgcolor='#AFFEFF' align='center'>".
						 "<td>".$Eid_row['IdCode']."</td><td>".$Eid_row['Name']."</td><td>".$Eid_row['Title']."</td>".
						 "<td>".$con_bdate." ~ ".$con_edate."</td><td>".$dept."</td><td>".$deptleader."</td><td>".$bug_leader."</td>".
						 "</tr></table>";
				}elseif($Eid_row['Role']=="S"){
					//$strSQL="select * from StudentData where std_stdcode='".$Eid_row['IdCode']."'";
					//$result=$db->query($strSQL);
					//$row=$result->fetch();
					$study_recorde="";
					$strSQL="SELECT * FROM [兼任人員資料庫].[dbo].[StdTerm] where std_stdcode='".$Eid_row['IdCode']."' ".
							"order by trm_year desc,trm_term desc";
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							if($row['trm_term']=="1"){
								$study_recorde.=$row['trm_year']."0801-".($row['trm_year']+1)."0131 ".$row['mgd_title']."<br>";
							}else{
								$study_recorde.=($row['trm_year']+1)."0201-".($row['trm_year']+1)."0731 ".$row['mgd_title']."<br>";
							}
						}
					}
					$out_recorde="";
					$strSQL="SELECT * FROM [兼任人員資料庫].[dbo].[stdAbsenceWithdraw] where std_stdcode='".$Eid_row['IdCode']."' ".
							"order by app_year desc,app_term desc";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							$out_recorde.=substr($row['app_date'],0,10)."  ".$row['app_type']."<br>";
						}
					}
					if(checkARC($Eid_row['Pid'])){$nation="外籍生";}else{$nation="本國生";}
					$strSQL_nation="select * from StudentData where std_stdcode='".$Eid_row['IdCode']."'";
					$result_nation=$db->query($strSQL_nation) or die($strSQL_nation);
					$row_nation=$result_nation->fetch();
					//echo print_r($row_nation);
					if(trim($row_nation['std_identity'])=="17"){$nation.="<br><font color='red'>陸生</font>";}
					echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
						 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
						 "<th>學號</th><th>姓名</th><th>學歷</th><th>學籍單位</th>".
						 "<th>在學期間</th><th>休退學記錄</th><th>國籍</th>".
						 "</tr><tr bgcolor='#AFFEFF' align='center'>".
						 "<td>".$Eid_row['IdCode']."</td><td>".$Eid_row['Name']."</td><td>".$stu_title[$Eid_row['Title']]."</td>".
						 "<td>暫無</td><td>".$study_recorde."</td><td>".$out_recorde."</td><td>".$nation."</td>".
						 "</tr></table>";
				}
			?>
			</fieldset>
		</div>
		<div id='same_period'>
			<fieldset>
				<legend>同期請核狀態列表</legend>
				<?
					$strSQL="select p.*,p2.*,".
							"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
							"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
							"from PT_employed p ".
							"left join PT_outline p2 on p2.SerialNo=p.SerialNo ".
							"where p.serialno in (select distinct serialno from PT_outline where FormStatus<>'-1') ".
							"and ((p.BeginDate<='".trim($Eid_row['BeginDate'])."' and p.Enddate>='".trim($Eid_row['BeginDate'])."') ".
							"or (p.BeginDate<='".trim($Eid_row['EndDate'])."' and p.Enddate>='".trim($Eid_row['EndDate'])."') ".
							"or (p.BeginDate<='".trim($Eid_row['BeginDate'])."' and p.Enddate>='".trim($Eid_row['EndDate'])."') ".
							"or (p.BeginDate>='".trim($Eid_row['BeginDate'])."' and p.Enddate<='".trim($Eid_row['EndDate'])."')) ".
							//"and (p.idcode='".trim($Eid_row['IdCode'])."' or p.pid='".trim($Eid_row['Pid'])."') and p.RecordStatus in ('0','-2') ".
							"and (p.idcode='".trim($Eid_row['IdCode'])."' or p.pid='".trim($Eid_row['Pid'])."') and p.RecordStatus in ('0') ".
							"and p.Eid<>'".$Eid."' ".
							"order by p2.FormStatus desc,p.BeginDate desc";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$bgcolor="#AFFEFF";		
						$result=$db->query($strSQL);
						echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
							 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
							 "<th>計畫編號</th><th>計畫名稱</th><th>計畫主持人</th><th>兼任職稱</th>".
							 "<th>請核目前<br>有效期間</th><th>狀態</th><th>月支金額</th><th>表單編號</th><th>表單狀態</th>".
							 "</tr>";
						while($row=$result->fetch()){
							if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
							else{$bgcolor="#AFFEFF";}
							echo "<tr bgcolor='".$bgcolor."' align='center'><td>".trim($row['BugNo'])."</td>";
							if(strlen(trim($row['bugname']))%2==1){
								echo "<td>".substr(trim($row['bugname']),0,51)."<a href='' title='".trim($row['bugname'])."'>...</a></td>";
							}else{echo "<td>".substr(trim($row['bugname']),0,50)."<a href='' title='".trim($row['bugname'])."'>...</a></td>";}
							echo "<td>".trim($row['leader'])."</td><td>".$PT_title[trim($row['PTtitle'])]."</td>";
							$periods=getCanTransformPeriod($row['Eid']);
							if($periods['num']==0){
								echo  "<td>".trim($row['start_y']).addLeadingZeros(trim($row['start_m']),2).
									  addLeadingZeros(trim($row['start_d']),2)."-".trim($row['end_y']).addLeadingZeros(trim($row['end_m']),2).
									  addLeadingZeros(trim($row['end_d']),2)."</td>";
							}else{
								$str="";
								$periodStr="";
								for($i=1;$i<=$periods['num'];$i++){
									if($i==1){
										$str.=$periods[$i][1]."-".$periods[$i][2];
										$periodStr.=$periods[$i][1]."-".$periods[$i][2];
									}else{
										$str.="<br>".$periods[$i][1]."-".$periods[$i][2];
										$periodStr.=",".$periods[$i][1]."-".$periods[$i][2];
									}
								}
								echo "<td>".$str."</td>";
							}	 
							if(trim($row['RecordStatus'])=="0"){echo "<td>有效</td>";}else{echo "<td>取消聘期</td>";}
							echo "<td>".trim($row['TotalAmount'])."</td><td>".trim($row['SerialNo'])."</td>";
							if(trim($row['FormStatus'])=="1"){echo "<td>審核通過</td>";}else{echo "<td>未審核</td>";}
							echo "</tr>";
						}
						echo "</table>";
						
					}else{echo "查無其他同期請核資料!!";}
				?>
			</fieldset>
		</div>
		<div id='all_period'>
			<fieldset>
				<legend>所有請核狀態列表</legend>
				<?
					$strSQL="select p.*,p2.*,".
							"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
							"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
							"from PT_employed p ".
							"left join PT_outline p2 on p2.SerialNo=p.SerialNo ".
							"where p.serialno in (select distinct serialno from PT_outline where FormStatus<>'-1') ".
							//"and (p.idcode='".trim($Eid_row['IdCode'])."' or p.pid='".trim($Eid_row['Pid'])."') and p.RecordStatus in ('0','-2') ".
							"and (p.idcode='".trim($Eid_row['IdCode'])."' or p.pid='".trim($Eid_row['Pid'])."') and p.RecordStatus in ('0') ".
							"and p.Eid<>'".$Eid."' ".
							"order by p2.FormStatus desc,p.BeginDate desc";
					//echo $strSQL;
					$result=$db->query($strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$bgcolor="#AFFEFF";	
						$result=$db->query($strSQL);
						echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
							 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
							 "<th>計畫編號</th><th>計畫名稱</th><th>計畫主持人</th><th>兼任職稱</th>".
							 "<th>請核目前<br>有效期間</th><th>狀態</th><th>月支金額</th><th>表單編號</th><th>表單狀態</th>".
							 "</tr>";
						while($row=$result->fetch()){
							if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
							else{$bgcolor="#AFFEFF";}
							echo "<tr bgcolor='".$bgcolor."' align='center'><td>".trim($row['BugNo'])."</td>";
							if(strlen(trim($row['bugname']))%2==1){
								echo "<td>".substr(trim($row['bugname']),0,51)."<a href='' title='".trim($row['bugname'])."'>...</a></td>";
							}else{echo "<td>".substr(trim($row['bugname']),0,50)."<a href='' title='".trim($row['bugname'])."'>...</a></td>";}
							echo "<td>".trim($row['leader'])."</td><td>".$PT_title[trim($row['PTtitle'])]."</td>";
							$periods=getCanTransformPeriod($row['Eid']);
							if($periods['num']==0){
								echo  "<td>".trim($row['start_y']).addLeadingZeros(trim($row['start_m']),2).
									  addLeadingZeros(trim($row['start_d']),2)."-".trim($row['end_y']).addLeadingZeros(trim($row['end_m']),2).
									  addLeadingZeros(trim($row['end_d']),2)."</td>";
							}else{
								$str="";
								$periodStr="";
								for($i=1;$i<=$periods['num'];$i++){
									if($i==1){
										$str.=$periods[$i][1]."-".$periods[$i][2];
										$periodStr.=$periods[$i][1]."-".$periods[$i][2];
									}else{
										$str.="<br>".$periods[$i][1]."-".$periods[$i][2];
										$periodStr.=",".$periods[$i][1]."-".$periods[$i][2];
									}
								}
								echo "<td>".$str."</td>";
							}
							if(trim($row['RecordStatus'])=="0"){echo "<td>有效</td>";}else{echo "<td>取消聘期</td>";}
							echo "<td>".trim($row['TotalAmount'])."</td><td>".trim($row['SerialNo'])."</td>";
							if(trim($row['FormStatus'])=="1"){echo "<td>審核通過</td>";}else{echo "<td>未審核</td>";}
							echo "</tr>";
						}
						echo "</table>";
						
					}else{echo "查無其他同期請核資料!!";}
				?>
			</fieldset>
		</div>
		<div id='upload'>
			<fieldset>
				<legend>個人證明文件和本次申請上傳資料</legend>
			<?
				
				$strSQL="select u.Fid,u.FileTitle,u.[type] as typeno,o.TypeName,o.TypeClass,u.[status],".
						"datepart(year,w.ID_StartDate) as start_y,datepart(month,w.ID_StartDate) as start_m,datepart(day,w.ID_StartDate) as start_d,".
						"datepart(year,w.ID_EndDate) as end_y,datepart(month,w.ID_EndDate) as end_m,datepart(day,w.ID_EndDate) as end_d ".
						"from UploadData u ".
						"left join UploadType o on o.TypeNo=u.[type] ".
						"left join working_periods w on w.Fid=u.Fid ".
						"where (SEid in ".
						"(select distinct Eid from PT_Employed where SerialNo='".$OrderNo."' and ";
				if(trim($Eid_row['FromEid'])==""){$strSQL.="FromEid is NULL ";}else{$strSQL.="FromEid='".trim($Eid_row['FromEid'])."' ";}
				$strSQL.="and RecordStatus in ('0','-2'))) ".
						"or (PEid in ".
						"(select Eid from [OuterStatus] where IdNo='".trim($Eid_row['Pid'])."') ".
						"and [TypeClass]='A') and u.[status]<>'-1' order by [type] desc";
				//echo $strSQL;
				$result=$db->query($strSQL);
				$row=$result->fetchAll();
				if(count($row)>0){
					$bgcolor="#AFFEFF";	
					$result=$db->query($strSQL);
					echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
						 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
						 "<th>&nbsp;</th><th>檔案類型</th><th>檔案標題</th><th>工作起訖</th><th>文件狀態</th>".
						 "</tr>";
					$result=$db->query($strSQL);
					while($row=$result->fetch()){
						if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
						else{$bgcolor="#AFFEFF";}
						echo "<tr bgcolor='".$bgcolor."' align='center'><td><a href='viewfile.php?fid=".$row['Fid']."' ".
							 "target='_blank'>檢視檔案</td>".
							 "<td>".trim($row['TypeName'])."</td><td>".trim($row['FileTitle'])."</td>";
						
						if(trim($row['TypeClass'])=="A" && trim($row['typeno'])=="4"){
							echo "<td>";
							echo (trim($row['start_y'])-1911).addLeadingZeros(trim($row['start_m']),2).addLeadingZeros(trim($row['start_d']),2);
							echo "-".(trim($row['end_y'])-1911).addLeadingZeros(trim($row['end_m']),2).addLeadingZeros(trim($row['end_d']),2);
							echo "</td>";
						}else{echo "<td>--</td>";}	
						if(trim($row['TypeClass'])=="A"){	
							if(trim($row['status'])=="0"){
								echo "<td><div id='op_".trim($row['Fid'])."'>".
									 "<input type='button' name='acceptfile' fid='".trim($row['Fid'])."' value='通過' />".
									 "<input type='button' name='unacceptfile' fid='".trim($row['Fid'])."' value='退件' />".
									 "</div></td>";
							}else if(trim($row['status'])=="1"){echo "<td>已審過</td>";}
							else if(trim($row['status'])=="-2"){echo "<td>已退件</td>";}
						}else{echo "<td>--</td>";}
						echo "</tr>";
						
					}
				}else{echo "無";}
			?>
			</fieldset>
		</div>
	</fieldset>
	<? }?>
	</form>
</body>
</html>
<script language="javascript">
function goCheckRules(){
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	var index=0,i=0,new_startdate="",new_enddate;
	var ErrMsg="",ErrMsg2="",ErrMsg_All="",ErrMsg_Overlay="";
	var warning="",warning2="",warning_All="";
	var Mod_ErrMsg="",Mod_ErrMsg2="";
	var checkdata=[];
	var checkMod=[];
	
	for(index=0;index<(selectedSN.length-1);index++){
		//alert("checked SN:"+selectedSN[index]);
		ErrMsg="";
		warning="";
		Mod_ErrMsg=""
		var name=document.getElementById('PName_'+selectedSN[index]).value;
		
		var OrderNo=document.getElementById('OrderNo').value;
		var bugno=document.getElementById('bugetno').value;
		var PNo=document.getElementById('IdCode_'+selectedSN[index]).value;
		var IdNo=document.getElementById('IdNo_'+selectedSN[index]).value;
		var identity=document.getElementById('Identity_'+selectedSN[index]).value;
		var payitem=document.getElementById('payitem_'+selectedSN[index]).value;
		var Prank=document.getElementById('Title_'+selectedSN[index]).value;
		var Ptitle=document.getElementById('PTtitle_'+selectedSN[index]).value;
		var start_date=document.getElementById('period_start_'+selectedSN[index]).value;
		var end_date=document.getElementById('period_end_'+selectedSN[index]).value;
		
		var PayTypeStr=document.getElementById('org_paytype_'+selectedSN[index]).value;
		var pay_unit=document.getElementById('org_payunit_'+selectedSN[index]).value;
		var pay_limit=document.getElementById('org_paylimit_'+selectedSN[index]).value;
		var pay_total=document.getElementById('org_paytotal_'+selectedSN[index]).value;
		var TransType=document.getElementById('TransType_'+selectedSN[index]).value;
		//alert(bugno+"  "+start_date+"  "+end_date+"  "+PNo+"  "+IdNo+"  "+identity+"  "+payitem+"  "+Prank+"  "+Ptitle+"  "+PayTypeStr+"  "+pay_unit+"  "+pay_limit+"  "+pay_total+"  "+selectedSN[index]);
		if(TransType!="1"){//規則檢查,聘期刪除不需要再做檢查
			checkdata=checkRules(OrderNo,bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,pay_unit,pay_limit,pay_total,selectedSN[index]);
			//alert("checkdata:"+checkdata);
			//console.log(checkRules(OrderNo,bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,pay_unit,pay_limit,pay_total,selectedSN[index]));
			if(checkdata[0]!="ok"){
				//console.log(checkdata);
				checkdata[0] = $.trim(checkdata[0]);
				checkdata[1] = $.trim(checkdata[1]);
				if(checkdata[0]!=""){ErrMsg+=checkdata[0]+"\n";}
				if(checkdata[1]!=""){warning+=checkdata[1]+"\n";}
				//console.log(ErrMsg);
			}
		}
		//工作費勾稽檢查
		//console.log(checkAppliedFee(bugno,start_date,end_date,PNo,IdNo,pay_total,selectedSN[index]));
		checkMod=checkAppliedFee(bugno,start_date,end_date,PNo,IdNo,pay_total,selectedSN[index]);
		if(TransType=="1" && parseInt(checkMod[0])>0){Mod_ErrMsg+=start_date+"-"+end_date+" 已有工作費入帳或申請,請先繳回或取消申請,再進行異動\n";}
		else if(checkMod[1]!="ok"){Mod_ErrMsg+=checkMod[1]+"\n";}
		ErrMsg = $.trim(ErrMsg);
		if(ErrMsg!=""){ErrMsg2+=selectedSN[index]+"  "+name+"\n"+ErrMsg+"\n\n";}
		warning = $.trim(warning);
		if(warning!=""){warning2+=selectedSN[index]+"  "+name+"\n"+warning+"\n\n";}
		Mod_ErrMsg = $.trim(Mod_ErrMsg);
		if(Mod_ErrMsg!=""){Mod_ErrMsg2+=selectedSN[index]+"  "+name+"\n"+Mod_ErrMsg+"\n\n";}
		//console.log(ErrMsg2);
		//console.log(warning);
		//console.log(Mod_ErrMsg);
	}
	if(ErrMsg2!=""){ErrMsg_All+="支領規則錯誤:\n"+ErrMsg2;}
	if(warning2!=""){warning_All="支領規則警告:\n"+warning2;}
	if(Mod_ErrMsg!=""){ErrMsg_All+="工作費勾稽錯誤:\n"+Mod_ErrMsg2;}
	if(ErrMsg_All!=""){alert(ErrMsg_All);}
	else{
		alert("規則檢測OK");
		document.getElementById('button_apply').disabled=false;
	}
}

function checkRules(OrderNo,bugno,start,end,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,FromEid){
	var returnMsg=[];
	
	$.ajax({
		url: 'checkRules.php',
		data:{
			OrderNo:OrderNo,
			bugno:bugno,
			start:start,
			end:end,
			PNo:PNo,
			IdNo:IdNo,
			identity:identity,
			payitem:payitem,
			Prank:Prank,
			Ptitle:Ptitle,
			PayTypeStr:PayTypeStr,
			Pay_unit:Pay_unit,
			Pay_limit:Pay_limit,
			PayTotal:PayTotal,
			FromEid:FromEid
		},
		dataType:'json',
		type: 'GET',
		async:false,
		error: function(xhr){
			//$('#errortest').html(xhr.responseText);
			alert(xhr.responseText);
		},
		success: function(json){
			var obj_num=parseInt(json['number']);
			var Msg="";
			var warning="";
			var sameErr=false;
			var tempMsg="";
			var tempMsg2="";
			for(i=0;i<obj_num;i++){
				tempMsg = $.trim(json[i].toString());
				if(tempMsg!="ok"){
					sameErr=false;
					if(json[i].toString().indexOf("warning")==-1){
						for(j=i-1;j>=0;j--){//去除相同的錯誤警語,若之前已有相同警語,就不再顯示
							tempMsg2 = $.trim(json[j].toString());
							if(tempMsg==tempMsg2){sameErr=true;}
						}
						if(!sameErr){Msg=Msg+tempMsg+"\n";}
					}else{
						for(j=i-1;j>=0;j--){//去除相同的警語,若之前已有相同警語,就不再顯示
							tempMsg2 = $.trim(json[j].toString());
							if(tempMsg==tempMsg2){sameErr=true;}
						}
						if(!sameErr){warning=warning+json[i].toString().substring(8)+"\n";}						
					}
					//console.log(json[i].toString()+"\n"+Msg+"\n"+warning);
				}
			}
			json=null;
			Msg = $.trim(Msg);
			if(Msg!=""){returnMsg[0]=Msg;}else{returnMsg[0]="";}
			warning = $.trim(warning);
			if(warning!=""){returnMsg[1]=warning;}else{returnMsg[1]="";}
			if(returnMsg[0]=="" && returnMsg[1]==""){returnMsg[0]="ok";}
			//alert(returnMsg);
		}
	});
	return returnMsg;
}
//工作費勾稽檢查
function checkAppliedFee(bugno,start,end,PNo,IdNo,pay_total,FromEid){
	var returnMsg=[];
	
	$.ajax({
		url: 'checkAppliedFee.php',
		data:{
			bugno:bugno,
			start:start,
			end:end,
			PNo:PNo,
			IdNo:IdNo,
			PayTotal:pay_total,
			FromEid:FromEid
		},
		dataType:'json',
		type: 'GET',
		async:false,
		error: function(xhr){
			//$('#errortest').html(xhr.responseText);
			alert(xhr.responseText);
		},
		success: function(json){
			returnMsg=json;
			//alert(returnMsg);
			json=null;
		}
	});
	return returnMsg;
}
function query_apply(){
	var apply_no=document.getElementById('OrderNo').value
	if(isNaN(apply_no)){alert("要查詢的單號必需是正整數!!");}
	else{
		document.verify_app.submit();
	}
}
function appliedForm(OrderNo){
	//alert(OrderNo);
	if(confirm("確定通過"+OrderNo+"<?echo $ApplyType;?>單,請先確認[規則檢核]都已通過!!")){
		var msg=checkDocumentVerfied(OrderNo);
		//alert("msg:"+msg);
		if(msg!="true"){
			if(confirm(msg+"\n,確定要送出嗎??")){
				document.verify_app.OrderNo.value=OrderNo;
				document.verify_app.formAction.value="applied";	
				document.verify_app.submit();
				document.verify_app.formAction.value="";	
			}
		}else{
			document.verify_app.OrderNo.value=OrderNo;
			document.verify_app.formAction.value="applied";	
			document.verify_app.submit();
			document.verify_app.formAction.value="";	
		}
	}
}
function checkDocumentVerfied(OrderNo){
	var returnMsg="";
	
	$.ajax({
		type:"POST",
		dataType:"text",
		data:{OrderNo:OrderNo,action:"DocumentVerfied"},
		async:false,
		url:"fileoperation.php",
		
		success:function(msg){
			//alert(msg);
			returnMsg=msg;
			//return msg;
		},
		error:function(){
			alert("審核操作錯誤!!");return;
		}	
	});
	return returnMsg;
}
function lockForm(OrderNo){
	//alert(OrderNo);
	if(confirm("是否鎖定單號"+OrderNo)){
		document.verify_app.OrderNo.value=OrderNo;
		document.verify_app.formAction.value="lock";	
		document.verify_app.submit();
	}
}
function unlockForm(OrderNo){
	//alert(OrderNo);	
	var reason=String(document.getElementById('UnLockReason').value);
	if(reason==""){
		alert("請填寫退回(解除鎖定)理由");
		return false;
	}
	if(confirm("是否退回(解除鎖定)單號"+OrderNo)){
		document.verify_app.OrderNo.value=OrderNo;
		document.verify_app.formAction.value="unlock";	
		document.verify_app.submit();
	}
}
function cancelForm(OrderNo){
	//alrt(OrderNo);
	if(confirm("是否刪除單號"+OrderNo)){
		document.verify_app.OrderNo.value=OrderNo;
		document.verify_app.formAction.value="delete";
		document.verify_app.submit();
	}
}
function loadingPT(OrderNo,Eid){
	document.verify_app.OrderNo.value=OrderNo;
	document.verify_app.Eid.value=Eid;
	document.verify_app.formAction.value="loading";
	document.verify_app.submit();
}
function addLeadingZero(str,index){
	var leadingzero="000000";
	str=leadingzero+str;
	return str.substr(str.length-index,index);
}
</script>
	
	
	