<?php

include("connectSQL.php");


$id=$_POST["id"];
$sql="select * from OuterStatus where idno='".$id ."' and RecordStatus <>-1 and isNCTU='0'";


$rsList=$db->query($sql);

$returnValue=0;


while ( $ds = $rsList->fetch() ) {
	
	$returnValue++;
}



echo $returnValue;

?>