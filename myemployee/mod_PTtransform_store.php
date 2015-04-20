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
<script type="text/javascript">
	$(function(){
		//alert("hello");
		//$("#addFile").click(function() {
			
			//$("#abcd").clone().insertBefore(this);
		//});
		
		
		$("input[name='editfile']").click(function(){
					var id=$(this).attr("blockid");
					var eid=$(this).attr("eid");
					var fid=$(this).attr("fid");
					var title=$("input[id='uploaded_filetitle_"+eid+"_"+id+"']").val();
					var type=$("select[id='uploaded_filetype_"+eid+"_"+id+"']").val();
					
					//alert(fid+" "+eid+" "+title+" "+type);
					$.ajax({

						type:"POST",
						dataType:"text",
						data:{fid:fid,title:title,type:type,action:"editfile"},
						url:"fileoperation.php",
						
						success:function(msg){

							if(msg=="true")
								alert("修改成功");
							else
								alert("修改失敗");
							
							},
						error:function(){

							alert("Ajax途中出錯了!!");
							
							}	
						});

					
				});

			$("input[name='delfile']").click(function(){
				if(confirm("確定要刪除檔案嗎?")==false)
					return false;
				var id=$(this).attr("blockid");
				var eid=$(this).attr("eid");
				var bkid="#fileblock_"+eid+"_"+id;
				$(bkid).css("display","none");
				var fid=$(this).attr("fid");
				$.ajax({

					type:"POST",
					dataType:"text",
					data:{fid:fid,action:"delfile"},
					url:"fileoperation.php",
					
					success:function(msg){

						if(msg=="true")
							alert("刪除成功");
						else
							alert("刪除失敗");
						
						},
					error:function(){

						alert("Ajax途中出錯了!!");
						
						}	
					});
				
			});
	});
