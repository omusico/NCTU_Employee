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
<title>��j�p�e�H���u�@�O�t�� - �b���ӽЪ�</title>
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
	//�T�{��ƬO�_�����e
		
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
		Else $Email  = "�d�LEmail���";

?>
		<center>
		<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'>
		<tr style='height:20.65pt'>
			<td width=476 colspan=3 valign=top style='width:357.2pt;border:solid windowtext 1.0pt;
				padding:0cm 5.4pt 0cm 5.4pt;height:20.65pt'>
				<p class=MsoNormal style='margin-top:9.0pt;mso-para-margin-top:.5gd'>
				<h3 style="font-family:'�з���' "><center>�p�e�H���~��/�u�@�O�t�� - �b���ӽЪ�</center></h3>
			</td>
		</tr>
		<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
			<td valign=center rowspan='2' style='width:150pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				<b>�p�e�D���H	�G <u><?php echo $HostUserAccount;?></u></b>
			</td>
			<td valign=top style='width:100pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				�m�W�G<u><?php echo $rsHost["Name"];?></u>	
			</td>
			<td valign=top style='width:100pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' > 	
				�����G<u><?php echo $rsHost["tel"];?></u>
			</td>
		</tr>
		<tr style='height:20.65pt'>
			<td valign=top colspan='2' style='width:200pt;border:solid windowtext 1.0pt;
				mso-border-alt:solid windowtext .5pt;padding:0.3cm 10.4pt 0.3cm 10.4pt' nowrap> 	
				E-mail�G<u><?php echo trim($Email);?></u>
			</td>
		</tr>	
	<?php
		$myYear=date("Y")-1911;
		$myMonth=date("m");
		$myDay=date("d");
		//$modify_info=explode("-",filterEvil($_GET['modify_info'])); //���ק�p�e�s��
		//$modify_count=count($modify_info); //��Ƽ�
		$i=0;
		$myDate = $myYear.$myMonth.$myDay;
		
		$strSQL = "select bugetno from vi_buget where (leaderid='".$HostUserAccount."') and  (cast(deadline as integer)>= '".$myDate."') AND (cast(start as integer) <= '".$myDate."')";	
		$rsPlan1 = mssql_query($strSQL);
		while($rsPlan = mssql_fetch_array($rsPlan1))
		{
			//�u��ܭק諸���
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
						<b>�p�e�s���G<?php echo $rsPlan["bugetno"];?></b>
					</td>
					<td valign=top style='width:100pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						�ӿ�H�J<?php echo $OfficerID;?>
					</td>
					<td valign=top style='width:100pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						�m�W�J<?php echo $OfficerName;?>
					</td>
				</tr>
				<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
					<td valign=top colspan='2' style='width:200pt;border:solid windowtext 1.0pt;mso-border-alt:solid windowtext .5pt;padding:0.2cm 10.4pt 0.2cm 10.4pt' > 	
						E-mail�G<?php echo $OfficerEmail;?>
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
				�p�e�D���Hñ���G<br>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><p>
			</td>
		</tr>
		<tr>
			<td colspan='3'>
				<font size='2'>��ñ����A�бN���ӽЪ���ǻ��ܥX�ǲ�</font><br>
				<font size='2'>
				���X�ǲռf�֫�N�|E-mail�q���A���ɧY�i�n�J���t��</font>
			</td>
		</tr>
		
		</div>
		</table>
		<div id="printbtn1" style='display'><input name=idPrint type=button value="�C�L����" onclick="printScreen(block)"><!--<input name=BackIndex type=button value="�^����" onclick="location.href='/Salary/index.asp'">--></div>
		
		</center>
<?php
    }
	Else
	{
		echo  $strSQL;
		echo  "��J��T���~�A�Э��s�ӽСI";
	}
?>  	
</body>
</html>