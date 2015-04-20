<?php

include("connectSQL.php");


$id=$_POST["id"];
$sql="select * from OuterStatus where idno='".$id ."' and isNCTU='1' and RecordStatus <>-1";


$rsList=$db->query($sql);


$returnValue=0;

if ( $ds = $rsList->fetch() ) {
	
		$returnValue++;
	
}



$sql="select * from OuterStatus where idcode='".$id ."' and isNCTU='1' and RecordStatus <>-1";


$rsList=$db->query($sql);



if ( $ds = $rsList->fetch() ) {

	$returnValue++;

}



echo $returnValue;

?>