</script>
</head>
<?php 
	include("connectSQL.php");
	include("function.php");
	
	$bugetno="";$ErrMsg="";$worktype="";$can_apply=false;
	$OrderNo=$_POST['OrderNo'];
	$worktype=$_POST['worktype'];
	$rowcount=$_POST['rowcount'];
	if(isset($_POST['bugetno'])){$bugetno=mb_strtoupper(filterEvil(trim($_POST['bugetno'])));}
	$bug_type=checkBugetTypeForPTtitle($bugetno);
	$act=$_POST['act'];
	
	//查詢出檔案類型
	$FileTypeArray=array();
	$sqlFileType="select * from UploadType where TypeClass='B'";
	$rsFileType=$db->query($sqlFileType);
	$ptr=0;
	while ( $dsFileType = $rsFileType->fetch() ) {
	
		$no=$dsFileType["TypeNo"];
		$name=$dsFileType["TypeName"];
		$FileTypeArray[$ptr][0]=$no;
		$FileTypeArray[$ptr][1]=$name;
		$ptr++;
		//array_push($FileTypeArray, $dsFileType["TypeName"]);
	}
	
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
		exit;
	}
	//echo $FormStatus;
	$selectedStr=$_POST['selectedSN'];
	$selectedSN=explode(",", $selectedStr);
	$queryStr=implode("','",$selectedSN);//查詢用
	
	$today=date('Y-m-d H:i:s');//做新增/修改/刪除的時間點
	if($act=="updateTransform"){//更新異動單
		//先刪除全部原來異動單的資料
		$strSQL="update PT_Employed set Recordstatus='-1',UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where SerialNo='".$OrderNo."' and RecordStatus<>'-1'";
		$db->query($strSQL);
		//重新建新的
		foreach($selectedSN as $p){
			$today=date('Y-m-d H:i:s');//做新增/修改/刪除的時間點
			//echo $p."<br>";
			//抓原本請核資料
			$strSQL="select p.*,".
					"datepart(year,p.BeginDate) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"datepart(year,p.EndDate) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d  ".
					"from PT_Employed p ".
					"where p.Eid='".$p."'";
			//echo $strSQL;
			$result=$db->query($strSQL);
			$org_data=$result->fetch();
			//print_r($org_data);
			$num_rows=$_POST['rowcount_'.$p];
			$m_periods=array();
			$uploadErrMsg="";
			$uploadErrMsg2="";
			for($i=1;$i<=$num_rows;$i++){//先取得有被異動的聘期
				$transtype=$_POST['TransType_'.$p.'_'.$i];
				$paytype=$_POST['paytype_'.$p.'_'.$i];
				$pay_unit=$_POST['pay_unit_'.$p.'_'.$i];
				if($paytype=="award_pay"){$pay_unit=2000;}
				$pay_limit=$_POST['pay_limit_'.$p.'_'.$i];
				$pay_total=$_POST['totalamount_'.$p.'_'.$i];
				$DelayCode=$_POST['DelayCode_'.$p.'_'.$i];
				$DelayReason=$_POST['DelayReason_'.$p.'_'.$i];
				$comment=$_POST['comment_'.$p.'_'.$i];
				$m_periods[$i][0]=$_POST['startDate_y_'.$p.'_'.$i]+1911;
				$m_periods[$i][1]=addLeadingZeros($_POST['startDate_m_'.$p.'_'.$i],2);
				$m_periods[$i][2]=addLeadingZeros($_POST['startDate_d_'.$p.'_'.$i],2);
				$m_periods[$i][3]=$_POST['endDate_y_'.$p.'_'.$i]+1911;
				$m_periods[$i][4]=addLeadingZeros($_POST['endDate_m_'.$p.'_'.$i],2);
				$m_periods[$i][5]=addLeadingZeros($_POST['endDate_d_'.$p.'_'.$i],2);
				//寫入被異動的部分
				$strSQL="insert into PT_Employed select '".$OrderNo."','".$org_data['IdCode']."','".$org_data['Pid']."','".$org_data['Name']."','".
						$org_data['Title']."','".$org_data['Role']."','".$org_data['PTtitle']."','".$org_data['JobType']."','".$org_data['noWorkIDcheck_byPrjLeader']."','".$m_periods[$i][0].
						"-".$m_periods[$i][1]."-".$m_periods[$i][2]."','".$m_periods[$i][3]."-".$m_periods[$i][4]."-".$m_periods[$i][5]."','".
						$org_data['JobItemCode']."',null,null";
				
				if($paytype=="hr_pay" || $paytype=="day_pay" || $paytype=="case_pay"){
					$strSQL.=",'".$paytype."','".$pay_unit."','".$pay_limit."',null,null,null,'".$pay_total."'";
				}else{
					$strSQL.=",null,null,null";
					if($paytype=="month_pay"){$strSQL.=",'".$pay_unit."',null,null,'".$pay_total."'";}
					else{$strSQL.=",null,'".$pay_unit."','".$pay_limit."','".$pay_total."'";}
				}
				
				$strSQL.=",'".$DelayCode."','".$DelayReason."','".$comment."','".$org_data['IsAboriginal']."','".$org_data['IsDisability']."','".$org_data['BossRelation']."','".$today."','".$_SESSION['UserID']."','".$today."','".$_SESSION['UserID']."',null,null,null,";
				if($transtype=="1"){$strSQL.="'-2'";}else{$strSQL.="'0'";}//-2表聘期被刪除,保持異動和被異動記錄起訖總合一致
				$strSQL.=",null,'".$org_data['SerialNo']."','".$p."','".$org_data['FirstEid']."'";
				
				$db->query($strSQL);
				//取得新記錄Eid
				$strSQL="select * from PT_Employed where updatedate='".$today."' order by Eid desc";
				$result=$db->query($strSQL);
				$row=$result->fetch();
				$Eid=$row['Eid'];
				//寫入人員當時狀態資料					
				if(trim($org_data['Role'])=="E"){//寫入職員資料到EmpStatus
					$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
							"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".$m_periods[$i][0]."-".$m_periods[$i][1]."-".$m_periods[$i][2]."' or e.EndDate>='".$m_periods[$i][0]."-".$m_periods[$i][1]."-".$m_periods[$i][2]."'".
							"where v.empno='".trim($org_data['IdCode'])."'";
					//echo $strSQL."<br>";
					$result=$db->query($strSQL);
					while($result && $row=$result->fetch()){
						$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".trim($org_data['IdCode'])."','".$row['Title']."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$org_pay_total."','".$today."','".$_SESSION['UserID']."'";
						$result=$db->query($strSQL);
						//echo $strSQL."<br>";
					}
				}else if(trim($org_data['Role'])=="S"){
					$stu_ym=getTerm(($m_periods[$i][0]-1911),$m_periods[$i][1]);//取得請核起日的學年度學期別
					$strSQL="select std_pid,std_degree,std_leavedate,學籍之在學狀況 as status,std_schoolid,std_gmonth,trm_year,trm_term,trm_studystatus,mgd_msgheaderno,".
							"mgd_title,app_type,app_year,app_term,app_date from StudentData s1 ".
							"left join stdterm s2 on s1.std_stdcode=s2.std_stdcode and s2.trm_year='".$stu_ym['cyear']."' and s2.trm_term='".$stu_ym['cterm']."' ".
							"left join stdAbsenceWithdraw s3 on s1.std_stdcode=s3.std_stdcode and s3.app_year='".$stu_ym['cyear']."' and s3.app_term='".$stu_ym['cterm']."' ".
							"where s1.std_stdcode='".trim($org_data['IdCode'])."'";
					//echo $strSQL."<br>";
					$result=$db->query($strSQL);
					$row=$result->fetch();
					$strSQL="insert into StuStatus select '".$OrderNo."','".$Eid."','".trim($org_data['IdCode'])."','".trim($org_data['Name'])."',null,'".$row['std_pid']."',null,null,null,'".$row['std_degree']."','".$row['std_leavedate']."','".$row['status']."','".$row['std_schoolid']."','".$row['std_gmonth']."','".$row['trm_year']."','".$row['trm_term']."','".$row['trm_studystatus']."','".$row['mgd_msgheaderno']."','".$row['mgd_title']."','".$row['app_type']."','".$row['app_year']."','".$row['app_term']."','".$row['app_date']."','".$today."','".$_SESSION['UserID']."'";
					$result=$db->query($strSQL);
					//echo $strSQL."<br>";
				}
			}
			//處理上傳Begin
			$num_rows=$_POST['uploadrowcount_'.$p];
			if($num_rows>0){
				for($i=1;$i<=$num_rows;$i++){//先取得有被異動的聘期
					$filetype=$_POST['filetype_'.$p.'_'.$i];
					$filetitle=filterEvil($_POST['filetitle_'.$p.'_'.$i]);
					
					//$path = 'upfile/' . time () . $key . strtolower ( strstr ( $value, "." ) );
					
					$dot_position=strrpos($_FILES ['upload_'.$p.'_'.$i] ['name'],".");

					$name=substr($_FILES ['upload_'.$p.'_'.$i] ['name'], 0,$dot_position);
					$type=substr($_FILES ['upload_'.$p.'_'.$i] ['name'], $dot_position+1,strlen($_FILES ['upload_'.$p.'_'.$i] ['name'] )-$dot_position);
					$status=2;//B類文件不用審核,預設為2
					//echo $_FILES ['upload_'.$p.'_'.$i] ['name']."<br/>";
					//echo "value=".$value."<br/>";
					//echo "key=".$key."<br/>";
					//echo "name=".$name."<br/>";
					//echo "filesize=".floatval($_FILES['upload_'.$p.'_'.$i]['size']/(1024*1024))."MB<br/>";
					//echo "type=".$type."<br/>";
					if(floatval($_FILES['upload_'.$p.'_'.$i]['size']/(1024*1024))<=2){
						$filecontent=file_get_contents($_FILES['upload_'.$p.'_'.$i]['tmp_name']);
						$queryIn = $db->prepare("
						INSERT INTO     UploadData (SEid ,FileContent,FileName,SubFileType,FileTitle,type,status,CreateEmp,UpdateEmp,CreateDate,UpdateDate)
						VALUES          (:peid , :content,:name,:type ,:filetitle,:filetype,:status,:createemp,:updateemp,getdate(),getdate())");
						//$queryIn->bindParam(':id', $rr );
						$queryIn->bindParam(':peid',$Eid );
						$queryIn->bindParam(':name', $name );
						$queryIn->bindParam(':type',$type );
						$queryIn->bindParam(':filetitle',$filetitle );
						$queryIn->bindParam(':status',$status );
						$queryIn->bindParam(':filetype',$filetype );
						$queryIn->bindParam(':createemp',$_SESSION['UserID'] );
						$queryIn->bindParam(':updateemp',$_SESSION['UserID'] );
						$queryIn->bindParam(':content', $filecontent , PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
						$queryIn->execute() or die(print_r($db->errorInfo(), true));
					}else{
						$uploadErrMsg.="檔名:".$name."\\n";
					}
				}
			}
			//因可能切成幾段聘期,上傳檔案的SEid目前只有一個,統一更新成最後一段異動的Eid,否則上傳的文件每次更新後就會不見
			//B類文件status一開始就是2
			$updateEid="update UploadData set SEid='".$Eid."',updateEmp='".$_SESSION['UserID']."',updatedate=getdate() where status='2' and SEid in (select Eid from PT_Employed where SerialNo='".$OrderNo."' and FromEid='".$p."' and RecordStatus='-1')";
			//echo $updateEid;
			$db->query($updateEid);
			//處理上傳End
			if($uploadErrMsg!=""){
				$uploadErrMsg2.=$org_data['Name']."\\n".$uploadErrMsg;	
				$uploadErrMsg="";
			}
		}
		//寫入異動表單狀態
		//先將舊資料設為刪除
		$strSQL="update Mod_PrintInfo set PrintType='-1',UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."' ".
				"where SerialNo='".$OrderNo."' and PrintType<>'-1'";
		$db->query($strSQL);		
		foreach($selectedSN as $p){
			//echo "<br>";
			$num_rows=$_POST['rowcount_'.$p];
			if($num_rows>0){
				//先寫入每個SN,異動前的狀況,此時會將可異動時期拆分成1~數筆記錄(如果被從聘期中間異動)
				$periods=getCanTransformPeriod($p);
				for($i=1;$i<=$periods['num'];$i++){
					$strSQL="insert into Mod_PrintInfo select ".
							"'".$OrderNo."',SerialNo,'0',Eid,IdCode,Pid,Name,Title,Role,Pttitle,JobType,noWorkIDcheck_byPrjLeader,".
							"'".$periods[$i][3]."','".$periods[$i][4]."',".
							"JobItemCode,TaxCategoryCode,TaxCategoryCode2,PayType,PayPerUnit,LimitPerMonth,".
							"MonthExpense,AwardUnit,AwardLimit,TotalAmount,DelayCode,TraceBackReason,Memo,IsAboriginal,".
							"IsDisability,BossRelation,CreateDate,CreateEmp,'".$today."','".$_SESSION['UserID']."',VerifyDate,".
							"VerifyEmp,OutSideDept,RecordStatus,PostTo,FromSN,FromEid,FirstEid ".
							"from PT_Employed where Eid='".$p."'";
					$db->query($strSQL) or die("Err:".$strSQL."<br>");
					//echo $strSQL."<br>";
				}
				//寫入本次異動部分
				$strSQL="insert into Mod_PrintInfo select ".
						"'".$OrderNo."','".$OrderNo."','1',Eid,IdCode,Pid,Name,Title,Role,Pttitle,JobType,noWorkIDcheck_byPrjLeader,BeginDate,EndDate,".
						"JobItemCode,TaxCategoryCode,TaxCategoryCode2,PayType,PayPerUnit,LimitPerMonth,".
						"MonthExpense,AwardUnit,AwardLimit,TotalAmount,DelayCode,TraceBackReason,Memo,IsAboriginal,".
						"IsDisability,BossRelation,CreateDate,CreateEmp,'".$today."','".$_SESSION['UserID']."',VerifyDate,".
						"VerifyEmp,OutSideDept,RecordStatus,PostTo,FromSN,FromEid,FirstEid ".
						"from PT_Employed where FromEid='".$p."' and SerialNo='".$OrderNo."' and RecordStatus<>'-1'";
				$db->query($strSQL) or die("Err:".$strSQL."<br>");
				//echo $strSQL."<br>";
				//寫入本次沒被異動的部分
				$m_periods=array();
				$strSQL="select datepart(year,p.BeginDate) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
						"datepart(year,p.EndDate) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d  ".
						"from PT_Employed p where p.FromEid='".$p."' and SerialNo='".$OrderNo."' and p.RecordStatus<>'-1'";
				$result=$db->query($strSQL) or die("Err:".$strSQL."<br>");
				//echo $strSQL."<br>";
				$i=0;
				while($row=$result->fetch()){
					$m_periods[$i][0]=trim($row['start_y']).addLeadingZeros(trim($row['start_m']),2).addLeadingZeros(trim($row['start_d']),2);
					$m_periods[$i][1]=trim($row['end_y']).addLeadingZeros(trim($row['end_m']),2).addLeadingZeros(trim($row['end_d']),2);
					$i++;
				}
				//print_r($periods);echo "<br>";
				//print_r($m_periods);echo "<br>";
				$org_periods=array();//沒有異動的部分,array[][0]:start array[][1]:end
				$index=0;
				for($k=1;$k<=$periods['num'];$k++){
					$intStartDate=$periods[$k][5];
					$intEndDate=$periods[$k][6];
					//echo $intStartDate." ".$intEndDate."<br>";
					$i=$intStartDate;
					//將沒有異動的時段找出來
					while((int)$i>=(int)$intStartDate && (int)$i<=(int)$intEndDate){
						$Contain="n";
						for($j=0;$j<=count($m_periods);$j++){
							if((int)$i>=(int)($m_periods[$j][0]) && (int)$i<=(int)($m_periods[$j][1])){
								$Contain="y";
								break;
							}else{
								continue;
							}
						}
						if($Contain=="n"){
							$a=substr($i,0,4)."-".substr($i,4,2)."-".substr($i,6,2);
							$a=date('Y-m-d',strtotime("$a   -1   day"));
							$a=substr($a,0,4).substr($a,5,2).substr($a,8,2);
							if((int)$org_periods[$index][1]!=(int)$a){
								$index++;
								$org_periods[$index][0]=$i;
								$org_periods[$index][1]=$i;
							}else{
								$org_periods[$index][1]=$i;
							}
						}
						//echo $i." ".$Contain."<br>";
						$i=substr($i,0,4)."-".substr($i,4,2)."-".substr($i,6,2);
						$i=date('Y-m-d',strtotime("$i   +1   day"));
						$i=substr($i,0,4).substr($i,5,2).substr($i,8,2);
					}
				}
				//echo "<br>";
				//print_r($org_periods);
				foreach($org_periods as $a){
					$strSQL="insert into Mod_PrintInfo select ".
						"'".$OrderNo."','".$OrderNo."','1',Eid,IdCode,Pid,Name,Title,Role,Pttitle,JobType,noWorkIDcheck_byPrjLeader,".
						"'".substr($a[0],0,4)."-".substr($a[0],4,2)."-".substr($a[0],6,2)."',".
						"'".substr($a[1],0,4)."-".substr($a[1],4,2)."-".substr($a[1],6,2)."',".
						"JobItemCode,TaxCategoryCode,TaxCategoryCode2,PayType,PayPerUnit,LimitPerMonth,".
						"MonthExpense,AwardUnit,AwardLimit,TotalAmount,DelayCode,TraceBackReason,Memo,IsAboriginal,".
						"IsDisability,BossRelation,CreateDate,CreateEmp,'".$today."','".$_SESSION['UserID']."',VerifyDate,".
						"VerifyEmp,OutSideDept,RecordStatus,PostTo,FromSN,FromEid,FirstEid ".
						"from PT_Employed where Eid='".$p."'";
					$db->query($strSQL) or die("Err:".$strSQL."<br>");
					//echo $strSQL."<br>";
				}
			}
		}
		
		if($uploadErrMsg2!=""){$uploadErrMsg2="上傳檔案過大(超過2MB),未成功:\\n".$uploadErrMsg2;}
		$msg="異動單更新完成!!\\n\\n".$uploadErrMsg2;		
		echo "<script language='javascript'>alert('".$msg."');";
		//exit;
		echo "window.location = 'qry_PTtransform.php';</script>";
		//exit;
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
		if($row!="notfound"){	
			$can_apply=true;//查得到資料,可以繼續異動
			
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
			$can_apply=false;
			$ErrMsg="查無 ".$bugetno." 計畫資料，請先洽詢主計室承辦人員或重新開始建立異動單，並維持頁面上計畫編號資料別刪除。";
		}
	}
	
	//echo $selectedStr."<br>".print_r($selectedSN)."<br>".$queryStr;
?>
<body bgcolor='#c1cfb4'>
	<form name="addPT" id="addPT" method="POST" action="mod_PTtransform_store.php" target="_self" enctype="multipart/form-data">
		<input type="hidden" name="bugetno" id="bugetno" value="<?echo $bugetno;?>">
		<input type="hidden" name="worktype" id="worktype" value="<?echo $worktype;?>">
		<input type="hidden" name="rowcount" id="rowcount" value="<?echo $rowcount;?>">
		<input type="hidden" name="selectedSN" id="selectedSN" value="<?echo $selectedStr;?>">
		<input type="hidden" name="OrderNo" id="OrderNo" value="<?echo $OrderNo;?>">
		<input type="hidden" name="act" id="act" value="">
	<fieldset border: solid 10px blue;>
		<legend>異動單狀態</legend>
		<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<td nowrap width="100" bgcolor="#C9CBE0">異動單號</td>
				<td width="260" bgcolor="FFFFCC" align="left">
				<?
					if($OrderNo==""){echo "尚未建立請核單";}
					else{echo $OrderNo;}
				?>
				</td>				
			</tr>				
		</table>
	</fieldset>
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
	<?}else{echo $ErrMsg;}?>
	<? if($ErrMsg=="" && $bugetno!=""){//可以繼續查請核資料 
			$org_data=array();
			$org_index=0;
			$strSQL="select p.*,t.TitleName,o.isStudent,o.isJob,o.Education,o.studentGrade,".
					"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
					"from PT_Employed p ".
					"left join title t on p.PTtitle=t.TitleCode ".
					"left join OuterStatus o on o.IdNo=p.IdCode ".
					"where p.Eid in ('".$queryStr."') ".
					"and p.RecordStatus='0' and JobType='".$worktype."' order by p.serialno,p.eid";
			//echo $strSQL;
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){	?>
				<fieldset border: solid 10px blue;>
					<legend>更新異動資料</legend>
					<table width="1400"  cellspacing="1" cellpadding="4" border="1">		
						<tr height="20" align="left" bgcolor="#C9CBE0">
							<td>狀態</td>
							<td>單號</td>
							<td>請核序號</td>
							<td>人員編號</td>		 		
							<td>姓名</td>
							<td>職稱或學歷</td>
							<td>兼任職稱</td>
							<td>可異動期間</td>
							<td>支領類別</td>
							<td>支領金額</td>
							<td>備註</td>
						</tr>
	<?
						//echo $strSQL;
						$result=$db->query($strSQL);
						while($row=$result->fetch()){
							echo "<tr height='20' align='left' bgcolor='FFFFCC'>";
							echo "<td>異動前</td><td>".$row['SerialNo']."</td>".
								 "<td>".$row['Eid']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td>";
							if(trim($row['Role'])=="E"){echo "<td>".trim($row['Title'])."</td>";}
							elseif(trim($row['Role'])=="S"){echo "<td>".$stu_title[trim($row['Title'])]."</td>";}
							else{
								if(trim($row['isStudent'])=="1"){echo "<td>".$stu_title[trim($row['studentGrade'])]."</td>";}
							elseif(trim($row['isJob'])=="1"){echo "<td>".$outer_title[trim($row['Education'])]."</td>";}
								else{echo "<td>查無資料</td>";}
							}
							
							echo "<td>".$row['TitleName']."</td>";
							
							$periods=getCanTransformPeriod($row['Eid']);
							if($periods['num']==0){echo "<td>無可異動期間</td>";}
							else{
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
							echo "</tr>";
							$org_data[$org_index][0]=$row['Eid'];
							$org_data[$org_index][1]=$row['PTtitle'];
							$org_data[$org_index][2]=$row['start_y'];
							$org_data[$org_index][3]=$row['start_m'];
							$org_data[$org_index][4]=$row['start_d'];
							$org_data[$org_index][5]=$row['end_y'];
							$org_data[$org_index][6]=$row['end_m'];
							$org_data[$org_index][7]=$row['end_d'];
							$org_index++;
							echo "<tr>";
							echo "<tr id='tr_".$row['Eid']."_0'><td colspan='11'>異動後".
								 "&nbsp;&nbsp;<img src='pics/plus.gif' style='cursor:pointer;' onClick=\"javascript:add_Row('".trim($row['Eid'])."','".$bug_type."','".$row['PTtitle']."','".$row['start_y']."','".$row['start_m']."','".$row['start_d']."','".$row['end_y']."','".$row['end_m']."','".$row['end_d']."','".$paytype."','".$pay_unit."','".$pay_limit."','".$pay_total."','-2','','','','','','','','','','');\">".
								 "&nbsp;&nbsp;<img src='pics/minus.gif' style='cursor:pointer;' onClick=\"javascript:del_Row('".trim($row['Eid'])."');\">".
								 "<input type='hidden' name='rowcount_".trim($row['Eid'])."' id='rowcount_".trim($row['Eid'])."' value='0'>".
								 "<input type='hidden' name='SN_".trim($row['Eid'])."' id='SN_".trim($row['Eid'])."' value='".trim($row['SerialNo'])."'>".
								 "<input type='hidden' name='IdCode_".trim($row['Eid'])."' id='IdCode_".trim($row['Eid'])."' value='".trim($row['IdCode'])."'>".
								 "<input type='hidden' name='IdNo_".trim($row['Eid'])."' id='IdNo_".trim($row['Eid'])."' value='".trim($row['Pid'])."'>".
								 "<input type='hidden' name='PName_".trim($row['Eid'])."' id='PName_".trim($row['Eid'])."' value='".trim($row['Name'])."'>".
								 "<input type='hidden' name='Title_".trim($row['Eid'])."' id='Title_".trim($row['Eid'])."' value='".trim($row['Title'])."'>".
								 "<input type='hidden' name='Identity_".trim($row['Eid'])."' id='Identity_".trim($row['Eid'])."' value='".trim($row['Role'])."'>".
								 "<input type='hidden' name='payitem_".trim($row['Eid'])."' id='payitem_".trim($row['Eid'])."' value='".trim($row['JobItemCode'])."'>".
								 "<input type='hidden' name='PTtitle_".trim($row['Eid'])."' id='PTtitle_".trim($row['Eid'])."' value='".trim($row['PTtitle'])."'>".
								 "<input type='hidden' name='org_period_".trim($row['Eid'])."' id='org_period_".trim($row['Eid'])."' value='".$periodStr."'>".
								 /*"<input type='hidden' name='startDate_".trim($row['Eid'])."' id='startDate_".trim($row['Eid'])."' value='".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."'>".
								 "<input type='hidden' name='endDate_".trim($row['Eid'])."' id='endDate_".trim($row['Eid'])."' value='".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."'>".*/
								 "<input type='hidden' name='org_paytype_".trim($row['Eid'])."' id='org_paytype_".trim($row['Eid'])."' value='".$paytype."'>".
								 "<input type='hidden' name='org_payunit_".trim($row['Eid'])."' id='org_payunit_".trim($row['Eid'])."' value='".$pay_unit."'>".
								 "<input type='hidden' name='org_paylimit_".trim($row['Eid'])."' id='org_paylimit_".trim($row['Eid'])."' value='".$pay_limit."'>".
								 "<input type='hidden' name='org_paytotal_".trim($row['Eid'])."' id='org_paytotal_".trim($row['Eid'])."' value='".$pay_total."'>".
								 " ";
							echo "</td></tr>";
							echo "<tr id='tr_upload_".$row['Eid']."_0'><td colspan='11'>";
							if(($row['PTtitle']=="4" || $row['PTtitle']=="15") && ($paytype=="day_pay" || $paytype=="month_pay" || $paytype=="case_pay")){
								echo "<div id='Div_upload_".$row['Eid']."' style=''>";
							}else{
								echo "<div id='Div_upload_".$row['Eid']."' style='display: none;'>";
							}
							echo "上傳文件".
								 "&nbsp;&nbsp;<img src='pics/plus.gif' style='cursor:pointer;' onClick=\"javascript:add_uploadRow('".trim($row['Eid'])."');\">".
								 "&nbsp;&nbsp;<img src='pics/minus.gif' style='cursor:pointer;' onClick=\"javascript:del_uploadRow('".trim($row['Eid'])."');\">&nbsp;&nbsp;&nbsp;&nbsp;".
								 "<input type='button' name='introFile_".$row['Eid']."' value='上傳說明' onClick='javascript:showFileIntro();'>".
								 "<input type='hidden' name='uploadrowcount_".trim($row['Eid'])."' id='uploadrowcount_".trim($row['Eid'])."' value='0'>";
							//抓出已上傳檔案							
							 //查詢的Eid是異動的記錄Eid集合,此處的trim($row['Eid'])是被異動的記錄Eid
							$findEid="select distinct Eid as Eid from PT_Employed where FromEid='".trim($row['Eid'])."' and RecordStatus in ('0','-2') and SerialNo='".$OrderNo."'";
							//echo $findEid;
							$findResult=$db->query($findEid);
							$findRow=$findResult->fetchAll();
							if(count($findRow)>0){
								$findResult=$db->query($findEid);
								$qStr = array();							
								while($findRow=$findResult->fetch()){array_push($qStr,$findRow['Eid']);}
								$sqlUploadFile="select * from UploadData where SEid in ('".implode("','",$qStr)."') and status<>'-1'";
								//echo $sqlUploadFile;
								$rsUploadFile=$db->query($sqlUploadFile);
								$ptr=0;
								$dsUploadFile = $rsUploadFile->fetchAll();
								if ( count($dsUploadFile) > 0 ) {
									$rsUploadFile=$db->query($sqlUploadFile);
									while($dsUploadFile = $rsUploadFile->fetch()){
										$fid=$dsUploadFile["Fid"];
										$type=$dsUploadFile["type"];
										$title=$dsUploadFile["FileTitle"];
										
										echo "<div id='fileblock_".trim($row['Eid'])."_".$ptr."' style='padding-top:10px;'>".
											 "<a href='viewfile.php?fid=".$fid."' target='_blank'>檢視檔案</a>&nbsp;&nbsp;&nbsp;".
											 "檔案類型:&nbsp;&nbsp;&nbsp;".
											 "<select name='filetype' id='uploaded_filetype_".trim($row['Eid'])."_".$ptr."' blockid='".$ptr."' eid='".trim($row['Eid'])."' fid='".$fid."'>";
										
										for($i=0;$i<count($FileTypeArray);$i++){
											$option_item="<option ";
											if($type==$FileTypeArray[$i][0])
												$option_item.="selected='selected' ";			
											$option_item.="value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
											echo $option_item;
										}
										
										echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;檔案標題:&nbsp;&nbsp;&nbsp;".
											 "<input type='text' name='filetitle' id='uploaded_filetitle_".trim($row['Eid'])."_".$ptr."' blockid='".$ptr."' eid='".trim($row['Eid'])."' fid='".$fid."' value='".$title."'>".
											 "<input type='button' name='editfile' blockid='".$ptr."' eid='".trim($row['Eid'])."' fid='".$fid."' value='修改' />".
											 "<input type='button' name='delfile' blockid='".$ptr."' eid='".trim($row['Eid'])."' fid='".$fid."' value='刪除' />".
											"</div>";
										$ptr++;
									}
								}
							}
							echo "<input type='hidden' name='uploadedrowcount_".trim($row['Eid'])."' id='uploadedrowcount_".trim($row['Eid'])."' value='".$ptr."'>";
							echo "</div>";
							echo "</td></tr>";
						}
						
	?>
					</table>
				</fieldset>
				<input type="button" value="上一步" onClick="back();">
				<input type="button" id="send_data" value="更新異動單" onClick="javascript:document.getElementById('send_data').disabled=true;next();document.getElementById('send_data').disabled=false;">
	<?		}else{echo "查無可異動資料";}
		}
	?>
	</form>
</body>
</html>
<script language="javascript">
<?
	//查詢上傳檔案類型
	$noArray=array();
	$nameArray=array();
	$sqlFileType="select * from UploadType where TypeClass='B'";
	$rsFileType=$db->query($sqlFileType);
	$ptr=0;
	$noStr="";
	$nameStr="";
	while ( $dsFileType = $rsFileType->fetch() ) {
		$no=$dsFileType["TypeNo"];
		$name=$dsFileType["TypeName"];
		$noArray[$ptr]=$no;
		$nameArray[$ptr]=$name;
		$ptr++;
	}
	$noStr=implode("\",\"",$noArray);
	$nameStr=implode("\",\"",$nameArray);
	echo "var FileTypeNo=new Array(\"".$noStr."\");";
	echo "var FileTypeName=new Array(\"".$nameStr."\");";
	//查詢延遲理由
	$DelayCodeArray=array();
	$DelayNameArray=array();
	$str_reason="select * from DelayType";
	$result_reason=$db->query($str_reason);
	while($row_reason=$result_reason->fetch()){
		array_push($DelayCodeArray,trim($row_reason['DelayCode']));
		array_push($DelayNameArray,trim($row_reason['DelayReason']));		
	}
	$DelayCodeStr=implode("\",\"",$DelayCodeArray);
	$DelayNameStr=implode("\",\"",$DelayNameArray);
	echo "var DelayCode=new Array(\"".$DelayCodeStr."\");";
	echo "var DelayName=new Array(\"".$DelayNameStr."\");";
	//抓出異動記錄,呼叫Add_row來加資料
	$strSQL="select *,".
			"(datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
			"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
			"from PT_employed where SerialNo='".$OrderNo."' and RecordStatus in ('0','-2') and FromEid in ('".$queryStr."') order by FromEid";
	//echo $strSQL;
	$result=$db->query($strSQL);
	while($result && $row=$result->fetch()){
		$key=-1;
		if(isset($row['MonthExpense'])){
			$pay_unit=$row['MonthExpense'];
			$pay_limit="1";
			$paytype="month_pay";
		}else if(isset($row['AwardUnit'])){
			$pay_unit=$row['AwardUnit'];
			$pay_limit=$row['AwardLimit'];
			$paytype="award_pay";
		}else if($row['PayType']=="hr_pay"){
			$pay_unit=$row['PayPerUnit'];
			$pay_limit=$row['LimitPerMonth'];
			$paytype="hr_pay";
		}else if($row['PayType']=="case_pay"){
			$pay_unit=$row['PayPerUnit'];
			$pay_limit=$row['LimitPerMonth'];
			$paytype="case_pay";
		}else if($row['PayType']=="day_pay"){
			$pay_unit=$row['PayPerUnit'];
			$pay_limit=$row['LimitPerMonth'];
			$paytype="day_pay";
		}
		$pay_total=$row['TotalAmount'];
		
		for($i=0;$i<$org_index;$i++){
			if($org_data[$i][0]==$row['FromEid']){$key=$i;break;}
		}
		if($key>=0){
			echo "add_Row('".$row['FromEid']."','".$bug_type."','".$org_data[$i][1]."','".$org_data[$i][2]."','".$org_data[$i][3]."','".$org_data[$i][4]."','".$org_data[$i][5]."','".$org_data[$i][6]."','".$org_data[$i][7]."','".$paytype."','".$pay_unit."','".$pay_limit."','".$pay_total."','".trim($row['RecordStatus'])."','".trim($row['start_y'])."','".trim($row['start_m'])."','".trim($row['start_d'])."','".trim($row['end_y'])."','".trim($row['end_m'])."','".trim($row['end_d'])."','".trim($row['DelayCode'])."','".trim($row['TraceBackReason'])."','".trim($row['Memo'])."');";
		}
	}
?>
function showFileIntro(){
	var intro="若為以下人員，應上傳相關證明文件：\n(1)「清潔工」支領類別選擇「月薪」\n(2)「清潔工」支領類別選擇「日薪」\n";
	intro += "(3)「清潔工」支領類別選擇「按件計酬」\n(4)「臨時工」支領類別選擇「月薪」\n(5)「臨時工」支領類別選擇「日薪」\n";
	intro += "(6)「臨時工」支領類別選擇「按件計酬」\n\n";
	intro += "PS.只能上傳 pdf/jpg 檔,每個檔案大小上限為2MB";
	alert(intro);
}
//確認是否需要顯示上傳介面
function ifShowUpload(eid){
	var Ptitle=document.getElementById('PTtitle_'+eid).value;//兼任職稱
	var needUpload=false;
	var uploadpaytype="";
	var num=document.getElementById('rowcount_'+eid).value;
	for(var i=1;i<=num;i++){
		var paytype=document.getElementById('paytype_'+eid+'_'+i).value;
		if(paytype=="month_pay" || paytype=="day_pay" || paytype=="case_pay"){needUpload=true;uploadpaytype=paytype;}
	}
	//alert(Ptitle+num+" "+uploadnum);
	if(needUpload){
		if(Ptitle=="4" && uploadpaytype=="month_pay"){var filetypeNo="9";}
		if(Ptitle=="4" && uploadpaytype=="day_pay"){var filetypeNo="10";}
		if(Ptitle=="4" && uploadpaytype=="case_pay"){var filetypeNo="11";}
		if(Ptitle=="15" && uploadpaytype=="month_pay"){var filetypeNo="6";}
		if(Ptitle=="15" && uploadpaytype=="day_pay"){var filetypeNo="7";}
		if(Ptitle=="15" && uploadpaytype=="case_pay"){var filetypeNo="8";}
		document.getElementById('Div_upload_'+eid).style.display = "";		
		//$('.Div_upload option[value='+filetypeNo+']').attr('selected','selected');	
		//20150316測試回饋要求,如尚未建立,要自動跑一行出來
		var uploadnum=parseInt(document.getElementById('uploadrowcount_'+eid).value);
		//alert(num);
		if(uploadnum==0){
			add_uploadRow(eid);
		}
	}else if(!needUpload){
		document.getElementById('Div_upload_'+eid).style.display = "none";
		var uploadnum=parseInt(document.getElementById('uploadrowcount_'+eid).value);
		if(uploadnum>0){
			for(i=uploadnum;i>0;i--){del_uploadRow(eid);}
		}
	}
}
//確認各欄位是否有異動,異動的話要做字體加粗變斜
function checkIfModification(eid,rowid){
	//檢視是否有異動
	//確認支領方式
	var old_paytype=document.getElementById('org_paytype_'+eid).value;
	var now_paytype=document.getElementById('paytype_'+eid+'_'+rowid).value;	
	if(old_paytype!=now_paytype){
		$("#paytype_"+eid+"_"+rowid).css({"font-weight": "bold", "font-style": "italic"});		
	}else{
		$("#paytype_"+eid+"_"+rowid).removeAttr('style')
	}
	//確認單位金額
	var old_unit=document.getElementById('org_payunit_'+eid).value;
	var now_unit=document.getElementById('pay_unit_'+eid+'_'+rowid).value;
	if(old_unit!=now_unit){
		$("#pay_unit_"+eid+"_"+rowid).css({"font-weight": "bold", "font-style": "italic"});		
	}else{
		$("#pay_unit_"+eid+"_"+rowid).removeAttr('style')
	}
	//確認每月上限
	var old_limit=document.getElementById('org_paylimit_'+eid).value;
	var now_limit=document.getElementById('pay_limit_'+eid+'_'+rowid).value;
	if(old_limit!=now_limit){
		$("#pay_limit_"+eid+"_"+rowid).css({"font-weight": "bold", "font-style": "italic"});		
	}else{
		$("#pay_limit_"+eid+"_"+rowid).removeAttr('style')
	}
	//確認總金額
	var old_paytotal=document.getElementById('org_paytotal_'+eid).value;
	var now_paytotal=document.getElementById('totalamount_'+eid+'_'+rowid).value;
	if(old_paytotal!=now_paytotal){
		$("#totalamount_"+eid+"_"+rowid).css({"font-weight": "bold", "font-style": "italic"});		
	}else{
		$("#totalamount_"+eid+"_"+rowid).removeAttr('style')
	}
}
//確認是否有延遲異動,是的話要選(+填)理由
function checkDelayModReason(){
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	//取得今天的日期
	var Today=new Date();
	var today_y=Today.getFullYear()-1911;
	var today_m=Today.getMonth()+1;
	var today_d=Today.getDate();
	var todayStr=date_str(today_y.toString(),today_m.toString(),today_d.toString());
	//alert(todayStr+" "+today_y+" "+today_m+" "+today_d);
	//記錄錯誤訊息
	var ErrMsg="",ErrMsg_All="";
	
	for(index=0;index<selectedSN.length;index++){
		var name=document.getElementById('PName_'+selectedSN[index]).value;
		var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
		
		if(parseInt(rowcount)>0){
			ErrMsg="";
			for(var i=1;i<=parseInt(rowcount);i++){
				var start_y=document.getElementById('startDate_y_'+selectedSN[index]+'_'+i).value;
				var start_m=document.getElementById('startDate_m_'+selectedSN[index]+'_'+i).value;
				var start_d=document.getElementById('startDate_d_'+selectedSN[index]+'_'+i).value;
				var start_date=date_str(start_y,start_m,start_d);
				
				var delaycode_now=document.getElementById('DelayCode_'+selectedSN[index]+'_'+i).value;
				var delayreason_now=document.getElementById('DelayReason_'+selectedSN[index]+'_'+i).value;
				delayreason_now = $.trim(delayreason_now);
				if(Compare_date(start_date,todayStr)==3 && delaycode_now==""){
					ErrMsg+="-第"+i+"行\n";
				}else if(Compare_date(start_date,todayStr)==3 && delaycode_now=="8" && delayreason_now==""){
					ErrMsg+="-第"+i+"行\n";
				}				
			}
			if(ErrMsg!=""){
				ErrMsg_All+=name+" \n"+ErrMsg+"\n";
			}
		}
	}
	//alert(ErrMsg_All);
	if(ErrMsg_All!=""){
		ErrMsg_All="未選擇或填寫延遲異動理由:\n"+ErrMsg_All;
		//alert(ErrMsg_All);
		return ErrMsg_All;
	}else{
		return true;
	}	
}
//新增的add_Row的TansType=修改的add_Row的recordstatus,差別在TransType=select tag的值,recordstatus=PT_Employed.RecordStatus
//recordsatus=-2(刪除),-1(補足破月用的,進資料庫時status還是0--變更金額),0(變更金額)
function add_Row(eid,bug_type,PTtitle,start_y,start_m,start_d,end_y,end_m,end_d,paytype,pay_unit,pay_limit,paytotalamount,recordstatus,new_start_y,new_start_m,new_start_d,new_end_y,new_end_m,new_end_d,DCode,DReason,comment){
	var num=document.getElementById('rowcount_'+eid).value;
	num=parseInt(num,10)+1;
	var yearStr="",yearStr2="";
	var monthStr="",monthStr2="";
	var dayStr="",dayStr2="";
	var now_tr_id="tr_"+eid+"_"+(num-1);
	var canedit="readonly";
	if(recordstatus=="0"){canedit="";}
	var str="<tr id='tr_"+eid+"_"+num+"'>"+
			"<td>"+num+".<select name='TransType_"+eid+"_"+num+"' id='TransType_"+eid+"_"+num+"' "+
			"onChange=\"javascript:ChangeTransType('"+eid+"','TransType_"+eid+"_"+num+"','paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');\">";
	if(recordstatus!="-1"){
		str +=	"<option value='1'";
		if(recordstatus=="-2"){str+=" selected";}
		str+=">聘期刪除</option><option value='2'";
		if(parseInt(recordstatus,10)==0){str+=" selected";}
		str+=">金額變更</option></select></td>";
		for(i=parseInt(start_y,10);i<=parseInt(end_y,10);i++){
			yearStr+="<option value='"+i+"'";
			if(new_start_y=="" && i==parseInt(start_y,10)){yearStr+=" selected";}
			else if(i==parseInt(new_start_y,10)){yearStr+=" selected";}
			yearStr+=">"+i+"</option>";
			yearStr2+="<option value='"+i+"'";
			if(new_end_y=="" && i==parseInt(end_y,10)){yearStr2+=" selected";}
			else if(i==parseInt(new_end_y,10)){yearStr2+=" selected";}
			yearStr2+=">"+i+"</option>";
		}
		if(start_y!=end_y){
			var mstart=1;
			var mlimit=12;
		}
		else{
			var mstart=parseInt(start_m,10);
			var mlimit=parseInt(end_m,10);
		}
		for(i=mstart;i<=mlimit;i++){
			monthStr+="<option value='"+i+"'";
			if(new_start_m=="" && i==parseInt(start_m,10)){monthStr+=" selected";}
			else if(i==parseInt(new_start_m,10)){monthStr+=" selected";}
			monthStr+=">"+i+"</option>";
			monthStr2+="<option value='"+i+"'";
			if(new_end_m=="" && i==parseInt(end_m,10)){monthStr2+=" selected";}
			else if(i==parseInt(new_end_m,10)){monthStr2+=" selected";}
			monthStr2+=">"+i+"</option>";
		}
		for(i=1;i<=31;i++){
			dayStr+="<option value='"+i+"'";
			if(new_start_d=="" && i==parseInt(start_d,10)){dayStr+=" selected";}
			else if(i==parseInt(new_start_d,10)){dayStr+=" selected";}
			dayStr+=">"+i+"</option>";
			dayStr2+="<option value='"+i+"'";
			if(new_end_d=="" && i==parseInt(end_d,10)){dayStr2+=" selected";}
			else if(i==parseInt(new_end_d,10)){dayStr2+=" selected";}
			dayStr2+=">"+i+"</option>";
		}
	}else{
		str +=	"<option value='2' selected>金額變更</option></select></td>";
		
		yearStr="<option value='"+start_y+"' selected>"+start_y+"</option>";
		yearStr2="<option value='"+end_y+"' selected>"+end_y+"</option>";
		monthStr="<option value='"+start_m+"' selected>"+start_m+"</option>";
		monthStr2="<option value='"+end_m+"' selected>"+end_m+"</option>";
		dayStr="<option value='"+start_d+"' selected>"+start_d+"</option>";
		dayStr2="<option value='"+end_d+"' selected>"+end_d+"</option>";
		
	}
	str +="<td colspan='3'><select name='startDate_y_"+eid+"_"+num+"' id='startDate_y_"+eid+"_"+num+"'>"+yearStr+"</select>/"+
		  "<select name='startDate_m_"+eid+"_"+num+"' id='startDate_m_"+eid+"_"+num+"'>"+monthStr+"</select>/"+
		  "<select name='startDate_d_"+eid+"_"+num+"' id='startDate_d_"+eid+"_"+num+"'>"+dayStr+"</select>"+
		  "~"+
		  "<select name='endDate_y_"+eid+"_"+num+"' id='endDate_y_"+eid+"_"+num+"'>"+yearStr2+"</select>/"+
		  "<select name='endDate_m_"+eid+"_"+num+"' id='endDate_m_"+eid+"_"+num+"'>"+monthStr2+"</select>/"+
		  "<select name='endDate_d_"+eid+"_"+num+"' id='endDate_d_"+eid+"_"+num+"'>"+dayStr2+"</select>"+
	      "</td>";
	str +="<td colspan='4'>";
	//20150209改以paytype_mapping內的記錄為準
	str +="<select name='paytype_"+eid+"_"+num+"' id='paytype_"+eid+"_"+num+"' ";
	//20150327有異動部分加粗斜體
	var old_paytype=document.getElementById('org_paytype_'+eid).value;
	if(old_paytype!=paytype){
		str += "style='font-weight: bold; font-style: italic;' ";
	}
	str +="onChange=\"javascript:ChangePayType('paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');ifShowUpload("+eid+");checkIfModification("+eid+","+num+");\"";
	if(recordstatus=="-2"){str+=" disabled";}
	str +=">";
	var paytypeArray=getPayTypes(bug_type,PTtitle);
	for(i=0;i<paytypeArray.length;i++){
		//alert(paytypeArray[i]);
		if(paytypeArray[i]!="undefined"){
			if(paytypeArray[i]=="hr_pay"){var paytypeStr="時薪";}
			if(paytypeArray[i]=="case_pay"){var paytypeStr="按件計酬";}
			if(paytypeArray[i]=="day_pay"){var paytypeStr="日薪";}
			if(paytypeArray[i]=="month_pay"){var paytypeStr="月薪";}
			if(paytypeArray[i]=="award_pay"){var paytypeStr="獎助單元";}
			
			str += "<option value='"+paytypeArray[i]+"'";
			if(paytype==paytypeArray[i]){str += " selected";}
			str += ">"+paytypeStr+"</option>";
		}
	}
	str += "</select>";	
	/*if(PTtitle=="4"){
		str +="<select name='paytype_"+eid+"_"+num+"' id='paytype_"+eid+"_"+num+"' onChange=\"javascript:ChangePayType('paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');ifShowUpload("+eid+");\"";
		if(recordstatus=="-2"){str+=" disabled";}
		str +=">";
		str += "<option value='hr_pay'";
		if(paytype=="hr_pay"){str += " selected";}
		str += ">時薪</option>";
		str += "<option value='case_pay'";
		if(paytype=="case_pay"){str += " selected";}
		str += ">按件計酬</option>";
		str += "<option value='day_pay'";
		if(paytype=="day_pay"){str += " selected";}
		str += ">日薪</option>";
		str += "</select>";								
	}else{
		str += "<select name='paytype_"+eid+"_"+num+"' id='paytype_"+eid+"_"+num+"' onChange=\"javascript:ChangePayType('paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');ifShowUpload("+eid+");\"";
		if(recordstatus=="-2"){str+=" disabled";}
		str +=">";
		//科技部 助教級、講師級僅能選月薪
		if(bug_type=="科技部" && (PTtitle=="13" || PTtitle=="14")){
			str += "<option value='month_pay'>月薪</option></select>";
		}else if(bug_type=="科技部" && PTtitle=="3"){//科技部兼任助理支領類別僅能選獎助單元
			str += "<option value='award_pay'>獎助單元</option></select>";
		}else{
			str += "<option value='award_pay'";
			if(paytype=="award_pay"){str += " selected";}
			str += ">獎助單元</option>";
			str += "<option value='month_pay'";
			if(paytype=="month_pay"){str += " selected";}
			str += ">月薪</option>";
			str += "</select>";
		}
	}*/
	str += "&nbsp;&nbsp;";
	str += "<input type='text' name='pay_unit_"+eid+"_"+num+"' id='pay_unit_"+eid+"_"+num+"' value='"+pay_unit+"' size='5' ";
	//20150327有異動部分加粗斜體
	var old_unit=document.getElementById('org_payunit_'+eid).value;
	if(old_unit!=pay_unit){
		str += " style='font-weight: bold; font-style: italic;' ";
	}
	str += "onChange=\"javascript:checkAmount('paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');checkIfModification("+eid+","+num+");\" ";
	if(paytype=="award_pay"){str += " readonly ";}
	else{ str += " "+canedit+" ";}
	if(recordstatus=="-2"){str += " disabled ";}
	str += ">單位/元&nbsp;&nbsp;&nbsp;&nbsp;";
	str += "每月上限<input type='text' name='pay_limit_"+eid+"_"+num+"' id='pay_limit_"+eid+"_"+num+"' value='"+pay_limit+"' size='5' ";
	//20150327有異動部分加粗斜體
	var old_limit=document.getElementById('org_paylimit_'+eid).value;
	if(old_limit!=pay_limit){
		str += " style='font-weight: bold; font-style: italic;' ";
	}
	str += "onChange=\"javascript:checkAmount('paytype_"+eid+"_"+num+"','pay_unit_"+eid+"_"+num+"','pay_limit_"+eid+"_"+num+"','totalamount_"+eid+"_"+num+"');checkIfModification("+eid+","+num+");\" ";
	if(paytype=="month_pay"){str += "readonly disabled";}
	else{str+=canedit;}
	if(recordstatus=="-2"){str += " disabled ";}
	str += "> 單位&nbsp;&nbsp;&nbsp;&nbsp;總額：<input type='text' name='totalamount_"+eid+"_"+num+"' id='totalamount_"+eid+"_"+num+"' value='"+paytotalamount+"' ";
	//20150327有異動部分加粗斜體
	var old_paytotal=document.getElementById('org_paytotal_'+eid).value;
	if(old_paytotal!=paytotalamount){
		str += " style='font-weight: bold; font-style: italic;' ";
	}
	str += " size='5' readonly ";
	if(recordstatus=="-2"){str += " disabled>";}
	else{str += ">";}
	str += "</td>";
	str += "<td colspan='2'>延遲異動理由:<br><select name='DelayCode_"+eid+"_"+num+"' id='DelayCode_"+eid+"_"+num+"'><option value=''>&nbsp;</option>";
	for(i=0;i<DelayCode.length;i++){
		str += "<option value='"+DelayCode[i]+"'";
		if(DCode==DelayCode[i]){str += " selected";}
		str += ">"+DelayName[i]+"</option>";
	}
	str += "</select><br><input type='text' name='DelayReason_"+eid+"_"+num+"' id='DelayReason_"+eid+"_"+num+"' size='30' value='"+DReason+"'></td>";
	str += "<td>備註:<input type='text' name='comment_"+eid+"_"+num+"' size='30' value='"+comment+"'></td>";
	str	+="</tr>";
	
	$('#'+now_tr_id).after(str);
	document.getElementById('rowcount_'+eid).value=num;
}
function add_uploadRow(eid){
	var Ptitle=document.getElementById('PTtitle_'+eid).value;//兼任職稱
	var needUpload=false;
	var uploadpaytype="";
	var num=document.getElementById('rowcount_'+eid).value;
	for(var i=1;i<=num;i++){
		var paytype=document.getElementById('paytype_'+eid+'_'+i).value;
		if(paytype=="month_pay" || paytype=="day_pay" || paytype=="case_pay"){needUpload=true;uploadpaytype=paytype;}
	}
	var filetypeNo="";
	if(needUpload){
		if(Ptitle=="4" && uploadpaytype=="month_pay"){filetypeNo="9";}
		if(Ptitle=="4" && uploadpaytype=="day_pay"){filetypeNo="10";}
		if(Ptitle=="4" && uploadpaytype=="case_pay"){filetypeNo="11";}
		if(Ptitle=="15" && uploadpaytype=="month_pay"){filetypeNo="6";}
		if(Ptitle=="15" && uploadpaytype=="day_pay"){filetypeNo="7";}
		if(Ptitle=="15" && uploadpaytype=="case_pay"){filetypeNo="8";}		
	}
	
	var num=document.getElementById('uploadrowcount_'+eid).value;
	num=parseInt(num,10)+1;
	var now_tr_id="tr_upload_"+eid+"_"+(num-1);
	var str="<tr id='tr_upload_"+eid+"_"+num+"'>"+
			"<td colspan='5'>"+num+
			"<input type='file' name='upload_"+eid+"_"+num+"' id='upload_"+eid+"_"+num+"'></td>"+
			"<td colspan='6'>檔案類型:&nbsp;&nbsp;&nbsp;<select name='filetype_"+eid+"_"+num+"' id='filetype_"+eid+"_"+num+"'>";
	for(var i=0;i<FileTypeNo.length;i++){
		str+="<option value='"+FileTypeNo[i]+"'";
		if(filetypeNo==FileTypeNo[i]){str+=" selected";}
		str+=">"+FileTypeName[i]+"</option>";
	}						
	str+="</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;檔案標題:&nbsp;&nbsp;&nbsp;";
	str += "<input type='text' name='filetitle_"+eid+"_"+num+"' id='filetitle_"+eid+"_"+num+"' />";
	str += "</select>";
	str	+="</tr>";
	
	$('#'+now_tr_id).after(str);
	document.getElementById('uploadrowcount_'+eid).value=num;
}
function del_uploadRow(eid){
	var num=document.getElementById('uploadrowcount_'+eid).value;
	num=parseInt(num,10);
	if(num>0){
		var now_tr_id="tr_upload_"+eid+"_"+num;
		$('#'+now_tr_id).remove();
		document.getElementById('uploadrowcount_'+eid).value=num-1;
	}else{
		alert("沒有得刪了!!!");
	}
}
function next(){
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	var index=0,i=0,new_startdate="",new_enddate;
	var ErrMsg="",ErrMsg2="",ErrMsg_All="",ErrMsg_Overlay="";
	for(index;index<selectedSN.length;index++){
		ErrMsg="";
		ErrMsg2="";
		ErrMsg_Overlay="";
		var name=document.getElementById('PName_'+selectedSN[index]).value;
		var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
		//var org_startdate=document.getElementById('startDate_'+selectedSN[index]).value;
		//var org_enddate=document.getElementById('endDate_'+selectedSN[index]).value;
		var periodStr=document.getElementById('org_period_'+selectedSN[index]).value;
		var periods=periodStr.split(",");
		var start_periods=[];
		var end_periods=[];
		for(i=0;i<periods.length;i++){
			var temp=periods[i];
			var temp2=temp.split("-");
			start_periods[i]=temp2[0];
			end_periods[i]=temp2[1];
		}
		
		if(rowcount>0){
			for(i=1;i<=parseInt(rowcount,10);i++){
				ErrMSg="";
				var start_y=document.getElementById('startDate_y_'+selectedSN[index]+'_'+i).value;
				var start_m=document.getElementById('startDate_m_'+selectedSN[index]+'_'+i).value;
				var start_d=document.getElementById('startDate_d_'+selectedSN[index]+'_'+i).value;
				var end_y=document.getElementById('endDate_y_'+selectedSN[index]+'_'+i).value;
				var end_m=document.getElementById('endDate_m_'+selectedSN[index]+'_'+i).value;
				var end_d=document.getElementById('endDate_d_'+selectedSN[index]+'_'+i).value;
				var start_date=date_str(start_y,start_m,start_d);
				var end_date=date_str(end_y,end_m,end_d);			
				var pay_unit=document.getElementById('pay_unit_'+selectedSN[index]+'_'+i).value;
				var pay_limit=document.getElementById('pay_limit_'+selectedSN[index]+'_'+i).value;
				var pay_total=document.getElementById('totalamount_'+selectedSN[index]+'_'+i).value;
				
				if(!isDate(start_y,start_m,start_d) || start_date==""){
					ErrMsg+="-異動起日未輸入或日期不存在(ex.4/31不存在)!!\n";				
				}
				if(Compare_date(start_date,end_date)==1){
					ErrMsg+="-異動訖日應該大於等於起日,請確認起訖日正確!!\n";
				}
				if(!isDate(end_y,end_m,end_d) || end_date==""){
					ErrMsg+="-異動訖日未輸入或日期不存在(ex.4/31不存在)!!\n";
				}
				if(pay_unit=="0" || pay_limit=="0" || pay_total=="0"){
					ErrMsg+="-請輸入欲異動的金額/上限/總額!!\n";
				}
				var in_periods=false;
				for(j=0;j<start_periods.length;j++){//可能有多個時段,只要在某個時段中就算OK
					if(Compare_date(start_periods[j],start_date)!=1 && Compare_date(end_periods[j],end_date)!=3){
						in_periods=true;
					}
				}
				if(in_periods==false){
					ErrMsg+="-異動的起訖日不可超過可異動範圍!!\n";
				}
				
				if(ErrMsg!=""){
					ErrMsg2+="-第"+i+"行\n"+ErrMsg;
				}
			}
			if(ErrMsg2!=""){
				ErrMsg_All+=name+" 異動資料錯誤:\n"+ErrMsg2+"\n";
			}
		}
		ErrMsg_Overlay=checkPeriodOverlay(selectedSN[index]);
		if(ErrMsg_Overlay!=""){ErrMsg_All+=name+" 異動時段重疊:\n"+ErrMsg_Overlay+"\n";}
	}
	//檢查DelayCode是否需要填寫
	var ErrMsg_Delay=checkDelayModReason();
	
	if(ErrMsg_All!=""){
		alert(ErrMsg_All);
	}else if(ErrMsg_Delay!=true){
		alert(ErrMsg_Delay);
	}else{
		//破月檢查+規則檢查+工作費勾稽
		ErrMsg=checkIsPartMonth();
		if(ErrMsg){
			var warning="";
			var warning2="";
			var warning_All="";
			ErrMsg2="";
			ErrMsg_All="";
			var Mod_ErrMsg="",Mod_ErrMsg2="";
			var checkdata=[];
			var checkMod=[];
			//做規則檢查
			for(index=0;index<selectedSN.length;index++){
				ErrMsg="";
				warning="";
				Mod_ErrMsg=""
				var name=document.getElementById('PName_'+selectedSN[index]).value;
				var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
				//alert(rowcount);
				if(rowcount>0){
					for(i=1;i<=parseInt(rowcount,10);i++){
						var bugno=document.getElementById('bugetno').value;
						var SN=document.getElementById('SN_'+selectedSN[index]).value;
						var PNo=document.getElementById('IdCode_'+selectedSN[index]).value;
						var IdNo=document.getElementById('IdNo_'+selectedSN[index]).value;
						var identity=document.getElementById('Identity_'+selectedSN[index]).value;
						var payitem=document.getElementById('payitem_'+selectedSN[index]).value;
						var Prank=document.getElementById('Title_'+selectedSN[index]).value;
						var Ptitle=document.getElementById('PTtitle_'+selectedSN[index]).value;
						var PayTypeStr=document.getElementById('org_paytype_'+selectedSN[index]).value;
						
						var start_y=document.getElementById('startDate_y_'+selectedSN[index]+'_'+i).value;
						var start_m=document.getElementById('startDate_m_'+selectedSN[index]+'_'+i).value;
						var start_d=document.getElementById('startDate_d_'+selectedSN[index]+'_'+i).value;
						var end_y=document.getElementById('endDate_y_'+selectedSN[index]+'_'+i).value;
						var end_m=document.getElementById('endDate_m_'+selectedSN[index]+'_'+i).value;
						var end_d=document.getElementById('endDate_d_'+selectedSN[index]+'_'+i).value;
						var start_date=date_str(start_y,start_m,start_d);
						var end_date=date_str(end_y,end_m,end_d);			
						var pay_unit=document.getElementById('pay_unit_'+selectedSN[index]+'_'+i).value;
						var pay_limit=document.getElementById('pay_limit_'+selectedSN[index]+'_'+i).value;
						var pay_total=document.getElementById('totalamount_'+selectedSN[index]+'_'+i).value;
						var TransType=document.getElementById('TransType_'+selectedSN[index]+'_'+i).value;
						//alert(bugno+"  "+start_date+"  "+end_date+"  "+PNo+"  "+IdNo+"  "+identity+"  "+payitem+"  "+Prank+"  "+Ptitle+"  "+PayTypeStr+"  "+pay_unit+"  "+pay_limit+"  "+pay_total+"  "+selectedSN[index]);
						if(TransType!="1"){//規則檢查,聘期刪除不需要再做檢查
							checkdata=checkRules(SN,bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,pay_unit,pay_limit,pay_total,selectedSN[index]);
							//alert("checkdata:"+checkdata);
							//console.log(checkRules(bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,pay_unit,pay_limit,pay_total,selectedSN[index]));
							if(checkdata[0]!="ok"){
								//console.log(checkdata);
								checkdata[0] = $.trim(checkdata[0].toString());
								if(checkdata[0]!=""){ErrMsg+=checkdata[0]+"\n";}
								checkdata[1] = $.trim(checkdata[1].toString());
								if(checkdata[1]!=""){warning+=checkdata[1]+"\n";}
							}
						}
						//工作費勾稽檢查
						//console.log(checkAppliedFee(bugno,start_date,end_date,PNo,IdNo,pay_total,selectedSN[index]));
						checkMod=checkAppliedFee(bugno,start_date,end_date,PNo,IdNo,pay_total,selectedSN[index]);
						if(TransType=="1" && parseInt(checkMod[0])>0){Mod_ErrMsg+=start_date+"-"+end_date+" 已有工作費入帳或申請,請先繳回或取消申請,再進行異動\n";}
						else if(checkMod[1]!="ok"){Mod_ErrMsg+=checkMod[1]+"\n";}
					}
				}
				ErrMsg = $.trim(ErrMsg);
				if(ErrMsg!=""){ErrMsg2+=name+"\n"+ErrMsg+"\n\n";}
				warning = $.trim(warning);
				if(warning!=""){warning2+=name+"\n"+warning+"\n\n";}
				Mod_ErrMsg = $.trim(Mod_ErrMsg);
				if(Mod_ErrMsg!=""){Mod_ErrMsg2+=name+"\n"+Mod_ErrMsg+"\n\n";}
			}
			//檢查是否需要附件和是否已有相應文件
			if(!checkIfNeedUpload()){return false;}
			//檢查上傳文件檔案標題
			if(!checkFileTitle()){return false;}
			//檔查上傳檔案副檔名
			var uploadMsg=checkFiletype();
			
			if(ErrMsg2!=""){ErrMsg_All+="支領規則錯誤:\n"+ErrMsg2;}
			if(warning2!=""){warning_All="支領規則警告:\n"+warning2;}
			if(Mod_ErrMsg!=""){ErrMsg_All+="工作費勾稽錯誤:\n"+Mod_ErrMsg2;}
			if(uploadMsg!=""){ErrMsg_All+="上傳檔案格式錯誤:\n"+uploadMsg;}
			if(ErrMsg_All!=""){alert(ErrMsg_All);}
			else{
				//alert("規則OK");
				if(warning_All!=""){alert(warning_All);}
				document.addPT.act.value="updateTransform";
				document.addPT.action="mod_PTtransform_store.php";
				//20150310要求取消confirm
				//if(confirm("確定送出??")){
					//確定送出時,將聘期刪除部分的支領方式select/input tag undisabed,否則會抓不到
					for(index=0;index<selectedSN.length;index++){
						var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
						if(rowcount>0){
							for(i=1;i<=parseInt(rowcount,10);i++){
								document.getElementById('paytype_'+selectedSN[index]+'_'+i).disabled =false;
								document.getElementById('pay_unit_'+selectedSN[index]+'_'+i).disabled =false;
								document.getElementById('pay_limit_'+selectedSN[index]+'_'+i).disabled =false;
								document.getElementById('totalamount_'+selectedSN[index]+'_'+i).disabled =false;
							}
						}						
					}
					document.addPT.submit();
				//}
			}
		}else{
			if(ErrMsg!=false){alert(ErrMsg);}
		}
	}
}
//檢查上傳檔案的副檔名
function checkFiletype() {
	var node_list = document.getElementsByTagName('input');
	var msg="";
	for ( var i = 0; i < node_list.length; i++ ) {
		var node = node_list[i];
		if ( node.getAttribute('type') == 'file' && node.value != "" ) {
			var filename = node.value;
			var toks = filename.split(".");
			var len = toks.length;
			var filetype = toks[len-1];		// 副檔名
			//alert(filename+" 副檔名:"+filetype);
			if ( filetype != "jpg" && filetype != "jpeg" && filetype != "pdf" ) {
				//alert("僅可上傳pdf、jpg檔");
				//return false;
				msg += "檔案名稱:"+ filename +" 副檔名:" + filetype+"\n";
			}
		}
	}
	return msg;
}
//檢查有值的上傳列,標題要寫
function checkFileTitle(){
	var emptyTitle=0;
	//檢查已存在的上傳文件標題
	var filetitle=document.getElementsByName('filetitle');
	for(var i = 0; i < filetitle.length; i++){
		filetitle[i].value = $.trim(filetitle[i].value);
		if(filetitle[i].value==""){emptyTitle=1;}
	}
	//檢查未上傳的文件
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	for(index=0;index<selectedSN.length;index++){
		var rowcount=document.getElementById('uploadrowcount_'+selectedSN[index]).value;
		if(parseInt(rowcount)>0){
			for(var i=1;i<=parseInt(rowcount);i++){
				var filetitle=document.getElementById('filetitle_'+selectedSN[index]+'_'+i).value;
				var uploadfile=document.getElementById('upload_'+selectedSN[index]+'_'+i).value;
				filetitle = $.trim(filetitle);
				if(filetitle=="" && uploadfile.value!=""){emptyTitle=1;}
			}
		}
	}
	if(emptyTitle==1){
		alert("上傳文件的檔案標題不可空白!!");
		return false;
	}else{return true;}
}
//檢查是否清潔工或臨時工,是的話需上傳檔案,且選對檔案格式
function checkIfNeedUpload(){
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	for(index=0;index<selectedSN.length;index++){
		var name=document.getElementById('PName_'+selectedSN[index]).value;
		var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
		var Ptitle=document.getElementById('PTtitle_'+selectedSN[index]).value;//兼任職稱
		
		if(parseInt(rowcount)>0){
			for(var i=1;i<=parseInt(rowcount);i++){
				var mapfile=0;
				var paytype=document.getElementById('paytype_'+selectedSN[index]+'_'+i).value;
				var transtype=document.getElementById('TransType_'+selectedSN[index]+'_'+i).value;
				if(transtype=="2"){
					if((Ptitle=="4" || Ptitle=="15") && (paytype=="month_pay" || paytype=="day_pay" || paytype=="case_pay")){
						if(Ptitle=="4" && paytype=="month_pay"){var filetypeNo="9";}
						if(Ptitle=="4" && paytype=="day_pay"){var filetypeNo="10";}
						if(Ptitle=="4" && paytype=="case_pay"){var filetypeNo="11";}
						if(Ptitle=="15" && paytype=="month_pay"){var filetypeNo="6";}
						if(Ptitle=="15" && paytype=="day_pay"){var filetypeNo="7";}
						if(Ptitle=="15" && paytype=="case_pay"){var filetypeNo="8";}
						//檢查已存在的上傳文件
						var uploadedrowcount=document.getElementById('uploadedrowcount_'+selectedSN[index]).value;
						for(var j=0;j<uploadedrowcount;j++)
						{
							var filetypes=$("select[id='uploaded_filetype_"+selectedSN[index]+"_"+j+"']").val();
							filetypes = $.trim(filetypes);
							if(filetypes==filetypeNo){mapfile=1;}
						}
						//檢查尚未上傳的文件
						var uploadrowcount=document.getElementById('uploadrowcount_'+selectedSN[index]).value;
						for(var j=1;j<=uploadrowcount;j++)
						{
							var filetypes=$("select[id='filetype_"+selectedSN[index]+"_"+j+"']").val();
							var uploadfile=$("input[id='upload_"+selectedSN[index]+"_"+j+"']").val();
							filetypes = $.trim(filetypes);
							if(filetypes==filetypeNo && uploadfile!=""){mapfile=1;}
						}
						if(mapfile==0){alert("臨時工或清潔工,若選擇時薪以外支領方式,需檢附文件,並選擇正確的[檔案類型]!!");return false;}
					}
				}
			}
		}
		
	}
	return true;
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
			alert("確認人員身份時發生錯誤");
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
				//alert("json["+i+"]:"+json[i].toString());
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
						if(!sameErr){warning=warning+tempMsg.substring(8)+"\n";}						
					}
					//console.log(json[i].toString()+"\n"+Msg+"\n"+warning);
				}
			}
			json=null;
			Msg = $.trim(Msg.toString());
			if(Msg!=""){returnMsg[0]=Msg;}else{returnMsg[0]="";}
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
			alert("檢查工作費勾稽時發生錯誤");
			alert(xhr.responseText);
		},
		success: function(json){
			returnMsg=json;
		}
	});
	return returnMsg;
}
//取得每個兼任職稱可以選擇的支領方式
function getPayTypes(bugnotype,PTtitle){
	var returnMsg=[];
	
	$.ajax({
		url: 'getPayTypes.php',
		data:{
			bugnotype:bugnotype,
			PTtitle:PTtitle
		},
		dataType:'json',
		type: 'GET',
		async:false,
		error: function(xhr){
			//$('#errortest').html(xhr.responseText);
			alert("查詢支領方式時發生錯誤");
			alert(xhr.responseText);
		},
		success: function(json){
			returnMsg=json;
		}
	});
	return returnMsg;
}
function del_Row(eid){
	var num=document.getElementById('rowcount_'+eid).value;
	num=parseInt(num,10);
	if(num>0){
		var now_tr_id="tr_"+eid+"_"+num;
		$('#'+now_tr_id).remove();
		document.getElementById('rowcount_'+eid).value=num-1;
	}else{
		alert("沒有得刪了!!!");
	}
}

