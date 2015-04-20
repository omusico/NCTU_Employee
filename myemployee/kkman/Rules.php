<?
	//同一計畫，同一時間內均不得再兼任支領，所有頂尖大學計畫(E、W)視為同一計畫；
	//但同一計畫同一時間內可擔任2個不同時薪之臨時工；同一計畫同一時間內可擔任2個（含）以上不同頂尖大學計畫(E、W)編號之臨時工
	/*規則:1.除頂尖計畫之外,其餘計畫,同一計畫,同一時間內,只能有一個兼任身份(不含臨時工)身份
		   2.除頂尖計畫之外,其餘計畫,同一計畫,同一時間內,可以擔任2個不同時薪之臨時工
	     3.頂尖計畫的兼任人員（不含臨時工），同時不得再任其他頂尖計畫兼任人員及臨時工
	     4.頂尖計畫的臨時工,可以同時擔任其他不同頂尖計畫的臨時工
	     上述一,三條中的兼任人員為全部兼任人員(不含臨時工)
			20141115改支領規則表和阿智回信為主
			金額加總以已通過表單為主 */
	function Rules_noduplicate($bugno,$PNo,$start,$end,$Ptitle,$PayTypeStr,$pay_unit){
		require("connectSQL.php");
		$duplicate_bug="";
		$num_PT04=0;//記錄同一計畫,同一時期,不同時薪,臨時工,數量(非頂尖)
		$now_bug_type=checkBugetTypeForRules($bugno);
		if($now_bug_type=="教育部"){//教育部的,要再確認是否為頂尖
			if(checkBugetTypeIFTopUniv($bugno)=="頂尖"){$now_bug_type="頂尖";}
		}
		$arr="";
		$strSQL="select p2.BugNo,p.PTtitle,p.PayType,p.PayPerUnit from PT_employed p ".
				"left join  PT_outline p2 on p2.SerialNo=p.SerialNo ".
				"where p.serialno in (select distinct serialno from PT_outline where FormStatus='1') ".
				"and ((p.BeginDate<='".$start."' and p.Enddate>='".$start."') ".
				"or (p.BeginDate<='".$end."' and p.Enddate>='".$end."') ".
				"or (p.BeginDate<='".$start."' and p.Enddate>='".$end."') ".
				"or (p.BeginDate>='".$start."' and p.Enddate<='".$end."')) ".
				"and (p.idcode='".$PNo."' or p.pid='".$PNo."') and p.RecordStatus='0'";//先抓到此人員本段時間,或有重疊到的全部請核
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){//有抓到資料表示有時段有cover到
			$temp_bugno=trim($row['BugNo']);
			$temp_bug_type=checkBugetTypeForRules($temp_bugno);
			if($temp_bug_type=="教育部"){//教育部的,要再確認是否為頂尖
				if(checkBugetTypeIFTopUniv($row['BugNo'])=="頂尖"){$temp_bug_type="頂尖";}
			}
			if($now_bug_type!="頂尖"){
				if($bugno==$temp_bugno){
					if($Ptitle!="4"){//非頂尖兼任人員不能多重兼任
						$duplicate_bug.="重複請核 ".$bugno." 兼任人員\n";
					}else{//非頂尖臨時工,要不同時薪才行
						if($PayTypeStr=="hr"){
							$num_PT04++;		
							if($pay_unit==$row['PayPerUnit']){$duplicate_bug.="重複請核 ".$bugno." 臨時工需為不同時薪\n";}
						}
					}
				}
			}else{
				if($Ptitle!="4" && $temp_bug_type=="頂尖"){
					$duplicate_bug.="重複請核 ".$bugno." 兼任人員或臨時工\n";
				}else if($Ptitle=="4" && $bugno==$temp_bugno){
					$duplicate_bug.="重複請核 ".$bugno." 臨時工\n";
				}else if($bugno==$temp_bugno){
					$duplicate_bug.="重複請核 ".$bugno." 兼任人員或臨時工\n";
				}
			}
			while($row=$result->fetch()){
				$temp_bugno=trim($row['BugNo']);
				$temp_bug_type=checkBugetTypeForRules($temp_bugno);
				if($temp_bug_type=="教育部"){//教育部的,要再確認是否為頂尖
					if(checkBugetTypeIFTopUniv($row['BugNo'])=="頂尖"){$temp_bug_type="頂尖";}
				}
				if($now_bug_type!="頂尖"){
					if($bugno==$temp_bugno){
						if($Ptitle!="4"){//非頂尖兼任人員不能多重兼任
							$duplicate_bug.="重複請核 ".$bugno." 兼任人員\n";
						}else{//非頂尖臨時工,要不同時薪才行
							if($PayTypeStr=="hr"){
								$num_PT04++;		
								if($pay_unit==$row['PayPerUnit']){$duplicate_bug.="重複請核 ".$bugno." 臨時工需為不同時薪\n";}
							}
						}
					}
				}else{
					if($Ptitle!="4" && $temp_bug_type=="頂尖"){
						$duplicate_bug.="重複請核 ".$bugno." 兼任人員或臨時工\n";
					}else if($Ptitle=="4" && $bugno==$temp_bugno){
						$duplicate_bug.="重複請核 ".$bugno." 臨時工\n";
					}else if($bugno==$temp_bugno){
						$duplicate_bug.="重複請核 ".$bugno." 兼任人員或臨時工\n";
					}
				}
			}
		}
		if($duplicate_bug!=""){
			if($num_PT04>2){$duplicate_bug.=$bugno." 不同時薪之臨時工最多只能2個\n";}
			$arr=$start."~".$end." 期間已存在相同計畫請核資料或同為頂尖大學計畫,請刪除舊請核,或縮短本次請核期間\n".$duplicate_bug;
			
		}else{
			$arr="ok";
			//$arr=$strSQL;			
		}		
		return $arr;
	}
	//註1：兼任教育部或其他機關計畫，只能兼任二項兼任助理或臨時工，
	//所支領兼任報酬以每月總額1萬元為限（專任人員薪資分攤若有涉及教育部計畫，應受此限制）	
	//金額加總以已通過表單為主
	function Rules_EduAndOthers_AmountLimit_PS01($PNo,$IdNo,$identity,$bugno,$start,$end,$totalamount){
		require("connectSQL.php");
		$arr="";
		$count_EduAndOthers=0;//用來計算教育部和其他計畫的加總
		$count_amount=0;//計算金額加總
		//先確認目前申請計畫的分類
		$bugtype=checkBugetTypeForRules($bugno);
		if($bugtype=="教育部" || $bugtype=="其他"){$count_EduAndOthers++;$count_amount=$totalamount;}
		//找已請核的記錄
		$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
				"where SerialNo in ".
				"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and PTtitle in ('3','4') and ".
				"RecordStatus='0' and BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."')) ".
				"and FormStatus='1'";
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){
			$bugtype=checkBugetTypeForRules($row['BugNo']);
			if($bugtype=="教育部" || $bugtype=="其他"){
				$count_EduAndOthers++;
				$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and PTtitle in ('3','4') and ".
				"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
				$result2=$db->query($strSQL2);
				while($row2=$result2->fetch()){
					$count_amount=$count_amount+$row2['TotalAmount'];
				}
			}
			while($row=$result->fetch()){
				$bugtype=checkBugetTypeForRules($row['BugNo']);
				if($bugtype=="教育部" || $bugtype=="其他"){
					$count_EduAndOthers++;
					$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and PTtitle in ('3','4') and ".
					"RecordStatus='0' and (BeginDate<='".$start."' and Enddate>='".$start."') ".
					"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
					"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
					"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
					$result2=$db->query($strSQL2);
					$row2=$result2->fetch();
					while($row2=$result2->fetch()){
						$count_amount=$count_amount+$row2['TotalAmount'];
					}
				}
			}
		}
		//若為職員,還要找其薪資分攤是否有涉及,但1萬元只算兼任金額
		if($identity=="E"){
			$strSQL="select distinct ExpenseSourceCode as bugno from [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource_buget] ".
					"where (empno='".$PNo."' or IdNo='".$IdNo."') and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
					"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
					"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
					"or (BeginDate>='".$start."' and Enddate<='".$end."'))";
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				$bugtype=checkBugetTypeForRules($row['bugno']);
				if($bugtype=="教育部" || $bugtype=="其他"){$count_EduAndOthers++;}
				while($row=$result->fetch()){
					$bugtype=checkBugetTypeForRules($row['bugno']);
					if($bugtype=="教育部" || $bugtype=="其他"){$count_EduAndOthers++;}
				}
			}
		}
		if($count_EduAndOthers>2){$arr.="兼任教育部或其他類計畫,最多只能兩項,或職員本身以此兩類計畫為薪資來源,最多只得再兼任一項\n";}
		if($count_amount>10000){$arr.="兼任教育部或其他類計畫,兼任報酬以每月總額1萬元為限";}
		if($arr==""){return "ok";}
		else{return $arr;}
		//return $count_EduAndOthers."-".$count_amount."-".checkBugetTypeForRules($bugno)."-".$totalamount."-".$strSQL2;
		//return $strSQL;
	}
	//註2：博士候選人合計不超過34000元；博士班合計不超過30000元；
	//碩士班合計不超過10000元；大學部合計不超過6000元（僅限科技部計畫的請核）
	//註10：民間委辦計畫(C,Q)不受限==>依表列,併至註2,3,9中判斷==>是科技部就不會是民間計畫
	//金額加總以已通過表單為主
	//完整條文為:科技部僅學生+技士/技佐或編制內助教可當助教級助理人員+編制內講師或具教師資格者可當講師級助理人員,其他人都不行
	//等於註二+註六,但註二和註六於OX表內不同時執行,應該註二可排除全部職員+校外人士
	function Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$start,$end,$totalamount){
		require("connectSQL.php");	
		$arr="";
		$BugTotalAmount=0;
		$bugtype=checkBugetTypeForRules($bugno);
		if($bugtype=="科技部"){$BugTotalAmount=$totalamount;}
		if($identity!="S"){$arr="除科技部計畫除特別規定外,只有學生可以擔任";}
		else{
			if($Prank=="0"){$Limit=34000;}
			else if($Prank=="1"){$Limit=30000;}
			else if($Prank=="2"){$Limit=10000;}
			else if($Prank=="3"){$Limit=6000;}
			$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
					"where SerialNo in ".
					"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
					"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
					"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
					"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
					"or (BeginDate>='".$start."' and Enddate<='".$end."'))) ".
					"and FormStatus='1'";
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				$bugtype=checkBugetTypeForRules($row['Bugno']);
				if($bugtype=="科技部"){
					$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
						"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
						"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
						"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
						"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
					$result2=$db->query($strSQL2);
					while($row2=$result2->fetch()){
						$BugTotalAmount+=$row2['TotalAmount'];
					}
				}
				while($row=$result->fetch()){
					$bugtype=checkBugetTypeForRules($row['Bugno']);
					if($bugtype=="科技部"){
						$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
							"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
							"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
							"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
							"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
						$result2=$db->query($strSQL2);
						while($row2=$result2->fetch()){
							$BugTotalAmount+=$row2['TotalAmount'];
						}
					}
				}
			}
			
			if($BugTotalAmount>$Limit){
				$arr="已請核科技部計畫金額總額(含此筆)超過科技部計畫上限".$Limit;
			}else{$arr="ok";}
		}
		return $arr;
	}
	/*註3：博士候選人單筆不超過34000元；博士班單筆不超過30000元；
	碩士班單筆不超過10000元；大學部單筆不超過6000元	
	註10：民間委辦計畫(C,Q)不受限==>依表列,併至註2,3,9中判斷
	金額加總以已通過表單為主*/
	function Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount){
		require("connectSQL.php");	
		$arr="";
		if($identity!="S"){return "ok";}
		else{
			if($Prank=="0"){$Limit=34000;}
			else if($Prank=="1"){$Limit=30000;}
			else if($Prank=="2"){$Limit=10000;}
			else if($Prank=="3"){$Limit=6000;}
			if($totalamount>$Limit && checkBugetTypeIFCivil($bugno)!="民間"){
				$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;
			}else{$arr="ok";}
		}
		return $arr;
	}
	//註4：3000元~5000元（警語），有另附計畫書者除外（上限比照註3規定）；
	//頂尖計畫(E、W)不需警語，上限比照註3規定==>註三規定指學生
	function Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount){
		require("connectSQL.php");	
		$arr="";		
		if($identity!="S"){$arr="ok";}
		else{
			if($Prank=="0"){$Limit=34000;}
			else if($Prank=="1"){$Limit=30000;}
			else if($Prank=="2"){$Limit=10000;}
			else if($Prank=="3"){$Limit=6000;}

			$bugType=checkBugetTypeIFTopUniv($bugno);
			if($bugType=="頂尖" && $totalamount>$Limit){
				$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;
			}else if($totalamount>=3000){
				if($bugType!="頂尖"){$arr="warning_請核金額3000以上,請注意";}
				else{$arr="ok";}
			}else{
				$arr="ok";
			}
		}
		return $arr;
	}
	//註5：約用人員不得超過其薪資之30%(兼任+臨時工+績效工作酬勞)；
	//公務人員不得超過其專業加給之60%(兼任+臨時工+績效工作酬勞)	
	//金額加總以已通過表單為主
	function Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$start,$end,$totalamount){
		require("connectSQL.php");	
		$arr="";
		if($identity=="E"){
			//抓人事代號開頭英文
			$str_TopEmp=getWordOfEmpno($PNo);
			$condition="";
			//確認是否為計畫主持人
			$strSQL="select count(a.bugetno) as 'count' from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] a ".
					"left join [PERSONDBOLD].[約用人員資料庫].[dbo].[DepartmentCode] b on a.leaderid=b.code ".
					"where bugetno='".$bugno."' and (a.leaderid='".$PNo."' or b.[主管人事代號]='".$PNo."')";
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				if($row['count']>0){$condition="計畫主持人";}
			}
			//確認是否為T類助教
			if($str_TopEmp=="T" && strpos($Prank, "助教")!==false){
				$condition="助教";
			}
			//取得薪資資料
			$strSQL = "select Empno,Idno,Passno,isnull(Salary,0) as Salary,isnull(Sal1,0) as Sal1,isnull(Sal2,0) as Sal2 ,isnull(Sal3,0) as Sal3 ".
					  ",Next_PointAmount as Next_Salary,PayContBDate ".
					  "from [SALARYDB].[工作費資料庫].[dbo].[vw_Personnel_Amount_WorkFee] where Empno='".$PNo."'";			
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				$SalaryEmp=$row['Salary'];
				$Next_SalaryEmp=$row['Next_Salary'];
				$PayContBDate=$row['PayContBDate'];
				$Sal1=$row['Sal1'];
				$Sal2=$row['Sal2'];
				$Sal3=$row['Sal3'];
			}else{
				$arr="查無".$PNo."薪資資料";
			}
			if($Next_SalaryEmp == "0" || $Next_SalaryEmp == ""){
				$Next_SalaryEmp = $SalaryEmp;
			}
			if($PayContBDate == ""){
				$PayContBDate = "1900-01-01";
			}
			//取得內控規則
			if($str_TopEmp!=""){
				$strSQL = "select code, salaryType, limit, countType, UncontrolledProkind, condition, limitRule ".
						  "from [SALARYDB].[工作費資料庫].dbo.[vw_WorkFeeLimit] where code='".trim($str_TopEmp)."' and condition='".$condition."'";
				$result=$db->query($strSQL);
				if($result && $row=$result->fetch()){
					$limit = $row['limit'];
					if($limit==""){$limit=0.3;}
					$limitRule = $row['limitRule'];
					$countType = $row['countType'];
					//計算支領上限金額
					if(substr($bugno,3)=="G201" && $limit>0.3){
						$TotalLimit = -1;	//依第135次會議決議，G201經費不控60%65%，但仍需控30%
						$Next_TotalLimit = -1;
					}else if(trim($row['salaryType'])=="學術加給"){
						$TotalLimit = $Sal3*$limit;
					}else if(trim($row['salaryType'])=="專業加給"){
						$TotalLimit = $Sal2*$limit;
					}else if (trim($row['salaryType'])=="薪資"){
						$TotalLimit = $SalaryEmp*$limit;
						$Next_TotalLimit = $Next_SalaryEmp*$limit;
					}else if (trim($row['salaryType'])=="不控"){
						$TotalLimit = -1;
						$Next_TotalLimit = -1;
					}else{
						$TotalLimit = $SalaryEmp*$limit;
					}
					if($Next_TotalLimit==""){
						$Next_TotalLimit = $TotalLimit;
					}
				}else{
					$arr.="\n查無".$PNo."控管規則";
				}				
			}else{
				$arr.="\n查無".$PNo."職員編號分類";				
			}
			//此支領項目是否控30%60%
			if($limitRule!=""){
				$strSQL = "SELECT DISTINCT SerialNo,TALimited,StaffLimited,OfficerLimited,profLimited60,profLimited65 ".
						  "from [SALARYDB].[工作費資料庫].dbo.[vw_PartTime_Rule] where SerialNo='".$payitem."'";				
				$result=$db->query($strSQL);
				if($result && $row=$result->fetch()){
					$CheckJobItem=$row[$limitRule];
					if($CheckJobItem=="1"){
						//取得目前累計金額	金額累計方式 countType	1 月計	2 案計
						$CheckYear=substr($start,0,strlen($start)-4);
						$CheckMonth=substr($start,strlen($start)-4,2);
						
						$end_y=substr($end,0,strlen($end)-4);
						$end_m=substr($end,strlen($end)-4,2);
						
						$DateCount=($end_y-$CheckYear-1)*12+(12-$CheckMonth+1)+$end_m;
						
						for($i=1;$i<=$DateCount;$i++){
							//計算y年m月共領了多少錢(案計只控管同一計畫)
							$TotalWorkSalary = 0;//晉薪前加總
							$Next_TotalWorkSalary = 0;//晉薪後加總
							if($countType=="1"){
								$strSQL="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and RecordStatus='0' ". 
								 "and cast(YEAR(BeginDate) as varchar)+cast(MONTH(BeginDate) as varchar)<='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and cast(YEAR(EndDate) as varchar)+cast(MONTH(EndDate) as varchar)>='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ";
							}else{
								$strSQL="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and RecordStatus='0' ". 
								 "and cast(YEAR(BeginDate) as varchar)+cast(MONTH(BeginDate) as varchar)<='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and cast(YEAR(EndDate) as varchar)+cast(MONTH(EndDate) as varchar)>='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and SerialNo in (select SerialNo from PT_Outline where BugNo='".$bugno."' and FormStatus='1')";
							}
							$result=$db->query($strSQL);
							if($result && $row=$result->fetch()){
								if($PayContBDate!="" && $PayContBDate!="1900-01-01 00:00:00.000"){//有晉薪日
									if(($CheckYear."-".addLeadingZeros($CheckMonth,2)."-01")>=$PayContBDate){
										$Next_TotalWorkSalary=$Next_TotalWorkSalary+$row['TotalAmount'];
									}else{
										$TotalWorkSalary=$TotalWorkSalary+$row['TotalAmount'];
									}
								}else{
									$TotalWorkSalary=$TotalWorkSalary+$row['TotalAmount'];
								}
								
								while($row=$result->fetch()){$TotalWorkSalary=$TotalWorkSalary+$row['TotalAmount'];}
							}
							
							if($TotalLimit!=-1 && $TotalLimit<($TotalWorkSalary+$totalamount)){
								$arr.=$CheckYear.addLeadingZeros($CheckMonth,2)." 已申請金額(含本筆)：".($TotalWorkSalary+$totalamount)."\n";//記錄有超過的月份
							}else if($Next_TotalLimit!=-1 && $Next_TotalLimit<($Next_TotalWorkSalary+$totalamount)){
								$arr.=$CheckYear.addLeadingZeros($CheckMonth,2)." 已申請金額(含本筆)：".($Next_TotalWorkSalary+$totalamount)."\n";//記錄有超過的月份
							}
							$CheckMonth=$CheckMonth+1;
							if($CheckMonth==13){
								$CheckMonth=1;
								$CheckYear=$CheckYear+1;
							}
							$DateCount = $DateCount-1;
						}
						if($arr==""){$arr="ok";}
						else{
							$arr=$PNo." 申請金額超出學校內控規定，超過狀況如下-\n".$arr;
						}
					}else{
						$arr="ok";
					}
				}else{
					$arr.="\n查無".$PNo."支領項目規則";
				}
			}
		}
		if($arr!=""){return $arr;}
		else{return "ok";}
	}
	//註6：助教級助理人員（限編制內助教或薪點200以上之技士、技佐），上限5000元；
	//講師級助理人員（限編制內講師或具教師資格者），上限6000元		
	//編制內助教+技士技佐資料來源[personnelcommon].[dbo].[vi_TechSpecialist]
	//限制為擔任科技部兼任人員
	//金額加總以已通過表單為主
	//完整條文為:科技部僅學生+技士/技佐或編制內助教可當助教級助理人員+編制內講師或具教師資格者可當講師級助理人員,其他人都不行
	//等於註二+註六,但註二和註六於OX表內不同時執行,應該註二可排除全部職員+校外人士
	function Rules_EmpAmountLimit_PS06($bugno,$PNo,$IdNo,$identity,$Prank,$Ptitle,$start,$end,$totalamount){
		require("connectSQL.php");	
		$arr="";
		$Limit=0;
		$count_total=0;
		$bugType=checkBugetTypeForRules($bugno);
		
		if($bugType=="科技部"){$count_total=$totalamount;}
		//助教級助理人員（限編制內助教或薪點200以上之技士、技佐）
		$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_TechSpecialist] where empno='".$PNo."'";
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){//表示為編制內助教或薪點200以上之技士、技佐
			$Limit=5000;
		}
		//是否講師
		$pos = strpos($Prank, "講師");
		if ($pos !== false) {$Limit=6000;}
		//找請核期間的其他請核資料
		$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
				"where SerialNo in ".
				"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
				"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."'))) ".
				"and FormStatus='1'";
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){
			$bugtype=checkBugetTypeForRules($row['BugNo']);
			if($bugtype=="科技部"){
				$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
				"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
				$result2=$db->query($strSQL2);
				while($row2=$result2->fetch()){
					$count_total=$count_total+$row2['TotalAmount'];
				}
			}
			while($row=$result->fetch()){
				$bugtype=checkBugetTypeForRules($row['BugNo']);
				if($bugtype=="科技部"){
					$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
					"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
					"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
					"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
					"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
					$result2=$db->query($strSQL2);
					$row2=$result2->fetch();
					while($row2=$result2->fetch()){
						$count_total=$count_total+$row2['TotalAmount'];
					}
				}
			}
		}
		if($Limit==5000){
			if($Ptitle!="13"){$arr="限編制內助教或薪點200以上之技士、技佐只能兼任助教級助理\n";}
			if($count_total>$Limit){$arr.="助教級助理人員，科技部計畫上限".$Limit."元";}
		}
		else if($Limit==6000){
			if($Ptitle!="14"){$arr="編制內講師或具教師資格者只能兼任講教級助理\n";}
			if($count_total>$Limit){$arr.="講師級助理人員，科技部計畫上限".$Limit."元";}
		}
		else if($Limit>0){$arr="ok";}
		else{$arr="人員身份不符 ".$bugno." 請核規則";}
		return $arr;
	}
	//註7：外籍學生除寒暑假外，每週工讀不得超過16小時
	//"11/6確認：
	//1.工讀生係指兼任職稱為「臨時工」者
	//2.寒假係指每年1~2月；暑假係指每年6~9月
	//3.請核系統外籍人士每月不得超過64小時；差勤系統外籍人士每週一至日不得超過16小時
	//4.詳細規則人事室再提供(如陸生和其他外籍是否有差異)
	function Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d){
		require("connectSQL.php");	
		$arr="";
		$Limit=64;
		$index=0;
		$count_hr=array();
		if($identity!="S"){$arr="ok";}
		else if($Ptitle!="4" || $PayTypeStr!="hr"){//不為臨時工或不是支領時薪
			$arr="ok";
		}else{
			//歐美學生是外籍生, std_identity = 4
			//另外僑生跟陸生等非中華民國國籍, 屬於近外生, std_identity in (3, 17)
			$strSQL="select * from StudentData where std_stdcode='".$PNo."'";
			$result=$db->query($strSQL);
			$row=$result->fetch();
			if($row['std_identity']=="3" || $row['std_identity']=="4" || $row['std_identity']=="17"){//外籍生
				
				$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				foreach($yymm as $temp){
					if($temp[1]==1 || $temp[1]==2 || $temp[1]==6 || $temp[1]==7 || $temp[1]==8 || $temp[1]==9){
						$count_hr[$index][0]=$temp[0];//年
						$count_hr[$index][1]=$temp[1];//月
						$count_hr[$index][2]=Round($Pay_limit*($temp[3]-$temp[2]+1)/$temp[4]);//本次申請工時
						//請核年月有對到就算
						$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
								 "RecordStatus='0' and PayType='hr_pay' and PTtitle='4' ".
								 "and cast(YEAR(BeginDate) as varchar)+cast(MONTH(BeginDate) as varchar)<='".$temp[0].addLeadingZeros($temp[1],2)."' ".
								 "and cast(YEAR(EndDate) as varchar)+cast(MONTH(EndDate) as varchar)>='".$temp[0].addLeadingZeros($temp[1],2)."' ";
						$result2=$db->query($strSQL2);
						if($result2 && $row2=$result2->fetch()){
							$count_hr[$index][2]+=$row2['LimitPerMonth'];
							while($row2=$result2->fetch()){$count_hr[$index][2]+=$row2['LimitPerMonth'];}
						}
						$index++;
					}
				}
				foreach($count_hr as $temp){
					if($temp[2]>64){$arr.=$temp[0]."/".$temp[1]."  ";}
				}
				if($arr==""){$arr="ok";}
				else{$arr="外籍生寒暑假工讀時數每月不得超過64小時,底下請核年月已超過\n".$arr;}
			}else{$arr="ok";}
		}
		return $arr;
	}
	//註8：頂尖大學計畫(E、W)除外
	//（本校專任人員不得再擔頂尖大學計畫兼任人員或臨時工）											
	function Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle){
		require("connectSQL.php");	
		if($identity!="E"){$arr="ok";}
		else{
			$Top_Empno=getWordOfEmpno($PNo);
			$bugtype=checkBugetTypeIFTopUniv($bugno);
			if($bugtype=="頂尖" && ($Ptitle=="3" || $Ptitle=="4")){
				if($Top_Empno!="C" && $Top_Empno!="VI" && $Top_Empno!="UT" && $Top_Empno!="PT" && $Top_Empno!="K" && $Top_Empno!="R" && $Top_Empno!="W"){
					$arr="本校專任人員不得再擔頂尖大學計畫兼任人員或臨時工";
				}else{$arr="ok";}
			}
			else{$arr="ok";}
		}
		return $arr;
	}
	//註9：博士候選人合計不超過68000元；博士班合計不超過60000元；
	//碩士班合計不超過20000元；大學部合計不超過12000元（非科技部計畫）	
	//註10：民間委辦計畫(C,Q)不受限==>依表列,併至註2,3,9中判斷
	//金額加總以已通過表單為主
	function Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$start,$end,$totalamount){
		require("connectSQL.php");	
		$arr="";
		$BugTotalAmount=0;
		$bugtype=checkBugetTypeForRules($row['bugno']);
		if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
			$BugTotalAmount=$totalamount;
			if($identity!="S"){$arr="ok";}//只提到學生
			else{
				if($Prank=="0"){$Limit=68000;}
				else if($Prank=="1"){$Limit=60000;}
				else if($Prank=="2"){$Limit=20000;}
				else if($Prank=="3"){$Limit=12000;}
				$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
					"where SerialNo in ".
					"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
					"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
					"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
					"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
					"or (BeginDate>='".$start."' and Enddate<='".$end."'))) ".
					"and FormStatus='1'";
				$result=$db->query($strSQL);
				if($result && $row=$result->fetch()){
					$bugtype=checkBugetTypeForRules($row['Bugno']);
					if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
						$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
								"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
								"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
								"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
								"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
						$result2=$db->query($strSQL2);
						while($row2=$result2->fetch()){
							$BugTotalAmount+=$row2['TotalAmount'];
						}
					}
					while($row=$result->fetch()){
						$bugtype=checkBugetTypeForRules($row['Bugno']);
						if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
							$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
								"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
								"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
								"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
								"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."'";
							$result2=$db->query($strSQL2);
							while($row2=$result2->fetch()){
								$BugTotalAmount+=$row2['TotalAmount'];
							}
						}
					}
				}
				
				if($BugTotalAmount>$Limit){
					$arr=$PNo."已請核科技部計畫金額總額(含此筆)超過科技部計畫上限".$Limit;
				}else{$arr="ok";}
			}
		}else{$arr="ok";}
		return $arr;
	}
	//註10：民間委辦計畫(C,Q)不受限==>依表列,併至註2,3,9中判斷

?>