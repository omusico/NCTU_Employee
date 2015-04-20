<?php 
	//功用:取得當時段 兼任人員基本資料,顯示並填入專任職稱/學生級別/學歷+兼任職稱options	
	//step1.檢查工號/學號/身份證號,取得兼任人員身份,在請核期間為職員/學生/校外人士
	//step2.寫回前頁
	include("connectSQL.php");
	include("function.php");
	
	$message="";
	$bugno=mb_strtoupper(filterEvil(trim($_GET["bugno"])));//使用的計畫代號
	$type=$_GET["type"];//是否可以追朔
	$IdCode=mb_strtoupper(filterEvil(trim($_GET["PNo"])));//使用者輸入的工號/學號/身份證號,若有英文字母,直接先轉為大寫
	$start_y=(string)((int)$_GET["start_y"]+1911);$start_m=$_GET["start_m"];$start_d=$_GET["start_d"];
	$end_y=(string)((int)$_GET["end_y"]+1911);$end_m=$_GET["end_m"];$end_d=$_GET["end_d"];
	
	$start=$start_y.addLeadingZeros($start_m,2).addLeadingZeros($start_d,2);
	$end=$end_y.addLeadingZeros($end_m,2).addLeadingZeros($end_d,2);
	
	//echo $bugno." ".$IdCode." ".$start." ".$end;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>
<?php
	$identity="";$empno="";$name="";$idno="";$title="";$titleStr="";$bug_type="";
	
	if(strlen($IdCode)>=8 && !checkIdno($IdCode) && !checkARC($IdCode)){//輸入長度超過8碼,不為工號或學號,但也不是身份證或居留證號
		$message="輸入的身份證或居留證號錯誤,請確認輸入正確";
		$identity="-1";
		echo $message;
	}
	
	if($identity!="-1"){
		$identity_data=checkIdentity($IdCode,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d);
		print_r($identity_data);
		$identity=$identity_data['identity'];
		$message=$identity_data['EmpError'].$identity_data['StuError'].$identity_data['OutError'];//應該只會有其中一種error
		$idno=$identity_data['IdNo'];
		$name=$identity_data['name'];
		//判斷是否職員
		if($identity_data['identity']=="E"){
			$empno=$identity_data['Empno'];
			$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] where (empno='".$identity_data['Empno']."' or idno='".$identity_data['IdNo']."')";
			$result=$db->query($strSQL);
			if($row=$result->fetch()){
				$title=trim($row['title']);
				$titleStr="<option value='".trim($row['title'])."'>".trim($row['title'])."</option>";
			}
		}else if($identity_data['identity']=="S"){
			$empno=$identity_data['StdNo'];
			$strSQL="select * from StudentData where [學籍之在學狀況] in ('應畢','在學') and (std_stdcode='".$identity_data['StdNo']."' or std_pid='".$identity_data['IdNo']."')";
			//echo $strSQL;
			$result=$db->query($strSQL);
			if($row=$result->fetch()){				
				$title=trim($row['std_degree']);					
				if($row['std_degree']=="1"){//如果是博士班的話,要加一個博班候選人
					$titleStr="<option value='".$row['std_degree']."'>".$stu_title[$row['std_degree']]."</option><option value='0'>".$stu_title["0"]."</option>";
				}else{
					$titleStr="<option value='".$row['std_degree']."'>".$stu_title[$row['std_degree']]."</option>";
				}
			}
		}else{
			$empno=$identity_data['IdNo'];
			$titleStr="";
			foreach($outer_code as $var)
			{
				echo $var."<br>";
				$titleStr.="<option value='".$var."'>".$outer_title[$var]."</option>";
			}
		}
		if($identity_data['identity']=="O" && strlen($identity_data['IdNo'])!=10){//不是輸入身份證或居留證,但比對不到人
			$message="查無此職員或學生,若為校外人士請輸入正確身份證或居留證號";
			$identity="-1";
			$titleStr="<option value=''></option>";
			$title="";
		}		
	}else{
		$titleStr="<option value=''></option>";
	}
	echo $titleStr;
