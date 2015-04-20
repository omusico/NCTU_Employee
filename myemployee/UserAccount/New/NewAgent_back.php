<?php
	/*  設定代理主持人
	*	NewAgent_back.php
	*	20140630
	*/
	require_once ('../../JSON.php');
	include_once ('../../connectSQL.php');
	include("../../function.php");
	$UserAccount = filterEvil($_SESSION["UserID"]);
	
	if(!empty($_POST)){
		$POSTDATA=array();
		foreach ($_POST as $k=>$v){
			$POSTDATA[$k]=filterEvil($v);
		}
		extract($POSTDATA);
	}
	
	$j = new Services_JSON();
	$return_data = array();
	
	if($t=='loadbugetlist'){//目前計畫與代理主持人列表
		
		$bugetlist = array();
		
		$myYear=date("Y")-1911;
		$myMonth=date("m");
		$myDay=date("d");
		$myDate = $myYear.$myMonth.$myDay;		
		$strsqL = "sp_qry_bugetByLeaderid '".$UserAccount."' , '".$myDate."' , '".$myDate."'";
		$rsPlan = $db->query($strsqL);
		$return_data["BugetList"]=array();
		while($rows = $rsPlan->fetch()){
			if($rows["oriLeader"]=='Y'){
				$bugetlist[] = $rows["bugetno"];
				$return_data["BugetList"][] = trim($rows["bugetno"]);
			}
		}

		$str = "select [UserAccount],[BugetNo],[BugetLeader], b.Name,convert(varchar(8),a.[EndDate],112) as 'EndDate' from [dbo].[BugetAgentLeader] a 
				left join [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] b on a.UserAccount=b.EmpNo
				where [Permission]='T' and BugetNo in ('".implode("','",$bugetlist)."')";
		
		
		$rsPer = $db->query($str);
		$return_data["Permission"]=array();
		while($rows = $rsPer->fetch()){
			$return_data["Permission"][] = array(
				'UserAccount' => trim($rows["UserAccount"]),
				'BugetNo' => trim($rows["BugetNo"]),
				'BugetLeader' => $rows["BugetLeader"],
				'EndDate' => $rows["EndDate"],
				'Name' => $rows["Name"]
			);
		}
		
		
	}elseif($t=='getname'){//取得工號姓名
		$str = "select name from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] where empno='".$agentempno."'";
		$rsPer = $db->query($str);
		if($row = $rsPer->fetch()){
			$return_data['name']=$row['name'];
			$return_data['success']='1';
		}else{
			$return_data['success']='0';
		}
	}elseif($t=='addagentempno'){//新增代理主持人權限
		
		$nowTime = date("Y-m-d H:i:s");
		
		//有舊資料，先取消其他資料權限
		if($hasdata=='T'){
			//取得資料
			$str = "select UserAccount from BugetAgentLeader where BugetNo='".$bugetno."' and Permission='T' ";
			$rsPer = $db->query($str);
			if($row = $rsPer->fetch()){
				$oldAgent = $row['UserAccount'];
				
				$str = "update BugetAgentLeader set Permission='F', UpdateDate='".$nowTime."',UpdateEmp='".$UserAccount."' where BugetNo='".$bugetno."' ";
				$rsPer = $db->query($str);
				
				//查詢是否還有計畫主持人權限
				$myYear=date("Y")-1911;
				$myMonth=date("m");
				$myDay=date("d");
				$myDate = $myYear.$myMonth.$myDay;		
				$strsqL = "sp_qry_bugetByLeaderid '".$oldAgent."' , '".$myDate."' , '".$myDate."'";
				$rsPlan = $db->query($strsqL);
				
				if($row = $rsPlan->fetch()){
					//尚有其他計畫
				}else{
					//計畫主持人權限關閉
					$str = "update salaryPermission set ProjLeader='F' where UserAccount='".$agentempno."'";
					$rsPer = $db->query($str);
				}
			}
		}
		
		//計畫主持人權限
		$str = "update salaryPermission set ProjLeader='T' where UserAccount='".$agentempno."'";
		$rsPer = $db->query($str);
		
		$str = "insert into BugetAgentLeader([UserAccount],[BugetNo],[BugetLeader],[EndDate],[UpdateDate],[UpdateEmp],[Permission],[CreateDate])
				values('".$agentempno."','".$bugetno."','".$UserAccount."','".$enddate."','".$nowTime."','".$UserAccount."','T','".$nowTime."') ";
		$rsPer = $db->query($str);
		if($rsPer){
			$return_data['success']='1';
		}else{
			$return_data['success']='0';
		}
	}elseif($t=='deleteagentempno'){//刪除代理主持人權限
		$nowTime = date("Y-m-d H:i:s");
		
		$str = "update BugetAgentLeader set Permission='F', UpdateDate='".$nowTime."',UpdateEmp='".$UserAccount."' where BugetNo='".$bugetno."' ";
		$rsPer = $db->query($str);
		if($rsPer){
			$return_data['success']='1';
		}else{
			$return_data['success']='0';
		}
		
		//查詢是否還有計畫主持人權限
		$myYear=date("Y")-1911;
		$myMonth=date("m");
		$myDay=date("d");
		$myDate = $myYear.$myMonth.$myDay;		
		$strsqL = "sp_qry_bugetByLeaderid '".$agentempno."' , '".$myDate."' , '".$myDate."'";
		$rsPlan = $db->query($strsqL);
		
		if($row = $rsPlan->fetch()){
			//尚有其他計畫
		}else{
			//計畫主持人權限關閉
			$str = "update salaryPermission set ProjLeader='F' where UserAccount='".$agentempno."'";
			$rsPer = $db->query($str);
		}
		
	}
	
	$jsonString = $j->encode($return_data); 
	echo $jsonString;
	
	exit();
?>
