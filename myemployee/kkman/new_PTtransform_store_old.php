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
<?php 
	include("connectSQL.php");
	include("function.php");
	
	$bugetno="";$ErrMsg="";$worktype="";$can_apply=false;
	
	$worktype=$_POST['worktype'];
	$rowcount=$_POST['rowcount'];
	if(isset($_POST['bugetno'])){$bugetno=mb_strtoupper(filterEvil(trim($_POST['bugetno'])));}
	$bug_type=checkBugetTypeForPTtitle($bugetno);
	$act=$_POST['act'];
	
	$selectedStr=$_POST['selectedSN'];
	$selectedSN=explode(",", $selectedStr);
	$queryStr=implode("','",$selectedSN);//查詢用
	
	$today=date('Y-m-d H:i:s');//做新增/修改/刪除的時間點
	if($act=="newTransform"){//新增異動單
		//建立異動單
		$strSQL="insert into PT_Outline ".
				"select '異動','".$worktype."','".$bugetno."','".$today."','".$_SESSION['UserID']."',null,null,'".$_SESSION['Dept']."','".$today."','".$_SESSION['UserID']."','0',*,null from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";
		$result=$db->query($strSQL) or die($strSQL);
		//echo $strSQL."<br>";
		$strSQL="select * from PT_Outline where updatedate='".$today."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		$OrderNo=$row['SerialNo'];
		//$OrderNo="67";
		foreach($selectedSN as $p){
			//抓原本請核資料
			$strSQL="select p.*,".
					"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
					"from PT_Employed p ".
					"where p.Eid='".$p."'";
			$result=$db->query($strSQL);
			$org_data=$result->fetch();
			//開始寫入異動資料+支領資料,年月相連且相同資料的串在一起,否則另開一筆記錄
			$yymm=countYYMMDD($org_data['start_y'],$org_data['start_m'],$org_data['start_d'],$org_data['end_y'],$org_data['end_m'],$org_data['end_d']);			
			$transformedSN="";//記錄異動產生的記錄Eid,寫回原本的pt_employed 記錄
			for($i=0;$i<count($yymm);$i++){
				$transtype=$_POST['TransType_'.$p.'_'.$i];
				$paytype=$_POST['paytype_'.$p.'_'.$i];
				$pay_unit=$_POST['pay_unit_'.$p.'_'.$i];
				$pay_limit=$_POST['pay_limit_'.$p.'_'.$i];
				$pay_total=$_POST['totalamount_'.$p.'_'.$i];
				$comment=$_POST['comment_'.$p.'_'.$i];
				
				if($i==0){
					$start_y=$yymm[$i][0];$start_m=$yymm[$i][1];$start_d=$yymm[$i][2];$end_y=$yymm[$i][0];$end_m=$yymm[$i][1];$end_d=$yymm[$i][3];
					$org_transtype=$transtype;$org_paytype=$paytype;$org_pay_unit=$pay_unit;$org_pay_limit=$pay_limit;$org_pay_total=$pay_total;
					$org_comment=$comment;
				}else if($transtype!=$org_transtype){
					//和之前異動狀況不同,將之前的建一筆資料
					$strSQL="insert into PT_Employed select '".$OrderNo."','".$org_data['IdCode']."','".$org_data['Pid']."','".$org_data['Name']."','".$org_data['Title']."','".$org_data['Role']."','".$org_data['PTtitle']."','".$org_data['JobType']."','".($start_y+1911)."-".$start_m."-".$start_d."','".($end_y+1911)."-".$end_m."-".$end_d."','".$org_data['JobItemCode']."',null,null";
					if($org_data['PTtitle']=="4"){//臨時工支領方式和金額
						$strSQL.=",'".$org_paytype."','".$org_pay_unit."','".$org_pay_limit."',null,null,null,'".$org_pay_total."'";
					}else{
						$strSQL.=",null,null,null";
						if($org_paytype=="month_pay"){$strSQL.=",'".$org_pay_unit."',null,null,'".$org_pay_total."'";}
						else{$strSQL.=",null,'".$org_pay_unit."','".$org_pay_limit."','".$org_pay_total."'";}
					}
					$strSQL.=",'".$org_data['TraceBackReason']."','".$org_comment."','".$org_data['IsAboriginal']."','".$org_data['IsDisability']."','".$org_data['BossRelation']."','".$today."','".$_SESSION['UserID']."','".$today."','".$_SESSION['UserID']."',null,null,null,";
					if($org_transtype=="1"){$strSQL.="'-3'";}else{$strSQL.="'0'";}
					$strSQL.=",null,null,null,'".$org_data['SerialNo']."','".$p."'";
					//echo $strSQL."<br>";
					$db->query($strSQL);
					//取得新記錄Eid
					$strSQL="select * from PT_Employed where updatedate='".$today."' order by Eid desc";
					$result=$db->query($strSQL);
					$row=$result->fetch();
					$Eid=$row['Eid'];
					$transformedSN.=$Eid.",";
					//建立每月支領狀況資料,如果是刪除聘期則不需要增加
					if($org_transtype!="1"){
						$yymm2=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
						for($index=0;$index<sizeof($yymm2);$index++){
							$day_diff=$yymm2[$index][3]-$yymm2[$index][2]+1;			
							$strSQL="insert into PT_PayInfo select '".$Eid."','".$yymm2[$index][0]."','".$yymm2[$index][1]."','".$yymm2[$index][2]."','".$yymm2[$index][3]."','".$day_diff."','".round($org_pay_total*$day_diff/$yymm2[$index][4])."','".$today."','".$_SESSION['UserID']."','0','".$p."','".$yymm2[$index][5]."'";
							//echo $strSQL."<br>";
							$result=$db->query($strSQL);
						}
					}
					//寫入人員當入狀態資料					
					if(trim($org_data['Role'])=="E"){//寫入職員資料到EmpStatus
						$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
								"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".($start_y+1911)."-".$start_m."-".$start_d."' or e.EndDate>='".($start_y+1911)."-".$start_m."-".$start_d."'".
								"where v.empno='".trim($org_data['IdCode'])."'";//不追溯,先用請核起日做基準
						//echo $strSQL."<br>";
						$result=$db->query($strSQL);
						while($result && $row=$result->fetch()){
							$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".trim($org_data['IdCode'])."','".$row['Title']."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$org_pay_total."','".$today."','".$_SESSION['UserID']."'";
							$result=$db->query($strSQL);
							//echo $strSQL."<br>";
						}
					}else if(trim($org_data['Role'])=="S"){
						$stu_ym=getTerm($start_y,$start_m);//取得請核起日的學年度學期別
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
					//將目前的狀況記錄起來
					$start_y=$yymm[$i][0];$start_m=$yymm[$i][1];$start_d=$yymm[$i][2];$end_y=$yymm[$i][0];$end_m=$yymm[$i][1];$end_d=$yymm[$i][3];
					$org_transtype=$transtype;$org_paytype=$paytype;$org_pay_unit=$pay_unit;$org_pay_limit=$pay_limit;$org_pay_total=$pay_total;
					$org_comment=$comment;
				}else{//異動選項一致
					if($org_paytype!=$paytype || $org_pay_unit!=$pay_unit || $org_pay_limit!=$pay_limit || $org_pay_total!=$pay_total){//但是有調整金額
						//和之前異動狀況不同,將之前的建一筆資料
						$strSQL="insert into PT_Employed select '".$OrderNo."','".$org_data['IdCode']."','".$org_data['Pid']."','".$org_data['Name']."','".$org_data['Title']."','".$org_data['Role']."','".$org_data['PTtitle']."','".$org_data['JobType']."','".($start_y+1911)."-".$start_m."-".$start_d."','".($end_y+1911)."-".$end_m."-".$end_d."','".$org_data['JobItemCode']."',null,null";
						if($org_data['PTtitle']=="4"){//臨時工支領方式和金額
							$strSQL.=",'".$org_paytype."','".$org_pay_unit."','".$org_pay_limit."',null,null,null,'".$org_pay_total."'";
						}else{
							$strSQL.=",null,null,null";
							if($org_paytype=="month_pay"){$strSQL.=",'".$org_pay_unit."',null,null,'".$org_pay_total."'";}
							else{$strSQL.=",null,'".$org_pay_unit."','".$org_pay_limit."','".$org_pay_total."'";}
						}
						$strSQL.=",'".$org_data['TraceBackReason']."','".$org_comment."','".$org_data['IsAboriginal']."','".$org_data['IsDisability']."','".$org_data['BossRelation']."','".$today."','".$_SESSION['UserID']."','".$today."','".$_SESSION['UserID']."',null,null,null,";
						if($org_transtype=="1"){$strSQL.="'-3'";}else{$strSQL.="'0'";}
						$strSQL.=",null,null,null,'".$org_data['SerialNo']."','".$p."'";
						//echo $strSQL."<br>";
						$db->query($strSQL);
						//取得新記錄Eid
						$strSQL="select * from PT_Employed where updatedate='".$today."' order by Eid desc";
						$result=$db->query($strSQL);
						$row=$result->fetch();
						$Eid=$row['Eid'];
						$transformedSN.=$Eid.",";
						//建立每月支領狀況資料,如果是刪除聘期則不需要增加
						if($org_transtype!="1"){
							$yymm2=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
							for($index=0;$index<sizeof($yymm2);$index++){
								$day_diff=$yymm2[$index][3]-$yymm2[$index][2]+1;			
								$strSQL="insert into PT_PayInfo select '".$Eid."','".$yymm2[$index][0]."','".$yymm2[$index][1]."','".$yymm2[$index][2]."','".$yymm2[$index][3]."','".$day_diff."','".round($org_pay_total*$day_diff/$yymm2[$index][4])."','".$today."','".$_SESSION['UserID']."','0','".$p."','".$yymm2[$index][5]."'";
								//echo $strSQL."<br>";
								$result=$db->query($strSQL);
							}
						}
						//寫入人員當入狀態資料					
						if(trim($org_data['Role'])=="E"){//寫入職員資料到EmpStatus
							$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
									"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".($start_y+1911)."-".$start_m."-".$start_d."' or e.EndDate>='".($start_y+1911)."-".$start_m."-".$start_d."'".
									"where v.empno='".trim($org_data['IdCode'])."'";//不追溯,先用請核起日做基準
							//echo $strSQL."<br>";
							$result=$db->query($strSQL);
							while($result && $row=$result->fetch()){
								$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".trim($org_data['IdCode'])."','".$row['Title']."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$org_pay_total."','".$today."','".$_SESSION['UserID']."'";
								$result=$db->query($strSQL);
								//echo $strSQL."<br>";
							}
						}else if(trim($org_data['Role'])=="S"){
							$stu_ym=getTerm($start_y,$start_m);//取得請核起日的學年度學期別
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
						//將目前的狀況記錄起來
						$start_y=$yymm[$i][0];$start_m=$yymm[$i][1];$start_d=$yymm[$i][2];$end_y=$yymm[$i][0];$end_m=$yymm[$i][1];$end_d=$yymm[$i][3];
						$org_transtype=$transtype;$org_paytype=$paytype;$org_pay_unit=$pay_unit;$org_pay_limit=$pay_limit;$org_pay_total=$pay_total;
						$org_comment=$comment;
					}else{//完全和之前的月份狀況相同
						$end_y=$yymm[$i][0];$end_m=$yymm[$i][1];$end_d=$yymm[$i][3];//只改本段異動的訖日
						$org_comment.=$comment;
					}
				}
			}
			//最後,尚未建記錄的一併建立
			$strSQL="insert into PT_Employed select '".$OrderNo."','".$org_data['IdCode']."','".$org_data['Pid']."','".$org_data['Name']."','".$org_data['Title']."','".$org_data['Role']."','".$org_data['PTtitle']."','".$org_data['JobType']."','".($start_y+1911)."-".$start_m."-".$start_d."','".($end_y+1911)."-".$end_m."-".$end_d."','".$org_data['JobItemCode']."',null,null";
			if($org_data['PTtitle']=="4"){//臨時工支領方式和金額
				$strSQL.=",'".$org_paytype."','".$org_pay_unit."','".$org_pay_limit."',null,null,null,'".$org_pay_total."'";
			}else{
				$strSQL.=",null,null,null";
				if($org_paytype=="month_pay"){$strSQL.=",'".$org_pay_unit."',null,null,'".$org_pay_total."'";}
				else{$strSQL.=",null,'".$org_pay_unit."','".$org_pay_limit."','".$org_pay_total."'";}
			}
			$strSQL.=",'".$org_data['TraceBackReason']."','".$org_comment."','".$org_data['IsAboriginal']."','".$org_data['IsDisability']."','".$org_data['BossRelation']."','".$today."','".$_SESSION['UserID']."','".$today."','".$_SESSION['UserID']."',null,null,null,";
			if($org_transtype=="1"){$strSQL.="'-3'";}else{$strSQL.="'0'";}
			$strSQL.=",null,null,null,'".$org_data['SerialNo']."','".$p."'";
			//echo $strSQL."<br>";
			$db->query($strSQL);	
			//取得新記錄Eid
			$strSQL="select * from PT_Employed where updatedate='".$today."' order by Eid desc";
			$result=$db->query($strSQL);
			$row=$result->fetch();
			$Eid=$row['Eid'];
			$transformedSN.=$Eid.",";
			//建立每月支領狀況資料,如果是刪除聘期則不需要增加
			if($org_transtype!="1"){
				$yymm2=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				for($index=0;$index<sizeof($yymm2);$index++){
					$day_diff=$yymm2[$index][3]-$yymm2[$index][2]+1;			
					$strSQL="insert into PT_PayInfo select '".$Eid."','".$yymm2[$index][0]."','".$yymm2[$index][1]."','".$yymm2[$index][2]."','".$yymm2[$index][3]."','".$day_diff."','".round($org_pay_total*$day_diff/$yymm2[$index][4])."','".$today."','".$_SESSION['UserID']."','0','".$p."','".$yymm2[$index][5]."'";
					//echo $strSQL."<br>";
					$result=$db->query($strSQL);
				}
			}
			//寫入人員當入狀態資料					
			if(trim($org_data['Role'])=="E"){//寫入職員資料到EmpStatus
				$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] v ".
						"left join [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource] e on v.empno=e.empno and e.BeginDate<='".($start_y+1911)."-".$start_m."-".$start_d."' or e.EndDate>='".($start_y+1911)."-".$start_m."-".$start_d."'".
						"where v.empno='".trim($org_data['IdCode'])."'";//不追溯,先用請核起日做基準
				//echo $strSQL."<br>";
				$result=$db->query($strSQL);
				while($result && $row=$result->fetch()){
					$strSQL="insert into EmpStatus select '".$OrderNo."','".$Eid."','".trim($org_data['IdCode'])."','".$row['Title']."','".substr($row['Con_BeginDate'],0,10)."','".substr($row['Con_EndDate'],0,10)."','".$org_pay_total."','".$today."','".$_SESSION['UserID']."'";
					$result=$db->query($strSQL);
					//echo $strSQL."<br>";
				}
			}else if(trim($org_data['Role'])=="S"){
				$stu_ym=getTerm($start_y,$start_m);//取得請核起日的學年度學期別
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
			//修改舊的請核/異動記錄,做transformed註記
			$strSQL="update PT_Employed set UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."',RecordStatus='-2',TransformedSN='".$OrderNo."',TransformedEid='".$transformedSN."' where Eid='".$p."'";
			$db->query($strSQL) or die($strSQL);
			//echo $strSQL."<br>";
			//修改舊的payinfo,做transformed註記
			$strSQL="update PT_PayInfo set UpdateDate='".$today."',UpdateEmp='".$_SESSION['UserID']."',PayStatus='-2' where Eid='".$p."'";
			$db->query($strSQL) or die($strSQL);
			//echo $strSQL."<br>";
		}
	}
	if($bugetno!=""){
		//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] where bugetno='".$bugetno."'";	
		$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
				  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
				  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
				  "where v.bugetno='".$bugetno."'";
		//echo $strSQL;
		$result=$db->query($strSQL);		
		if($result && $row=$result->fetch()){
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
	<form name="addPT" id="addPT" method="POST" action="new_PTtransform_store.php" target="_self">
		<input type="hidden" name="bugetno" value="<?echo $bugetno;?>">
		<input type="hidden" name="worktype" value="<?echo $worktype;?>">
		<input type="hidden" name="rowcount" value="<?echo $rowcount;?>">
		<input type="hidden" name="selectedSN" value="<?echo $selectedStr;?>">
		<input type="hidden" name="act" value="">
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
			$strSQL="select p.*,t.TitleName,".
					"(datepart(year,p.BeginDate)-1911) as start_y,datepart(month,p.BeginDate) as start_m,datepart(day,p.BeginDate) as start_d,".
					"(datepart(year,p.EndDate)-1911) as end_y,datepart(month,p.EndDate) as end_m,datepart(day,p.EndDate) as end_d ".
					"from PT_Employed p ".
					"left join title t on p.PTtitle=t.TitleCode ".
					"where p.Eid in ('".$queryStr."') ".
					"and p.RecordStatus='0' and JobType='".$worktype."' order by p.serialno,p.eid";
			//echo $strSQL;
			$result=$db->query($strSQL);
			$index=1;
			if($result && $row=$result->fetch()){	?>
				<fieldset border: solid 10px blue;>
					<legend>建立異動資料</legend>
					<table width="1100"  cellspacing="1" cellpadding="4" border="1">		
						<tr height="20" align="left" bgcolor="#C9CBE0">
							<td>將態</td>
							<td>單號</td>
							<td>請核序號</td>
							<td>人員編號</td>		 		
							<td>姓名</td>
							<td>兼任職稱</td>
							<td>支領期間</td>
							<td>支領類別</td>
							<td>支領金額</td>
							<td>備註</td>
						</tr>
	<?
						$yymm=countYYMMDD($row['start_y'],$row['start_m'],$row['start_d'],$row['end_y'],$row['end_m'],$row['end_d']);						
						echo "<tr height='20' align='left' bgcolor='FFFFCC'>";
						echo "<td>異動前</td><td>".$row['SerialNo']."</td>".
							 "<td>".$row['Eid']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td><td>".$row['TitleName']."</td>".
							"<td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
						
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
						echo "<tr>";
						echo "<td rowspan='".count($yymm)."'>異動後</td>";
						for($i=0;$i<count($yymm);$i++){												
							if($i==0){
								echo "<td><select name='TransType_".$row['Eid']."_".$i."' id='TransType_".$row['Eid']."_".$i."' ".
									 "onChange=\"javascript:ChangeTransType('TransType_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."');\">".
									 "<option value='0'>不變更</option><option value='1'>聘期刪除</option><option value='2'>金額變更</option>".
									 "</td>";	
								echo "<td colspan='2'>".$yymm[$i][0]." 年 ".$yymm[$i][1]." 月 ".$yymm[$i][2]." - ".$yymm[$i][3]." 日</td>";
							}else{
								echo "<td><select name='TransType_".$row['Eid']."_".$i."' id='TransType_".$row['Eid']."_".$i."' ".
									 "onChange=\"javascript:ChangeTransType('TransType_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."');\">".
									 "<option value='0'>不變更</option><option value='1'>聘期刪除</option><option value='2'>金額變更</option>".
									 "</td>";	
								echo "<td colspan='2'>".$yymm[$i][0]." 年 ".$yymm[$i][1]." 月 ".$yymm[$i][2]." - ".$yymm[$i][3]." 日</td>";
							}
							echo "<td colspan='6'>";
							if($row['PTtitle']=="4"){
								echo "<select name='paytype_".$row['Eid']."_".$i."' id='paytype_".$row['Eid']."_".$i."' onChange=\"javascript:ChangePayType('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."');\">";
								echo "<option value='hr_pay'";
								if($paytype=="hr_pay"){echo " selected";}
								echo ">時薪</option>";
								echo "<option value='case_pay'";
								if($paytype=="case_pay"){echo " selected";}
								echo ">按件計酬</option>";
								echo "<option value='day_pay'";
								if($paytype=="day_pay"){echo " selected";}
								echo ">日薪</option>";
								echo "</select>";								
							}else{
								echo "<select name='paytype_".$row['Eid']."_".$i."' id='paytype_".$row['Eid']."_".$i."' onChange=\"javascript:ChangePayType('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."');\">";
								//科技部 助教級、講師級僅能選月薪
								if($bug_type=="科技部" && ($row['PTtitle']=="13" || $row['PTtitle']=="14")){
									echo "<option value='month_pay'>月薪</option></select>";
								}else if($bug_type=="科技部" && $row['PTtitle']=="3"){//科技部兼任助理支領類別僅能選獎助單元
									echo "<option value='award_pay'>獎助單元</option></select>";
								}else{
									echo "<option value='award_pay'";
									if($paytype=="award_pay"){echo " selected";}
									echo ">獎助單元</option>";
									echo "<option value='month_pay'";
									if($paytype=="month_pay"){echo " selected";}
									echo ">月薪</option>";
									echo "</select>";
								}
							}
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<input type='text' name='pay_unit_".$row['Eid']."_".$i."' id='pay_unit_".$row['Eid']."_".$i."' value='".Round($pay_unit)."' size='5' onChange=\"javascript:checkAmount('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."')\" readonly>單位/元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "每月上限<input type='text' name='pay_limit_".$row['Eid']."_".$i."' id='pay_limit_".$row['Eid']."_".$i."' value='".Round($pay_limit)."' size='5' onChange=\"javascript:checkAmount('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."')\" readonly> 單位";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總額：<input type='text' name='totalamount_".$row['Eid']."_".$i."' id='totalamount_".$row['Eid']."_".$i."' value='".Round($pay_total)."' size='5' readonly>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;備註:<input type='text' name='comment_".$row['Eid']."_".$i."' size='30'>";
							echo "</td>";
							echo "</tr>";
						}
						while($row=$result->fetch()){
							$yymm=countYYMMDD($row['start_y'],$row['start_m'],$row['start_d'],$row['end_y'],$row['end_m'],$row['end_d']);						
							echo "<tr height='20' align='left' bgcolor='FFFFCC'>";
							echo "<td>異動前</td><td>".$row['SerialNo']."</td>".
								 "<td>".$row['Eid']."</td><td>".$row['IdCode']."</td><td>".$row['Name']."</td><td>".$row['TitleName']."</td>".
								"<td>".$row['start_y'].addLeadingZeros($row['start_m'],2).addLeadingZeros($row['start_d'],2)."-".$row['end_y'].addLeadingZeros($row['end_m'],2).addLeadingZeros($row['end_d'],2)."</td>";
							
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
							echo "<tr>";
							echo "<td rowspan='".count($yymm)."'>異動後</td>";
							for($i=0;$i<count($yymm);$i++){												
								if($i==0){
									echo "<td><select name='TransType_".$row['Eid']."_".$i."' id='TransType_".$row['Eid']."_".$i."' ".
									 "onChange=\"javascript:ChangeTransType('TransType_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."');\">".
										 "<option value='0'>不變更</option><option value='1'>聘期刪除</option><option value='2'>金額變更</option>".
										 "</td>";	
									echo "<td colspan='2'>".$yymm[$i][0]." 年 ".$yymm[$i][1]." 月 ".$yymm[$i][2]." - ".$yymm[$i][3]." 日</td>";
								}else{
									echo "<td><select name='TransType_".$row['Eid']."_".$i."' id='TransType_".$row['Eid']."_".$i."' ".
									 "onChange=\"javascript:ChangeTransType('TransType_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."');\">".
										 "<option value='0'>不變更</option><option value='1'>聘期刪除</option><option value='2'>金額變更</option>".
										 "</td>";	
									echo "<td colspan='2'>".$yymm[$i][0]." 年 ".$yymm[$i][1]." 月 ".$yymm[$i][2]." - ".$yymm[$i][3]." 日</td>";
								}
								echo "<td colspan='6'>";
								if($row['PTtitle']=="4"){
									echo "<select name='paytype_".$row['Eid']."_".$i."' id='paytype_".$row['Eid']."_".$i."' onChange=\"javascript:ChangePayType('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."');\">";
									echo "<option value='hr_pay'";
									if($paytype=="hr_pay"){echo " selected";}
									echo ">時薪</option>";
									echo "<option value='case_pay'";
									if($paytype=="case_pay"){echo " selected";}
									echo ">按件計酬</option>";
									echo "<option value='day_pay'";
									if($paytype=="day_pay"){echo " selected";}
									echo ">日薪</option>";
									echo "</select>";								
								}else{
									echo "<select name='paytype_".$row['Eid']."_".$i."' id='paytype_".$row['Eid']."_".$i."' onChange=\"javascript:ChangePayType('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."');\">";
									//科技部 助教級、講師級僅能選月薪
									if($bug_type=="科技部" && ($row['PTtitle']=="13" || $row['PTtitle']=="14")){
										echo "<option value='month_pay'>月薪</option></select>";
									}else if($bug_type=="科技部" && $row['PTtitle']=="3"){//科技部兼任助理支領類別僅能選獎助單元
										echo "<option value='award_pay'>獎助單元</option></select>";
									}else{
										echo "<option value='award_pay'";
										if($paytype=="award_pay"){echo " selected";}
										echo ">獎助單元</option>";
										echo "<option value='month_pay'";
										if($paytype=="month_pay"){echo " selected";}
										echo ">月薪</option>";
										echo "</select>";
									}
								}
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								echo "<input type='text' name='pay_unit_".$row['Eid']."_".$i."' id='pay_unit_".$row['Eid']."_".$i."' value='".Round($pay_unit)."' size='5' onChange=\"javascript:checkAmount('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."')\" readonly>單位/元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								echo "每月上限<input type='text' name='pay_limit_".$row['Eid']."_".$i."' id='pay_limit_".$row['Eid']."_".$i."' value='".Round($pay_limit)."' size='5' onChange=\"javascript:checkAmount('paytype_".$row['Eid']."_".$i."','pay_unit_".$row['Eid']."_".$i."','pay_limit_".$row['Eid']."_".$i."','totalamount_".$row['Eid']."_".$i."')\" readonly> 單位";
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;總額：<input type='text' name='totalamount_".$row['Eid']."_".$i."' id='totalamount_".$row['Eid']."_".$i."' value='".Round($pay_total)."' size='5' readonly>";
								echo "&nbsp;&nbsp;&nbsp;&nbsp;備註:<input type='text' name='comment_".$row['Eid']."_".$i."' size='30'>";
								echo "</td>";
								echo "</tr>";
							}
						}
	?>
					</table>
				</fieldset>
				<input type="button" value="上一步" onClick="back();">
				<input type="button" value="建立異動單" onClick="next();">
	<?		}else{echo "查無可異動資料";}
		}
	?>
	</form>
</body>
</html>
<script language="javascript">
function checkAmount(paytype_id,new_unit_id,new_limit_id,new_totalamount_id){
	var type=document.getElementById(paytype_id).value;
	var new_totalamount=document.getElementById(new_totalamount_id).value;
	var new_unit=document.getElementById(new_unit_id).value;
	var new_limit=document.getElementById(new_limit_id).value;
	
	if(isNaN(new_unit) || isNaN(new_limit)){
		alert("金額和單位只能輸入數字!!");
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_limit_id).value=0;
		document.getElementById(new_totalamount_id).value=0;
		return false;
	}
	if(type=="hr_pay" && parseInt(new_unit)<115){
		alert("時薪最低不得低於115!!");
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_totalamount_id).value=0;
	}
	document.getElementById(new_totalamount_id).value=Math.round(new_unit*new_limit);
}
function ChangeTransType(transtype_id,new_unit_id,new_limit_id){
	var transtype=document.getElementById(transtype_id).value;
	if(transtype=="2"){
		document.getElementById(new_unit_id).readOnly =false;
		document.getElementById(new_limit_id).readOnly =false;
	}else{
		document.getElementById(new_unit_id).readOnly =true;
		document.getElementById(new_limit_id).readOnly =true;
	}
}
function ChangePayType(paytype_id,new_unit_id,new_limit_id,new_totalamount_id){
	var type=document.getElementById(paytype_id).value;
	var new_totalamount=document.getElementById(new_totalamount_id).value;
	var new_unit=document.getElementById(new_unit_id).value;
	var new_limit=document.getElementById(new_limit_id).value;
	
	if(type=="hr_pay" && parseInt(new_unit)<115){
		alert("時薪最低不得低於115!!");
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_totalamount_id).value=0;
	}else if(type=="award_pay"){
		document.getElementById(new_unit_id).value=2000;
		document.getElementById(new_unit_id).readOnly =true;
		
		document.getElementById(new_limit_id).value=0;
		document.getElementById(new_limit_id).readOnly =false;
		
		document.getElementById(new_totalamount_id).value=0;
	}else if(type=="month_pay"){
		document.getElementById(new_limit_id).value=1;
		document.getElementById(new_limit_id).readOnly =true;
		
		document.getElementById(new_unit_id).value=0;
		document.getElementById(new_unit_id).readOnly =false;
		
		document.getElementById(new_totalamount_id).value=0;
	}
}
function next(){
	document.addPT.act.value="newTransform";
	document.addPT.action="new_PTtransform_store.php";
	document.addPT.submit();
}
function back(){
	document.addPT.action="new_PTtransform_select.php";
	document.addPT.submit();
}
</script>
	
	
	