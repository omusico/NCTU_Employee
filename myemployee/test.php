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

</head>
<?
	include("function.php");
	include("connectSQL.php");
	
	//if(checkARC("FC03030008"))echo "real arc";
	//else echo "wrong arc";
	//$start="2014-01-01";
	//$end="2015-01-31";
	//$str_sec = explode("-",$start);
	//print_r($str_sec);
	$IdCode="0353957";
	//$IdCode="H0122";
	$start_y="2015";
	$start_m="04";
	$start_d="012";
	$end_y="2015";
	$end_m="04";
	$end_d="12";
	
	echo "<pre>".print_r(checkIdentity($IdCode,$start_y,$start_m,$start_d,$end_y,$end_m,$end_d))."</pre>";
	//if()
	//echo print_r($stu_code);
	//echo print_r($stu_title);
	//print_r(getCanTransformPeriod("207"));
	//include('C:/Apache2.2/htdocs/parttime_employ_new/PHPMailer/class.phpmailer.php');		
	/*$msg=$msg."敬啟者，您好：<br/><br/>[請勿直接回覆本信：系統每週自動通知計畫主持人與承辦人經費請核之狀態]<br/>";
		$msg=$msg."[本功能依<a href='http://secretariat.nctu.edu.tw/FCKeditor_upload/file/MeetingMinutes_download/adm/101/adm101-23.pdf'>103年9月2日第132次行政會議紀錄</a>執行]<br/><br/>";
		$msg=$msg."您曾經請核：<br/><br/>";
		$msg=$msg."<table border='1' style='border-collapse:collapse;' borderColor='black'><tr><th>經費編號</th><th>經費名稱</th><th>人員</th><th>請核期間</th><th>兼任職稱</th><th>未申請工作費年月</th></tr>".
			"<tr><td>103B530</td><td>103年度教育部智慧電子整...</td><td>0115343 江如茵</td><td>1031001-1031031<br>1040101-1040131</td>".
			"<td>兼任助理</td><td>10310</td></tr>".
			"<tr><td>103B530</td><td>103年度教育部智慧電子整...</td><td>E120622847 顏清輝</td><td>1031101-1040215</td>".
			"<td>臨時工 </td><td>10311</td></tr>";
		$msg=$msg."</table>";
		$msg=$msg."迄今已逾20天（計畫主持人30天）尚未造冊，請儘速至工作費請款系統辦理請款，".
				  "若該月份工作費需取消或金額需變更，請務必辦理兼任請核異動，避免影響渠等權益。<br><br>".

				 "業務聯絡人資訊：<br>".
				 "請洽人事室廖勇智，分機52241；林家慧，分機52233";

		
		$fromName = "兼任請核系統";
		//$fromName = iconv('UTF-8','Big5//IGNORE',trim($fromName));
		$subject="兼任請核系統-申請單超過20天未申請工作費-計畫承辦人通知-F9707"; //信件標題*/
		/*
		$msg=$msg."敬啟者，您好：<br/><br/>[請勿直接回覆本信：系統每週自動通知計畫主持人與承辦人經費請核之狀態]<br/>";
		$msg=$msg."[本功能依<a href='http://secretariat.nctu.edu.tw/FCKeditor_upload/file/MeetingMinutes_download/adm/101/adm101-23.pdf'>103年9月2日第132次行政會議紀錄</a>執行]<br/><br/>";
		$msg=$msg."您曾經請核：<br/><br/>";
		$msg=$msg."<table border='1' style='border-collapse:collapse;' borderColor='black'><tr><th>經費編號</th><th>經費名稱</th><th>人員</th><th>請核期間</th><th>兼任職稱</th></tr>".
			"<tr><td>103B530</td><td>103年度教育部智慧電子整...</td><td>0115343 江如茵</td><td>1031001-1031031<br>1040101-1040131</td>".
			"<td>兼任助理</td></tr>";
		$msg=$msg."</table>";
		$msg=$msg."該生學籍資料狀態已變更為[應屆畢業]，請於離校日或當年度學期結束前一日辦理聘期或身分異動，避免影響渠等權益。<br><br>".

				 "業務聯絡人資訊：<br>".
				 "請洽人事室廖勇智，分機52241；林家慧，分機52233";

		
		$fromName = "兼任請核系統";
		//$fromName = iconv('UTF-8','Big5//IGNORE',trim($fromName));
		//$subject="兼任請核系統-申請單超過20天未申請工作費-計畫承辦人通知-F9707"; //信件標題
		$subject="兼任請核系統-學生狀態變更通知-計畫承辦人通知-F9707"; //信件標題
		
		$mail = new PHPMailer();
		$mail->From = "noreply@nctu.edu.tw";
		$mail->FromName = $fromName;
		$mail->Host = "smtp.cc.NCTU.edu.tw";  //mail server
		$mail->Mailer = "smtp";
		$mail->Subject = $subject;
		$mail->CharSet = 'utf-8';
		$mail->IsHTML(true);
		$mail->Body = $msg;
		$mail->AddAddress('wslee@mail.nctu.edu.tw','李維順');

		try{
			$mail->Send();
		}catch (phpmailerException $e) {}
		*/

?>
<script language="javascript">
var paytype=getPayTypes("科技部","3");
var str="";
for(var i=0;i<paytype.length;i++){
	str+=paytype[i]+",";
}
//alert(str);

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
</script>