?>
<script language="javascript">		
	//發出警語
	alert("發出警語");
	var msg="<?echo $message;?>";
	if(msg!=""){alert(msg);}
	var identity="<?echo $identity?>";
	var name="<?echo $name?>";
	var title="<?echo $title?>";
	//alert(identity);
	if(identity!="-1"){
		parent.document.getElementById('PT_Identity').value=identity;
		parent.document.getElementById('PNo').value="<?echo $empno?>";
		parent.document.getElementById('Pname').value="<?echo $name?>";
		parent.document.getElementById('IdNo').value="<?echo $idno?>";
		if(identity=="O" && name==""){//校外人士開放輸入姓名
			parent.document.getElementById('Pname').readOnly=false;
		}
	}else{//有錯誤訊息,清空畫面上有的資料
		parent.document.getElementById('PT_Identity').value="";
		parent.document.getElementById('PNo').value="";
		parent.document.getElementById('Pname').value="";
		parent.document.getElementById('IdNo').value="";
		parent.document.addPT.Ptitle.options.length=0;
		parent.document.addPT.payitem.options.length=0;
		parent.document.addPT.Prank.options.length=0;
	}
	
	if(identity!="-1"){
		//變更 "專任職稱/學生級別/學歷" 內的選項
		if(title=="1"){alert("若為博士班候選人,專任職稱/學生級別/學歷欄請選擇「博士班候選人」");}
		parent.document.getElementById('PrankOption').innerHTML="<select name='Prank' id='Prank'><?echo $titleStr?></select>";
		//初始化兼任職稱+支領項目(之後修改兼任助理,在getPTTitle.php中處理)
		parent.document.addPT.Ptitle.options.length=0;
		<?  $payitem="";$Ptitle="";
			$index=0;
			$bug_type=checkBugetTypeForPTtitle($bugno);
			if($identity!="-1"){//兼任職稱+支領項目先初始一遍(之後修改兼任助理,在getPTTitle.php中處理)
				if($identity=="E"){
					$PT_index=getWordOfEmpno($empno);
				}elseif($identity=="S"){$PT_index="Stu";}
				elseif($identity=="O"){$PT_index="Out";}
				$strSQL = "select * from PT_TitleMapping_Empno p ".
				  "left join title t on t.TitleCode=p.PT_titleCode ".
				  "where pre_empno='".$PT_index."' and buget_type='".$bug_type."'";	
				$result=$db->query($strSQL);
			
				if($row=$result->fetch()){//確認可以支領該種計畫,否則讀不到資料
					$payitem=$row['PayItem'];//記錄第一筆兼任職稱和支領項目做為預設
					$Ptitle=$row['TitleCode'];
					echo "parent.document.addPT.Ptitle.options[".$index."]=new Option(('".trim($row['TitleName'])."'), ('".trim($row['TitleCode'])."'), false, true);";
					$index++;
					while($row=$result->fetch()){
						echo "parent.document.addPT.Ptitle.options[".$index."]=new Option(('".trim($row['TitleName'])."'), ('".trim($row['TitleCode'])."'), false, false);";
						$index++;
					}
				}else{?>
					parent.document.getElementById('PT_Identity').value="";
					parent.document.getElementById('PNo').value="";
					parent.document.getElementById('Pname').value="";
					parent.document.getElementById('IdNo').value="";
					parent.document.addPT.Prank.options.length=0;
					alert("該職員無法請核 <?echo $bug_type?> 類計畫!!");
			<?	}
			}	?>
		parent.document.addPT.payitem.options.length=0;
		<?	
			if($payitem!=""){//無法支領該種計畫的情況
				$index=0;
				$tok = strtok($payitem, ",");
				while ($tok !== false) {
					$strSQL = "select * from [SALARYDB].[工作費資料庫].dbo.vw_PartTime_Rule where parttime='1' and SerialNo='".$tok."'";	
					$result=$db->query($strSQL);
					$row=$result->fetch();
					echo "parent.document.addPT.payitem.options[".$index."]=new Option(('".trim($row['JobItem_1'])."'), ('".trim($row['SerialNo'])."'), false, false);";
					$tok = strtok(",");$index++;
				}
				//控制支領類別的使用
				if($Ptitle=="4"){//4 為臨時工
					echo "parent.document.getElementById('hr_pay').disabled=false;";
					echo "parent.document.getElementById('hr_pay_unit').readOnly =false;";
					echo "parent.document.getElementById('hr_pay_limit').readOnly =false;";
					echo "parent.document.getElementById('hr_pay_unit').value = 0;";
					echo "parent.document.getElementById('hr_pay_limit').value = 0;";
					
					echo "parent.document.getElementById('case_pay').disabled=false;";
					echo "parent.document.getElementById('case_pay_unit').readOnly =false;";
					echo "parent.document.getElementById('case_pay_limit').readOnly =false;";
					echo "parent.document.getElementById('case_pay_unit').value =0;";
					echo "parent.document.getElementById('case_pay_limit').value =0;";
					
					echo "parent.document.getElementById('day_pay').disabled=false;";
					echo "parent.document.getElementById('day_pay_unit').readOnly =false;";
					echo "parent.document.getElementById('day_pay_limit').readOnly =false;";
					echo "parent.document.getElementById('day_pay_unit').value =0;";
					echo "parent.document.getElementById('day_pay_limit').value =0;";
					
					echo "parent.document.getElementById('award_pay').disabled=true;";
					echo "parent.document.getElementById('award_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('award_pay_limit').readOnly =true;";
					echo "parent.document.getElementById('award_pay_unit').value =2000;";
					echo "parent.document.getElementById('award_pay_limit').value =0;";
					
					echo "parent.document.getElementById('month_pay').disabled=true;";
					echo "parent.document.getElementById('month_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('month_pay_unit').value =0;";
					
					echo "parent.document.getElementById('totalpay').value=0;";
				}else{//臨時工外,其他都一樣
					echo "parent.document.getElementById('hr_pay').disabled=true;";
					echo "parent.document.getElementById('hr_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('hr_pay_limit').readOnly =true;";
					echo "parent.document.getElementById('hr_pay_unit').value =0;";
					echo "parent.document.getElementById('hr_pay_limit').value =0;";
					
					echo "parent.document.getElementById('case_pay').disabled=true;";
					echo "parent.document.getElementById('case_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('case_pay_limit').readOnly =true;";
					echo "parent.document.getElementById('case_pay_unit').value =0;";
					echo "parent.document.getElementById('case_pay_limit').value =0;";
					
					echo "parent.document.getElementById('day_pay').disabled=true;";
					echo "parent.document.getElementById('day_pay_unit').readOnly =true;";
					echo "parent.document.getElementById('day_pay_limit').readOnly =true;";
					echo "parent.document.getElementById('day_pay_unit').value =0;";
					echo "parent.document.getElementById('day_pay_limit').value =0;";
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
					
					echo "parent.document.getElementById('month_pay_unit').value =0;";
					
					echo "parent.document.getElementById('totalpay').value=0;";
				}
			}
		?>
	}
</script>
<body>
</body>
</html>
