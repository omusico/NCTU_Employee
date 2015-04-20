<?php 
	//功用:依計畫種類和人員身份,抓出可選擇的兼任身份和支領項目,並控制支領類別
	//
	include("connectSQL.php");
	include("function.php");
	
	$message="";
	$bugno=mb_strtoupper(filterEvil(trim($_GET["bugno"])));//使用的計畫代號
	$empno=$_GET["PNo"];
	$identity=$_GET["identity"];
	$Ptitle=$_GET["Ptitle"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>
<?php
	$bug_type=checkBugetTypeForPTtitle($bugno);
	if($identity=="E"){
		$PT_index=getWordOfEmpno($empno);
	}elseif($identity=="S"){$PT_index="Stu";}
	elseif($identity=="O"){$PT_index="Out";}
	$strSQL = "select * from PT_TitleMapping_Empno p ".
			  "left join title t on t.TitleCode=p.PT_titleCode ".
			  "where pre_empno='".$PT_index."' and buget_type='".$bug_type."' and TitleCode='".$Ptitle."'";	
	//echo $strSQL;
	$result=$db->query($strSQL);
	$row=$result->fetch();
	$payitem=$row['PayItem'];
	$PTrole=$row['TitleCode'];
?>
<script language="javascript">		
	parent.document.addPT.payitem.options.length=0;
	//console.log("<?echo $bugno." ".$empno." ".$identity." ".$Ptitle." ".$strSQL?>");
	//alert("<?echo $payitem?>");
	<?	
		if($payitem!=""){//無法支領該種計畫的情況
			$index=0;
			$tok = strtok($payitem, ",");
			while ($tok != false) {
				$strSQL = "select * from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1' and SerialNo='".$tok."'";	
				//echo "console.log(\"".$strSQL."\")";
				$result=$db->query($strSQL);
				$row=$result->fetch();
				echo "parent.document.addPT.payitem.options[".$index."]=new Option(('".trim($row['JobItem_1'])."'), ('".trim($row['SerialNo'])."'), false, false);";
				$tok = strtok(",");$index++;
			}
			//控制支領類別的使用
			//20150209改以paytype_mapping內的記錄為準
			$strSQL="select * from paytype_mapping where TitleCode='".$Ptitle."' and (Plan_Type='".$bug_type."' or Plan_Type='all-others') order by priority asc";
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){
				//有資料的話取第一筆
				$result=$db->query($strSQL);
				$row=$result->fetch();
				$is_hr=trim($row['hr_pay']);
				$is_day=trim($row['day_pay']);
				$is_case=trim($row['case_pay']);
				$is_award=trim($row['award_pay']);
				$is_month=trim($row['month_pay']);
			}else{
				$is_hr="0";
				$is_day="0";
				$is_case="0";
				$is_award="0";
				$is_month="0";
			}
			if($is_hr=="1"){
				echo "parent.document.getElementById('hr_pay').checked=false;";
				echo "parent.document.getElementById('hr_pay').disabled=false;";
				echo "parent.document.getElementById('hr_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('hr_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('hr_pay_unit').value = 0;";
				echo "parent.document.getElementById('hr_pay_limit').value = 0;";
			}else{
				echo "parent.document.getElementById('hr_pay').checked=false;";
				echo "parent.document.getElementById('hr_pay').disabled=true;";
				echo "parent.document.getElementById('hr_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('hr_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('hr_pay_unit').value =0;";
				echo "parent.document.getElementById('hr_pay_limit').value =0;";
			}
			if($is_day=="1"){
				echo "parent.document.getElementById('day_pay').checked=false;";
				echo "parent.document.getElementById('day_pay').disabled=false;";
				echo "parent.document.getElementById('day_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('day_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('day_pay_unit').value =0;";
				echo "parent.document.getElementById('day_pay_limit').value =0;";
			}else{
				echo "parent.document.getElementById('day_pay').checked=false;";
				echo "parent.document.getElementById('day_pay').disabled=true;";
				echo "parent.document.getElementById('day_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('day_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('day_pay_unit').value =0;";
				echo "parent.document.getElementById('day_pay_limit').value =0;";
			}
			if($is_case=="1"){
				echo "parent.document.getElementById('case_pay').checked=false;";
				echo "parent.document.getElementById('case_pay').disabled=false;";
				echo "parent.document.getElementById('case_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('case_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('case_pay_unit').value =0;";
				echo "parent.document.getElementById('case_pay_limit').value =0;";
			}else{
				echo "parent.document.getElementById('case_pay').checked=false;";
				echo "parent.document.getElementById('case_pay').disabled=true;";
				echo "parent.document.getElementById('case_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('case_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('case_pay_unit').value =0;";
				echo "parent.document.getElementById('case_pay_limit').value =0;";
			}
			if($is_award=="1"){
				echo "parent.document.getElementById('award_pay').checked=false;";
				echo "parent.document.getElementById('award_pay').disabled=false;";
				echo "parent.document.getElementById('award_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('award_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('award_pay_unit').value =2000;";
				echo "parent.document.getElementById('award_pay_limit').value =0;";
			}else{
				echo "parent.document.getElementById('award_pay').checked=false;";
				echo "parent.document.getElementById('award_pay').disabled=true;";
				echo "parent.document.getElementById('award_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('award_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('award_pay_unit').value =2000;";
				echo "parent.document.getElementById('award_pay_limit').value =0;";
			}
			if($is_month=="1"){
				echo "parent.document.getElementById('month_pay').checked=false;";
				echo "parent.document.getElementById('month_pay').disabled=false;";
				echo "parent.document.getElementById('month_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('month_pay_unit').value =0;";
			}else{
				echo "parent.document.getElementById('month_pay').checked=false;";
				echo "parent.document.getElementById('month_pay').disabled=true;";
				echo "parent.document.getElementById('month_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('month_pay_unit').value =0;";
			}				
			echo "parent.document.getElementById('totalpay').value=0;";
			/*if($PTrole=="4"){//4 為臨時工
				echo "parent.document.getElementById('hr_pay').disabled=false;";
				echo "parent.document.getElementById('hr_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('hr_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('hr_pay_unit').value = 0;";
				echo "parent.document.getElementById('hr_pay_limit').value = 0;";
				
				echo "parent.document.getElementById('case_pay').disabled=false;";
				echo "parent.document.getElementById('case_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('case_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('case_pay_unit').value = 0;";
				echo "parent.document.getElementById('case_pay_limit').value = 0;";
				
				echo "parent.document.getElementById('day_pay').disabled=false;";
				echo "parent.document.getElementById('day_pay_unit').readOnly =false;";
				echo "parent.document.getElementById('day_pay_limit').readOnly =false;";
				echo "parent.document.getElementById('day_pay_unit').value = 0;";
				echo "parent.document.getElementById('day_pay_limit').value = 0;";
				
				echo "parent.document.getElementById('award_pay').checked=false;";
				echo "parent.document.getElementById('award_pay').disabled=true;";
				echo "parent.document.getElementById('award_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('award_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('award_pay_unit').value =2000;";
				echo "parent.document.getElementById('award_pay_limit').value =0;";
				
				echo "parent.document.getElementById('month_pay').checked=false;";
				echo "parent.document.getElementById('month_pay').disabled=true;";
				echo "parent.document.getElementById('month_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('month_pay_unit').value = 0;";
				
				echo "parent.document.getElementById('totalpay').value = 0;";
			}else{//臨時工外,其他都一樣
				echo "parent.document.getElementById('hr_pay').checked=false;";
				echo "parent.document.getElementById('hr_pay').disabled=true;";
				echo "parent.document.getElementById('hr_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('hr_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('hr_pay_unit').value = 0;";
				echo "parent.document.getElementById('hr_pay_limit').value = 0;";
				
				echo "parent.document.getElementById('case_pay').checked=false;";
				echo "parent.document.getElementById('case_pay').disabled=true;";
				echo "parent.document.getElementById('case_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('case_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('case_pay_unit').value = 0;";
				echo "parent.document.getElementById('case_pay_limit').value = 0;";
				
				echo "parent.document.getElementById('day_pay').checked=false;";
				echo "parent.document.getElementById('day_pay').disabled=true;";
				echo "parent.document.getElementById('day_pay_unit').readOnly =true;";
				echo "parent.document.getElementById('day_pay_limit').readOnly =true;";
				echo "parent.document.getElementById('day_pay_unit').value = 0;";
				echo "parent.document.getElementById('day_pay_limit').value = 0;";
				//科技部 助教級、講師級僅能選月薪
				if($bug_type!="科技部" || ($Ptitle!="13" && $Ptitle!="14")){				
					echo "parent.document.getElementById('award_pay').disabled=false;";
					echo "parent.document.getElementById('award_pay_unit').readOnly =false;";
					echo "parent.document.getElementById('award_pay_limit').readOnly =false;";
				}else{
					echo "parent.document.getElementById('award_pay').disabled=true;";
					echo "parent.document.getElementById('award_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('award_pay_limit').readOnly =true;";
				}	
				echo "parent.document.getElementById('award_pay_unit').value =2000;";
				echo "parent.document.getElementById('award_pay_limit').value =0;";
				
				//科技部兼任助理支領類別僅能選獎助單元
				if($bug_type!="科技部" || $Ptitle!="3"){				
					echo "parent.document.getElementById('month_pay').disabled=false;";
					echo "parent.document.getElementById('month_pay_unit').readOnly =false;";
				}else{
					echo "parent.document.getElementById('month_pay').disabled=true;";
					echo "parent.document.getElementById('month_pay_unit').readOnly = true;";
				}
				echo "parent.document.getElementById('month_pay_unit').value = 0;";
				
				echo "parent.document.getElementById('totalpay').value = 0;";
			}*/
		}
		
	?>
</script>
<body>
</body>
</html>
