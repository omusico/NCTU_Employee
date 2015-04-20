<?php


include("connectSQL.php");


$id=$_POST["id"];
$status=$_POST["status"];
$returnValue="0";


if($status=="S"){

	$sql="select *
		  from StudentData
		  where std_pid='".$id."' and 學籍之在學狀況  in ('在學','應畢','延畢')";
	
	$rsList=$db->query($sql);
	
	
	if ( $ds = $rsList->fetch() ) {
		$returnValue="1";
	}
	
}
else if($status=="J"){
	
	$sql="select *
	   	  from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime]
		  where idno ='".$id."'";
	
	$rsList=$db->query($sql);
	
	
	if ( $ds = $rsList->fetch() ) {
		$returnValue="1";
	}
	
}

echo $returnValue;


?>