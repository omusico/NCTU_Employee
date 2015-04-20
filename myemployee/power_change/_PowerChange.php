<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	

</head>
<?php 
	//功用:接受由人事管理系統傳過來的值
	include("../connectSQL.php");
	include("../function.php");
	
	/* Redirect browser */
	echo ""$_SESSION["UserID"];
	//echo $_SESSION["UserID"]." ".$_SESSION["power"];
	/*if($_SESSION["UserID"]!="ADMIN"){
		$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] where empno='".$_SESSION["UserID"]."'";
		$result=$db->query($strSQL) or die($strSQL);
		
		if($row=$result->fetch()){
			$_SESSION['Dept']=trim($row['服務單位代碼']);
			$_SESSION["name"]=trim($row['Name']);
			
			$strSQL = "sp_qryUserRole '".$_SESSION["UserID"]."'";
			//echo $strSQL."<br>";
			$User_result = $db->query($strSQL);
			$row_User = $User_result->fetchAll();
			//print_r($row_User);echo "<br>";
			$User_result = $db->query($strSQL);
			if(count($row_User)>0)
			{
				$User = $User_result->fetch();
				$_SESSION["ProjLeader"] = $User["ProjLeader"];
				$_SESSION["ProjOfficer"] = $User["ProjOfficer"];
				
				//確認是否有代理主持人權限
				$myYear=date("Y")-1911;
				$myMonth=date("m");
				$myDay=date("d");
				$myDate = $myYear.$myMonth.$myDay;		
				$strsqL = "sp_qry_bugetByLeaderid '".$_SESSION["UserID"]."' , '".$myDate."' , '".$myDate."'";
				echo "<br>".$strsqL;
				$result_rsPlan = $db->query($strsqL);
				$row=$result_rsPlan->fetchAll();
				if(count($row)>0){
					$_SESSION["ProjLeaderAgent"]="T";
				}
				//echo "<br>leader=".$_SESSION["ProjLeader"]." agent=".$_SESSION["ProjLeaderAgent"];
				//掛鈴鐺
				//LoginRec();
			}				
		}
	}else{
		$_SESSION['Dept']="admin";
		$_SESSION["name"]="管理員";		
		$_SESSION["ProjLeader"] = "";
		$_SESSION["ProjOfficer"] = "";
		$_SESSION["ProjLeaderAgent"]="";
	}
	//echo $_SESSION["Dept"]." ".$_SESSION["name"];
	if($_SESSION["name"]!=""){
		header("Location:main.php");
	}else{
		echo "<script language='javascript'>alert('使用者不存在或無權限');";			
		echo "window.close();";
		echo "</script>";
		
	}*/
	 
	/* Make sure that code below does not get executed when we redirect. */
	exit;
?>
<body>
</body>
</html>