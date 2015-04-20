<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>
<script type="text/javascript">
	$(function(){
		//alert("hello");
		//$("#addFile").click(function() {
			
			//$("#abcd").clone().insertBefore(this);
		//});
		
		
		$("input[name='editfile']").click(function(){
					var id=$(this).attr("blockid");
					var fid=$(this).attr("fid");
					var title=$("input[name='filetitle']").eq(id).val();
					var type=$("select[name='filetype']").eq(id).val();
					
					//alert(fid);
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
				var bkid="#fileblock"+id;
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


			$("#addFile").click(function() {
				
				$("#clonediv").clone().css("display","block").insertAfter(this);
			});
		
		$("#addFileModify").click(function() {
				
				$("#clonediv").clone().css("display","block").insertBefore(this);
			});
		
		
	});
</script>
</head>

<?php 
	include("connectSQL.php");
	include("function.php");
	
	//echo $_SESSION['UserID'];	
	$OrderNo="";$formAction="";$bugetno="";$buget_start="";$buget_end="";
	$IdNo="";$PT_Identity="";$PNo="";$Pname="";$special_status="";$paytype="";
	$start_y="";$start_m="";$start_d="";$end_y="";$end_m="";$end_d="";
	$pay_unit="";$pay_limit="";$totalpay="";$comment="";$Eid="";
	if($_POST['OrderNo']!=""){$OrderNo=$_POST['OrderNo'];}
	//$OrderNo="15";//測試
	
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
	
	
	$Eid=$_POST['Eid'];	
	if($_POST['formAction']!=""){$formAction=$_POST['formAction'];}
	
	$bugetno=filterEvil($_POST['bugetno']);
	//$bugetno="103H800";//測試--其他類
	//$bugetno="103W940";//測試--頂尖
	//$bugetno="103B512";//測試--教育部
	//$bugetno="103R006";//測試--科技部
	$buget_start=filterEvil($_POST['buget_start']);
	$buget_end=filterEvil($_POST['buget_end']);
	$start_y=filterEvil($_POST['PTstart_y']);
	$start_m=filterEvil($_POST['PTstart_m']);
	$start_d=filterEvil($_POST['PTstart_d']);
	$end_y=filterEvil($_POST['PTend_y']);
	$end_m=filterEvil($_POST['PTend_m']);
	$end_d=filterEvil($_POST['PTend_d']);
	$IdNo=filterEvil($_POST['IdNo']);
	$PT_Identity=filterEvil($_POST['PT_Identity']);
	$PNo=filterEvil($_POST['PNo']);
	$Pname=filterEvil($_POST['Pname']);
	$payitem=$_POST['payitem'];//支領項目
	$Prank=$_POST['Prank'];//在校身份或校外人士學歷
	$Ptitle=$_POST['Ptitle'];//兼任職稱	
	if(isset($_POST['aborigin'])){$aborigin="1";}else{$aborigin="0";}
	if(isset($_POST['disability'])){$disability="1";}else{$disability="0";}
	if(isset($_POST['relatives'])){$relatives="1";}else{$relatives="0";}
	$paytype=filterEvil($_POST['paytype']);
	$pay_unit=filterEvil($_POST['pay_unit']);
	$pay_limit=filterEvil($_POST['pay_limit']);
	$totalpay=filterEvil($_POST['totalpay']);
	$TraceBackReason=filterEvil($_POST['TraceBackReason']);
	$comment=filterEvil($_POST['comment']);
	
	$worktype=$_POST['worktype'];//工作類型
	if($worktype=="" && $OrderNo!=""){//點修改進來時,worktype沒有初值
		$strSQL="select * from PT_Outline where SerialNo='".trim($OrderNo)."'";
		$result=$db->query($strSQL) or die($strSQL);
		$row=$result->fetch();
		$worktype=$row['JobType'];
	}
	
	$today=date('Y-m-d H:i:s');//做新增/修改/刪除的時間點
	if($formAction=="newPTapply"){//新增請核單		
		$worktype=$_POST['worktype'];
		$strSQL="insert into PT_Outline ".
				"select '請核','".$worktype."','".$bugetno."','".$today."','".$_SESSION['UserID']."',null,null,'".$_SESSION['Dept']."','".$today."','".$_SESSION['UserID']."','0',*,null from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";
		$result=$db->query($strSQL) or die($strSQL);
		$strSQL="select * from PT_Outline where updatedate='".$today."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		$OrderNo=$row['SerialNo'];
	}else if($formAction=="add"){//新增兼任人員
		//加入請核單明細中
		$strSQL="insert into PT_Employed select '".$OrderNo."','".$PNo."','".$IdNo."','".$Pname."','".$Prank."','".$PT_Identity."','".$Ptitle."','".$worktype."','".($start_y+1911)."-".$start_m."-".$start_d."','".($end_y+1911)."-".$end_m."-".$end_d."','".$payitem."',null,null";
		if($Ptitle=="4"){//臨時工支領方式和金額
			$strSQL.=",'".$paytype."','".$pay_unit."','".$pay_limit."',null,null,null,'".$totalpay."'";
		}else{
			$strSQL.=",null,null,null";
			if($paytype=="month_pay"){$strSQL.=",'".$pay_unit."',null,null,'".$totalpay."'";}
			else{$strSQL.=",null,'".$pay_unit."','".$pay_limit."','".$totalpay."'";}
		}
		$strSQL.=",'".$TraceBackReason."','".$comment."','".$aborigin."','".$disability."','".$relatives."','".$today."','".$_SESSION['UserID']."','".$today."','".$_SESSION['UserID']."',null,null,null,'0',null,null,null,null,null";
		//echo $strSQL."<br>";
		$result=$db->query($strSQL);
		//寫入人員當入狀態資料
		$strSQL="select * from PT_Employed where SerialNo='".$OrderNo."' and UpdateDate='".$today."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		$Eid=trim($row['Eid']);//取得Eid
		if($PT_Identity=="E"){//寫入職員資料到EmpStatus
			$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
					"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".($start_y+1911)."-".$start_m."-".$start_d."' or e.EndDate>='".($start_y+1911)."-".$start_m."-".$start_d."'".
					"where v.empno='".$PNo."'";//不追溯,先用請核起日做基準
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
			while($result && $row=$result->fetch()){
				$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".$PNo."','".$Prank."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$row['TotalAmount']."','".$today."','".$_SESSION['UserID']."'";
				$result=$db->query($strSQL);
				//echo $strSQL."<br>";
			}
		}else if($PT_Identity=="S"){
			$stu_ym=getTerm($start_y,$start_m);//取得請核起日的學年度學期別
			$strSQL="select std_pid,std_degree,std_leavedate,學籍之在學狀況 as status,std_schoolid,std_gmonth,trm_year,trm_term,trm_studystatus,mgd_msgheaderno,".
					"mgd_title,app_type,app_year,app_term,app_date from StudentData s1 ".
					"left join stdterm s2 on s1.std_stdcode=s2.std_stdcode and s2.trm_year='".$stu_ym['cyear']."' and s2.trm_term='".$stu_ym['cterm']."' ".
					"left join stdAbsenceWithdraw s3 on s1.std_stdcode=s3.std_stdcode and s3.app_year='".$stu_ym['cyear']."' and s3.app_term='".$stu_ym['cterm']."' ".
					"where s1.std_stdcode='".$PNo."'";
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
			$row=$result->fetch();
			$strSQL="insert into StuStatus select '".$OrderNo."','".$Eid."','".$PNo."','".$Pname."',null,'".$row['std_pid']."',null,null,null,'".$row['std_degree']."','".$row['std_leavedate']."','".$row['status']."','".$row['std_schoolid']."','".$row['std_gmonth']."','".$row['trm_year']."','".$row['trm_term']."','".$row['trm_studystatus']."','".$row['mgd_msgheaderno']."','".$row['mgd_title']."','".$row['app_type']."','".$row['app_year']."','".$row['app_term']."','".$row['app_date']."','".$today."','".$_SESSION['UserID']."'";
			$result=$db->query($strSQL);
			//echo $strSQL."<br>";
		}
		//建立每月支領狀況資料
		//echo $start_y.$start_m.$start_d.$end_y.$end_m.$end_d;
		$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
		//print_r($yymm);
		for($index=0;$index<sizeof($yymm);$index++){
			$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
			$strSQL="insert into PT_PayInfo select '".$Eid."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$_SESSION['UserID']."','0',null,'".$yymm[$index][5]."'";
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
		}
		
		//================================================
		
	$filetypes=$_POST ['filetype'];
	$filetitles=$_POST ['filetitle'];
	
	$sqlMaxEid="select Max(Eid) as MaxEid from PT_Employed";
	
	
	$rsMaxEid=$db->query($sqlMaxEid);
	$dsMaxEid = $rsMaxEid->fetch();
	$maxeid=$dsMaxEid["MaxEid"];
	
	
	function check($var) { //驗證陣列的傳回值是否為空
		return ($var != "");
	}	
		
	$arrayindex=0;
	$array = array_filter ( $_FILES ["upload"] ["name"], "check" ); //去除陣列中空值
	foreach ( $array as $key => $value ) { //循環讀取陣列中資料
		$path = 'upfile/' . time () . $key . strtolower ( strstr ( $value, "." ) );

		
		$dot_position=strpos($_FILES ["upload"] ["name"] [$key],".");

		$name=substr($_FILES ["upload"] ["name"] [$key], 0,$dot_position);
		$type=substr($_FILES ["upload"] ["name"] [$key], $dot_position+1,strlen($_FILES ["upload"] ["name"] [$key])-$dot_position);
		$filetitle=$filetitles[$arrayindex];
		$filetype=$filetypes[$arrayindex];
		$arrayindex++;
		$status=0;//未審核為0
		echo $_FILES ["upload"] ["name"] [$key]."<br/>";
		echo "value=".$value."<br/>";
		echo "key=".$key."<br/>";
		echo "name=".$name."<br/>";
		echo "type=".$type."<br/>";
		
		
		
		
		$filecontent=file_get_contents($_FILES['upload']['tmp_name'][$key]);
		$queryIn = $db->prepare("
        INSERT INTO     UploadData (SEid ,FileContent,FileName,SubFileType,FileTitle,type,status,CreateEmp,UpdateEmp,CreateDate,UpdateDate)
        VALUES          (:peid , :content,:name,:type ,:filetitle,:filetype,:status,:createemp,:updateemp,getdate(),getdate())");
		//$queryIn->bindParam(':id', $rr );
		$queryIn->bindParam(':peid',$maxeid );
		$queryIn->bindParam(':name', $name );
		$queryIn->bindParam(':type',$type );
		$queryIn->bindParam(':filetitle',$filetitle );
		$queryIn->bindParam(':status',$status );
		$queryIn->bindParam(':filetype',$filetype );
		$queryIn->bindParam(':createemp',$_SESSION['UserID'] );
		$queryIn->bindParam(':updateemp',$_SESSION['UserID'] );
		$queryIn->bindParam(':content', $filecontent , PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
		$queryIn->execute();
		
	}
		
		//================================================
		
		echo "<script language='javascript'>alert('新增兼任人員成功!!');</script>";		
	}else if($formAction=="modify"){//修改兼任人員
		//修改請核單明細中,可以更換人員
		$strSQL="update PT_Employed set IdCode='".$PNo."',Pid='".$IdNo."',[Name]='".$Pname."',Title='".$Prank."',Role='".$PT_Identity."',PTtitle='".$Ptitle."',JobType='".$worktype."',BeginDate='".($start_y+1911)."-".$start_m."-".$start_d."',EndDate='".($end_y+1911)."-".$end_m."-".$end_d."',JobItemCode='".$payitem."'";
		if($Ptitle=="4"){//臨時工支領方式和金額
			$strSQL.=",PayType='".$paytype."',PayPerUnit='".$pay_unit."',LimitPerMonth='".$pay_limit."',MonthExpense=Null,AwardUnit=Null,AwardLimit=Null,TotalAmount='".$totalpay."'";
		}else{
			if($paytype=="month_pay"){$strSQL.=",PayType=Null,PayPerUnit=Null,LimitPerMonth=Null,MonthExpense='".$pay_unit."',AwardUnit=Null,AwardLimit=Null,TotalAmount='".$totalpay."'";}
			else{$strSQL.=",PayType=Null,PayPerUnit=Null,LimitPerMonth=Null,MonthExpense=Null,AwardUnit='".$pay_unit."',AwardLimit='".$pay_limit."',TotalAmount='".$totalpay."'";}
		}
		$strSQL.=",TraceBackReason='".$TraceBackReason."',Memo='".$comment."',IsAboriginal='".$aborigin."',IsDisability='".$disability."',BossRelation='".$relatives."',UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."' where Eid='".$Eid."'";
		//echo $strSQL."<br>";
		$result=$db->query($strSQL);
		//寫入人員當入狀態資料
		if($PT_Identity=="E"){//寫入職員資料到EmpStatus
			$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
					"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".($start_y+1911)."-".$start_m."-".$start_d."' or e.EndDate>='".($start_y+1911)."-".$start_m."-".$start_d."'".
					"where v.empno='".$PNo."'";//不追溯,先用請核起日做基準
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
			while($result && $row=$result->fetch()){
				$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".$PNo."','".$Prank."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$row['TotalAmount']."','".$today."','".$_SESSION['UserID']."'";
				$result=$db->query($strSQL);
			}
		}else if($PT_Identity=="S"){
			$stu_ym=getTerm($start_y,$start_m);//取得請核起日的學年度學期別
			$strSQL="select std_pid,std_degree,std_leavedate,學籍之在學狀況 as status,std_schoolid,std_gmonth,trm_year,trm_term,trm_studystatus,mgd_msgheaderno,".
					"mgd_title,app_type,app_year,app_term,app_date from StudentData s1 ".
					"left join stdterm s2 on s1.std_stdcode=s2.std_stdcode and s2.trm_year='".$stu_ym['cyear']."' and s2.trm_term='".$stu_ym['cterm']."' ".
					"left join stdAbsenceWithdraw s3 on s1.std_stdcode=s3.std_stdcode and s3.app_year='".$stu_ym['cyear']."' and s3.app_term='".$stu_ym['cterm']."' ".
					"where s1.std_stdcode='".$PNo."'";
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
			$row=$result->fetch();
			$strSQL="insert into StuStatus select '".$OrderNo."','".$Eid."','".$PNo."','".$Pname."',null,'".$row['std_pid']."',null,null,null,'".$row['std_degree']."','".$row['std_leavedate']."','".$row['status']."','".$row['std_schoolid']."','".$row['std_gmonth']."','".$row['trm_year']."','".$row['trm_term']."','".$row['trm_studystatus']."','".$row['mgd_msgheaderno']."','".$row['mgd_title']."','".$row['app_type']."','".$row['app_year']."','".$row['app_term']."','".$row['app_date']."','".$today."','".$_SESSION['UserID']."'";
			$result=$db->query($strSQL);
			//echo $strSQL;
		}		
		//先刪除目前支領狀況
		$strSQL="delete from PT_PayInfo where Eid='".$Eid."'";
		$result=$db->query($strSQL);
		//建立每月支領狀況資料
		//echo $start_y.$start_m.$start_d.$end_y.$end_m.$end_d;
		$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
		//print_r($yymm);
		for($index=0;$index<sizeof($yymm);$index++){
			$day_diff=$yymm[$index][3]-$yymm[$index][2]+1;			
			$strSQL="insert into PT_PayInfo select '".$Eid."','".$yymm[$index][0]."','".$yymm[$index][1]."','".$yymm[$index][2]."','".$yymm[$index][3]."','".$day_diff."','".round($totalpay*$day_diff/$yymm[$index][4])."','".$today."','".$_SESSION['UserID']."','0',null,'".$yymm[$index][5]."'";
			//echo $strSQL."<br>";
			$result=$db->query($strSQL);
		}
		
		//=============================================
		
		$filetypes=$_POST ['filetype'];
		$filetitles=$_POST ['filetitle'];
		
		function check($var) { //驗證陣列的傳回值是否為空
		return ($var != "");
	}	
		
	$arrayindex=0;
	if($_FILES ["upload"] ["name"]!=null)
	$array = array_filter ( $_FILES ["upload"] ["name"], "check" ); //去除陣列中空值
	if($array!=null)
	foreach ( $array as $key => $value ) { //循環讀取陣列中資料
		$path = 'upfile/' . time () . $key . strtolower ( strstr ( $value, "." ) );

		
		$dot_position=strpos($_FILES ["upload"] ["name"] [$key],".");

		$name=substr($_FILES ["upload"] ["name"] [$key], 0,$dot_position);
		$type=substr($_FILES ["upload"] ["name"] [$key], $dot_position+1,strlen($_FILES ["upload"] ["name"] [$key])-$dot_position);
		$filetitle=$filetitles[$arrayindex];
		$filetype=$filetypes[$arrayindex];
		$arrayindex++;
		$status=0;//未審核為0
		echo $_FILES ["upload"] ["name"] [$key]."<br/>";
		echo "value=".$value."<br/>";
		echo "key=".$key."<br/>";
		echo "name=".$name."<br/>";
		echo "type=".$type."<br/>";
		
		
		
		
		$filecontent=file_get_contents($_FILES['upload']['tmp_name'][$key]);
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
		$queryIn->execute();
		
	}
	
		//=============================================
		
		echo "<script language='javascript'>alert('修改兼任人員成功!!');</script>";		
	}else if($formAction=="delete"){//刪除兼任人員
		//註記PT_Employed中資料為刪除
		$strSQL="update PT_Employed set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',RecordStatus='-1' where SerialNo='".$OrderNo."' and Eid='".$Eid."'";
		$result=$db->query($strSQL);
		//echo $strSQL."<br>";
		//註記支領項目中資料為刪除
		$strSQL="update PT_PayInfo set updatedate='".$today."',UpdateEmp='".$_SESSION['UserID']."',PayStatus='-1' where Eid='".$Eid."'";
		$result=$db->query($strSQL);
		//echo $strSQL."<br>";
		echo "<script language='javascript'>alert('系統編號:".$Eid."的兼任人員已被刪除!!');</script>";		
	}else if($formAction=="loading"){//帶出已存在兼任人員
		//將資料帶入讓使用者修改,起訖日用JS 呼叫 getBugno.php 做初始
		//但其他的資料直接帶入
		$strSQL="select * from PT_employed where Eid='".$Eid."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		//echo $strSQL;
		
		$IdNo=trim($row['Pid']);
		$PT_Identity=trim($row['Role']);
		$PNo=trim($row['IdCode']);
		$Pname=trim($row['Name']);		
		$payitem=trim($row['JobItemCode']);//支領項目
		$Prank=trim($row['Title']);//在校身份或校外人士學歷
		$Ptitle=trim($row['PTtitle']);//兼任職稱
		$worktype=trim($row['JobType']);//工作類型
		$aborigin=trim($row['IsAboriginal']);
		$disability=trim($row['IsDisability']);
		$relatives=trim($row['BossRelation']);		
		$paytype=trim($row['PayType']);
		$pay_unit=trim($row['PayPerUnit']);
		$pay_limit=trim($row['LimitPerMonth']);
		$monthExpense=trim($row['MonthExpense']);
		if($monthExpense!=""){
			$pay_unit="1";
			$pay_limit=$monthExpense;
		}
		$AwardUnit=trim($row['AwardUnit']);
		$AwardLimit=trim($row['AwardLimit']);
		if($AwardLimit!=""){
			$pay_unit=$AwardUnit;
			$pay_limit=$AwardLimit;
		}
		$totalamount=trim($row['TotalAmount']);
		$comment=trim($row['Memo']);
		$TraceBackReason=trim($row['TraceBackReason']);
	}
	
?>

<body bgcolor='#c1cfb4'>
	<form name="addPT" id="addPT" method="POST" action="new_PTForm.php" target="_self" enctype="multipart/form-data">
	<input type="hidden" name="buget_start" id="buget_start" value="">
	<input type="hidden" name="buget_end" id="buget_end" value="">
	
	<fieldset border: solid 10px blue;>
		<legend>請核單狀態</legend>
		<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<input type="hidden" name="OrderNo" id="OrderNo" value="<?echo $OrderNo;?>"><!--若已建請核單,單號記於此-->
				<input type="hidden" name="formAction" id="formAction" value=""><!--目前輸入部分為新增還是修改,或尚未決定-->
				<input type="hidden" name="Eid" id="Eid" value="<?if($formAction=="loading"){echo $Eid;}?>"><!--記錄要刪或改的Eid-->
				<td nowrap width="100" bgcolor="#C9CBE0">請核單號</td>
				<td width="260" bgcolor="FFFFCC" align="left">
				<?
					if($OrderNo==""){echo "尚未建立請核單";}
					else{echo $OrderNo;}
				?>
				</td>				
			</tr>				
		</table>
	</fieldset>
	<hr>
	<fieldset border: solid 10px blue;>
		<legend>計畫資料</legend><!--初值由 iframe 呼叫 getBugno.php 輸入-->
		<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<td nowrap width="100" bgcolor="#C9CBE0">計畫編號</td>
				<td width="260" bgcolor="FFFFCC" align="left"><input type="text" name="bugetno" id="bugetno" value="" size="30" readonly></td>		 		
				<td nowrap width="120" bgcolor="#C9CBE0">計畫名稱</td>
				<td width="220" bgcolor="FFFFCC" align="left"><div id="bugname"></div></td>				
			</tr>	
			<tr height="20" align="left" bgcolor="#C9CBE0">  		  		
				<td nowrap width="100" bgcolor="#C9CBE0">計畫主持人</td>
				<td  width="260" bgcolor="FFFFCC" align="left" ><div id="bugleader"></div></td>	
				<td nowrap width="120" bgcolor="#C9CBE0" width="120">計畫執行單位</td>
				<td bgcolor="FFFFCC" width="220" align="left"><div id="bugDepname"></div></td>
			</tr>
			<tr height="20" align="left" bgcolor="#C9CBE0"> 		
				<td nowrap width="100" bgcolor="#C9CBE0" width="120">計畫補助/委託單位</td>
				<td nowrap width="260" bgcolor="FFFFCC" align="left"><div id="bugEntrustUnit"></div></td>	
				<td nowrap width="120" bgcolor="#C9CBE0">計畫執行期限</td>
				<td bgcolor="FFFFCC" width="220" align="left"><div id="bugExeDate"></div></td>	
			</tr>
		</table>
	</fieldset>
	<hr>
	<fieldset>
		<legend>請核資料列表</legend>
		<table width="700"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr height="20" align="left" bgcolor="#C9CBE0">
				<th bgcolor="#C9CBE0">序號</th>
				<th bgcolor="#C9CBE0">人事代號</th><th bgcolor="#C9CBE0">姓名</th><th bgcolor="#C9CBE0">兼任職稱</th>
				<th bgcolor="#C9CBE0">工作型態</th><th bgcolor="#C9CBE0">支領項目</th><th bgcolor="#C9CBE0">支領期間</th>
				<th bgcolor="#C9CBE0">支領類別</th><th bgcolor="#C9CBE0">支領金額</th><th bgcolor="#C9CBE0">備註</th>
				<th bgcolor="#C9CBE0">功能</th>
			</tr>				
			<?	
				$bgcolor="";
				if($OrderNo!=""){//已有新增人員,列出清單
					$strSQL="select pt.*,".
							"(datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d,".
							"(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d".
							" from PT_Employed pt ".							
							"where pt.SerialNo='".$OrderNo."' and pt.RecordStatus='0' order by eid desc";
					//echo $strSQL;
					$result=$db->query($strSQL);
					while($row=$result->fetch()){
						if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
						else{$bgcolor="#AFFEFF";}
						echo "<tr bgcolor=".$bgcolor.">".
							 "<td>".trim($row['Eid'])."</td><td>".trim($row['IdCode'])."</td><td>".trim($row['Name'])."</td><td>".$PT_title[$row['PTtitle']]."</td>";
						if(trim($row['JobType'])=="work"){echo "<td>工作型</td>";}else{echo "<td>學習型</td>";}
						$strSQL2="select top 1 * from  [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1' and SerialNo='".trim($row['JobItemCode'])."'";
						$result2=$db->query($strSQL2);
						$row2=$result2->fetch();
						echo "<td>".trim($row2['JobItem_1'])."</td><td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
						if(isset($row['MonthExpense'])){echo "<td>月薪</td>";}
						else if(isset($row['AwardUnit'])){echo "<td>獎助單元</td>";}
						else if($row['PayType']=="hr_pay"){echo "<td>時薪</td>";}
						else if($row['PayType']=="case_pay"){echo "<td>按件計酬</td>";}
						else if($row['PayType']=="day_pay"){echo "<td>日薪</td>";}
						echo "<td>".$row['TotalAmount']."</td>";
						echo "<td>".$row['Memo']."</td>";
						if($formAction=="loading" && $Eid==trim($row['Eid'])){echo "<td><strong>修改中...</strong></td>";}
						else{
							echo "<td><input type='button' value='修改' onClick='javascript:loadingPT(".$OrderNo.",".trim($row['Eid']).");'>";
							echo "<input type='button' value='刪除' onClick='javascript:deletePT(".$OrderNo.",".trim($row['Eid']).");'></td>";
						}
						echo "</tr>";
					}
				}
			?>			
		</table>
		<table width="700" border="0">				
			<tr height="20" align="left">
				<td align="right"><input type="button" name="printform" id="printform" value="產出請核單/契約書"></td>
			</tr>							
		</table>
	</fieldset>
	<hr>
	
	<fieldset>
		<legend>新增請核人員資料</legend>
		<table width="1000"  cellspacing="1" cellpadding="4" bgcolor="#9194BF">				
			<tr align="left" bgcolor="#C9CBE0">
				<td nowrap width="100" bgcolor="#C9CBE0">請核起訖日</td><!--初值由 iframe 呼叫 getBugno.php 輸入-->
				<td  bgcolor="FFFFCC" align="left" colspan="5">
				<?if($formAction!="loading"){?>
					自
					<select name="PTstart_y" id="PTstart_y" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=start&type=<?echo $worktype;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTstart_m" id="PTstart_m" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=start&type=<?echo $worktype;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTstart_d" id="PTstart_d"><option value="">&nbsp;</option></select>
					至
					<select name="PTend_y" id="PTend_y" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=end&type=<?echo $worktype;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTend_m" id="PTend_m" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=end&type=<?echo $worktype;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTend_d" id="PTend_d"><option value="">&nbsp;</option></select>
				<?}else{?>
					自
					<select name="PTstart_y" id="PTstart_y" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=start&type=<?echo $worktype;?>&action=loading&eid=<?echo $Eid;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTstart_m" id="PTstart_m" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=start&type=<?echo $worktype;?>&action=loading&eid=<?echo $Eid;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTstart_d" id="PTstart_d"><option value="">&nbsp;</option></select>
					至
					<select name="PTend_y" id="PTend_y" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=end&type=<?echo $worktype;?>&action=loading&eid=<?echo $Eid;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTend_m" id="PTend_m" onchange="javascript:document.getElementById('getNormalPaycont').src='getBugno.php?bugno='+document.getElementById('bugetno').value+'&tag=end&type=<?echo $worktype;?>&action=loading&eid=<?echo $Eid;?>';"><option value="">&nbsp;</option></select>/
					<select name="PTend_d" id="PTend_d"><option value="">&nbsp;</option></select>
				<?}?>
				<br><font color='red'>PS.請核時間無法回朔</font>
				</td>				
			</tr>	
			<tr height="20" align="left" bgcolor="#C9CBE0">  		  		
				<td nowrap width="100" bgcolor="#C9CBE0">人事代號(現職人員)/<br>學號(在校生)/<br>身分證字號(校外人士)</td>
				<td width="100" bgcolor="FFFFCC" align="left">
					<input type="hidden" name="IdNo" id="IdNo" value="<?if($formAction=="loading"){echo $IdNo;}?>">
					<input type="hidden" name="PT_Identity" id="PT_Identity" value="<?if($formAction=="loading"){echo $PT_Identity;}?>"><!--記錄判斷後的人員類別,E:職員/S:學生/O:校外人士-->
					<input type="text" name="PNo" id="PNo" value="<?if($formAction=="loading"){echo $PNo;}?>" size="10" onchange="javascript:if(checkBugnoAndDate()){document.getElementById('getNormalPaycont').src='getPTPersonInfo.php?bugno='+document.getElementById('bugetno').value+'&type=<?echo $worktype;?>&PNo='+document.getElementById('PNo').value+'&start_y='+document.getElementById('PTstart_y').value+'&start_m='+document.getElementById('PTstart_m').value+'&start_d='+document.getElementById('PTstart_d').value+'&end_y='+document.getElementById('PTend_y').value+'&end_m='+document.getElementById('PTend_m').value+'&end_d='+document.getElementById('PTend_d').value;}" <?if($formAction=="loading"){echo "readonly";}?>>
				</td>
				<td nowrap width="120" bgcolor="#C9CBE0">姓名</td>
				<td width="500" bgcolor="FFFFCC" align="left"><input type="text" name="Pname" id="Pname" value="<?if($formAction=="loading"){echo $Pname;}?>" size="10" readonly></td>
				<td nowrap width="120" bgcolor="#C9CBE0" width="120">支領項目</td>
				<td bgcolor="FFFFCC" align="left">
					<select name="payitem" id="payitem">
					<?
						if($formAction=="loading"){
							$bug_type=checkBugetTypeForPTtitle($bugetno);
							if($PT_Identity=="E"){
								$PT_index=getWordOfEmpno($PNo);
							}elseif($PT_Identity=="S"){$PT_index="Stu";}
							elseif($PT_Identity=="O"){$PT_index="Out";}
							$strSQL = "select * from PT_TitleMapping_Empno p ".
									  "left join title t on t.TitleCode=p.PT_titleCode ".
									  "where pre_empno='".$PT_index."' and buget_type='".$bug_type."' and TitleCode='".$Ptitle."'";	
							
							$result=$db->query($strSQL);
							$row=$result->fetch();
							$payitem=$row['PayItem'];
							$PTrole=$row['TitleCode'];
							$tok = strtok($payitem, ",");
							while ($tok != false) {
								$strSQL = "select * from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1' and SerialNo='".$tok."'";	
								//echo "console.log(\"".$strSQL."\")";
								$result=$db->query($strSQL);
								$row=$result->fetch();
								echo "<option value='".trim($row['SerialNo'])."'";
								if(trim($row['SerialNo'])==$$paytiem){echo " selected";}
								echo ">".trim($row['JobItem_1'])."</option>";
								$tok = strtok(",");
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr height="20" align="left" bgcolor="#C9CBE0">  		  		
				<td nowrap width="100" bgcolor="#C9CBE0">專任職稱/學生級別/學歷</td>
				<td width="100" bgcolor="FFFFCC" align="left">
					<div id="PrankOption">
						<select name="Prank" id="Prank">
						<?
							if($formAction=="loading"){
								if($PT_Identity=="E"){echo "<option value='".$Prank."' selected>".$Prank."</option>";}
								if($PT_Identity=="S"){
									foreach($stu_code as $temp){
										echo "<option value='".$temp."'";
										if($temp==$Prank){echo " selected";}
										echo ">".$stu_title[$temp]."</option>";
									}
								}
								if($PT_Identity=="O"){
									foreach($outer_code as $temp){
										echo "<option value='".$temp."'";
										if($temp==$Prank){echo " selected";}
										echo ">".$outer_title[$temp]."</option>";
									}
								}
							}
						?>
						</select>
					</div>
				</td>
				<td nowrap width="100" bgcolor="#C9CBE0">兼任職稱</td>
				<td  width="200" bgcolor="FFFFCC" align="left" >
					<select name="Ptitle" id="Ptitle" onchange="javascript:document.getElementById('getNormalPaycont').src='getPTTitle.php?bugno='+document.getElementById('bugetno').value+'&type=<?echo $worktype;?>&PNo='+document.getElementById('PNo').value+'&identity='+document.getElementById('PT_Identity').value+'&Ptitle='+document.getElementById('Ptitle').value;">
					<?
						if($formAction=="loading"){
							$bug_type=checkBugetTypeForPTtitle($bugetno);
						
							if($PT_Identity=="E"){
								$PT_index=getWordOfEmpno($PNo);
							}elseif($PT_Identity=="S"){$PT_index="Stu";}
							elseif($PT_Identity=="O"){$PT_index="Out";}
							$strSQL = "select * from PT_TitleMapping_Empno p ".
							  "left join title t on t.TitleCode=p.PT_titleCode ".
							  "where pre_empno='".$PT_index."' and buget_type='".$bug_type."'";	
							$result=$db->query($strSQL);
							while($row=$result->fetch()){
								echo "<option value='".$row['PT_titleCode']."'";
								if($row['PT_titleCode']==$Ptitle){echo " selected";}
								echo ">".$row['TitleName']."</option>";
							}
						}
					?>
					</select>
				</td>	
				<td nowrap width="120" bgcolor="#C9CBE0" width="120">工作型態</td>
				<td bgcolor="FFFFCC" width="220" align="left">
					<select name="worktype" id="worktype" >
						<?if($worktype=="work"){ ?>
							<option value="work" selected>工作型</option>						
						<?}else{?>
							<option value="study" selected>學習型</option>
						<?}?>
					</select>
				</td>
			</tr>
			<tr>
				<td nowrap width="120" bgcolor="#C9CBE0">特殊身份(複選)</td>
				<td bgcolor="FFFFCC" align="left">					
					<input type="checkbox" name="aborigin" id="aborigin" value="aborigin" <?if($formAction=="loading" && $aborigin=="1"){echo  "checked";}?>>原住民<br>
					<input type="checkbox" name="disability" id="disability" value="disability" <?if($formAction=="loading" && $disability=="1"){echo  "checked";}?>>身障人士<br>
					<input type="checkbox" name="relatives" id="relatives" value="relatives" <?if($formAction!="loading" || $relatives!="0"){echo  "checked";}?>>直屬主管之配偶或三等內親眷
				</td>
				<td nowrap width="120" bgcolor="#C9CBE0">支領類別</td>
				<td bgcolor="FFFFCC" align="left"><!--底下輸入預設都無法使用,等上面填的差不多再開放-->					
					<input type="hidden" name="pay_unit" id="pay_unit" value="<?if($formAction=="loading"){echo $pay_unit;}?>"><!--不管選那一種,都把支薪單位和上限寫到此-->
					<input type="hidden" name="pay_limit" id="pay_limit" value="<?if($formAction=="loading"){echo $pay_limit;}?>">
					<input type="radio" name="paytype" id="hr_pay" value="hr_pay" onClick="javascript:checkPayType('hr_pay','hr_pay_unit','hr_pay_limit');" 
					<?
						if($paytype=="hr_pay"){echo " checked";}
						if($formAction!="loading" || $Ptitle!="4"){echo " disabled";}
					?>
					>
					時&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;薪：
					<input type="text" name="hr_pay_unit" id="hr_pay_unit" value="<?if($formAction=="loading" && $paytype=="hr_pay"){echo $pay_unit;}?>" size="5" onChange="javascript:checkPayType('hr_pay','hr_pay_unit','hr_pay_limit');"
					<?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>元/時<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					每月時數上限<input type="text" name="hr_pay_limit" id="hr_pay_limit" value="<?if($formAction=="loading" && $paytype=="hr_pay"){echo $pay_limit;}?>" size="5" onChange="javascript:checkPayType('hr_pay','hr_pay_unit','hr_pay_limit');" <?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>小時<br>
					
					<input type="radio" name="paytype" id="case_pay" value="case_pay" onClick="javascript:checkPayType('case_pay','case_pay_unit','case_pay_limit');"
					<?
						if($paytype=="case_pay"){echo " checked";}
						if($formAction!="loading" || $Ptitle!="4"){echo " disabled";}
					?>
					>
					按件計酬：
					<input type="text" name="case_pay_unit" id="case_pay_unit" value="<?if($formAction=="loading" && $paytype=="case_pay"){echo $pay_unit;}?>" size="5" onChange="javascript:checkPayType('case_pay','case_pay_unit','case_pay_limit');"  <?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>元/件，<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					每月件數上限<input type="text" name="case_pay_limit" id="case_pay_limit" value="<?if($formAction=="loading" && $paytype=="case_pay"){echo $pay_limit;}?>" size="5" onChange="javascript:checkPayType('case_pay','case_pay_unit','case_pay_limit');" <?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>件<br>
					
					<input type="radio" name="paytype" id="day_pay" value="day_pay" onClick="javascript:checkPayType('day_pay','day_pay_unit','day_pay_limit');"
					<?
						if($paytype=="day_pay"){echo " checked";}
						if($formAction!="loading" || $Ptitle!="4"){echo " disabled";}
					?>
					>
					日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;薪：
					<input type="text" name="day_pay_unit" id="day_pay_unit" value="<?if($formAction=="loading" && $paytype=="day_pay"){echo $pay_unit;}?>" size="5" onChange="javascript:checkPayType('day_pay','day_pay_unit','day_pay_limit');" <?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>元/日<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					每月日數上限<input type="text" name="day_pay_limit" id="day_pay_limit" value="<?if($formAction=="loading" && $paytype=="day_pay"){echo $pay_limit;}?>" size="5" onChange="javascript:checkPayType('day_pay','day_pay_unit','day_pay_limit');" <?if($formAction!="loading" || $Ptitle!="4"){echo " readonly";}?>>日<br>
					
					<input type="radio" name="paytype" id="award_pay" value="award_pay" onClick="javascript:checkPayType('award_pay','award_pay_unit','award_pay_limit');"
					<?
						if($AwardUnit!=""){echo " checked";}
						if($formAction!="loading" || $Ptitle=="4"){echo " disabled";}
					?>
					>
					獎助單元：
					<input type="text" name="award_pay_unit" id="award_pay_unit" value="2000" size="5" onChange="javascript:checkPayType('award_pay','award_pay_unit','award_pay_limit');" readonly>元/單元&nbsp;&nbsp;&nbsp;&nbsp;<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					每&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="award_pay_limit" id="award_pay_limit" value="<?if($formAction=="loading" && $AwardUnit!=""){echo $AwardLimit;}?>" size="5" onChange="javascript:checkPayType('award_pay','award_pay_unit','award_pay_limit');" <?if($formAction!="loading" || $Ptitle=="4"){echo " readonly";}?>>&nbsp;&nbsp;單元<br>
					
					<input type="radio" name="paytype" id="month_pay" value="month_pay" onClick="javascript:checkPayType('month_pay','month_pay_unit','month_pay_limit');"
					<?
						if($monthExpense!=""){echo " checked";}
						if($formAction!="loading" || $Ptitle=="4"){echo " disabled";}
					?>
					>月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;薪：<input type="text" name="month_pay_unit" id="month_pay_unit" value="<?if($formAction=="loading" && $monthExpense!=""){echo $monthExpense;}?>" size="5" onChange="javascript:checkPayType('month_pay','month_pay_unit','month_pay_limit');" <?if($formAction!="loading" || $Ptitle=="4"){echo " readonly";}?>>元/月<br>
					<!--加一個月薪上限,預設value=1,統一各種支領項目輸入的格式-->
					<input type="hidden" name="month_pay_limit" id="month_pay_limit" value="1" size="5" readonly>
					<br>
					支領金額共<input type="text" name="totalpay" id="totalpay" size="30" value="<?if($formAction=="loading"){echo round($totalamount);}?>" readonly>元
				</td>
				<td colspan="2" bgcolor="#C9CBE0">
					追溯請核理由:<br><textarea name="TraceBackReason" id="TraceBackReason" rows="3" cols="25"><?if($formAction=="loading"){echo $TraceBackReason;}?></textarea><br>
					
					備註:<br><textarea name="comment" id="comment" rows="3" cols="25"><?if($formAction=="loading"){echo $comment;}?></textarea><br><br>
					<?if($formAction=="loading"){?>
						<input type="button" name="addPTUser" id="addPTUser" value="修改兼任人員" onClick="javascript:document.getElementById('addPTUser').disabled=true;modifyUser();document.getElementById('addPTUser').disabled=false;">
						<input type="button" name="clear" id="clear" value="取消修改" onClick="javascript:clearinput();">
					<?}else{?>
						<input type="button" name="addPTUser" id="addPTUser" value="新增兼任人員" onClick="javascript:document.getElementById('addPTUser').disabled=true;addUser();document.getElementById('addPTUser').disabled=false;">
						<input type="button" name="clear" id="clear" value="取消新增" onClick="javascript:clearinput();">
					<?}?>
					
				<td>
			</tr>
			<tr>
				<td colspan="6">
					<!--<fieldset>
						<legend>僱主負擔費用</legend>
						<table border="0">
							<tr>
								<td colspan="6">
									勞保總額:<input type="text" name="LaborIns_emp_pay_total" id="LaborIns_emp_pay_total" value="" readonly>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									勞退總額:<input type="text" name="retire_pay_total" id="retire_pay_total" value="" readonly>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									健保總額:<input type="text" name="health_pay_total" id="health_pay_total" value="" readonly>
								</td>
							</tr>							
						</table>
					</fieldset>-->
					
					<?php 

if($formAction=="loading"){
	?>
				<fieldset>
						<legend>上傳文件</legend>
<div id="upload_div">
<?php 
	$sqlUploadFile="select * from UploadData where SEid='".$Eid."'";
	//echo $sqlUploadFile;
	$rsUploadFile=$db->query($sqlUploadFile);
	$ptr=0;
	while ( $dsUploadFile = $rsUploadFile->fetch() ) {

			$fid=$dsUploadFile["Fid"];
			$type=$dsUploadFile["type"];
			$title=$dsUploadFile["FileTitle"];
		?>
			
<div id="fileblock<?php echo $ptr;?>" style="padding-top:10px;">
<!-- <input type="file" name="upload[]"> -->
<a href="viewfile.php?fid=<?php echo $fid?>" target="_blank">檢視檔案</a>&nbsp;&nbsp;&nbsp;
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option ";
		if($type==$FileTypeArray[$i][0])
			$option_item.="selected='selected' ";			
		$option_item.="value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle" value="<?php echo $title;?>">
<input type="button" name="editfile" blockid="<?php echo $ptr;?>" fid="<?php echo $fid;?>" value="修改" />
<input type="button" name="delfile" blockid="<?php echo $ptr;?>" fid="<?php echo $fid;?>" value="刪除" />
</div>
	<?php $ptr++;}	?>
</div>	
<input type="button" value="新增上傳欄位1" id="addFile">	
</fieldset>
<?php
}
else{?>
	
										<fieldset>
						<legend>上傳文件</legend>
						<div id="upload_div">
<div id="abcd" style="padding-top:10px;">
<input type="file" name="upload[]">
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype[]">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle[]" />
</div>

<input type="button" value="新增上傳欄位2" id="addFileModify">
</div>
					</fieldset>

<?php	
}
?>
					
				</td>				
			</tr>			
		</table>
	</fieldset>
	</form>
	<div id="clonediv" style="display: none;">
<br />
<input type="file" name="upload[]">
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype[]">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle[]" />
<br />
</div>
</body>
</html>
<script language="javascript">
<?if($OrderNo!="" && $formAction!="loading"){?>
	//因沒有另外建立單子本身的流程,所以在第一個人輸入後建單,之後計畫資料需固定帶入,且鎖定不可以修改
	document.getElementById('getNormalPaycont').src='getBugno.php?bugno=<?echo $bugetno;?>&type=<?echo $worktype;?>';
<?}?>
<?if($formAction=="loading"){?>
	//回復請核起訖日,用getBugno.php控起訖下拉選單
	document.getElementById('getNormalPaycont').src='getBugno.php?bugno=<?echo $bugetno;?>&action=loading&eid=<?echo $Eid;?>&type=<?echo $worktype;?>';	
<?}?>

function addUser(){	
	//無計畫編號或沒有起訖年月日下拉表未輸入計畫資料
	var bugno=document.getElementById('bugetno').value;
	if(bugno==""){alert("尚未輸入欲請核之計畫編號,請輸入仍可以請核之計畫編號!!");return false;}
	var start_y=document.getElementById('PTstart_y').value;
	var start_m=document.getElementById('PTstart_m').value;
	var start_d=document.getElementById('PTstart_d').value;
	var end_y=document.getElementById('PTend_y').value;
	var end_m=document.getElementById('PTend_m').value;
	var end_d=document.getElementById('PTend_d').value;
	var start_date=date_str(start_y,start_m,start_d);
	var end_date=date_str(end_y,end_m,end_d);	
	var start=start_y+start_m+start_d;
	var end=end_y+end_m+end_d;
	var Prank=document.getElementById('Prank').value;
	var payitem=document.getElementById('payitem').value;
	var IdNo=document.getElementById('IdNo').value;
	
	if(!isDate(start_y,start_m,start_d) || start_date==""){
		alert("請核起日未輸入或日期不存在(ex.4/31不存在)");
		return false;
	}
	if(Compare_date(start_date,end_date)==1){alert("請核訖日應該大於等於起日,請確認起訖日正確");return false;}
	if(!isDate(end_y,end_m,end_d) || end_date==""){
		alert("請核訖日未輸入或日期不存在(ex.4/31不存在)");
	}
		
	var PNo=document.getElementById('PNo').value;
	if(PNo==""){alert("請輸入工號/學號/身份證號");return false;}
	var identity=document.getElementById('PT_Identity').value;
	if(identity=="O"){//校外人士要輸入姓名
		var name=document.getElementById('Pname').value;
		if(name.trim()==""){alert("校外人士請記得輸入姓名!!");return false;}
	}
	var Ptitle=document.getElementById('Ptitle').value;//兼任職稱
	var PayType=get_PayType();
	if(PayType=="none" || PayType==""){alert("請選擇支領類別,和相對應的單位薪資和上限數字!");return false;}
	else{
		var PayTypeStr=PayType.substring(0,PayType.indexOf("_"));
		var Pay_unit=document.getElementById(PayTypeStr+'_pay_unit').value;
		var Pay_limit=document.getElementById(PayTypeStr+'_pay_limit').value;
		var PayTotal=document.getElementById('totalpay').value;
		if(PayTypeStr=="hr" && parseInt(Pay_unit)<115){alert("時薪最低不得低於115元");return false;}
		if(parseInt(PayTotal)!=parseInt(Math.round(Pay_unit*Pay_limit))){alert("支薪單元*支薪上限不等於支薪總額,請確認輸入的支薪上限和單元正確");return false;}
	}
	if(document.getElementById('relatives').checked==true){alert("請核人不得為直屬主管之配偶或三等親等內之血親、姻親");return false;}
	
	checkIdentity_Again(bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,'add');
}
function modifyUser(){
	//無計畫編號或沒有起訖年月日下拉表未輸入計畫資料
	var bugno=document.getElementById('bugetno').value;
	if(bugno==""){alert("尚未輸入欲請核之計畫編號,請輸入仍可以請核之計畫編號!!");return false;}
	var start_y=document.getElementById('PTstart_y').value;
	var start_m=document.getElementById('PTstart_m').value;
	var start_d=document.getElementById('PTstart_d').value;
	var end_y=document.getElementById('PTend_y').value;
	var end_m=document.getElementById('PTend_m').value;
	var end_d=document.getElementById('PTend_d').value;
	var start_date=date_str(start_y,start_m,start_d);
	var end_date=date_str(end_y,end_m,end_d);	
	var start=start_y+start_m+start_d;
	var end=end_y+end_m+end_d;
	var Prank=document.getElementById('Prank').value;
	var payitem=document.getElementById('payitem').value;
	var IdNo=document.getElementById('IdNo').value;
	
	if(!isDate(start_y,start_m,start_d) || start_date==""){
		alert("請核起日未輸入或日期不存在(ex.4/31不存在)");
		return false;
	}
	if(Compare_date(start_date,end_date)==1){alert("請核訖日應該大於等於起日,請確認起訖日正確");return false;}
	if(!isDate(end_y,end_m,end_d) || end_date==""){
		alert("請核訖日未輸入或日期不存在(ex.4/31不存在)");
	}
	var PNo=document.getElementById('PNo').value;
	if(PNo==""){alert("請輸入工號/學號/身份證號");return false;}
	var identity=document.getElementById('PT_Identity').value;
	if(identity=="O"){//校外人士要輸入姓名
		var name=document.getElementById('Pname').value;
		if(name.trim()==""){alert("校外人士請記得輸入姓名!!");return false;}
	}
	var Ptitle=document.getElementById('Ptitle').value;//兼任職稱
	var PayType=get_PayType();
	if(PayType=="none" || PayType==""){alert("請選擇支領類別,和相對應的單位薪資和上限數字!");return false;}
	else{
		var PayTypeStr=PayType.substring(0,PayType.indexOf("_"));
		var Pay_unit=document.getElementById(PayTypeStr+'_pay_unit').value;
		var Pay_limit=document.getElementById(PayTypeStr+'_pay_limit').value;
		var PayTotal=document.getElementById('totalpay').value;
		if(PayTypeStr=="hr" && parseInt(Pay_unit)<115){alert("時薪最低不得低於115元");return false;}
		if(parseInt(PayTotal)!=parseInt(Math.round(Pay_unit*Pay_limit))){alert("支薪單元*支薪上限不等於支薪總額,請確認輸入的支薪上限和單元正確");return false;}
	}
	if(document.getElementById('relatives').checked==true){alert("請核人不得為直屬主管之配偶或三等親等內之血親、姻親");return false;}
	
	checkIdentity_Again(bugno,start_date,end_date,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,'modify');
}
function checkRules(bugno,start,end,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,act){
	$.ajax({
		url: 'checkRules.php',
		data:{
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
			PayTotal:PayTotal
		},
		dataType:'json',
		type: 'GET',
		async:false,
		error: function(xhr){
			//$('#errortest').html(xhr.responseText);
			alert(xhr.responseText);
		},
		success: function(json){
			document.getElementById('addPTUser').disabled=false;
			var obj_num=parseInt(json['number']);
			var Msg="";
			var warning="";
			
			for(i=0;i<obj_num;i++){
				//alert(json[i]);
				if(json[i].toString().trim()!="ok"){
					if(json[i].toString().indexOf("warning")==-1){Msg=Msg+json[i].toString().trim()+"\n";}
					else{warning=warning+json[i].toString().substring(8)+"\n";}
				}
			}
			json=null;
			if(Msg.trim()!=""){alert(Msg+"\n");}
			else{
				if(warning.trim()!=""){alert("警告:\n"+warning);}
				alert("act="+act);
				document.addPT.formAction.value=act;
				document.addPT.submit();		
			}
		}
	});
}
//於送出時再確認一次身份和請核起訖日是否符合
function checkIdentity_Again(bugno,start,end,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,act){
	$.ajax({
		url: 'checkIdentity_Again.php',
		data:{
			PNo:PNo,
			start:start,
			end:end
		},
		dataType:'json',
		type: 'GET',
		async:false,
		error: function(xhr){
			//$('#errortest').html(xhr.responseText);
			alert(xhr.responseText);
		},
		success: function(json){			
			//請核追溯超過30天
			if(parseInt(json['dayDiff'])>30){
				var reason=document.getElementById('TraceBackReason').value;
				if(reason.trim()==""){alert("請核日期逾請聘日30日以上，請填寫原因");return false;}
			}
			var Msg="";			
			Msg=Msg+json['EmpError'].toString()+"\n"+json['StuError'].toString()+"\n"+json['OutError'].toString()+"\n";
				
			json=null;			
			if(Msg.trim()!=""){
				alert(Msg);
				return false;
			}else{
				checkRules(bugno,start,end,PNo,IdNo,identity,payitem,Prank,Ptitle,PayTypeStr,Pay_unit,Pay_limit,PayTotal,act);	
			}
		}
	}); 
}
function GetUnique(inputArray) {
	var outputArray = [];

	for (var i = 0; i < inputArray.length; i++) {
		if ((jQuery.inArray(inputArray[i], outputArray)) == -1) {
			outputArray.push(inputArray[i]);
		}
	}
	return outputArray;
}

function deletePT(OrderNo,Eid){
	if(confirm("確定刪除系統編號:"+Eid+"的兼任人員")){
		document.addPT.Eid.value=Eid;
		document.addPT.formAction.value="delete";
		document.addPT.submit();
	}
}
function loadingPT(OrderNo,Eid){
	document.addPT.Eid.value=Eid;
	document.addPT.formAction.value="loading";
	document.addPT.submit();
}
function clearinput(){//重新load此頁,不設loading即可
	document.addPT.Eid.value="";
	document.addPT.formAction.value="";
	document.addPT.submit();
}
function get_PayType(){
  //當只有一個選項的時候 可以得到value 也就不會等於undefined了
  if (document.addPT.paytype.value != undefined)
  {
      return document.addPT.paytype.value;
   }
  else //當有兩個以上的選項時 要用迴圈取得checked的選項
  {
    for (var i=0; i<document.addPT.paytype.length; i++)
    {
      if (document.addPT.paytype[i].checked)
      {
        return document.addPT.paytype[i].value;        
      }
    }	
  }
  return "none";
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

function checkBugnoAndDate(){	
	if(document.getElementById('bugetno').value==""){
		alert("請輸入欲請核的計畫編號");
		return false;
	}else if(document.getElementById('PTstart_y').value=="" && document.getElementById('PTend_y').value==""){
		alert("請輸入尚可請核的計畫編號");
		return false;
	}else{
		var start=document.getElementById('PTstart_y').value+addLeadingZero(document.getElementById('PTstart_m').value,2)+addLeadingZero(document.getElementById('PTstart_d').value,2);
		var end=document.getElementById('PTend_y').value+addLeadingZero(document.getElementById('PTend_m').value,2)+addLeadingZero(document.getElementById('PTend_d').value,2);
		//alert(start+" "+end);
		if(start>end){alert("請核訖日應大於起日,請確認請核起訖範圍正確!!");return false;}
		else{return true;}
	}
}
function addLeadingZero(str,index){
	var leadingzero="000000";
	str=leadingzero+str;
	return str.substr(str.length-index,index);
}
function checkPayType(pay_type,pay_unit,pay_limit){
	document.getElementById(pay_type).checked=true;
	var unit=document.getElementById(pay_unit).value;
	var limit=document.getElementById(pay_limit).value;
	if(unit!="" || limit!=""){//2個都有值時才開始計算
		if(isNaN(unit)){
			alert("支領單位必需為數字或小數");
			document.getElementById(pay_unit).value=0;
		}else if(isNaN(limit)){
			alert("支領上限都必需為數字");
			document.getElementById(pay_limit).value=0;
		}else{
			document.getElementById('totalpay').value=Math.round(unit*limit);
			document.getElementById('pay_unit').value=unit;
			document.getElementById('pay_limit').value=limit;
		}
	}
}
</script>
	
	
	