function checkIsPartMonth(){
	var selectedStr=document.getElementById('selectedSN').value;
	var selectedSN=selectedStr.split(","); 
	var index=0,i=0,j=0,isPart=false,tempYYMM="",tempY="",tempM="";tempD="";
	var ErrMsg="",ErrMsg2="",ErrMsg_All="";
	//此部分判斷是否有切太細的狀況,因上面已排除日期重疊,以幫每個月份加總次數的方式,判斷是否切太細
	for(index=0;index<selectedSN.length;index++){
		ErrMsg="";
		isPart=false;
		var name=document.getElementById('PName_'+selectedSN[index]).value;
		var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
		if(rowcount>0){
			var start_periods=[];
			var end_periods=[];
			for(i=1;i<=parseInt(rowcount,10);i++){
				var start_y=document.getElementById('startDate_y_'+selectedSN[index]+'_'+i).value;
				var start_m=document.getElementById('startDate_m_'+selectedSN[index]+'_'+i).value;
				var start_d=document.getElementById('startDate_d_'+selectedSN[index]+'_'+i).value;
				var end_y=document.getElementById('endDate_y_'+selectedSN[index]+'_'+i).value;
				var end_m=document.getElementById('endDate_m_'+selectedSN[index]+'_'+i).value;
				var end_d=document.getElementById('endDate_d_'+selectedSN[index]+'_'+i).value;
				start_periods[i]=date_str(start_y,start_m,start_d);
				end_periods[i]=date_str(end_y,end_m,end_d);
			}
			var YYMM=[];//記錄有被提到的年月
			var YYMM_count=[];//和YYMM相應的年月,分割段數,記錄被分割的端點
			var pt_index=0;
			for(i=1;i<=parseInt(rowcount,10);i++){
				tempYYMM=start_periods[i].substring(0,5);
				tempY=start_periods[i].substring(0,3);
				tempM=start_periods[i].substring(3,5);
				tempD=start_periods[i].substring(5);
				if(YYMM.toString().indexOf(tempYYMM)==-1){
					YYMM[pt_index]=tempYYMM;
					if(tempD=="01"){YYMM_count[pt_index]="";}
					else{
						YYMM_count[pt_index]=tempD;//若該月最後一天在startDate,仍要切一塊
					}
					pt_index++;
				}else{
					j=YYMM.toString().indexOf(tempYYMM);
					
					if(tempD!="01"){
						if(YYMM_count[j]==""){YYMM_count[j]=YYMM_count[j]+tempD;}
						else{YYMM_count[j]=YYMM_count[j]+"-"+tempD;}
					}
				}
				tempYYMM=end_periods[i].substring(0,5);
				tempY=end_periods[i].substring(0,3);
				tempM=end_periods[i].substring(3,5);
				tempD=end_periods[i].substring(5);
				if(YYMM.toString().indexOf(tempYYMM)==-1){
					YYMM[pt_index]=tempYYMM;
					//若該月第一天在endDate,仍有切一塊
					//以下取得該月最後一日
					var mydate = new Date((parseInt(tempY,10)+1911),parseInt(tempM)-1,"01");
					//alert(tempY+"/"+tempM+"/"+tempD+"    "+mydate+" "+mydate.getMonth());
					mydate.setMonth(mydate.getMonth()+1);
					mydate.setDate(0);
					//alert(tempYYMM+" "+mydate+"  "+mydate.getDate());
					if(tempD==mydate.getDate()){YYMM_count[pt_index]="";}
					else{YYMM_count[pt_index]=tempD;}
					
					pt_index++;
				}else{
					j=YYMM.toString().indexOf(tempYYMM);
					
					var mydate = new Date((parseInt(tempY,10)+1911),parseInt(tempM)-1,"01");					
					//alert(tempY+"/"+tempM+"/"+tempD+"    "+mydate+" "+mydate.getMonth());
					mydate.setMonth(mydate.getMonth()+1);
					mydate.setDate(0);
					//alert(tempYYMM+" "+mydate+"  "+mydate.getDate());
					if(tempD!=mydate.getDate()){
						if(YYMM_count[j]==""){YYMM_count[j]=YYMM_count[j]+tempD;}
						else{YYMM_count[j]=YYMM_count[j]+"-"+tempD;}
					}
				}
			}
			//alert(YYMM_count);
			for(i=0;i<pt_index;i++){//超過二段的年月,串起來
				//if(YYMM_count[i]>1){ErrMsg+=" "+YYMM[i];}
				if(YYMM_count[i]!=""){
					var point=YYMM_count[i].split("-");
					if(point.length>1){//有超過一個端點,若為2個端點且相連,則沒問題,若不是,則切太細
						var diff=parseInt(point[0],10)-parseInt(point[1],10);
						//alert(diff);
						if(point.length==2 && (diff==1 || diff==-1)){}//此為OK情況
						else{ErrMsg+=" "+YYMM[i];}
					}
				}
			}
		}
		if(ErrMsg!=""){ErrMsg2+=name+" 以下月份切割過細:\n"+ErrMsg+"\n";}
	}
	if(ErrMsg2!=""){
		ErrMsg2="破月錯誤\n-原本破月的月份請勿再切割\n-完整月份最多異動為2段,且合起來為完整月份\n\n"+ErrMsg2;
		alert(ErrMsg2);
		return false;
	}else{//沒有切割過細問題,再來看是否有原本完整,切成破月,但又無法合成完整月份
		  //==>若確定是原本足月,就確定切的後一天+第一天(切後面)或最後一天(切前面)有在本次異動時段內即可
		pt_index=0;
		var needAddEid=[];//記錄有需要另加一筆異動來補足月的eid
		var needAddName=[];
		var needAddPartYYMM=[];
		var needAdd_start=[];
		var needAdd_end=[];	
		for(index=0;index<selectedSN.length;index++){
			var name=document.getElementById('PName_'+selectedSN[index]).value;
			var rowcount=document.getElementById('rowcount_'+selectedSN[index]).value;
			if(rowcount>0){
				start_periods=[];
				end_periods=[];
				for(i=1;i<=parseInt(rowcount,10);i++){
					var start_y=document.getElementById('startDate_y_'+selectedSN[index]+'_'+i).value;
					var start_m=document.getElementById('startDate_m_'+selectedSN[index]+'_'+i).value;
					var start_d=document.getElementById('startDate_d_'+selectedSN[index]+'_'+i).value;
					var end_y=document.getElementById('endDate_y_'+selectedSN[index]+'_'+i).value;
					var end_m=document.getElementById('endDate_m_'+selectedSN[index]+'_'+i).value;
					var end_d=document.getElementById('endDate_d_'+selectedSN[index]+'_'+i).value;
					start_periods[i]=date_str(start_y,start_m,start_d);
					end_periods[i]=date_str(end_y,end_m,end_d);
				}
				for(i=1;i<=parseInt(rowcount);i++){
					//檢查起期
					tempYYMM=start_periods[i].substring(0,5);
					tempY=start_periods[i].substring(0,3);
					tempM=start_periods[i].substring(3,5);
					tempD=start_periods[i].substring(5);
					if((needAddPartYYMM.toString().indexOf(tempYYMM)==-1)){//此年月完全不在需要補足月記錄中,要確認
						var check=IsFullMonth(selectedSN[index],tempY,tempM);
						if(check[0]=="full"){//原本是足月的才要往下做
							if(tempD!="01"){//起日且tempD不為第一天時,為破月,要往前確認該月第一天是否在其他時段內,若不存在,應該要增加
								if(!checkIfContain((tempYYMM+"01"),start_periods,end_periods)){
									needAddEid.push(selectedSN[index]);
									needAddName.push(name);
									needAddPartYYMM.push(tempYYMM);
									needAdd_start.push(1);
									needAdd_end.push(parseInt(tempD)-1);
								}
							}
						}
					}else{//若存在此YYMM,要再確認是否為此時檢查的eid所有,若是則結束,不是則要確認
						if(needAddEid[needAddPartYYMM.toString().indexOf(tempYYMM)]!=selectedSN[index]){
							var check=IsFullMonth(selectedSN[index],tempY,tempM);
							if(check[0]=="full"){//原本是足月的才要往下做
								if(tempD!="01"){//起日且tempD不為第一天時,為破月,要往前確認該月第一天是否在其他時段內,若不存在,應該要增加
									if(!checkIfContain((tempYYMM+"01"),start_periods,end_periods)){
										needAddEid.push(selectedSN[index]);
										needAddName.push(name);
										needAddPartYYMM.push(tempYYMM);
										needAdd_start.push(1);
										needAdd_end.push(parseInt(tempD)-1);
									}
								}
							}
						}
					}
					//檢查迄期
					tempYYMM=end_periods[i].substring(0,5);
					tempY=end_periods[i].substring(0,3);
					tempM=end_periods[i].substring(3,5);
					tempD=end_periods[i].substring(5);
					if((needAddPartYYMM.toString().indexOf(tempYYMM)==-1)){//此年月完全不在需要補足月記錄中,要確認
						var check=IsFullMonth(selectedSN[index],tempY,tempM);
						if(check[0]=="full"){//原本是足月的才要往下做
							if(tempD!=check[3]){//迄期且tempD不為最後一天時,為破月,要往後確認該月最後一天是否在其他時段內,若不存在,應該要增加
								if(tempD!=check[3]){//迄期且tempD不為最後一天時,為破月,要往後確認該月最後一天是否在其他時段內,若不存在,應該要增加
									if(!checkIfContain((tempYYMM+check[3]),start_periods,end_periods)){
										needAddEid.push(selectedSN[index]);
										needAddName.push(name);
										needAddPartYYMM.push(tempYYMM);
										needAdd_start.push(parseInt(tempD)+1);
										needAdd_end.push(parseInt(check[3]));
									}
								}
							}
						}
					}else{//若存在此YYMM,要再確認是否為此時檢查的eid所有,若是則結束,不是則要確認
						if(needAddEid[needAddPartYYMM.toString().indexOf(tempYYMM)]!=selectedSN[index]){
							var check=IsFullMonth(selectedSN[index],tempY,tempM);
							if(check[0]=="full"){//原本是足月的才要往下做
								if(tempD!=check[3]){//迄期且tempD不為最後一天時,為破月,要往後確認該月最後一天是否在其他時段內,若不存在,應該要增加
									if(!checkIfContain((tempYYMM+check[3]),start_periods,end_periods)){
										needAddEid.push(selectedSN[index]);
										needAddName.push(name);
										needAddPartYYMM.push(tempYYMM);
										needAdd_start.push(parseInt(tempD)+1);
										needAdd_end.push(parseInt(check[3]));
									}
								}
							}
						}
					}
				}
			}
		}
		ErrMsg="";
		var tempEid="";
		if(needAddEid.length>0){
			for(i=0;i<needAddEid.length;i++){
				if(tempEid!=needAddEid[i]){
					ErrMsg+="\n"+needAddName[i]+"  請核序號:"+needAddEid[i]+"\n";
					tempEid=needAddEid[i];
				}
				ErrMsg+="-"+needAddPartYYMM[i]+addLeadingZero(needAdd_start[i],2)+"-"+needAddPartYYMM[i]+addLeadingZero(needAdd_end[i],2)+"\n";
			}
		}
		if(ErrMsg!=""){
			ErrMsg2="破月檢查\n-因本次異動將下列人員原本完整的請核月份改為破月,為系統運算方便,請增加下列起迄的異動,選擇「金額變更」並維持金額不變\n"+ErrMsg+"\n\n請按「確認」由系統協助您新增異動記錄,或按「取消」由您自己處理";
			if(confirm(ErrMsg2)){
				for(i=0;i<needAddEid.length;i++){
					var PTtitle=document.getElementById('PTtitle_'+needAddEid[i]).value;
					var yy=needAddPartYYMM[i].substring(0,3);
					var mm=needAddPartYYMM[i].substring(3,5);
					var paytype=document.getElementById('org_paytype_'+needAddEid[i]).value;
					var pay_unit=document.getElementById('org_payunit_'+needAddEid[i]).value;
					var pay_limit=document.getElementById('org_paylimit_'+needAddEid[i]).value;
					var paytotalamount=document.getElementById('org_paytotal_'+needAddEid[i]).value;
					//add_Row(needAddEid[i],"<?echo $bug_type;?>",PTtitle,yy,mm,needAdd_start[i],yy,mm,needAdd_end[i],paytype,pay_unit,pay_limit,paytotalamount,"2");			
					//recordstatus=-1表不異動,切出來補足破月而已
					add_Row(needAddEid[i],"<?echo $bug_type;?>",PTtitle,yy,mm,needAdd_start[i],yy,mm,needAdd_end[i],paytype,pay_unit,pay_limit,paytotalamount,"-1","","","","","","","","","");
				}
				alert("新增完成");
				return false;
			}
			else{return false;}
		}		
	}
	return true;
}
//判斷YYMMDD是否包含在start[]或end[]中
function checkIfContain(yymmdd,start_periods,end_periods){
	for(var i=0;i<=start_periods.length;i++){
		if(start_periods[i]==""){continue;}
		if(Compare_date(yymmdd,start_periods[i])!=3 && Compare_date(yymmdd,end_periods[i])!=1){return true;}
	}
	return false;
}
//判斷在原本的聘期中,此年月是否為完整月,回傳array,array[0]=full/part,array[1]=start day,array[2]=end day,array[3]=該月最後一天
function IsFullMonth(eid,yy,mm){
	var periodStr=document.getElementById('org_period_'+eid).value;
	var periods=periodStr.split(",");
	var start_periods=[];
	var end_periods=[];
	var isFull=false;
	var returnData=[];
	for(i=0;i<periods.length;i++){
		var temp=periods[i];
		var temp2=temp.split("-");
		start_periods[i]=temp2[0];
		end_periods[i]=temp2[1];
	}
	//取得本月有幾天
	var mydate = new Date((parseInt(yy,10)+1911),parseInt(mm)-1,"01");
	mydate.setMonth(mydate.getMonth()+1);
	mydate.setDate(0);
	var numDays=mydate.getDate();
	//若有一段時期包含本月第一和最後一天,則為完整月
	for(i=0;i<periods.length;i++){
		if((parseInt((yy+mm+"01"),10)>=parseInt(start_periods[i],10)) && (parseInt((yy+mm+numDays),10)<=parseInt(end_periods[i],10))){
			returnData[0]="full";
			returnData[1]="1";
			returnData[2]=numDays;
			returnData[3]=numDays;
			return returnData;
		}
	}
	//若沒有的話,由start_periods或end_periods找
	for(i=0;i<periods.length;i++){
		var subStart=start_periods[i].substring(0,5);
		var subEnd=end_periods[i].substring(0,5);
		var targetYYMM=yy+addLeadingZero(mm,2);
		if(subStart==targetYYMM){
			returnData[0]="part";
			returnData[1]=start_periods[i].substring(5);
			returnData[2]=numDays;
			returnData[3]=numDays;
			return returnData;
		}else if(subEnd==targetYYMM){
			returnData[0]="part";
			returnData[1]="1";
			returnData[2]=end_periods[i].substring(5);
			returnData[3]=numDays;
			return returnData;
		}
	}
}

