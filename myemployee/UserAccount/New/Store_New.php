<?php
	include("../../connectSQL.php");
	include("../../function.php");
	//'###############�D���H�򥻸��###################
	$HostUserAccount = filterEvil($_POST["HostUserAccount"]);
	$HostUserTel = filterEvil($_POST["HostUserTel"]);
	$HostUserEmail = filterEvil($_POST["HostUserEmail"]);
	$systemCode = 2;//=2��ܭp�e�u�@�O
	//echo $HostUserAccount."/".$HostUserTel."/".$HostUserEmail;
	$StrSQL ="select DeptCode,ProjPermission,Name,IdNo from UserMain where UserAccount = '".$HostUserAccount."'";
	$rsDept_result	=$db->query($StrSQL);
	$rsDept = $rsDept_result->fetch();
	If(!empty($rsDept))
	{
		$DeptCode = $rsDept["DeptCode"];
		if(($rsDept["ProjPermission"] == "F") || ($rsDept["ProjPermission"] == "X")){
			$strSQL = "update UserMain set ProjPermission='T' where UserAccount='".$HostUserAccount."'";		
			$db->query($strSQL) or die("Error:".$strSQL);
            /*$str = "insert into UserMain_Log values ('".$DeptCode."','".$HostUserAccount."','','',NULL,'F','".$rsDept["Name"]."','".$rsDept["IdNo"]."','F',0,'".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";			
			mssql_query($str) or die("Error:".$str);*/
		}
	}

	//��s�D���H���򥻸�ơA�tE-mail, tel
	$strSQL = "sp_upd_Tel '".$HostUserAccount."','".$HostUserTel."','".$HostUserAccount."'";
	$db->query($strSQL) or die("Error:".$strSQL);
	$strSQL = "sp_ins_upd_email '".$HostUserAccount."','".$HostUserEmail."','".$DeptCode."','".$HostUserAccount."'";
	$db->query($strSQL) or die("Error:".$strSQL);
	
	//�T�{�O�_�w�g�ӽйL�b���A�Y�L�A�h�s�W
	$strSQL = "select * from salaryPermission where UserAccount='".$HostUserAccount."' and DeptCode='".$DeptCode."'";
	$rsAccount_result=$db->query($strSQL);
	$rsAccount=$rsAccount_result->fetch();
	If(empty($rsAccount)){
		$strSQL = "insert into salaryPermission (UserAccount, DeptCode, ProjLeader,ProjOfficer,Teller,Admin,Update_Date) values ('".$HostUserAccount."','".$DeptCode."','T','F','F','F','".$Update_Time."')";		
		$db->query($strSQL) or die("Error:".$strSQL);
		/*$str = "insert into salaryPermission_Log (UserAccount, DeptCode, ProjLeader,ProjOfficer,Teller,Admin,Update_Date,Update_EmpNo,SQLString) values ('".$HostUserAccount."','".$DeptCode."','T','F','F','F','".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";	 
		$db->query($str) or die("Error:".$str);*/
	}			
	Else{	
		If(($rsAccount["ProjLeader"] =="V")||($rsAccount["ProjLeader"]=="F")){
			$strSQL = "update salaryPermission set ProjLeader='T' where UserAccount='".$HostUserAccount."'";	
			$db->query($strSQL);
			/*$str = "insert into salaryPermission_Log (UserAccount,DeptCode,ProjLeader,Update_Date,Update_EmpNo,SQLString) values ('".$HostUserAccount."','".$DeptCode."','T','".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";	 
			mssql_query($str) or die("Error:".$str);*/
		}
	} 
	
	//###############�D���H�򥻸��###################			
	//�ӿ�H�U���򥻸��
	
	$TotalNumber = $_POST["TotalNumber"]; //�`�p�e��
	$modify_info = "";//�����ק���
	for($i=1;$i<=$TotalNumber;$i++) 
	{
		for($j=1;$j<=$_POST["num_".$i];$j++){
			if(!empty($_POST["UserAccount_".$i."_".$j]))
			{
				$UserAccount = filterEvil($_POST["UserAccount_".$i."_".$j]);							
				$UserEmail = filterEvil($_POST["UserEmail_".$i."_".$j]);
				$UserTel = filterEvil($_POST["UserTel_".$i."_".$j]);
				If(filterEvil($UserAccount)!=""){	
					$strSQL = "select * from UserMain where UserAccount = '".$UserAccount."'";	
					$rsUser_result = $db->query($strSQL);
					$rsUser = $rsUser_result->fetch();
					If(!empty($rsUser))
					{
						if($rsUser["ProjPermission"] == "F"){
							$strSQL = "update UserMain set ProjPermission='T' where UserAccount='".$UserAccount."'";		
							$db->query($strSQL) or die("Error:".$strSQL);
							/*$str = "insert into UserMain_Log values ('".$DeptCode."','".$UserAccount."','','',NULL,'F','".$rsUser["Name"]."','".$rsUser["IdNo"]."','F',0,'".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";			
							mssql_query($str) or die("Error:".$str);*/
						}
						$strSQL = "sp_ins_upd_email '".$UserAccount."','".$UserEmail."','".$rsUser["DeptCode"]."','".$HostUserAccount."'";
						$db->query($strSQL) or die("Error:".$strSQL);
						if($UserTel!=""){		
							$strSQL = "sp_upd_Tel '".$UserAccount."','".$UserTel."','".$HostUserAccount."'";										
							$db->query($strSQL);	
						}
						//�T�{�O�_�w�g�ӽйL�b���A�Y�L�A�h�s�W
						$strSQL = "select * from salaryPermission where UserAccount='".$UserAccount."' and DeptCode='".$rsUser["DeptCode"]."'";
						$rsAccount_result=$db->query($strSQL);
						$rsAccount=$rsAccount_result->fetch();						
						if(empty($rsAccount)){	
							$strSQL = "insert into salaryPermission (UserAccount, DeptCode, projLeader,ProjOfficer,Teller,Admin,Update_Date) values ('".$UserAccount."','".$rsUser["DeptCode"]."','F','T','F','F','".$Update_Time."')";		
							$db->query($strSQL) or die("Error:".$strSQL);
							/*$str = "insert into salaryPermission_Log (UserAccount, DeptCode, ProjLeader,ProjOfficer,Teller,Admin,Update_Date,Update_EmpNo,SQLString) values ('".$UserAccount."','".$rsUser["DeptCode"]."','F','T','F','F','".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";	 
							mssql_query($str) or die("Error:".$str);*/
						}
						Else{	
							If(($rsAccount["ProjOfficer"]=="X")||($rsAccount["ProjOfficer"]=="F")){
								$strSQL = "Update salaryPermission set ProjOfficer ='T' where UserAccount='".$UserAccount."'";	
								$db->query($strSQL) or die("Error:".$strSQL);
								/*$str = "insert into salaryPermission_Log (UserAccount,DeptCode,ProjOfficer,Update_Date,Update_EmpNo,SQLString) values ('".$UserAccount."','".$rsUser["DeptCode"]."','T','".$Update_Time."','".$HostUserAccount."','".str_replace("'","''",$strSQL)."')";
								mssql_query($str) or die("Error:".$str);*/
							}
						}
						$PlanID = filterEvil($_POST["PlanID_".$i."_".$j]);
						$strSQL = "select * from Officer_Project where BugetNo='".$PlanID."' and UserAccount='".$UserAccount."'";	
						$rsPlan_result = $db->query($strSQL);
						$rsPlan = $rsPlan_result->fetch();
						If(empty($rsPlan)){
							$strSQL="sp_ins_Officer_Project '".$UserAccount."','".$PlanID."','".filterEvil($_SESSION["UserID"])."'";
							$db->query($strSQL);
						}					
					}
				}
			}
		}
	}		
	$_SESSION["HostUserAccount"] = $HostUserAccount;
	//echo "<meta http-equiv=refresh content=0;url=NewAss.php?modify_info=".$modify_info.">";
	echo "<meta http-equiv=refresh content=0;url=NewAss.php>";
	//###############�ӿ�H�򥻸��###################
?>