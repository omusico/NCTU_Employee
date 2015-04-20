<?php
	
	include("connectSQL.php");

	$fid=$_POST["fid"];
	$title=$_POST["title"];
	$type=$_POST["type"];
	$action=$_POST["action"];
	$begin=$_POST["begin"];
	$end=$_POST["end"];
	$returnValue="";
	
	
	if($action=="editfile"){
	

	try {
		$db->beginTransaction();
		
		$sql="update UploadData set type='".$type."',FileTitle='".$title."',updatedate=getdate() where fid='".$fid."'";
		$db->exec($sql);
		
		if($type==4){
			$sql_sel="select * from working_periods where fid='".$fid."'";
			$rsPeriod=$db->query($sql_sel);
			if( $dsPeriod = $rsPeriod->fetch()){
				$sql="update working_periods set ID_StartDate='".$begin."',ID_EndDate='".$end."',status='0' where fid='".$fid."'";
				$db->exec($sql);
			}
			else{
				$sql="insert into working_periods (Fid,ID_StartDate,ID_EndDate,status,UpdateEmpNo,UpdateDate)";
				$sql.= " values('".$fid."','".$begin."','".$end."','0','Z0373',getdate())";
				$db->exec($sql);
			}
			
		}else{
			
			$sql_sel="select * from working_periods where fid='".$fid."'";
			$rsPeriod=$db->query($sql_sel);
			if( $dsPeriod = $rsPeriod->fetch()){
				$sql="update working_periods set status='-1' where fid='".$fid."'";
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
	else if($action=="delfile"){
		
		
		
		try {
			$db->beginTransaction();
			$sql="update UploadData set status='-1' where fid='".$fid."'";
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
	
	
	echo $returnValue;
?>