function checkPeriodOverlay(eid){//檢查異動時期是否有互相重疊
	var rowcount=document.getElementById('rowcount_'+eid).value;
	var errmsg="";
	
	if(rowcount>1){//2段以上才需要比
		for(i=1;i<=parseInt(rowcount,10);i++){
			var start_y=document.getElementById('startDate_y_'+eid+'_'+i).value;
			var start_m=document.getElementById('startDate_m_'+eid+'_'+i).value;
			var start_d=document.getElementById('startDate_d_'+eid+'_'+i).value;
			var end_y=document.getElementById('endDate_y_'+eid+'_'+i).value;
			var end_m=document.getElementById('endDate_m_'+eid+'_'+i).value;
			var end_d=document.getElementById('endDate_d_'+eid+'_'+i).value;
			var start_date=date_str(start_y,start_m,start_d);
			var end_date=date_str(end_y,end_m,end_d);
			for(j=1;j<=parseInt(rowcount,10);j++){
				if(i==j){continue;}
				else{
					var temp_start_y=document.getElementById('startDate_y_'+eid+'_'+j).value;
					var temp_start_m=document.getElementById('startDate_m_'+eid+'_'+j).value;
					var temp_start_d=document.getElementById('startDate_d_'+eid+'_'+j).value;
					var temp_end_y=document.getElementById('endDate_y_'+eid+'_'+j).value;
					var temp_end_m=document.getElementById('endDate_m_'+eid+'_'+j).value;
					var temp_end_d=document.getElementById('endDate_d_'+eid+'_'+j).value;
					var temp_start_date=date_str(temp_start_y,temp_start_m,temp_start_d);
					var temp_end_date=date_str(temp_end_y,temp_end_m,temp_end_d);
					if(Compare_date(temp_start_date,start_date)!=3 && Compare_date(temp_start_date,end_date)!=1){
						errmsg="第 "+i+" 和第 "+j+" 段異動時期重疊";						
					}else if(Compare_date(temp_end_date,start_date)!=3 && Compare_date(temp_end_date,end_date)!=1){
						errmsg="第 "+i+" 和第 "+j+" 段異動時期重疊";
					}
				}
				if(errmsg!=""){return errmsg;}
			}
		}
	}
	return errmsg;
}

