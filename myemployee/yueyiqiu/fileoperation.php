<?php
	
	include("connectSQL.php");

	$fid=$_POST["fid"];
	$title=$_POST["title"];
	$type=$_POST["type"];
	$action=$_POST["action"];
	$returnValue="";
	
	
	if($action=="editfile"){
	
	$sql="update UploadData set type='".$type."',FileTitle='".$title."',updatedate=getdate() where fid='".$fid."'";
	
	try {
		
		$db->exec($sql);
		$returnValue="true";
	} catch (Exception $e) {
		$returnValue="false";
	}
	
	}
	else if($action=="delfile"){
		
		$sql="delete UploadData where fid='".$fid."'";
		
		try {
		
			$db->exec($sql);
			$returnValue="true";
		} catch (Exception $e) {
			$returnValue="false";
		}
		
	}
	
	
	echo $returnValue;
?>