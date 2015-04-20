<?php

include("connectSQL.php");


$id=$_POST["id"];
$sql="select * from OuterStatus where idno='".$id ."'";


$rsList=$db->query($sql);

$returnValue=0;


while ( $ds = $rsList->fetch() ) {
	
	$returnValue++;
}



echo $returnValue;

?>