function checkAmount(paytype_id,new_unit_id,new_limit_id,new_totalamount_id){
	var type=document.getElementById(paytype_id).value;
	var new_totalamount=document.getElementById(new_totalamount_id).value;
	var new_unit=document.getElementById(new_unit_id).value;
	var new_limit=document.getElementById(new_limit_id).value;
	
	if(!IsNumeric(new_unit) || !IsNumeric(new_limit)){
		alert("金額和單位只能輸入數字!!");
		if(type=="award_pay"){
			document.getElementById(new_unit_id).value=2000;
			document.getElementById(new_unit_id).readOnly =true;
		}else{
			document.getElementById(new_unit_id).value=0;
			document.getElementById(new_unit_id).readOnly =false;
		}
		if(type=="month_pay"){
			document.getElementById(new_limit_id).value=1;
			document.getElementById(new_limit_id).readOnly =true;
		}else{
			document.getElementById(new_limit_id).value=0;
			document.getElementById(new_limit_id).readOnly =false;
		}
		document.getElementById(new_totalamount_id).value=0;
		return false;
	}
	if(type=="hr_pay" && parseInt(new_unit)<115){
		alert("時薪最低不得低於115!!");
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_limit_id).value=0;
		document.getElementById(new_totalamount_id).value=0;
	}else if(type=="month_pay"){
		new_limit=1;
	}else if(type=="award_pay"){
		new_unit=2000;
	}
	document.getElementById(new_totalamount_id).value=Math.round(new_unit*new_limit);
}
function addLeadingZero(str,index){
	var leadingzero="000000";
	str=leadingzero+str;
	return str.substr(str.length-index,index);
}
function ChangePayType(paytype_id,new_unit_id,new_limit_id,new_totalamount_id){
	var type=document.getElementById(paytype_id).value;
	var new_totalamount=document.getElementById(new_totalamount_id).value;
	var new_unit=document.getElementById(new_unit_id).value;
	var new_limit=document.getElementById(new_limit_id).value;
	
	if(type=="hr_pay"){
		document.getElementById(new_unit_id).readOnly =false;
		document.getElementById(new_unit_id).disabled =false;
		
		document.getElementById(new_limit_id).readOnly =false;
		document.getElementById(new_limit_id).disabled =false;
		
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_limit_id).value=0;
		document.getElementById(new_totalamount_id).value=0;

		if(parseInt(new_unit)<115){
			alert("時薪最低不得低於115!!");
		}
	}else if(type=="award_pay"){
		document.getElementById(new_unit_id).value=2000;
		document.getElementById(new_unit_id).readOnly =true;
		
		document.getElementById(new_limit_id).value=0;
		document.getElementById(new_limit_id).readOnly =false;
		
		document.getElementById(new_totalamount_id).value=0;
	}else if(type=="month_pay"){
		document.getElementById(new_limit_id).value=1;
		document.getElementById(new_limit_id).readOnly =true;
		document.getElementById(new_limit_id).disabled =true;
		
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_unit_id).readOnly =false;
		
		document.getElementById(new_totalamount_id).value=0;
	}
}
function ChangeTransType(eid,transtype_id,new_paytype_id,new_unit_id,new_limit_id,new_total_id){
	var transtype=document.getElementById(transtype_id).value;
	var paytype=document.getElementById(new_paytype_id).value;
	if(transtype=="2"){
		document.getElementById(new_paytype_id).disabled =false;
		if(paytype!="award_pay"){
			document.getElementById(new_unit_id).readOnly =false;
			document.getElementById(new_unit_id).disabled =false;
		}
		if(paytype!="month_pay"){
			document.getElementById(new_limit_id).readOnly =false;
			document.getElementById(new_limit_id).disabled =false;
		}
		
		document.getElementById(new_total_id).disabled =false;
		ifShowUpload(eid);
	}else{
		document.getElementById(new_paytype_id).disabled =true;
		document.getElementById(new_unit_id).readOnly =true;
		document.getElementById(new_unit_id).disabled =true;
		document.getElementById(new_limit_id).readOnly =true;
		document.getElementById(new_limit_id).disabled =true;
		
		document.getElementById(new_total_id).disabled =true;
	}
}
function Compare_date(date1,date2){
	if(parseInt(date1,10)>parseInt(date2,10)){return 1;}
	else if(parseInt(date1,10)==parseInt(date2,10)){return 2;}
	else {return 3;}
}

function date_str(y,m,d){	
	var m=m,d=d;
	if(m.length==1){m="0"+m;}
	if(d.length==1){d="0"+d;}
	return (y+m+d);
}

function IsNumeric(val) {
    if (isNaN(parseFloat(val))) {
          return false;
    }
    return true;
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
       dateStr = (parseInt(year,10)+1911).toString() + '/' + month + '/' + day;  
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
function back(){
	document.addPT.action="mod_PTtransform_select.php";
	document.addPT.submit();
}
</script>
	
	
	