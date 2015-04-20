<html>
<head>
<title>申請計畫主持人帳號</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
</head>
<script language="JavaScript">
<!--
	function myCheck(){
			if (form1.UserAccount.value == "") {window.alert("請輸入主持人人事代號");	return false;}
			if (form1.UserID.value == "") {window.alert("請輸入主持人身份證字號");	return false;}
			form1.NewApply.value="confirm"
			form1.submit();
	}			  		
-->
</script>
<body>
<form name='form1' action='NewAss.php' method='post'>
<center><h1 style="font-family:'標楷體' ">申請計畫主持人帳號</h1>
<TABLE cellspacing="1" cellpadding="2" bgcolor="#7ca0c0">
	<TR>
		<TD bgcolor="#7ca0c0" width="5" rowspan='3'></TD>
		<TD bgcolor="#a3bcd3" width="3" rowspan='3'></TD>
		<TD bgcolor="#c2d3e2" width="2" rowspan='3'></TD>  		
		<td nowrap bgcolor="#c2d3e2" colspan="2" align="center">請輸入主持人基本資料</td>
	<tr>  				
		<td nowrap bgcolor="#FFFFFF">人事代號</td><td nowrap bgcolor="#FFFFFF"><input type='text' name='UserAccount' size='5' maxlength='5'></td>
	</tr>
	<tr>  				
		<td nowrap bgcolor="#FFFFFF">身份證字號</td><td nowrap bgcolor="#FFFFFF">
		<input type='password' name='UserID' size='12' maxlength='10'>&nbsp;&nbsp;&nbsp;&nbsp;(身份確認用)</td>  		
	</tr>
</table>  		
<input type='button' name='確認' value='確認' OnClick="Javascript:myCheck()"><input type='button' name='button2' value='回首頁' OnClick='location.href="../../index.php"'>
<input type='hidden' name='NewApply' value=''>
</center> 
</form>
</body>
</html>
