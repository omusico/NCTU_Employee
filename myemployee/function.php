<?php
include_once("connectSQL.php");
//學生身份列表
$strSQL="select * from StuTitle";
$result=$db->query($strSQL);
while($row=$result->fetch()){
	$stu_code[$row['TitleCode']]=$row['TitleCode'];
	$stu_title[$row['TitleCode']]=$row['TitleName'];		
}
//校外人士學歷列表
$strSQL="select * from OuterTitle";
$result=$db->query($strSQL);
while($row=$result->fetch()){
	$outer_code[$row['TitleCode']]=$row['TitleCode'];
	$outer_title[$row['TitleCode']]=$row['TitleName'];		
}
//兼任職稱列表
$strSQL="select * from title";
$result=$db->query($strSQL);
while($row=$result->fetch()){
	$PT_code[$row['TitleCode']]=$row['TitleCode'];
	$PT_title[$row['TitleCode']]=$row['TitleName'];		
}
//支領項目列表
$strSQL="select distinct SerialNo,Jobitem_1 from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1'";
$result=$db->query($strSQL);
while($row=$result->fetch()){
	$Jobitem_Code[$row['SerialNo']]=$row['SerialNo'];
	$Jobitem[$row['SerialNo']]=$row['Jobitem_1'];
}
// 去掉奇怪的字元
function filterEvil($str) {
	$str = trim($str);
	$str = preg_replace('/"|\'/', '', $str);
	$str = preg_replace('/[\/]/', '', $str);	
	$str = preg_replace('/[\\\]/', '', $str);	
	return $str;
}
function HTMLalert($msg){
	echo "<script language='javascript'>";
	echo "alert('".$msg."')";
	echo "</script>";
}
function get_select_option($type){
	$str="";
	if($type=="y"){
		$start_year=date("Y")-1911-1; 
		$end_year=date("Y")-1911+5;
		for($i=$start_year;$i<=$end_year;$i++){$str.="<option value='".$i."'>".$i."</option>";}
	}else if($type=="m"){
		for($i=1;$i<=12;$i++){$str.="<option value='".$i."'>".$i."</option>";}
	}else if($type=="d"){
		for($i=1;$i<=31;$i++){$str.="<option value='".$i."'>".$i."</option>";}
	}
	return $str;
}
function ToUTF8Str($str){
	return iconv("big5","utf-8",$str);
}
function addLeadingZeros($str,$index){
	$leading="00000000";
	$str=$leading.$str;
	return substr($str,strlen($str)-$index,$index);
}
function getBugInfo($bugno){
	require("connectSQL.php");
	$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
			  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
			  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
			  "where v.bugetno='".$bugno."'";
	$result=$db->query($strSQL) or die("Err".$strSQL);
	$row=$result->fetchAll();
	$num=count($row);
	if($num>0){
		$result=$db->query($strSQL) or die("Err".$strSQL);
		$row=$result->fetch();		
	}else{
		$row="notfound";
	}
	return $row;
}
//取得該EID目前可以異動的時段,連續部分串起來,回傳一個array,array['num']=時段個數,array[i][1]=時段起日,array[i][2]=時段迄日
function getCanTransformPeriod($eid){
	require("connectSQL.php");
	$returnData = array();
	$returnData['num']=0;
	$index=1;
	//抓出payinfo資訊,只有paystatus='1'的才是可以異動的時段
	$strSQL="select * from PT_PayInfo ".
			"where Eid='".$eid."' and PayStatus='1' ".
			"order by PayYear,PayMonth,StartDay";
	$result=$db->query($strSQL) or die($strSQL);
	$row=$result->fetchAll();
	$num=count($row);
	$tempStart="";
	$tempEnd="";
	if($num>0){		
		$result=$db->query($strSQL);
		while($row=$result->fetch()){
			$StartDate=(int)($row['PayYear'].addLeadingZeros($row['PayMonth'],2).addLeadingZeros($row['StartDay'],2));
			$StartDateStr=($row['PayYear']+1911)."-".addLeadingZeros($row['PayMonth'],2)."-".addLeadingZeros($row['StartDay'],2);
			
			$EndDate=(int)($row['PayYear'].addLeadingZeros($row['PayMonth'],2).addLeadingZeros($row['EndDay'],2));
			$EndDateStr=($row['PayYear']+1911)."-".addLeadingZeros($row['PayMonth'],2)."-".addLeadingZeros($row['EndDay'],2);
			
			if($tempStart==""){
				$returnData[$index][1]=$StartDate;
				$returnData[$index][2]=$EndDate;
				
				$tempStart=date('Y-m-d',strtotime("$StartDateStr"));
				$tempEnd=date('Y-m-d',strtotime("$EndDateStr"));				
				
				$returnData[$index][3]=$tempStart;
				$returnData[$index][4]=$tempEnd;
				$returnData[$index][5]=date('Ymd',strtotime("$StartDateStr"));
				$returnData[$index][6]=date('Ymd',strtotime("$EndDateStr"));	
			}else{
				if($tempEnd==date('Y-m-d',strtotime("$StartDateStr -1 day"))){//本筆記錄的startdate是否上筆enddate的下一天
					$returnData[$index][2]=$EndDate;				
					$tempEnd=date('Y-m-d',strtotime("$EndDateStr"));				
					$returnData[$index][4]=$tempEnd;
					$returnData[$index][6]=date('Ymd',strtotime("$EndDateStr"));
				}else{
					$index++;
					$returnData[$index][1]=$StartDate;
					$returnData[$index][2]=$EndDate;
					
					$tempStart=date('Y-m-d',strtotime("$StartDateStr"));
					$tempEnd=date('Y-m-d',strtotime("$EndDateStr"));

					$returnData[$index][3]=$tempStart;
					$returnData[$index][4]=$tempEnd;
					$returnData[$index][5]=date('Ymd',strtotime("$StartDateStr"));
					$returnData[$index][6]=date('Ymd',strtotime("$EndDateStr"));
				}
			}
		}
		$returnData['num']=$index;
	}	
	return $returnData;
}
//取得該EID在起訖日內,是否已申請工作費超過請核金額,
//array[0]=已入帳筆數,供判斷是否可以刪除
//array[1]=回傳訊息,OK:工作費申請未超過,有內容的字串,為有超過的年月訊息
function getAppliedFee($bugno,$start,$end,$PNo,$IdNo,$PayTotal,$FromEid){
	require("connectSQL.php");
	$returnData = array();
	$returnData[0]=0;
	$returnData[1]="ok";
	$strSQL="select * from PT_Employed where Eid='".$FromEid."'";
	$result=$db->query($strSQL);
	$row=$result->fetch();
	$FirstEid=trim($row['FirstEid']);
	if($FirstEid==""){$FirstEid=$FromEid;}
	
	$start_y=(substr($start,0,strlen($start)-4));
	$start_m=substr($start,strlen($start)-4,2);
	$start_d=substr($start,strlen($start)-2,2);
	$end_y=(substr($end,0,strlen($end)-4));
	$end_m=substr($end,strlen($end)-4,2);
	$end_d=substr($end,strlen($end)-2,2);
	
	$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
	//$returnData[1]=$strSQL."<br>";
	foreach($yymm as $temp){
		$accounted_fee=0;//已入帳申請金額
		$applied_fee=0;//未入帳申請金額
		$back_fee=0;//繳回金額
		$strSQL="select * from [Salary_ProjectPay] ".
				"where PTNewFirstEID='".$FirstEid."' and jobstatus in ('0','4') and salaryYYMM='".($temp[0]+1911).$temp[1]."' ".
				"union ".
				"select s.* from [Salary_ProjectPay] s,oldApplyData_Transfer o ".
				"where o.old_Eid=s.PTeid and o.new_Eid='".$FromEid."' and s.jobstatus in ('0','4') ".
				"and s.salaryYYMM='".($temp[0]+1911).$temp[1]."'";
		//$returnData[1].=$strSQL."<br>";
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){
			$result=$db->query($strSQL);
			while($row=$result->fetch()){
				if(trim($row['JobStatus'])=="4"){$accounted_fee+=trim($row['SalaryAmount']);}
				else{$applied_fee+=trim($row['SalaryAmount']);}
				$returnData[0]++;
			}
			$total=$accounted_fee+$applied_fee+$back_fee;
			if($total>=$PayTotal){
				$returnData[1]=($temp[0]+1911).$temp[1]." 已申請工作費,已入帳:".$accounted_fee.",未入帳:".$applied_fee.",已繳回:".$back_fee;
				break;
			}
		}
	}
	return $returnData;
}
//確認人員在此時段中的身份,回傳全部抓得到的資料,傳入的時段值為西元
//注意,若換工號,暫時不能處理
function checkIdentity($IdCode,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d){
	require("connectSQL.php");
	$condition = array();
	$condition['EmpError']="";
	$condition['StuError']="";
	$condition['OutError']="";
	$condition['IdNo']="";
	$condition['name']="";
	$condition['Empno']="";
	$condition['identity']="";
	$condition['StdNo']="";
	$condition['IsOuter']="";
	$condition['UploadError']="";
	
	$start=$start_y."-".addLeadingZeros($start_m,2)."-".addLeadingZeros($start_d,2);
	$end=$end_y."-".addLeadingZeros($end_m,2)."-".addLeadingZeros($end_d,2);
	
	//判斷請核起日距今是否已超過30天
	$datetime1 = $start_y."-".addLeadingZeros($start_m,2)."-".addLeadingZeros($start_d,2);
	$datetime2 = date('Y')."-".date('m')."-".date('d');
	$diff = strtotime($datetime1) - strtotime($datetime2);
	if($diff<0){$abs_diff=abs($diff);}else{$abs_diff=0;}//有追溯的話,與本日相減應該<0,大於0就不用算
	$days = floor(($abs_diff)/ (60*60*24));
	$condition['dayDiff']=$days;
	
	//抓工號/學號、姓名、身分證號、身分  statue：1 教職員  2 學生  3 校外人士或其他找不到資料之編號	
	$strSQL = "sp_checkIdno '".$IdCode."'";
	$result=$db->query($strSQL);
	$row=$result->fetchAll();
	if(count($row)>0){
		$result=$db->query($strSQL);
		$row=$result->fetch();
		
		$empno=trim($row['EmpNo']);
		$IdNo=trim($row['IdNo']);
		$first_emp = getWordOfEmpno($empno);
		$cname=trim($row['Name']);
		$identity=trim($row['status']);
		
		$condition['IdNo']=$IdNo;
		$condition['name']=$cname;		
	}	
	
	//依人事代號/學號/身分證號尋找專任經歷檔聘期
	$strSQL = "[dbo].[sp_getEmpDate_history] '".$empno."'";
	$getEmpDate_history = $db->query($strSQL);
		
	//依人事代號/學號/身分證號尋找專任聘期
	$strSQL = "[dbo].[sp_getEmpDate]  '".$empno."'";
	$getEmpDate = $db->query($strSQL);		
	if($getEmpDate && $row_EmpDate=$getEmpDate->fetch()){
		//若有聘期，抓整段聘期起訖日(含經歷檔)
		if(is_null($row_EmpDate['Con_BeginDate'])){
			if(is_null($row_EmpDate['Con_EndDate'])){
				if($first_emp!="D" && $first_emp!="E" && $first_emp!="F" && $first_emp!="G" && $first_emp!="T"){					
					$condition['EmpError']="查無此人專任聘期!!";
				}else{
					$Con_BeginDate = substr(trim($row_EmpDate['Con_BeginDate']),0,10);
					$Con_EndDate = "1900-01-01";
				}
			}			
		}else{
			$Con_BeginDate = substr(trim($row_EmpDate['Con_BeginDate']),0,10);
			if(is_null($row_EmpDate['enddate']) || substr(trim($row_EmpDate['enddate']),0,10)=="1900-01-01"){
				if(is_null($row_EmpDate['Con_EndDate']) || substr(trim($row_EmpDate['Con_EndDate']),0,10)=="1900-01-01"){
					$Con_EndDate = "1900-01-01";
				}else{
					$Con_EndDate = substr(trim($row_EmpDate['Con_EndDate']),0,10);
				}
			}else{
				$Con_EndDate = date('Y-m-d',(strtotime($row_EmpDate['enddate']) - 60*60*24));
				
			}
			//抓經歷檔最早的起聘日
			if($getEmpDate_history && $row_EmpDate_history=$getEmpDate_history->fetch()){
				$Con_BeginDate = substr(trim($row_EmpDate_history['Con_BeginDate']),0,10);
			}
			if($Con_BeginDate>=$Con_EndDate && substr(trim($Con_EndDate),0,10)!="1900-01-01"){
				$condition['EmpError'].="此人專任聘期有誤!!".$Con_BeginDate;
			}
		}
	}else if($getEmpDate_history && $row_EmpDate_history=$getEmpDate_history->fetch()){
		//若現在無聘期但有經歷檔聘期，有可能是已離職，找經歷檔整段聘期
		$Con_BeginDate = substr(trim($row_EmpDate_history['Con_BeginDate']),0,10);
		$Con_EndDate = substr(trim($row_EmpDate_history['Con_EndDate']),0,10);
	}else{
		//若無專任聘期，且無經歷 -->非教職員
		$CheckDate = 0;
	}
	
	if($getEmpDate || $getEmpDate_history){
		//判斷兼任起訖日是否落在聘期中
		if (($start<$Con_BeginDate && $end<$Con_BeginDate) || ($Con_EndDate<$start && $Con_EndDate<$end && substr(trim($Con_EndDate),0,10)!="1900-01-01")){
			//若兼任起訖日都在專任以外-->非教職員身分
			$CheckDate = 0;
		}else if(($start<$Con_BeginDate && $Con_BeginDate<=$end) || ($start<=$Con_EndDate && $Con_EndDate<$end)){
			//若兼任起訖日部分期間在專任聘期內，部分期間在專任聘其外
			//-->應清空EndDate，提示使用者有身分轉換問題
			$CheckDate = 1;
		}else if( $Con_BeginDate<=$start && ($end<=$Con_EndDate || substr(trim($Con_EndDate),0,10)=="1900-01-01")){
			//若兼任起訖日完全在專任聘期內-->是教職員，將idno自動改為人事代號
			$CheckDate = 2;
			$condition['Empno']=$empno;
			$condition['identity']="E";			
		}
	}
	if($CheckDate==0){
		//若非教職員或兼任期間非教職員
		//計算請核起日的學年度及學期
		$temp=getTerm($start_y-1911,$start_m);
		$EmpYear=$temp['cyear'];
		$EmpTerm=$temp['cterm'];		
		
		//計算請核迄日的學年度及學期
		$temp=getTerm($end_y-1911,$end_m);
		$EndYear=$temp['cyear'];
		$EndTerm=$temp['cterm'];	
				
		//依身分證號尋找請核起日在學資料
		$strSQL = "[dbo].[sp_getStdDate] '".$IdNo."','".$EmpYear."','".$EmpTerm."'";
		$getStdDate = $db->query($strSQL);		
		if($getStdDate && $row_getStdDate=$getStdDate->fetch()){
			//若有找到在學資料
			$condition['name']=trim($row_getStdDate['std_cname']);
			$condition['IdNo']=trim($row_getStdDate['std_pid']);
			//return $row_getStdDate;
			if($row_getStdDate['counter']>1){
				//若有雙重學籍或類似狀況發生(同一學期有兩筆資料)
				if($IdCode == $IdNo){
					//若輸入的ID是身分證號，出現提示訊息，並清空ID欄位和聘期迄日					
					$condition['StuError']="此人於此兼任起訖日期間為學生，請重新輸入學號!!";
				}else{
					//若輸入的ID是學號，再以使用者輸入的ID去比對出符合的資料
					if($row_getStdDate['std_stdcode']!=$IdCode){
						while($row_getStdDate=$getStdDate->fetch()){
							if($row_getStdDate['std_stdcode']==$IdCode){break;}
						}
					}
				}
			}
			$std_code = $row_getStdDate['std_stdcode'];			
			
			//計算在學起日
			if(trim($row_getStdDate['std_enrollterm'])=="1"){
				//第一學期起日為8/1
				$Std_BeginDate = (string)((int)trim($row_getStdDate['std_enrollyear'])+1911)."-08-01";
			}else if(trim($row_getStdDate['std_enrollterm'])=="1"){
				//第二學期起日為2/1
				$Std_BeginDate = (string)((int)trim($row_getStdDate['std_enrollyear'])+1912)."-02-01";
			}
			
			//計算在學迄日
			if( trim($row_getStdDate['mgd_title'])=="畢業" && !is_null($row_getStdDate['std_leavedate'])){
				//*****103/7/7會議決議，若有離校日，在學迄日=離校日當天*****
				//若是畢業，且離校日有資料，在學迄日=離校日的月底
				$Std_EndDate = trim($row_getStdDate['std_leavedate']);
			}else if(trim($row_getStdDate['mgd_title'])=="畢業" && !is_null($row_getStdDate['std_gmonth'])){
				//*****103/6/30會議決議，若有畢業年月且無離校日，在學迄日=當學期末*****
				//若是畢業，且畢業年月有資料，在學迄日=畢業年月的月底
				//ex:若std_gmonth="10205"，5/31為迄日-->先抓5/1，加1個月再減1天
				if(trim($EmpTerm)=="1"){
					$Std_EndDate = (string)((int)$EmpYear+1912)."-01-31";
				}else if(trim($EmpTerm)=="2"){
					$Std_EndDate = (string)((int)$EmpYear+1912)."-07-31";
				}
			}else if(trim($row_getStdDate['mgd_title'])=="畢業" && is_null($row_getStdDate['std_leavedate']) && is_null($row_getStdDate['std_gmonth'])){
				//若是畢業，但無離校日或畢業年月，視為在學中
				$Std_EndDate = "1900-01-01";
			}else if(trim($row_getStdDate['mgd_title'])=="休學"){
				//若是休學
				if(trim($row_getStdDate['app_type'])=="休學" && !is_null($row_getStdDate['app_date'])){
					//*****103/5/19會議決議，休學日當天即為在學迄日，不再計算到月底*****
					//有休學日，在學迄日=休學日的月底
					//ex:若102/3/26休學，3/31為迄日-->先抓102/3/1，加1個月再減1天
					//*****103/7/3會議決議，休學日前一日為在學迄日*****
					$Std_EndDate = date('Y-m-d',(strtotime($row_getStdDate['app_date']) - 60*60*24));
				}else if(is_null(trim($row_getStdDate['app_type'])) || is_null($row_getStdDate['app_date'])){
					//無休學日，在學迄日=請核起日的學期初
					if(trim($EmpTerm)=="1"){
						$Std_EndDate = (string)((int)$EmpYear+1911)."-07-31";
					}else if(trim($EmpTerm)=="2"){
						$Std_EndDate = (string)((int)$EmpYear+1912)."-01-31";
					}
				}
			}else if(trim($row_getStdDate['mgd_title'])=="期中退學" || trim($row_getStdDate['mgd_title'])=="期末退學"){
				//若是期中退學或是期末退學
				if(trim($row_getStdDate['app_type'])=="退學" && !is_null($row_getStdDate['app_date'])){
					//*****103/5/19會議決議，退學日當天即為在學迄日，不再計算到月底*****
					//有退學日，在學迄日=退學日的月底
					//ex:若102/3/26退學，3/31為迄日-->先抓102/3/1，加1個月再減1天
					$Std_EndDate = trim($row_getStdDate['app_date']);
				}else if(is_null(trim($row_getStdDate['app_type'])) || is_null($row_getStdDate['app_date'])){
					//無退學日，在學迄日=請核起日的學期初
					if(trim($EmpTerm)=="1"){
						$Std_EndDate = (string)((int)$EmpYear+1911)."-08-01";
					}else if(trim($EmpTerm)=="2"){
						$Std_EndDate = (string)((int)$EmpYear+1912)."-02-01";
					}
				}
			}else if(trim($row_getStdDate['mgd_title'])=="在學" || trim($row_getStdDate['mgd_title'])=="應畢"){
				//若仍然在學中，或應屆畢業
				$Std_EndDate = "1900-01-01";
			}else{
				$condition['StuError']="此人非在學狀態，但是無離校日期，無法確認在學期間!!";
			}
			
			//若請核起日為在學中 且 請核起迄日所在學期不同 -->有可能請核迄日所在學期有離校，要再另外確認一次
			if(substr(trim($Std_EndDate),0,10) == "1900-01-01" && (trim($EmpYear)!=trim($EndYear) || trim($EmpTerm)!=trim($EndTerm))){
				//依身分證號尋找請核迄日在學資料
				$strSQL = "[dbo].[sp_getStdDate] '".$IdNo."','".$EndYear."','".$EndTerm."'";
				$getStdDateEnd = $db->query($strSQL);
				if($getStdDateEnd && $row_getStdDateEnd=$getStdDateEnd->fetch()){
					if($row_getStdDateEnd['counter']>0){						
						//若有雙重學籍或類似狀況發生(同一學期有兩筆資料)
						if($IdCode == $IdNo){
							//若輸入的ID是身分證號，出現提示訊息，並清空ID欄位和聘期迄日
							$condition['StuError']="此人於此兼任起訖日期間為學生，請重新輸入學號!!";
						}else{
							//若輸入的ID是學號，再以使用者輸入的ID去比對出符合的資料
							if($row_getStdDateEnd['std_stdcode']!=$IdCode){
								while($row_getStdDateEnd=$getStdDateEnd->fetch()){
									if($row_getStdDateEnd['std_stdcode']==$IdCode){break;}
								}
							}
						}
					}
					
					//再次計算迄日
					if(trim($row_getStdDateEnd['mgd_title'])=="畢業" && !is_null($row_getStdDateEnd['std_leavedate'])){
						//*****103/7/7會議決議，若有離校日，在學迄日=離校日當天*****
						//若是畢業，且離校日有資料，在學迄日=離校日的月底
						$Std_EndDate = trim($row_getStdDateEnd['std_leavedate']);
					}else if(trim($row_getStdDateEnd['mgd_title'])=="畢業" && !is_null($row_getStdDateEnd['std_gmonth'])){
						//*****103/6/30會議決議，若有畢業年月且無離校日，在學迄日=當學期末*****
						//若是畢業，且畢業年月有資料，在學迄日=畢業年月的月底
						//ex:若std_gmonth="10205"，5/31為迄日-->先抓5/1，加1個月再減1天
						if(trim($EndTerm)=="1"){
							$Std_EndDate = (string)((int)$EndYear+1912)."-01-31";
						}else if(trim($EndTerm)=="2"){
							$Std_EndDate = (string)((int)$EndYear+1912)."-07-31";
						}
					}else if(trim($row_getStdDateEnd['mgd_title'])=="畢業" && is_null($row_getStdDateEnd['std_leavedate']) && is_null($row_getStdDateEnd['std_gmonth'])){
						//若是畢業，但無離校日或畢業年月，視為在學中
						$Std_EndDate = "1900-01-01";
					}else if(trim($row_getStdDateEnd['mgd_title'])=="休學" && trim($row_getStdDateEnd['app_type'])=="休學" && !is_null($row_getStdDateEnd['app_date'])){
						//若是休學
						if(trim($row_getStdDateEnd['app_type'])=="休學" && !is_null($row_getStdDateEnd['app_date'])){
							//*****103/5/19會議決議，休學日當天即為在學迄日，不再計算到月底*****
							//有休學日，在學迄日=休學日的月底
							//ex:若102/3/26休學，3/31為迄日-->先抓102/3/1，加1個月再減1天
							//*****103/7/3會議決議，休學日前一日為在學迄日*****
							$Std_EndDate = date('Y-m-d',(strtotime($row_getStdDateEnd['app_date']) - 60*60*24));
						}else if(is_null(trim($row_getStdDateEnd['app_type'])) || is_null($row_getStdDateEnd['app_date'])){
							//無休學日，在學迄日=請核迄日的上學期末
							if(trim($EndTerm)=="1"){
								$Std_EndDate = (string)((int)$EndYear+1911)."-07-31";
							}else if(trim($EndTerm)=="2"){
								$Std_EndDate = (string)((int)$EndYear+1912)."-01-31";
							}
						}
					}else if(trim($row_getStdDateEnd['mgd_title'])=="期中退學" || trim($row_getStdDateEnd['mgd_title'])=="期末退學"){
						//若是期中退學或是期末退學
						if(trim($row_getStdDateEnd['app_type'])=="退學" && !is_null($row_getStdDateEnd['app_date'])){
							//*****103/5/19會議決議，退學日當天即為在學迄日，不再計算到月底*****
							//有退學日，在學迄日=退學日的月底
							//ex:若102/3/26退學，3/31為迄日-->先抓102/3/1，加1個月再減1天
							$Std_EndDate = trim($row_getStdDateEnd['app_date']);
						}else if(is_null(trim($row_getStdDateEnd['app_type'])) || is_null($row_getStdDateEnd['app_date'])){
							//無退學日，在學迄日=請核迄日的學期初
							if(trim($EndTerm)=="1"){
								$Std_EndDate = (string)((int)$EndYear+1911)."-08-01";
							}else if(trim($EndTerm)=="2"){
								$Std_EndDate = (string)((int)$EndYear+1912)."-02-01";
							}
						}
					}else if(trim($row_getStdDateEnd['mgd_title'])=="在學" || trim($row_getStdDateEnd['mgd_title'])=="應畢"){
						//若仍然在學中，或應屆畢業
						$Std_EndDate = "1900-01-01";
					}else{
						$condition['StuError']="此人非在學狀態，但是無離校日期，無法確認在學期間!!";
					}
				}else{
					//若請核起日有學生資料且在學，但請核迄日無學生資料，
					//依然算在學(有可能請核到以後的學期，當然不會有資料)
					$Std_EndDate = "1900-01-01";
				}
			}
			
			//判斷兼任起訖日是否落在在校期間中
			if(($start<$Std_BeginDate && $end<$Std_BeginDate) || ($Std_EndDate<$start && $Std_EndDate<$end && $Std_EndDate!="1900-01-01")){
				//若兼任起訖日在在校期間外-->非學生身分
				$CheckDate = 0;
			}else if(($start<$Std_BeginDate && $Std_BeginDate<=$end) || ($start<=$Std_EndDate && $Std_EndDate<$end)){
				//若兼任起訖日部分期間在在校期間內，部分期間在在校期間外
				//-->應清空EndDate，提示使用者有身分轉換問題
				$CheckDate = 1;
			}else if($Std_BeginDate<=$start && ($end<=$Std_EndDate || $Std_EndDate=="1900-01-01")){
				//若兼任起訖日完全在在校期間內-->是學生，將idno改為學號
				$CheckDate = 3;
				$condition['StdNo']=$std_code;
				$condition['identity']="S";
			}
		}else{
			//若無在學資料-->可能是新生尚未入學(ex:103/8/1入學、欲請核103年7月)
			//*****103/7/28會議決議，若有報到日，報到後一律視為學生身分*****
			$strSQL = "select * from studentdata where std_pid='".$IdNo."' order by std_enrollyear desc,std_enrollterm desc";
			$StdData = $db->query($strSQL);
			if($StdData && $row_StdData=$StdData->fetch()){
				$condition['name']=$row_StdData['std_cname'];
				if($row_StdData['學籍之在學狀況']=="在學"){					
					//抓入學日
					if($row_StdData['std_enrollterm']=="1"){
						$Std_BeginDate = (string)((int)trim($row_StdData['std_enrollyear'])+1911)."-08-01";
					}else if($row_StdData['std_enrollterm']=="2"){
						$Std_BeginDate = (string)((int)trim($row_StdData['std_enrollyear'])+1912)."-02-01";
					}
					
					//若有找到此人的學籍資料，以找到最新一筆的學年學期去找報到資料
					$strSQL = "[dbo].[sp_getCheckInDate] '".$IdNo."','".trim($row_StdData['std_enrollyear'])."','".trim($row_StdData['std_enrollterm'])."'";$getStdCheckIn = $db->query($strSQL);
					if($getStdCheckIn && $row_getStdCheckIn=$getStdCheckIn->fetch()){
						//若有找到報到資料，且報到日小於入學日，且報到日小於請核起日
						if(trim($row_getStdCheckIn['學號'])==trim($row_StdData['std_stdcode']) && $row_getStdCheckIn['LoginTime']<$Std_BeginDate && $row_getStdCheckIn['LoginTime']<=$start){
							//報到日小於請核起日
							$std_code = trim($row_getStdCheckIn['學號']);
							$CheckDate = 3;
							$condition['StdNo']=$std_code;
							$condition['identity']="S";
						}else{
							//報到日比入學日還晚，卻要請核比入學日還早的時間 或 尚未發給學號 或 報到與學籍學號不一致-->非學生
							$CheckDate = 0;
						}
					}else{						
						//沒有報到資料 或 報到系統尚未輸入身分證號-->非學生
						//可能是往後報,而且尚未產生學期資料,如103學年1學期時就要去報104/2/1-104/7/31,所以再確認往前一個學期有學期資料
						if($EmpTerm=="2"){
							$EmpYear2=$EmpYear;
							$EmpTerm2="1";
						}else{
							$EmpYear2=$EmpYear-1;
							$EmpTerm2="2";
						}
						$strSQL2 = "[dbo].[sp_getStdDate] '".$IdNo."','".$EmpYear2."','".$EmpTerm2."'";
						$getStdDate2 = $db->query($strSQL2);
						if($getStdDate2 && $row_getStdDate2=$getStdDate2->fetch()){
							if($row_getStdDate2['mgd_title']=="在學" || $row_getStdDate2['mgd_title']=="應畢"){
								$std_code = trim($row_getStdDate2['std_stdcode']);
								$CheckDate = 3;
								$condition['StdNo']=$std_code;
								$condition['identity']="S";
							}else{
								$CheckDate = 0;
							}
						}else{
							$CheckDate = 0;
						}
					}
				}else{
					//有學籍資料但非在學狀態
					$CheckDate = 0;
				}
			}else{
				//若無學籍資料-->非學生
				$CheckDate = 0;
			}
		}
	}
	if($CheckDate==0){
		//兼任起訖日皆為校外人士
		
		//若使用者輸入的ID不是身分證號
		//$condition['OutError']="此人於此兼任起訖日期間為校外人士!!";
		//校內都無資料,到outstatus找
		$strSQL="select *,[Name] as outname from [OuterStatus] where IdNo='".$IdCode."'";
		$outresult=$db->query($strSQL);
		$outrow=$outresult->fetchAll();
		if(count($outrow)>0){
			$outresult=$db->query($strSQL);
			$outrow=$outresult->fetch();
			//$condition['OutError'].=$strSQL;
			$condition['name']=trim($outrow['outname']);
			$condition['IdNo']=trim($outrow['IdNo']);
			$IdNo=trim($outrow['IdNo']);
			$OuterType=trim($outrow['Education']);
		}else{
			$condition['OutError'].="查無此人身分證號或居留證號!!";
		}
		//為校外人士，設定IsOutSide變數為1
		$condition['IsOuter']="1";
		$condition['identity']="O";
		
	}else if($CheckDate==1){
		$condition['EmpError']="此人於此兼任起訖日期間，有做身分的轉換，請依校外人士、教職員、學生等不同身分分別輸入!!";
	}else if($CheckDate==2){
		if($IdCode!=$empno){
			$condition['EmpError']="此人於此兼任起訖日期間為教職員!!";
		}
		//非校外人士，設定IsOutSide變數為0
		$condition['IsOuter']="0";
	}else if($CheckDate==3){
		if(trim($IdCode)!=$std_code){
			$condition['StuError']="此人於此兼任起訖日期間為學生!!";
		}
		//非校外人士，設定IsOutSide變數為0
		$condition['IsOuter']="0";
	}
	//人員證明文件需求
	/*校外人士
		外國人且非學生=>工作證+居留證
		外國人且是學生=>工作證+居留證+學生證

		本國人且非學生=>身份證
		本國人且是學生=>身份證+學生證

	本校人員
		外國人且非學生=>工作證+居留證
		外國人且是學生=>工作證
	*/
	//if(($condition['IsOuter']=="1" || checkARC($condition['IdNo'])) && $condition['identity']!="E"){
	if($condition['identity']=="O"){
		//確認身份證和居留證,uploadstatus可以為0:未審,1:已審,不可以為-1(刪除),-2(被退件),職員不用確認身份證/居留證
		$strSQL="select * from UploadData ".
				"where PEid in (select Eid from OuterStatus where IdNo='".$condition['IdNo']."') and [type]='1' and status in ('0','1')";
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)==0){
			$condition['UploadError']="校外人士或外籍人士,請至[請核人員建檔和上傳個人證明資料]".			
									  "功能建立個人檔案並上傳身份證明文件之後,再繼續請核。";
			//$condition['UploadError']=$strSQL;
		}
	}
	//確認工作許可
	//依20150330需求,要加入"外籍人士免工作許可"的條件,獨立至其他function 
	/*if(checkARC($condition['IdNo']) && $condition['identity']!="E"){//職員不用確認工作證明
		$documentExist=0;
		$strSQL="select u.Fid,".
				"datepart(year,w.ID_StartDate) as start_y,datepart(month,w.ID_StartDate) as start_m,datepart(day,w.ID_StartDate) as start_d,".
				"datepart(year,w.ID_EndDate) as end_y,datepart(month,w.ID_EndDate) as end_m,datepart(day,w.ID_EndDate) as end_d ".
				"from UploadData u ".
				"left join working_periods w on w.Fid=u.Fid ".
				"where u.PEid in (select Eid from OuterStatus where IdNo='".$condition['IdNo']."') and [type]='4' and u.[status] in ('0','1')";
		//$condition['UploadError'].=$strSQL;
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){
			$start=$start_y.addLeadingZeros($start_m,2).addLeadingZeros($start_d,2);
			$end=$end_y.addLeadingZeros($end_m,2).addLeadingZeros($end_d,2);
			$result=$db->query($strSQL);
			//查全部工作時期的起訖,要有一段全包含才行
			while($row=$result->fetch()){
				$work_start=trim($row['start_y']).addLeadingZeros(trim($row['start_m']),2).addLeadingZeros(trim($row['start_d']),2);
				$work_end=trim($row['end_y']).addLeadingZeros(trim($row['end_m']),2).addLeadingZeros(trim($row['end_d']),2);
				if((int)$start>=(int)$work_start && (int)$end<=(int)$work_end){$documentExist=1;}
			}
		}
		if($documentExist==0){
			$condition['UploadError'].="超出工作許可期間，請至[請核人員建檔和上傳個人證明資料]".			
									   "功能再新增一筆許可工作證及工作許可期間。";
			//$condition['UploadError'].=$strSQL.$start."/".$work_start."/".$end."/".$work_end;
		}		
	}*/
	return $condition;
}
function checkWorkingPeriod($IdNo,$identity,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d){
	require("connectSQL.php");
	$returnData = array();
	
	if($identity!="E"){//職員不用確認工作證明
		$documentExist=0;
		$strSQL="select u.Fid,".
				"datepart(year,w.ID_StartDate) as start_y,datepart(month,w.ID_StartDate) as start_m,datepart(day,w.ID_StartDate) as start_d,".
				"datepart(year,w.ID_EndDate) as end_y,datepart(month,w.ID_EndDate) as end_m,datepart(day,w.ID_EndDate) as end_d ".
				"from UploadData u ".
				"left join working_periods w on w.Fid=u.Fid ".
				"where u.PEid in (select Eid from OuterStatus where IdNo='".$IdNo."') and [type]='4' and u.[status] in ('0','1')";
		//$condition['UploadError'].=$strSQL;
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){
			$start=$start_y.addLeadingZeros($start_m,2).addLeadingZeros($start_d,2);
			$end=$end_y.addLeadingZeros($end_m,2).addLeadingZeros($end_d,2);
			$result=$db->query($strSQL);
			//查全部工作時期的起訖,要有一段全包含才行
			while($row=$result->fetch()){
				$work_start=trim($row['start_y']).addLeadingZeros(trim($row['start_m']),2).addLeadingZeros(trim($row['start_d']),2);
				$work_end=trim($row['end_y']).addLeadingZeros(trim($row['end_m']),2).addLeadingZeros(trim($row['end_d']),2);
				if((int)$start>=(int)$work_start && (int)$end<=(int)$work_end){$documentExist=1;}
			}
		}
		if($documentExist==0){
			$returnData['msg']="超出工作許可期間，請至[請核人員建檔和上傳個人證明資料]".			
							   "功能再新增一筆許可工作證及工作許可期間。";
			//$condition['UploadError'].=$strSQL.$start."/".$work_start."/".$end."/".$work_end;
		}else{$returnData['msg']="ok";}		
	}else{$returnData['msg']="ok";}
	return $returnData;
}
//取得申請年月的學年度學期別
function getTerm($year,$month){
	$year = intval($year);
	$month = intval($month);
	//目前學年度學期別
	$cyear = 0;
	$cterm = 0;
	
	if(in_array($month, array(2,3,4,5,6,7))){
		$cterm = 2;
		$cyear = $year-1;
	}else{
		$cterm = 1;
		$cyear = ($month == 1)? $year-1:$year;
	}
	return array('cyear' => $cyear, 'cterm'=>$cterm);
}
//將請核起訖年月日分成一個個月,方便計算支領資訊
//回傳一個N*5的array,N為起訖日共包含幾個月,array[0]=年,array[1]=月,array[2]=當月起日,array[3]=當月訖日,array[4]=當月有幾天,array[5]=當月是否破月
function countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d){
	$index=0;
	$start_y=intval($start_y);
	$start_m=intval($start_m);
	$start_d=intval($start_d);
	$end_y=intval($end_y);
	$end_m=intval($end_m);
	$end_d=intval($end_d);
	
	if($start_y==$end_y){$month_diff=$end_m-$start_m+1;}
	else{
		$year_diff=$end_y-$start_y-1;
		$month_diff=(12-$start_m+1)+(12*$year_diff)+$end_m;
	}
	
	$temp_y=$start_y;
	$temp_m=$start_m;
	while($index<$month_diff){
		$yymm[$index][0]=$temp_y;//年
		$yymm[$index][1]=$temp_m;//月
		if($temp_y==$start_y && $temp_m==$start_m){
			$mtime=($temp_y+1911)."-".$temp_m."-".$start_d;
			$yymm[$index][2]=$start_d;//當月起日
			//需考慮起訖在同一個月的狀況\
			if($temp_m==$end_m && $temp_y==$end_y){
				$yymm[$index][3]=$end_d;
			}else{
				$yymm[$index][3]=substr(getthemonth($mtime),8,2);//當月訖日
			}
			//確認是否破月
			if($start_d==1 && (($start_y!=$end_y && $start_m!=$end_m) || ($start_y==$end_y && $start_m==$end_m && $end_d==substr(getthemonth($mtime),8,2)))){//起日為1,
				$yymm[$index][5]=0;
			}else{$yymm[$index][5]=1;}
		}else if($temp_y==$end_y && $temp_m==$end_m){
			$mtime=($temp_y+1911)."-".$temp_m."-".$end_d;
			$yymm[$index][2]=1;
			$yymm[$index][3]=$end_d;
			//$yymm[$index][3]=getthemonth($mtime);//當月訖日
			if($end_d==substr(getthemonth($mtime),8,2))//表示$end_d為當月最後一日
			{$yymm[$index][5]=0;}
			else{$yymm[$index][5]=1;}
		}else{
			$mtime=($temp_y+1911)."-".$temp_m."-15";
			$yymm[$index][2]=1;
			$yymm[$index][3]=substr(getthemonth($mtime),8,2);//當月訖日
			//$yymm[$index][3]=getthemonth($mtime);//當月訖日
			$yymm[$index][5]=0;
		}		
		$yymm[$index][4]=substr(getthemonth($mtime),8,2);//當月有幾天
		//echo $temp_y.$temp_m."<br>";
		$temp_m=$temp_m+1;
		if($temp_m>12){$temp_m=1;$temp_y=$temp_y+1;}
		$index++;
	}
	return $yymm;
}
//取得指定日期的最後一天
function getthemonth($date)
{
    return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day')); 
} 
//規則表判斷中,分教育部(BI),頂尖(E,W),科技部(NRY),其他(ACFGHIPQT),另外有頂尖和民間,再另外判斷
//計畫編號 I 類之教育部計畫範圍：102年度編碼區間為601~649，103年度以後，一律固定編碼區間為101~150。
function checkBugetTypeForRules($bugno){
	$pattern1="[0-9]{2,3}[Bb][0-9]{3,5}";//教育部
	$pattern2="[0-9]{2,3}[NnRrYy][0-9]{3,5}";//科技部
	$pattern3="[0-9]{2,3}[Ii][0-9]{3,5}";//I 類說明如上
	$pattern4="[0-9]{2,3}[EeWw][0-9]{3,5}";//I 類說明如上
	if(eregi($pattern1,$bugno)){return "教育部";}
	if(eregi($pattern2,$bugno)){return "科技部";}
	if(eregi($pattern4,$bugno)){return "頂尖";}
	if(eregi($pattern3,$bugno)){
		$year=intval(substr($bugno,0,strpos($bugno,"I")));
		$number=intval(substr($bugno,strpos($bugno,"I")+1,3));//因為有些計畫有-,如99I552-1,所以只取I後面3碼
		if($year>=103){
			if($number>=101 && $number<=150){return "教育部";}
			else{return "其他";}
		}else{
			if($number>=601 && $number<=649){return "教育部";}
			else{return "其他";}
		}
	}
	return "其他";
}
//在校身份和兼任身份對應中,分4類,教育部(B,I),頂尖(E,W),科技部(N,R,Y),其他(A,C,F,G,H,I,P,Q,T),需和規則限制的計畫分類有所區別(不需分民間)
//計畫編號 I 類之教育部計畫範圍：102年度編碼區間為601~649，103年度以後，一律固定編碼區間為101~150。
function checkBugetTypeForPTtitle($bugno){
	$pattern1="[0-9]{2,3}[Bb][0-9]{3,5}";//教育部
	$pattern2="[0-9]{2,3}[NnRrYy][0-9]{3,5}";//科技部
	$pattern3="[0-9]{2,3}[Ii][0-9]{3,5}";//I 類說明如上
	$pattern4="[0-9]{2,3}[EeWw][0-9]{3,5}";//頂尖大學
	if(eregi($pattern1,$bugno)){return "教育部";}
	if(eregi($pattern2,$bugno)){return "科技部";}
	if(eregi($pattern3,$bugno)){
		$year=intval(substr($bugno,0,strpos($bugno,"I")));
		$number=intval(substr($bugno,strpos($bugno,"I")+1,3));//因為有些計畫有-,如99I552-1,所以只取I後面3碼
		if($year>=103){
			if($number>=101 && $number<=150){return "教育部";}
			else{return "其他";}
		}else{
			if($number>=601 && $number<=649){return "教育部";}
			else{return "其他";}
		}
	}
	if(eregi($pattern4,$bugno)){return "頂尖";}
	return "其他";
}
//另外確認計畫是否頂尖大學計畫,因為規則表中頂尖算在教育部,但又有獨立規則
function checkBugetTypeIFTopUniv($bugno){
	$pattern="[0-9]{2,3}[EeWw][0-9]{3,5}";//頂尖大學
	if(eregi($pattern,$bugno)){return "頂尖";}
	return "其他";
}
//另外確認計畫是否民間計畫,因為規則表中算在其他,但又有獨立規則
function checkBugetTypeIFCivil($bugno){
	$pattern="[0-9]{2,3}[Cc][0-9]{3,5}";//其他計畫
	if(eregi($pattern,$bugno)){return "民間";}
	return "其他";
}
//分割工號的字母和數字,以便確認可選擇的兼任身份
function getWordOfEmpno($empno){
	$reg = '/^[a-zA-Z]$/';                    //正規式
    $proArr = str_split( $empno );           // 字母的部分切成幾組
    $proLen = count( $proArr );               // 計算有幾個組
    $wordStr = $numStr = '';                  // 字母和數字分別放在不同的變數
    
    for( $iCount = 0; $iCount < $proLen; $iCount++ )
    {
        if( preg_match( $reg, $proArr[$iCount], $match ) )    //滿足正規式表示是字母
        {
             $wordStr .= $proArr[$iCount];                    //放字母
        }
        else                                 
        {
             $numStr .= $proArr[$iCount];    //放數字
        }
    }
    //$sorDataArr = array( 'word' => $wordStr, 'num' => $numStr );
	return $wordStr;
}
function checkIdno($id){ //檢查身分證號
	$id = strtoupper($id);
	//建立字母分數陣列
	$headPoint = array(
		'A'=>1,'I'=>39,'O'=>48,'B'=>10,'C'=>19,'D'=>28,
		'E'=>37,'F'=>46,'G'=>55,'H'=>64,'J'=>73,'K'=>82,
		'L'=>2,'M'=>11,'N'=>20,'P'=>29,'Q'=>38,'R'=>47,
		'S'=>56,'T'=>65,'U'=>74,'V'=>83,'W'=>21,'X'=>3,
		'Y'=>12,'Z'=>30
	);
	//建立加權基數陣列
	$multiply = array(8,7,6,5,4,3,2,1);
	//檢查身份字格式是否正確
	if (ereg("^[a-zA-Z][1-2][0-9]+$",$id) AND strlen($id) == 10){
		//切開字串
		$len = strlen($id);
		for($i=0; $i<$len; $i++){
			$stringArray[$i] = substr($id,$i,1);
		}
		//取得字母分數
		$total = $headPoint[array_shift($stringArray)];
		//取得比對碼
		$point = array_pop($stringArray);
		//取得數字分數
		$len = count($stringArray);
		for($j=0; $j<$len; $j++){
			$total += $stringArray[$j]*$multiply[$j];
		}
		//計算餘數碼並比對
		$last = (($total%10) == 0 )?0:(10-($total%10));
		if ($last != $point) {
			return false;
		} else {
			return true;
		}
	}  else {
	   return false;
	}
}
function checkARC($id){//居留證號檢查
	$id=trim($id);
	//檢查居留證號格式是否正確 前兩位字母與後8碼數字
	if (ereg("^[a-zA-Z][a-dA-D][0-9]{8}$",$id)){
		//轉換英文字母
		$head="ABCDEFGHJKLMNPQRSTUVXYWZIO";		
		$id = (strrpos($head, substr($id,0,1))+10)
			 .(strrpos($head, substr($id,1,1))%10)
			 .substr($id,2,8);
	
		$checksum =(int)(substr($id,0,1)) + 
		(int)(substr($id,1,1)) * 9 + 
		(int)(substr($id,2,1)) * 8 + 
		(int)(substr($id,3,1)) * 7 + 			
		(int)(substr($id,4,1)) * 6 + 
		(int)(substr($id,5,1)) * 5 + 
		(int)(substr($id,6,1)) * 4 + 
		(int)(substr($id,7,1)) * 3 + 
		(int)(substr($id,8,1)) * 2 + 
		(int)(substr($id,9,1)) + 
		(int)(substr($id,10,1));
		
		if (($checksum % 10) != 0)
			return false;
		else
			return true;
	} else {
	   return false;
	}
}
function convert($str){
	if($str===null || $str=='')
		return '';
	$str = mb_convert_encoding($str,"utf-8","big5");
	$str = trim($str);
	return $str;
}
?>