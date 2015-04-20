<?
	//由eid或Fromeid取得FirstEid,判斷是否要跳過該一連串的請核資料(因為此一連串資料為同一筆請核的不同異動
	//可能都有效,就會變成重複判斷)
	function getFirstEid($Eid){
		require("connectSQL.php");
		if($Eid!="" || $Eid!="-1"){
			$strSQL="select * from PT_employed where Eid='".$Eid."'";
			$result=$db->query($strSQL) or die("Err:".$strSQL);
			$row=$result->fetch();
			if(trim($row['FirstEid'])==""){return $Eid;}
			else return trim($row['FirstEid']);
		}
		$Eid="";
		return $Eid;
	}
	//同一計畫，同一時間內均不得再兼任支領，所有頂尖大學計畫(E、W)視為同一計畫；
	//但同一計畫同一時間內可擔任2個不同時薪之臨時工；同一計畫同一時間內可擔任2個（含）以上不同頂尖大學計畫(E、W)編號之臨時工
	/*規則:1.除頂尖計畫之外,其餘計畫,同一計畫,同一時間內,只能有一個兼任身份(不含臨時工)身份
		   2.除頂尖計畫之外,其餘計畫,同一計畫,同一時間內,可以擔任2個不同時薪之臨時工
	     3.頂尖計畫的兼任人員（不含臨時工），同時不得再任其他頂尖計畫兼任人員及臨時工
	     4.頂尖計畫的臨時工,可以同時擔任其他不同頂尖計畫的臨時工
	     上述一,三條中的兼任人員為全部兼任人員(不含臨時工)
			20141115改支領規則表和阿智回信為主
			金額加總以已通過表單為主 */
	/*1031225新規則為
		同一計畫，同一時間內不得有2個身分（不得為：專+兼、專+臨、兼+兼、兼+臨）；但同一計畫同一時間內可擔任2個不同時薪之臨時工
	*/
	function Rules_noduplicate($OrderNo,$bugno,$PNo,$IdNo,$start,$end,$Ptitle,$PayTypeStr,$pay_unit,$FromEid){
		require("connectSQL.php");
		$arr="";
		$num_PT04=0;//記錄同一計畫,同一時期,不同時薪,臨時工,數量
		$diff_hrpay = array();
		if($Ptitle=="4" && $PayTypeStr=="hr_pay"){//本次申請計畫臨時工和時薪記錄
			$num_PT04=1;
			array_push($diff_hrpay, $pay_unit);
		}
		//確認是否有專任身份
		$strSQL="select distinct ExpenseSourceCode as bugno from [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource_buget] ".
				"where (empno='".$PNo."' or IdNo='".$IdNo."') ".
				"and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."'))";
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){
			$result=$db->query($strSQL);
			while($row=$result->fetch()){
				if($bugno==trim($row['bugno'])){
					$arr="已擔任 ".$bugno." 專任人員,不得重複請核兼任人員\n";
					return $arr;
				}
			}
		}
		//20150308改為未審單的金額也要控管
		$strSQL="select p2.BugNo,p.PTtitle,p.PayType,p.PayPerUnit from PT_employed p ".
				"left join  PT_outline p2 on p2.SerialNo=p.SerialNo ".
				"where (p.serialno in (select distinct serialno from PT_outline where FormStatus in ('1','0','-2') ".
				"and serialno!='".$OrderNo."') ".
				"and ((p.BeginDate>='".$start."' and p.BeginDate<'".$end."') ".
				"or (p.EndDate>='".$start."' and p.Enddate<'".$end."') ".
				"or (p.BeginDate<='".$start."' and p.Enddate>='".$end."') ".
				"or (p.BeginDate>='".$start."' and p.Enddate<='".$end."')) ".
				"and (p.idcode='".$PNo."' or p.pid='".$IdNo."') ".
				"and p.RecordStatus='0'".//先抓到此人員本段時間,或有重疊到的全部請核
				"and p.Eid<>'".trim(getFirstEid($FromEid))."' and (p.FirstEid<>'".trim(getFirstEid($FromEid))."' or p.FirstEid is null)) ";
		if($OrderNo!=""){
			$strSQL.="or (p.serialno='".$OrderNo."' ".
					"and ((p.BeginDate>='".$start."' and p.BeginDate<'".$end."') ".
					"or (p.EndDate>='".$start."' and p.Enddate<'".$end."') ".
					"or (p.BeginDate<='".$start."' and p.Enddate>='".$end."') ".
					"or (p.BeginDate>='".$start."' and p.Enddate<='".$end."')) ".
					"and (p.idcode='".$PNo."' or p.pid='".$IdNo."') ".
					"and p.RecordStatus='0'".//或同張單子裡的同一時段同一人
					"and p.Eid<>'".trim(getFirstEid($FromEid))."' and (p.FirstEid<>'".trim(getFirstEid($FromEid))."' or p.FirstEid is null)) ";
		}		
		//return $strSQL;
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){//有抓到資料表示有時段有cover到
			$result=$db->query($strSQL);
			while($row=$result->fetch()){
				$temp_bugno=trim($row['BugNo']);
				if($temp_bugno==$bugno){
					if($Ptitle=="4" && trim($row['PTtitle'])=="4" && $PayTypeStr=="hr_pay" && trim($row['PayType'])=="hr_pay" && (int)$pay_unit!=(int)trim($row['PayPerUnit'])){//此次為臨時工且目前確認資料亦為臨時工
						if(!in_array(trim($row['PayPerUnit']),$diff_hrpay)){
							array_push($diff_hrpay, trim($row['PayPerUnit']));
						}
						$num_PT04++;
					}else{
						$arr=$start."~".$end." 期間已存在相同計畫請核資料,不得重複請核兼任人員,但可以請核不同時薪臨時工共計兩筆\n";
						return $arr;
					}
				}
			}
		}
		if($num_PT04>2){
			$arr=$bugno." 不同時薪之臨時工最多只能2個,目前已請核".$num_PT04."個不同時薪臨時工(含本筆)\n";
		}else if($num_PT04==2 && count($diff_hrpay)<2){
			$arr="同計畫 ".$bugno." 同一時段至多2個臨時工身份,且時薪需不同\n";
		}else{
			$arr="ok";
			//$arr=$strSQL;			
		}		
		return $arr;
		//return $arr."<br>".$pay_unit."<br>".$num_PT04."<br>".print_r($diff_hrpay);
		//return $strSQL;			
	}
	//註1：專任人員兼任教育部或其他機關計畫，只能兼任二項兼任助理或臨時工，
	//所支領兼任報酬以每月總額1萬元為限（專任人員薪資分攤若有涉及教育部計畫，應受此限制）	
	//金額加總以已通過表單為主-->1031001版
	function Rules_EduAndOthers_AmountLimit_PS01($OrderNo,$PNo,$IdNo,$identity,$bugno,$start,$end,$totalamount,$FromEid){
		require("connectSQL.php");
		$arr="";
		$count_EduAndOthers=0;//用來計算教育部和其他計畫的加總,專任和兼任都算在內
		$otherPlans = array();//記錄其他兼任的計畫
		$count_amount=0;//計算金額加總
		$count_YYMM=0;//暫記每個月的加總金額
		//先確認目前申請計畫的分類
		$bugtype=checkBugetTypeForRules($bugno);
		if($bugtype=="教育部" || $bugtype=="頂尖" || $bugtype=="其他"){$count_EduAndOthers++;$count_amount=$totalamount;}
		//找已請核的記錄,先算已請核個數加總
		//20150308改為未審單的金額也要控管
		$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
				"where (SerialNo in ".
				"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and PTtitle in ('3','4') and ".
				"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
				"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
				"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
				"or (BeginDate>='".$start."' and Enddate<='".$end."')) and FirstEid<>'".trim(getFirstEid($FromEid))."') ".
				"and FormStatus in ('1','0','-2')) ";
		if($OrderNo!=""){
			$strSQL.="or (SerialNo in (select distinct SerialNo from PT_Employed where SerialNo='".$OrderNo."' ".
					"and RecordStatus in ('0') ".//先抓到此人員本段時間,或有重疊到的全部請核
					"and Eid<>'".trim(getFirstEid($FromEid))."' and (FirstEid<>'".trim(getFirstEid($FromEid))."' or FirstEid is null))) ";
		}
		//return $strSQL;
		$result=$db->query($strSQL);
		$row=$result->fetchAll();
		if(count($row)>0){
			$result=$db->query($strSQL);
			while($row=$result->fetch()){
				$bugtype=checkBugetTypeForRules($row['BugNo']);
				if($bugtype=="教育部" || $bugtype=="頂尖" || $bugtype=="其他"){
					$count_EduAndOthers++;
					array_push($otherPlans,$row['BugNo']);
				}
			}
		}		
		if($count_EduAndOthers>2){
			$arr="專任人員兼任教育部或其他類計畫,最多只能兩項,此段時期已兼任:".implode("、", $otherPlans)."\n";
			return $arr;
		}else{//再來確認每月加總
			//20150308改為已審/未審都算金額
			$start_arr=explode("-",$start);
			$start_y=$start_arr[0]-1911;
			$start_m=$start_arr[1];
			$start_d=$start_arr[2];
			$end_arr=explode("-",$end);
			$end_y=$end_arr[0]-1911;
			$end_m=$end_arr[1];
			$end_d=$end_arr[2];
			
			$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
			for($index=0;$index<sizeof($yymm);$index++){
				$count_YYMM=0;
				//算已審核
				$strSQL="select * from PT_PayInfo ".
						"where Eid in (select Eid from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') ".
						"and FirstEid<>'".trim(getFirstEid($FromEid))."' and RecordStatus='0') ".
						"and PayStatus='1' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
				$result=$db->query($strSQL) or die("Err:".$strSQL);
				$row=$result->fetchAll();
				if(count($row)>0){
					$result=$db->query($strSQL) or die("Err:".$strSQL);
					while($row=$result->fetch()){
						$bugtype=checkBugetTypeForRules($row['BugNo']);
						if($bugtype=="教育部" || $bugtype=="頂尖" || $bugtype=="其他"){
							$count_YYMM+=(int)(trim($row['PayAmount']));
						}
					}
				}
				//算未審核(含新建和鎖定)
				$m_start=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][2],2);
				$m_end=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][3],2);
				$strSQL2="select p2.BugNo,p.* from PT_Employed p ".
						 "left join PT_Outline p2 on p.SerialNo=p2.SerialNo ".
						 "where Eid in (select Eid from PT_Employed ".
						 "where (IdCode='".$PNo."' or Pid='".$IdNo."') and FirstEid<>'".trim(getFirstEid($FromEid))."' ".
						 "and SerialNo in (select serialno from PT_Outline where FormStatus in ('-2','0'))) and ".
						 "RecordStatus='0' and ((BeginDate<='".$m_start."' and Enddate>='".$m_start."') ".
						 "or (BeginDate<='".$m_end."' and Enddate>='".$m_end."') ".
						 "or (BeginDate<='".$m_start."' and Enddate>='".$m_end."') ".
						 "or (BeginDate>='".$m_start."' and Enddate<='".$m_end."'))";
				//return $strSQL."\n".$strSQL2;
				$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
				$row2=$result2->fetchAll();
				if(count($row2)>0){
					$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
					while($row2=$result2->fetch()){
						$bugtype=checkBugetTypeForRules($row2['BugNo']);
						if($bugtype=="教育部" || $bugtype=="頂尖" || $bugtype=="其他"){
							$count_YYMM+=(int)(trim($row2['TotalAmount']));
						}
					}
				}
				if(($count_YYMM+$count_amount)>10000){
					$count_amount+=$count_YYMM;
					$arr="專任人員兼任教育部或其他類計畫,兼任報酬以每月總額1萬元為限,".$yymm[$index][0].addLeadingZeros($yymm[$index][1],2).
						  "已申請".$count_amount."元(含此筆)!";
					return $arr;
				}
			}
		}
		
		return "ok";		
		//return $count_EduAndOthers."-".$count_amount."-".checkBugetTypeForRules($bugno)."-".$totalamount."-".$strSQL2;
		//return $strSQL;
	}
	//註2：博士候選人合計不超過34000元；博士班合計不超過30000元；
	//碩士班合計不超過10000元；大學部合計不超過6000元（僅限科技部計畫的請核）
	//註10：民間委辦計畫(C)不受限==>依表列,併至註2,3,9中判斷==>是科技部就不會是民間計畫
	//金額加總以已通過表單為主
	//完整條文為:科技部僅學生+技士/技佐或編制內助教可當助教級助理人員+編制內講師或具教師資格者可當講師級助理人員,其他人都不行
	//等於註二+註六,但註二和註六於OX表內不同時執行,應該註二可排除全部職員+校外人士
	//20150306
	//註2：校內外學生：博士候選人合計不超過34000元；博士班合計不超過30000元；
	//碩士班合計不超過10000元；大學部合計不超過6000元（科技部計畫）	
	//金額加總範圍為全部表單	
	function Rules_EduStu_AmountLimit_PS02($OrderNo,$bugno,$PNo,$IdNo,$identity,$Prank,$start,$end,$totalamount,$FromEid){
		require("connectSQL.php");	
		$arr="";
		$BugTotalAmount=0;
		
		$bugtype=checkBugetTypeForRules($bugno);
		if($bugtype=="科技部"){$BugTotalAmount=$totalamount;}
		//校外人士只有學生類可以
		if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
		else{$outStu=0;}
		if($identity=="E" || ($identity=="O" && $outStu==0)){$arr="除科技部計畫除特別規定外,只有校內外學生可以擔任";return $arr;}
		else{
			if($Prank=="0" || $Prank=="13"){$Limit=34000;}
			else if($Prank=="1" || $Prank=="12"){$Limit=30000;}
			else if($Prank=="2" || $Prank=="11"){$Limit=10000;}
			else if($Prank=="3" || $Prank=="10"){$Limit=6000;}
			
			$start_arr=explode("-",$start);
			$start_y=$start_arr[0]-1911;
			$start_m=$start_arr[1];
			$start_d=$start_arr[2];
			$end_arr=explode("-",$end);
			$end_y=$end_arr[0]-1911;
			$end_m=$end_arr[1];
			$end_d=$end_arr[2];
			
			$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
			for($index=0;$index<sizeof($yymm);$index++){
				$count_YYMM=0;
				//算已審核
				$strSQL="select * from PT_PayInfo ".
						"where Eid in (select Eid from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') ".
						"and FirstEid<>'".trim(getFirstEid($FromEid))."' and RecordStatus='0') ".
						"and PayStatus='1' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
				$result=$db->query($strSQL) or die("Err:".$strSQL);
				$row=$result->fetchAll();
				if(count($row)>0){
					$result=$db->query($strSQL) or die("Err:".$strSQL);
					while($row=$result->fetch()){
						$bugtype=checkBugetTypeForRules($row['BugNo']);
						if($bugtype=="科技部"){
							$count_YYMM+=(int)(trim($row['PayAmount']));
						}
					}
				}
				//算未審核(含新建和鎖定)
				$m_start=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][2],2);
				$m_end=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][3],2);
				$strSQL2="select p2.BugNo,p.* from PT_Employed p ".
						 "left join PT_Outline p2 on p.SerialNo=p2.SerialNo ".
						 "where Eid in (select Eid from PT_Employed ".
						 "where (IdCode='".$PNo."' or Pid='".$IdNo."') and FirstEid<>'".trim(getFirstEid($FromEid))."' ".
						 "and SerialNo in (select serialno from PT_Outline where FormStatus in ('-2','0'))) and ".
						 "RecordStatus='0' and ((BeginDate<='".$m_start."' and Enddate>='".$m_start."') ".
						 "or (BeginDate<='".$m_end."' and Enddate>='".$m_end."') ".
						 "or (BeginDate<='".$m_start."' and Enddate>='".$m_end."') ".
						 "or (BeginDate>='".$m_start."' and Enddate<='".$m_end."'))";
				//return $strSQL2;
				$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
				$row2=$result2->fetchAll();
				if(count($row2)>0){
					$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
					while($row2=$result2->fetch()){
						$bugtype=checkBugetTypeForRules($row2['BugNo']);
						if($bugtype=="科技部"){
							$count_YYMM+=(int)(trim($row2['TotalAmount']));
						}
					}
				}
				if(($count_YYMM+$BugTotalAmount)>$Limit){
					$BugTotalAmount+=$count_YYMM;
					$arr="兼任科技部計畫,兼任報酬以每月總額".$Limit."元為限,".$yymm[$index][0].addLeadingZeros($yymm[$index][1],2).
						  "已申請".$BugTotalAmount."元(含此筆)!";
					return $arr;
				}
			}
		}
		return "ok";
	}
	/*註3：博士候選人單筆不超過34000元；博士班單筆不超過30000元；
	碩士班單筆不超過10000元；大學部單筆不超過6000元	
	註10：民間委辦計畫(C)不受限==>依表列,併至註2,3,9中判斷
	金額加總以已通過表單為主*/
	//20150306
	//註3：校內外學生：博士候選人單筆不超過34000元；
	//博士班單筆不超過30000元；碩士班單筆不超過10000元；
	//大學部單筆不超過6000元														
	//金額加總範圍為全部表單
	function Rules_Stu_SingleApplyLimit_PS03($OrderNo,$bugno,$identity,$Prank,$totalamount,$FromEid){
		require("connectSQL.php");	

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
		
		$arr="";
		if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
		else{$outStu=0;}
		if($identity=="E" || ($identity=="O" && $outStu==0)){return "ok";}
		else{
			if($Prank=="0" || $Prank=="13"){$Limit=34000;}
			else if($Prank=="1" || $Prank=="12"){$Limit=30000;}
			else if($Prank=="2" || $Prank=="11"){$Limit=10000;}
			else if($Prank=="3" || $Prank=="10"){$Limit=6000;}
			if($totalamount>$Limit && checkBugetTypeIFCivil($bugno)!="民間"){
				if($identity=="E"){
					$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;
				}else{
					$arr="校外".$outer_title[$Prank]." 單筆請核不得超過 ".$Limit;
				}
			}else{$arr="ok";}
		}
		return $arr;
	}
	//註4：3000元~5000元（警語），有另附計畫書者除外（上限比照註3規定）；
	//頂尖計畫(E、W)不需警語，上限比照註3規定==>註三規定指學生														
	function Rules_SingleApplyLimit_PS04($OrderNo,$bugno,$identity,$Prank,$totalamount,$FromEid){
		require("connectSQL.php");	
		
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
		$arr="";
		$Prank=trim($Prank);
		if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
		else{$outStu=0;}
		if($identity=="E" || ($identity=="O" && $outStu==0)){$arr="ok";}
		else{
			if($Prank=="0" || $Prank=="13"){$Limit="34000";}
			else if($Prank=="1" || $Prank=="12"){$Limit="30000";}
			else if($Prank=="2" || $Prank=="11"){$Limit="10000";}
			else if($Prank=="3" || $Prank=="10"){$Limit="6000";}
			$warn_Limit="3000";
			$bugType=checkBugetTypeIFTopUniv($bugno);
			if((int)$totalamount>(int)$Limit){
				$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;
			}else if((int)$totalamount>=(int)$warn_Limit){
				if($bugType!="頂尖"){
					if((int)$totalamount>(int)$Limit){$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;}
					else{$arr="warning_請核金額3000以上,請注意";}
				}else{
					if((int)$totalamount>(int)$Limit){$arr=$stu_title[$Prank]." 單筆請核不得超過 ".$Limit;}
					else{$arr="ok";}
				}
			}else{
				$arr="ok";
			}
		}
		return $arr;
		//return $Prank." ".$totalamount." ".$Limit." ".$haha;
	}
	//註5：約用人員不得超過其薪資之30%(兼任+臨時工+績效工作酬勞)；
	//公務人員不得超過其專業加給之60%(兼任+臨時工+績效工作酬勞)	
	//金額加總以已通過表單為主
	//20150308金額加總範圍為全部表單
	function Rules_EmpAmountLimit_PS05($OrderNo,$bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$start,$end,$totalamount,$FromEid){
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
								 "and cast(YEAR(EndDate) as varchar)+cast(MONTH(EndDate) as varchar)>='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and Eid<>'".trim(getFirstEid($FromEid))."' and FirstEid<>'".trim(getFirstEid($FromEid))."'";
							}else{
								$strSQL="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and RecordStatus='0' ". 
								 "and cast(YEAR(BeginDate) as varchar)+cast(MONTH(BeginDate) as varchar)<='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and cast(YEAR(EndDate) as varchar)+cast(MONTH(EndDate) as varchar)>='".$CheckYear.addLeadingZeros($CheckMonth,2)."' ".
								 "and SerialNo in (select SerialNo from PT_Outline where BugNo='".$bugno."' and FormStatus in ('1','-2','0'))".
								 "and Eid<>'".trim(getFirstEid($FromEid))."' and FirstEid<>'".trim(getFirstEid($FromEid))."'";
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
								$arr.=$CheckYear.addLeadingZeros($CheckMonth,2)." 已申請金額(含本筆)：".($TotalWorkSalary+$totalamount).",可申請:".$TotalLimit."\n";//記錄有超過的月份
							}else if($Next_TotalLimit!=-1 && $Next_TotalLimit<($Next_TotalWorkSalary+$totalamount)){
								$arr.=$CheckYear.addLeadingZeros($CheckMonth,2)." 已申請金額(含本筆)：".($Next_TotalWorkSalary+$totalamount).",可申請:".$TotalLimit."\n";//記錄有超過的月份
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
	//20150306
	//註6：助教級助理人員（限編制內助教或薪點200以上之技士、技佐），上限5000元；
	//講師級助理人員（限編制內講師或具教師資格者），上限6000元
	//（校外人士非學生支領科技部比照公務人員支領科技部規則）
	//金額加總範圍為全部表單
	function Rules_EmpAmountLimit_PS06($OrderNo,$bugno,$PNo,$IdNo,$identity,$Prank,$Ptitle,$start,$end,$totalamount,$FromEid){
		require("connectSQL.php");	
		$arr="";
		$Limit=0;
		$count_total=0;
		$bugType=checkBugetTypeForRules($bugno);
		
		if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
		else{$outStu=0;}
		
		if($bugType=="科技部"){$count_total=$totalamount;}
		if($identity=="E"){
			//助教級助理人員（限編制內助教或薪點200以上之技士、技佐）
			$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_TechSpecialist] where empno='".$PNo."'";
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){//表示為編制內助教或薪點200以上之技士、技佐
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
					"and FormStatus in ('1','-2','0')";
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){
				$result=$db->query($strSQL);
				while($row=$result->fetch()){
					$bugtype=checkBugetTypeForRules($row['BugNo']);
					if($bugtype=="科技部"){
						$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
						"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
						"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
						"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
						"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."' ".
						"and Eid<>'".trim(getFirstEid($FromEid))."' and FirstEid<>'".trim(getFirstEid($FromEid))."'";
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
		}elseif($identity=="O" && $outStu==0){
			if($Ptitle=="13" || $Ptitle=="14"){
				if($Ptitle=="13"){$Limit=5000;}
				elseif($Ptitle=="14"){$Limit=6000;}
				//找請核期間的其他請核資料
				$strSQL="select distinct BugNo,SerialNo from PT_Outline ".
						"where SerialNo in ".
						"(select distinct SerialNo from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
						"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
						"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
						"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
						"or (BeginDate>='".$start."' and Enddate<='".$end."'))) ".
						"and FormStatus in ('1','-2','0')";
				$result=$db->query($strSQL);
				$row=$result->fetchAll();
				if(count($row)>0){
					$result=$db->query($strSQL);
					while($row=$result->fetch()){
						$bugtype=checkBugetTypeForRules($row['BugNo']);
						if($bugtype=="科技部"){
							$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
							"RecordStatus='0' and ((BeginDate<='".$start."' and Enddate>='".$start."') ".
							"or (BeginDate<='".$end."' and Enddate>='".$end."') ".
							"or (BeginDate<='".$start."' and Enddate>='".$end."') ".
							"or (BeginDate>='".$start."' and Enddate<='".$end."')) and SerialNo='".$row['SerialNo']."' ".
							"and Eid<>'".trim(getFirstEid($FromEid))."' and FirstEid<>'".trim(getFirstEid($FromEid))."'";
							$result2=$db->query($strSQL2);
							$row2=$result2->fetch();
							while($row2=$result2->fetch()){
								$count_total=$count_total+$row2['TotalAmount'];
							}
						}
					}
				}
				if($Limit==5000){
					if($count_total>$Limit){$arr.="校外人士助教級助理人員，科技部計畫上限".$Limit."元";}
				}
				else if($Limit==6000){
					if($count_total>$Limit){$arr.="校外人士講師級助理人員，科技部計畫上限".$Limit."元";}
				}
				else if($Limit>0){$arr="ok";}
				else{$arr="人員身份不符 ".$bugno." 請核規則";}
			}else{$arr="ok";}
		}else{$arr="ok";}
		return $arr;
	}
	//註7：外籍學生除寒暑假外，每週工讀不得超過16小時
	//"11/6確認：
	//1.工讀生係指兼任職稱為「臨時工」者
	//2.寒假係指每年1~2月；暑假係指每年6~9月
	//3.請核系統外籍人士每月不得超過64小時；差勤系統外籍人士每週一至日不得超過16小時
	//4.詳細規則人事室再提供(如陸生和其他外籍是否有差異)
	//20150306註7：外籍學生除寒暑假外，每週工讀不得超過16小時，每月不得超過64小時	
	//金額加總範圍為全部表單
	function Rules_StuLimit_PS07($OrderNo,$bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d,$FromEid){
		require("connectSQL.php");	
		$arr="";
		$Limit=64;
		$index=0;
		$count_hr=array();
		if($identity!="S"){$arr="ok";}
		else if($Ptitle!="4" || $PayTypeStr!="hr_pay"){//不為臨時工或不是支領時薪
			$arr="ok";
		}else{
			//歐美學生是外籍生, std_identity = 4
			//另外僑生跟陸生等非中華民國國籍, 屬於境外生, std_identity in (3, 17)
			$strSQL="select * from StudentData where std_stdcode='".$PNo."'";
			$result=$db->query($strSQL);
			$row=$result->fetch();
			//if($row['std_identity']=="3" || $row['std_identity']=="4" || $row['std_identity']=="17"){//外籍生
			if(checkARC($IdNo) || ($row['std_identity']=="3" || $row['std_identity']=="4" || $row['std_identity']=="17")){//外籍生一定是居留證
				$yymm=countYYMMDD(($start_y-1911),$start_m,$start_d,($end_y-1911),$end_m,$end_d);
				foreach($yymm as $temp){
					if($temp[1]!=1 && $temp[1]!=2 && $temp[1]!=6 && $temp[1]!=7 && $temp[1]!=8 && $temp[1]!=9){
						$count_hr[$index][0]=$temp[0];//年
						$count_hr[$index][1]=$temp[1];//月
						$count_hr[$index][2]=Round($Pay_limit*($temp[3]-$temp[2]+1)/$temp[4]);//本次申請工時
						//請核年月有對到就算
						$temp_start=($temp[0]+1911)."-".addLeadingZeros($temp[1],2)."-".addLeadingZeros($temp[2],2);
						$temp_end=($temp[0]+1911)."-".addLeadingZeros($temp[1],2)."-".addLeadingZeros($temp[3],2);
						
						$strSQL2="select * from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') and ".
								 "RecordStatus='0' and PayType='hr_pay' and PTtitle='4' ".
								 "and ((BeginDate<='".$temp_start."' and Enddate>='".$temp_start."') ".
								 "or (BeginDate<='".$temp_end."' and Enddate>='".$temp_end."') ".
							     "or (BeginDate<='".$temp_start."' and Enddate>='".$temp_end."') ".
							     "or (BeginDate>='".$temp_start."' and Enddate<='".$temp_end."')) ".
								 "and Eid<>'".trim(getFirstEid($FromEid))."' and FirstEid<>'".trim(getFirstEid($FromEid))."' ".
								 "and SerialNo in (select SerialNo from PT_Outline where FormStatus in ('1','-2','0'))";
						//return $strSQL2;
						$result2=$db->query($strSQL2);
						$row2=$result2->fetchAll();
						if(count($row2)>0){
							$result2=$db->query($strSQL2);
							while($row2=$result2->fetch()){$count_hr[$index][2]+=$row2['LimitPerMonth'];}
						}
						$index++;
					}
				}
				//return print_r($count_hr);
				foreach($count_hr as $temp){
					if($temp[2]>64){$arr.=$temp[0]."/".$temp[1]."  ";}
				}
				if($arr==""){$arr="ok";}
				else{$arr="外籍生除寒暑假外工讀時數每月不得超過64小時,底下請核年月已超過\n".$arr;}
			}else{$arr="ok";}
		}
		return $arr;
	}
	//註8：頂尖大學計畫(E、W)+科技部+其他政府計畫的合計,博士候選人上限34000(計畫範圍就是C以外)
	//博士30000,碩士10000,大學6000
	//20150306
	//註8：頂尖大學計畫(E、W)+科技部+其他政府計畫
	//（本校專任人員不得再擔頂尖大學計畫兼任人員或臨時工）：
	//博士候選人合計不超過34000元；博士班合計不超過30000元；碩士班合計不超過10000元；
	//大學部合計不超過6000元	
	//金額加總範圍為全部表單
	function Rules_EmpLimit_PS08($OrderNo,$bugno,$PNo,$IdNo,$identity,$Prank,$Ptitle,$start,$end,$totalamount,$FromEid){
		require("connectSQL.php");	
		
		if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
		else{$outStu=0;}
		
		if($identity=="E"){
			$Top_Empno=getWordOfEmpno($PNo);
			$bugtype=checkBugetTypeIFTopUniv($bugno);
			if($bugtype=="頂尖" && ($Ptitle=="3" || $Ptitle=="4")){
				if($Top_Empno!="C" && $Top_Empno!="VI" && $Top_Empno!="UT" && $Top_Empno!="PT" && $Top_Empno!="K" && $Top_Empno!="R" && $Top_Empno!="W"){
					$arr="本校專任人員不得再擔頂尖大學計畫兼任人員或臨時工";
				}else{$arr="ok";}
			}
			else{$arr="ok";}
		}else if($identity=="S" || $outStu==1){
			$arr="";
			$BugTotalAmount=0;
			
			//$bugtype=checkBugetTypeForRules($bugno);
			$bugtype=checkBugetTypeIFCivil($bugno);
			if($bugtype!="民間"){$BugTotalAmount=$totalamount;}
			
			if($Prank=="0" || $Prank=="13"){$Limit=34000;}
			else if($Prank=="1" || $Prank=="12"){$Limit=30000;}
			else if($Prank=="2" || $Prank=="11"){$Limit=10000;}
			else if($Prank=="3" || $Prank=="10"){$Limit=6000;}
			
			$start_arr=explode("-",$start);
			$start_y=$start_arr[0]-1911;
			$start_m=$start_arr[1];
			$start_d=$start_arr[2];
			$end_arr=explode("-",$end);
			$end_y=$end_arr[0]-1911;
			$end_m=$end_arr[1];
			$end_d=$end_arr[2];
			
			$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
			for($index=0;$index<sizeof($yymm);$index++){
				$count_YYMM=0;
				//算已審核
				$strSQL="select * from PT_PayInfo ".
						"where Eid in (select Eid from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') ".
						"and FirstEid<>'".trim(getFirstEid($FromEid))."' and RecordStatus='0') ".
						"and PayStatus='1' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
				$result=$db->query($strSQL) or die("Err:".$strSQL);
				$row=$result->fetchAll();
				if(count($row)>0){
					$result=$db->query($strSQL) or die("Err:".$strSQL);
					while($row=$result->fetch()){
						$bugtype=checkBugetTypeIFCivil($row['BugNo']);
						if($bugtype!="民間"){$count_YYMM+=(int)(trim($row['PayAmount']));}
					}
				}
				//算未審核(含新建和鎖定)
				$m_start=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][2],2);
				$m_end=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][3],2);
				$strSQL2="select p2.BugNo,p.* from PT_Employed p ".
						 "left join PT_Outline p2 on p.SerialNo=p2.SerialNo ".
						 "where Eid in (select Eid from PT_Employed ".
						 "where (IdCode='".$PNo."' or Pid='".$IdNo."') and FirstEid<>'".trim(getFirstEid($FromEid))."' ".
						 "and SerialNo in (select serialno from PT_Outline where FormStatus in ('-2','0'))) and ".
						 "RecordStatus='0' and ((BeginDate<='".$m_start."' and Enddate>='".$m_start."') ".
						 "or (BeginDate<='".$m_end."' and Enddate>='".$m_end."') ".
						 "or (BeginDate<='".$m_start."' and Enddate>='".$m_end."') ".
						 "or (BeginDate>='".$m_start."' and Enddate<='".$m_end."'))";
				//return $strSQL2;
				$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
				$row2=$result2->fetchAll();
				if(count($row2)>0){
					$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
					while($row2=$result2->fetch()){
						$bugtype=checkBugetTypeIFCivil($row2['BugNo']);
						if($bugtype!="民間"){
							$count_YYMM+=(int)(trim($row2['TotalAmount']));
						}
					}
				}
				if(($count_YYMM+$BugTotalAmount)>$Limit){
					$BugTotalAmount+=$count_YYMM;
					$arr="兼任科技部/教育部/其他政府計畫,兼任報酬以每月總額".$Limit."元為限,".$yymm[$index][0].addLeadingZeros($yymm[$index][1],2).
						  "已申請".$BugTotalAmount."元(含此筆)!";
					return $arr;
				}
			}
			
			return "ok";
		}
		return $arr;
	}
	//註9：博士候選人合計不超過68000元；博士班合計不超過60000元；
	//碩士班合計不超過20000元；大學部合計不超過12000元（非科技部計畫）	
	//註10：民間委辦計畫(C)不受限==>依表列,併至註2,3,9中判斷
	//金額加總以已通過表單為主
	//20150306
	//註9：校內外學生：博士候選人合計不超過68000元；博士班合計不超過60000元；
	//碩士班合計不超過20000元；大學部合計不超過12000元（非科技部計畫）
	//金額加總範圍為全部表單	
	function Rules_StuLimit_PS09($OrderNo,$bugno,$PNo,$IdNo,$identity,$Prank,$start,$end,$totalamount,$FromEid){
		require("connectSQL.php");	
		$arr="";
		$BugTotalAmount=0;
		$bugtype=checkBugetTypeForRules($row['bugno']);
		//return $bugtype;
		if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
			if($identity=="O" && ($Prank=="0" || $Prank=="1" || $Prank=="2" || $Prank=="3")){$outStu=1;}
			else{$outStu=0;}
			
			$BugTotalAmount=$totalamount;
			
			if($identity=="E" || ($identity=="O" && $outStu==0)){$arr="ok";}//只提到學生
			else{				
				if($Prank=="0" || $Prank=="13"){$Limit=68000;}
				else if($Prank=="1" || $Prank=="12"){$Limit=60000;}
				else if($Prank=="2" || $Prank=="11"){$Limit=20000;}
				else if($Prank=="3" || $Prank=="10"){$Limit=12000;}
				
				$start_arr=explode("-",$start);
				$start_y=$start_arr[0]-1911;
				$start_m=$start_arr[1];
				$start_d=$start_arr[2];
				$end_arr=explode("-",$end);
				$end_y=$end_arr[0]-1911;
				$end_m=$end_arr[1];
				$end_d=$end_arr[2];
				
				$yymm=countYYMMDD($start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				for($index=0;$index<sizeof($yymm);$index++){
					$count_YYMM=0;
					//算已審核
					$strSQL="select * from PT_PayInfo ".
							"where Eid in (select Eid from PT_Employed where (IdCode='".$PNo."' or Pid='".$IdNo."') ".
							"and FirstEid<>'".trim(getFirstEid($FromEid))."' and RecordStatus='0') ".
							"and PayStatus='1' and PayYear='".$yymm[$index][0]."' and PayMonth='".$yymm[$index][1]."'";
					//return $strSQL;
					$result=$db->query($strSQL) or die("Err:".$strSQL);
					$row=$result->fetchAll();
					if(count($row)>0){
						$result=$db->query($strSQL) or die("Err:".$strSQL);
						while($row=$result->fetch()){
							$bugtype=checkBugetTypeForRules($row['BugNo']);
							if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
								$count_YYMM+=(int)(trim($row['PayAmount']));
							}
						}
					}
					//算未審核(含新建和鎖定)
					$m_start=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][2],2);
					$m_end=($yymm[$index][0]+1911)."-".addLeadingZeros($yymm[$index][1],2)."-".addLeadingZeros($yymm[$index][3],2);
					$strSQL2="select p2.BugNo,p.* from PT_Employed p ".
							 "left join PT_Outline p2 on p.SerialNo=p2.SerialNo ".
							 "where Eid in (select Eid from PT_Employed ".
							 "where (IdCode='".$PNo."' or Pid='".$IdNo."') and FirstEid<>'".trim(getFirstEid($FromEid))."' ".
							 "and SerialNo in (select serialno from PT_Outline where FormStatus in ('-2','0'))) and ".
							 "RecordStatus='0' and ((BeginDate<='".$m_start."' and Enddate>='".$m_start."') ".
							 "or (BeginDate<='".$m_end."' and Enddate>='".$m_end."') ".
							 "or (BeginDate<='".$m_start."' and Enddate>='".$m_end."') ".
							 "or (BeginDate>='".$m_start."' and Enddate<='".$m_end."'))";
					//return $strSQL2;
					$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
					$row2=$result2->fetchAll();
					if(count($row2)>0){
						$result2=$db->query($strSQL2) or die("Err:".$strSQL2);
						while($row2=$result2->fetch()){
							$bugtype=checkBugetTypeForRules($row2['BugNo']);
							if($bugtype!="科技部" && checkBugetTypeIFCivil($bugno)!="民間"){
								$count_YYMM+=(int)(trim($row2['TotalAmount']));
							}
						}
					}
					if(($count_YYMM+$BugTotalAmount)>$Limit){
						$BugTotalAmount+=$count_YYMM;
						$arr="兼任非科技部計畫,兼任報酬以每月總額".$Limit."元為限,".$yymm[$index][0].addLeadingZeros($yymm[$index][1],2).
							  "已申請".$BugTotalAmount."元(含此筆)!";
						return $arr;
					}
				}
			}
			$arr="ok";
		}else{$arr="ok";}
		return $arr;
	}
	//註10：民間委辦計畫(C)不受限==>依表列,併至註2,3,9中判斷

?>