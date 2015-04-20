<?php
	include("../../connectSQL.php");
?>
<html>
<head>
	<style>
	</style>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>交大計畫人員工作費系統 - 帳號申請表</title>
<body>

<script language="Javascript">

function printScreen(block){
	var value = block.innerHTML;
	var printPage = window.open("","printPage","");
	printPage.document.open();
	printPage.document.write("<HTML><head></head><BODY onload='window.print();window.close()'>");
	printPage.document.write("<PRE>");
	printPage.document.write(value);
	printPage.document.write("</PRE>");
	printPage.document.close("</BODY></HTML>");
}
</Script>

<div id="block">
<?php
	//確認資料是否有重送
		
	$HostUserAccount = filterEvil($_SESSION["HostUserAccount"]);
	$strSQL = "sp_qry_UserMainPersonnel '".$HostUserAccount."','','1','2'";
	//$strSQL = "select * from UserMain u, Personnel p where u.UserAccount='".$HostUserAccount."'  and u.UserAccount=p.EmpNo ";
	$rsHost = mssql_query($strSQL);
	$rsHost = mssql_fetch_array($rsHost);
	If(!empty($rsHost))
	{
		$strmail = "select * from email where UserAccount='".$HostUserAccount."'";
		$rsmail = mssql_query($strmail);
		$rsmail = mssql_fetch_array($rsmail);
		If(!empty($rsmail))	$Email  = $rsmail["email"];
		Else $Email  = "查無Email資料";

?>
		<center>
		<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'>
		<tr style='height:20.65pt'>
			<td width=476 colspan=3 valign=top style='width:357.2pt;border:solid windowtext 1.0pt;
				padding:0cm 5.4pt 0cm 5.4pt;height:20.65pt'>
				<p class=MsoNormal style='margin-top:9.0pt;mso-para-margin-top:.5gd'>
				<h3 style="font-family:'標楷體' "><center>計畫人員薪資/工作費系統 - 帳號申請表</center></h3>
			</td>
		</tr>
		<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
			<td valign=center rowspan='2' style='width:150pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				<b>計畫主持人	： <u><?php echo $HostUserAccount;?></u></b>
			</td>
			<td valign=top style='width:100pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				姓名：<u><?php echo $rsHost["Name"];?></u>	
			</td>
			<td valign=top style='width:100pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				分機：<u><?php echo $rsHost["tel"];?></u>
			</td>
		</tr>
		<tr style='height:20.65pt'>
			<td valign=top colspan='2' style='width:200pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' nowrap> 	
				E-mail：<u><?php echo trim($Email);?></u>
			</td>
		</tr>	
	<?php
		$myYear=date("Y")-1911;
		$myMonth=date("m");
		$myDay=date("d");
		//$modify_info=explode("-",filterEvil($_GET['modify_info'])); //有修改計畫編號
		//$modify_count=count($modify_info); //資料數
		$i=0;
		$myDate = $myYear.$myMonth.$myDay;
		
		$strSQL = "select bugetno from vi_buget where (leaderid='".$HostUserAccount."') and  (cast(deadline as integer)>= '".$myDate."') AND (cast(start as integer) <= '".$myDate."')";	
		$rsPlan1 = mssql_query($strSQL);
		while($rsPlan = mssql_fetch_array($rsPlan1))
		{
			//只顯示修改的資料
			//if($i<$modify_count && !strcmp(Trim($rsPlan["bugetno"]),$modify_info[$i]))
			//{
			$i++;
			$strSQL = "select op.UserAccount, e.email, p.Name from Personnel p, email e, Officer_Project op where op.UserAccount=p.EmpNo and e.UserAccount=op.UserAccount and op.BugetNo='".Trim($rsPlan["bugetno"])."' and op.Permission='V'";
			$rsUser1 = mssql_query($strSQL);
			//$rsUser = mssql_fetch_array($rsUser);
			
			//If(!empty($rsUser))
			//{
			while($rsUser = mssql_fetch_array($rsUser1)){
				$OfficerID= $rsUser["UserAccount"];
				$OfficerName=$rsUser["Name"];
				$OfficerEmail =$rsUser["email"];
			
	?>		
				<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
					<td valign=center rowspan='2' style='width:150pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 		  
						<b>計畫編號：<?php echo $rsPlan["bugetno"];?></b>
					</td>
					<td valign=top style='width:100pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						承辦人︰<?php echo $OfficerID;?>
					</td>
					<td valign=top style='width:100pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						姓名︰<?php echo $OfficerName;?>
					</td>
				</tr>
				<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
					<td valign=top colspan='2' style='width:200pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						E-mail：<?php echo $OfficerEmail;?>
					</td>
				</tr>
	<?php 
			}
			//}
		}//while 
		?>

		<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
			<td valign=top colspan='3' style='width:100pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
				計畫主持人簽章：<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><p>
			</td>
		</tr>
		<tr>
			<td colspan='3'>
				<font size='2'>※簽章後，請將此申請表公文傳遞至出納組</font><br>
				<font size='2'>
				※出納組審核後將會E-mail通知，屆時即可登入本系統</font>
			</td>
		</tr>
		
		</div>
		</table>
		<div id="printbtn1" style='display'><input name=idPrint type=button value="列印本頁" onclick="printScreen(block)"><!--<input name=BackIndex type=button value="回首頁" onclick="location.href='/Salary/index.asp'">--></div>
		
		</center>
<?php
    }
	Else
	{
		echo  $strSQL;
		echo  "輸入資訊錯誤，請重新申請！";
	}
?>  	
</body>
</html>