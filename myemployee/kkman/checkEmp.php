<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>新增兼任請核單</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/parttime_employ_new/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/parttime_employ_new/JS/jquery-impromptu.js"></script>

</head>
<?php 
	//功用:接受由人事管理系統傳過來的值
	include("connectSQL.php");
	include("function.php");
	
	function decrypt($cipherText, $cipherKey) { 
		$plainText = '';
		$cipherText = base64_decode($cipherText);
		for($i=0,$j=0; $i<strlen($cipherText); $i+=2,$j++) {
			$keychar = substr($cipherKey, ($j+1) % strlen($cipherKey), 1);
			$char = substr($cipherText, $i, 2);
			$char = substr($char, 1, 1).substr($char, 0, 1); 
			$char = chr(hexdec($char)-ord($keychar)); 
			$plainText.=$char;
		}
		return $plainText;
	}
	$r = isset($_POST['r'])? $_POST['r']:'';

	//echo Date(Y).Date(m).Date(d)."B0B7F501B51D4354B60C97AE1F7WOF9JOI30E0FA228E5AD7ED2042732FC";
	//$_POST['txtID']=decrypt($r, Date(Y).Date(m).Date(d)."B0B7F501B51D4354B60C97AE1F7WOF9JOI30E0FA228E5AD7ED2042732FC");
	$_POST['txtID']="admin";
	if(!isset($_POST['txtID']) || empty($_POST['txtID']) || strlen($_POST['txtID'])==0){
		echo "<script language='javascript'>alert('登入失敗');</script>";
	}
	else {
		/* Redirect browser */
		$_SESSION["UserID"]=strtoupper(trim($_POST["txtID"]));
		$_SESSION["power"]=trim($_POST["power"]);
		//echo $_SESSION["UserID"]." ".$_SESSION["power"];
		echo "power=".$_SESSION["power"]." and id=".$_SESSION["UserID"];
		if($_SESSION["UserID"]!="ADMIN"){
			$strSQL="select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] where empno='".$_SESSION["UserID"]."'";
			$result=$db->query($strSQL);
			if($row=$result->fetch()){
				$_SESSION['Dept']=trim($row['服務單位代碼']);
				$_SESSION["name"]=trim($row['Name']);
			}
		}else{
			$_SESSION['Dept']="admin";
			$_SESSION["name"]="管理員";			
		}
		//echo $_SESSION["Dept"]." ".$_SESSION["name"];
		if($_SESSION["name"]!=""){
			$_SESSION["power"]=1;
			header("Location:main.php");
		}else{
			echo "<script language='javascript'>alert('使用者不存在或無權限');";			
			echo "window.close();";
			echo "</script>";
			
		}
		 
		/* Make sure that code below does not get executed when we redirect. */
		exit;
	}
?>
<body>
</body>
</html>