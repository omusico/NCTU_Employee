<?php
	include("../connectSQL.php");
	include("../function.php");
?>  

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>

</head>
<body bgcolor="#fafafa">

<head>
	<title>Destroydrop &raquo; Javascripts &raquo; Tree</title>
	<link rel="StyleSheet" href="../JS/dtree.css" type="text/css" />
	<script type="text/javascript" src="../JS/dtree.js"></script>
	<script type="text/javascript" src="../JS/jquery-1.8.0.js"></script>
	<script type="text/javascript" src="../JS/jquery.cookie.js"></script>
</head>

<body>
<TABLE cellspacing="1" cellpadding="1" bgcolor="#FFD700" width="200" style="border:2px dotted #7ca0c0">
	<TR> <TD bgcolor="#FFD700" align="center"><?echo $_SESSION["name"];?>(<?echo $_SESSION["UserID"];?>)</TD>
		 <TD></TD>
	</TR>
</TABLE>
<P>
<TABLE cellspacing="1" cellpadding="1" bgcolor="#c2d3e2" width="200" style="border:2px dotted #7ca0c0">
	<TR> <TD bgcolor="#c2d3e2" align="center">系統功能列<br/>Serial functions</TD>
		 <TD></TD>
	</TR>
</TABLE>
<div class="dtree">
	<p><a href="javascript: d.openAll();">open all</a> | <a href="javascript: d.closeAll();">close all</a></p>
	
	<script type="text/javascript">
		d = new dTree('d');
		d.add(0,-1,'系統功能列(Serial functions)');
	
		d.add(1,0,'請核單作業','');
		d.add(101,1,'新增請核單','../new_PTapply.php','','mainFrame');
		d.add(102,1,'查詢/修改請核單','../qry_PTapply.php','','mainFrame');
		
		d.add(2,0,'異動單作業','');
		d.add(201,2,'新增異動單','../new_PTtransform_select.php','','mainFrame');
		d.add(202,2,'查詢/修改異動單','../qry_PTtransform.php','','mainFrame');
		
		d.add(3,0,'請核人員建檔和上傳個人證明資料','');
		d.add(301,3,'新增校外請核人員','../outerperson.php','','mainFrame');
		d.add(304,3,'新增校內請核人員','../innerperson.php','','mainFrame');
		d.add(302,3,'查詢/修改請核人員','../outerperson_list.php','','mainFrame');
		<? if($_SESSION["power"]=="1"){?>
		d.add(303,3,'審核個人證明文件','../uploadManList.php','','mainFrame');
		<? }?>
		
		<? if($_SESSION["power"]=="1"){?>
			d.add(5,0,'請核/異動單審核','');
			d.add(501,5,'請核/異動單審核','../verify_apply.php','','mainFrame');
		<? }?>
		<? if($_SESSION["ProjLeaderAgent"]=="T" || $_SESSION["ProjLeader"]=="T"){?>
			d.add(4,0,'計畫主持人','');
			d.add(401,4,'申請承辦助理帳號','../UserAccount/New/NewAss.php','申請承辦助理帳號','mainFrame');
			<? if($_SESSION["ProjLeader"]=="T"){?>
				d.add(402,4,'授權代理主持人帳號','../UserAccount/New/NewAgent.php','授權代理主持人帳號','mainFrame');
			<? }?>
		<? }?>
		
		document.write(d);
	</script>
	<?if($_SESSION["UserID"]==trim($row['CreateEmp']) || $_SESSION["power"]=="1"){?>
	<script type="text/javascript">
		d1 = new dTree('d');
		d1.add(0,-1,'管理功能列(Management functions)');
	
		d1.add(2,0,'計畫可追溯日期設定','../BugetSetting.php','','mainFrame');
	
		document.write(d1);
	</script>
	<?}?>
</div>
<p>	

<TABLE cellspacing="1" cellpadding="1" bgcolor="#c2d3e2" width="200" style="border:2px dotted #7ca0c0">
<TR> <TD bgcolor="#c2d3e2" align="center">文件下載區</TD></TR>
</TABLE>
	<script type="text/javascript">
		q = new dTree('q');

		q.add(0,-1,'Q&A');
		q.add(1,0,'文件下載區<br/>');
		q.add(101,1,'新版兼任請核教育訓練文件','../document/新版兼任請核教育訓練.pps','新版兼任請核教育訓練文件','_blank');
		
		document.write(q);
	</script>	

<p>	

<TABLE cellspacing="1" cellpadding="1" bgcolor="#c2d3e2" width="200" style="border:2px dotted #7ca0c0">
<TR> <TD bgcolor="#c2d3e2" align="center">個人帳號資訊</TD></TR>
</TABLE>

	<script type="text/javascript">
		<!--

		e = new dTree('e');

		e.add(0,-1,'個人帳號');
		e.add(3,0,'登出系統','../userlogout.php','Log out','_parent');
		document.write(e);


	</script>
<p>
</body>
</html>