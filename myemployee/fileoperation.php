<?php
	
	include("connectSQL.php");
	require_once ('JSON.php');
	
	$fid=$_POST["fid"];
	$title=$_POST["title"];
	$type=$_POST["type"];
	$action=$_POST["action"];
	$begin=$_POST["begin"];
	$end=$_POST["end"];
	$returnValue="";
	$empid=$_SESSION["UserID"];
	$audit_flag=false;
	
	if($action=="editfile"){
	
		
	$audit_sql="select *
				from UploadData
				where fid='".$fid."' and status='1'";	

	$rsAudit=$db->query($audit_sql);
	if( $dsAudit = $rsAudit->fetch()){
		$audit_flag=true;
	}
	
	
	if($audit_flag==false){
	
		try {
			$db->beginTransaction();
			
			$sql="update UploadData set type='".$type."',FileTitle='".$title."',updatedate=getdate(),UpdateEmp='".$empid."' where fid='".$fid."'";
			$db->exec($sql);
			
			if($type==4){
				$sql_sel="select * from working_periods where fid='".$fid."'";
				$rsPeriod=$db->query($sql_sel);
				if( $dsPeriod = $rsPeriod->fetch()){
					$sql="update working_periods set ID_StartDate='".$begin."',ID_EndDate='".$end."',status='0',UpdateEmpNo='".$empid."',updatedate=getdate() where fid='".$fid."'";
					$db->exec($sql);
				}
				else{
					$sql="insert into working_periods (Fid,ID_StartDate,ID_EndDate,status,UpdateEmpNo,UpdateDate)";
					$sql.= " values('".$fid."','".$begin."','".$end."','0',$empid,getdate())";
					$db->exec($sql);
				}
				
			}else{
				
				$sql_sel="select * from working_periods where fid='".$fid."'";
				$rsPeriod=$db->query($sql_sel);
				if( $dsPeriod = $rsPeriod->fetch()){
					$sql="update working_periods set status='-1',UpdateEmpNo='".$empid."',UpdateDate=getdate() where fid='".$fid."'";
					$db->exec($sql);
				}
			}
			
			
			$db->commit();
			
			
			$returnValue="true";
		} catch (Exception $e) {
			$returnValue="false";
			$db->rollBack();
			//echo "新增資料出錯了";
		}
	}
	else{
		$returnValue="audited";
	}
	
	}
	else if($action=="delfile"){
		
		
		
		try {
			$db->beginTransaction();
			$sql="update UploadData set status='-1',UpdateEmp='".$empid."',UpdateDate=getdate() where fid='".$fid."'";
			$db->exec($sql);
			
			$sql="update working_periods set status='-1' where fid='".$fid."'";
			$db->exec($sql);
			
			$db->commit();
			$returnValue="true";
		} catch (Exception $e) {
			$returnValue="false";
			$db->rollBack();
		}
		
	}
	else if($action=="DocumentVerfied"){//退回A類文件
		$sql="select o.* from UploadData u ".
			 "left join OuterStatus o on u.PEid=o.Eid ".
			 "where PEid in (select o.Eid from PT_Employed p,OuterStatus o where p.SerialNo='".$OrderNo."' and p.Pid=o.IdNo) ".
			 "and [type] in (select TypeNo from UploadType where TypeClass='A') ".
			 "and [status] in ('0')";
		
		try {
			$result=$db->query($sql);
			$row=$result->fetchAll();
			if(count($row)>0){
				$returnValue="本單內仍有人員身份證明文件尚未通過,含";
				$result=$db->query($sql);
				while($row=$result->fetch()){
					$returnValue.=$row['Name']."  ";
				}
			}else{$returnValue="true";}
		} catch (Exception $e) {
			$returnValue="false";
		}		
	}
	else if($action=="acceptfile"){//通過A類文件
		//$sql="delete UploadData where fid='".$fid."'";
		$sql="update UploadData set status='1',updateEmp='".$_SESSION['UserID']."',updatedate=getdate(),AuditEmpno='".$_SESSION['UserID']."',AuditDate=getdate() where fid='".$fid."'";
		//確認是否為工作證,如果是要再審通過工作時期
		/*$strSQL="select * from UploadData where fid='".$fid."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		if($row['type']=="4"){
			$sql2="update working_periods set status='1',UpdateEmpNo='".$_SESSION['UserID']."',UpdateDate=getdate(),VerifyEmpNo='".$_SESSION['UserID']."',VerifyDate=getdate() where fid='".$fid."'"
		}else{$sql2="";}*/
		try {
			$db->exec($sql);
			//if($sql2!=""){$db->exec($sql2);}
			$returnValue="true";
		} catch (Exception $e) {
			$returnValue="false";
		}
		
	}
	else if($action=="unacceptfile"){//退回A類文件
		//$sql="delete UploadData where fid='".$fid."'";
		$sql="update UploadData set status='-2',updateEmp='".$_SESSION['UserID']."',updatedate=getdate(),AuditEmpno='".$_SESSION['UserID']."',AuditDate=getdate() where fid='".$fid."'";
		try {
			$db->exec($sql);
			$returnValue="true";
		} catch (Exception $e) {
			$returnValue="false";
		}
		
	}
	
	echo $returnValue;
?>