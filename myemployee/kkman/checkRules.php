<?
	include("connectSQL.php");
	include("function.php");
	include("Rules.php");
	require_once ('JSON.php');
	
	$j = new Services_JSON();
	$return_data = array();
	
	$bugno=filterEvil($_GET['bugno']);
	$start=filterEvil($_GET['start']);
	$end=filterEvil($_GET['end']);
	$PNo=filterEvil($_GET['PNo']);
	$IdNo=filterEvil($_GET['IdNo']);
	$identity=filterEvil($_GET['identity']);
	$Prank=filterEvil($_GET['Prank']);
	$Ptitle=filterEvil($_GET['Ptitle']);
	$PayTypeStr=filterEvil($_GET['PayTypeStr']);
	$Pay_unit=filterEvil($_GET['Pay_unit']);
	$Pay_limit=filterEvil($_GET['Pay_limit']);
	$totalamount=filterEvil($_GET['PayTotal']);
	$payitem=filterEvil($_GET['payitem']);
	
	$start_y=(substr($start,0,strlen($start)-4))+1911;
	$start_m=substr($start,strlen($start)-4,2);
	$start_d=substr($start,strlen($start)-2,2);
	$cstart=$start_y."-".$start_m."-".$start_d;
	
	$end_y=(substr($end,0,strlen($end)-4))+1911;
	$end_m=substr($end,strlen($end)-4,2);
	$end_d=substr($end,strlen($end)-2,2);
	$cend=$end_y."-".$end_m."-".$end_d;
	//共同規則,同一計畫，同一時段內均不得再兼任支領，所有頂尖大學計畫(E、W)視為同一計畫
	$result=Rules_noduplicate($bugno,$PNo,$cstart,$cend,$Ptitle,$PayTypeStr,$pay_unit);
	$return_data[0]=$result;
	
	$return_index=1;
	
	//整理目前已有之身份,condition[$index][0]=bugno(公務+約用=nctu),condition[$index][1]=計畫類別(公務+約用=nctu),condition[$index][2]=公務/約用/專任/兼任/
	//condition[$index][3]=兼任職稱,第一筆為目前請核資料
	//首先是目前身份
	$index=1;
	$condition = array();
	if($identity=="E"){
		$str_TopEmp=getWordOfEmpno($PNo);
		if($str_TopEmp=="S" || $str_TopEmp=="L" || $str_TopEmp=="P"){//公務人員
			$condition[$index][0]="nctu";
			$condition[$index][1]="nctu";
			$condition[$index][2]="公務";
			$condition[$index][3]="";
		}else if($str_TopEmp=="B" || $str_TopEmp=="D" || $str_TopEmp=="E" || $str_TopEmp=="F" || $str_TopEmp=="G" || $str_TopEmp=="X" || $str_TopEmp=="XB"){//約用人員
			$condition[$index][0]="nctu";
			$condition[$index][1]="nctu";
			$condition[$index][2]="約用";
			$condition[$index][3]="";
		}else{
			$strSQL="select distinct ExpenseSourceCode as bugno from [PERSONDBOLD].[personnelcommon].[dbo].[view_workfee_ExpenseSource_buget] ".
					"where (empno='".$PNo."' or IdNo='".$IdNo."') ".
					"and ((BeginDate<='".$cstart."' and Enddate>='".$cstart."') ".
					"or (BeginDate<='".$cend."' and Enddate>='".$cend."') ".
					"or (BeginDate<='".$cstart."' and Enddate>='".$cend."') ".
					"or (BeginDate>='".$cstart."' and Enddate<='".$cend."'))";
			$result=$db->query($strSQL);
			if($result && $row=$result->fetch()){
				$condition[$index][0]=trim($row['bugno']);
				$condition[$index][1]=checkBugetTypeForRules(trim($row['bugno']));
				$condition[$index][2]="專任";
				$condition[$index][3]="";
				while($row=$result->fetch()){
					$index++;
					$condition[$index][0]=trim($row['bugno']);
					$condition[$index][1]=checkBugetTypeForRules(trim($row['bugno']));
					$condition[$index][2]="專任";
					$condition[$index][3]="";
				}
			}
		}
	}else if($identity!="E"){//校外人士和學生,都直接納入即可
		$condition[$index][0]=$bugno;
		$condition[$index][1]=checkBugetTypeForRules($bugno);
		$condition[$index][2]="兼任";
		$condition[$index][3]=$Ptitle;
	}	
	
	//其他已請核身份從請核資料抓
	$strSQL="select distinct p1.BugNo,p1.SerialNo,p2.PTtitle from PT_Outline p1 ".
			"left join PT_Employed p2 on p1.SerialNo=p2.SerialNo and (p2.IdCode='".$PNo."' or p2.Pid='".$IdNo."') and p2.RecordStatus='0' ".
			"where FormStatus='1' and ((BeginDate<='".$cstart."' and Enddate>='".$cstart."') ".
			"or (BeginDate<='".$cend."' and Enddate>='".$cend."') ".
			"or (BeginDate<='".$cstart."' and Enddate>='".$cend."') ".
			"or (BeginDate>='".$cstart."' and Enddate<='".$cend."'))";
	$result=$db->query($strSQL);
	if($result && $row=$result->fetch()){
		$index++;
		$condition[$index][0]=trim($row['BugNo']);
		$condition[$index][1]=checkBugetTypeForRules(trim($row['BugNo']));
		$condition[$index][2]="兼任";
		$condition[$index][3]=trim($row['PTtitle']);
		while($row=$result->fetch()){
			$index++;
			$condition[$index][0]=trim($row['BugNo']);
			$condition[$index][1]=checkBugetTypeForRules(trim($row['BugNo']));
			$condition[$index][2]="兼任";
			$condition[$index][3]=trim($row['PTtitle']);
		}
	}
		
	foreach($condition as $temp){
		$bugType=checkBugetTypeForRules($bugno);//這次請核計畫是那一種類別
		if($temp[2]=="公務"){			
			if($bugType=="科技部" && $Ptitle!="4"){
				$return_data[$return_index++]=Rules_EmpAmountLimit_PS06($bugno,$PNo,$IdNo,$identity,$Prank,$Ptitle,$cstart,$cend,$totalamount);
			}else{
				$return_data[$return_index++]="公務人員不得兼任此類計畫";
			}
		}else if($temp[2]=="約用"){
			if($bugType=="教育部" && $Ptitle!="4"){
				$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
				$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
				$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
			}else if($bugType=="教育部" && $Ptitle=="4"){
				$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
				$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
			}else if($bugType=="其他"){//其他類兼任人員和臨時工規則一樣
				$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
			}else{
				$return_data[$return_index++]="約用人員不得兼任此類計畫";
			}
		}else if($temp[2]=="專任"){
			if($temp[1]=="教育部"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduAndOthers_AmountLimit_PS01($PNo,$IdNo,$identity,$bugno,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_EduAndOthers_AmountLimit_PS01($PNo,$IdNo,$identity,$bugno,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="其他"){//其他類兼任人員和臨時工規則一樣
					$return_data[$return_index++]=Rules_EduAndOthers_AmountLimit_PS01($PNo,$IdNo,$identity,$bugno,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
				}else{
					$return_data[$return_index++]="教育部專任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="科技部"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="其他"){//其他類兼任人員和臨時工規則一樣
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
				}else{
					$return_data[$return_index++]="科技部專任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="其他"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_EmpLimit_PS08($bugno,$PNo,$IdNo,$identity,$Ptitle);
				}else if($bugType=="其他"){//其他類兼任人員和臨時工規則一樣
					$return_data[$return_index++]=Rules_EmpAmountLimit_PS05($bugno,$PNo,$IdNo,$Prank,$payitem,$identity,$cstart,$cend,$totalamount);
				}else{
					$return_data[$return_index++]="其他類計畫專任人員不得兼任此類計畫";
				}
			}
		}else{//處理多重兼任身份
			if($temp[1]=="教育部" && $temp[3]!="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="教育部計畫兼任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="教育部" && $temp[3]=="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="教育部計畫兼任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="科技部" && $temp[3]!="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="科技部計畫兼任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="科技部" && $temp[3]=="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="科技部計畫兼任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="其他" && $temp[3]!="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount);
					$return_data[$return_index++]=Rules_StuLimit_PS09($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="科技部計畫兼任人員不得兼任此類計畫";
				}
			}else if($temp[1]=="科技部" && $temp[3]=="4"){
				if($bugType=="教育部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_SingleApplyLimit_PS04($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="教育部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="科技部" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_EduStu_AmountLimit_PS02($bugno,$PNo,$IdNo,$identity,$Prank,$cstart,$cend,$totalamount);
				}else if($bugType=="科技部" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else if($bugType=="其他" && $Ptitle!="4"){
					$return_data[$return_index++]=Rules_Stu_SingleApplyLimit_PS03($bugno,$identity,$Prank,$totalamount);
				}else if($bugType=="其他" && $Ptitle=="4"){
					$return_data[$return_index++]=Rules_StuLimit_PS07($bugno,$PNo,$IdNo,$identity,$Ptitle,$PayTypeStr,$Pay_limit,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
				}else{
					$return_data[$return_index++]="科技部計畫兼任人員不得兼任此類計畫";
				}
			}
		}
	}
	$return_data['number']=$return_index;
	
	$jsonString = $j->encode($return_data); 
	echo $jsonString;
	exit;
?>
