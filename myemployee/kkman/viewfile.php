<?php

include("connectSQL.php");

$fid=$_GET['fid'];


	$queryOut = $db->prepare("SELECT Fid,FileContent,FileName,SubFileType FROM UploadData WHERE Fid = :id ");
	$queryOut->bindParam(':id',  $fid);
	$queryOut->execute();
	$queryOut->bindColumn(2, $FileContent, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
	$queryOut->bindColumn(3, $name, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
	$queryOut->bindColumn(4, $type, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
	$row = $queryOut->fetch(PDO::FETCH_ASSOC);
	$filename=$name.".".$type;
	if ($row) {
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename);
	header("Content-Transfer-Encoding: binary");
	header("Cache-Control: cache, must-revalidate");
	//header("Cache-Control: no-cache");
	header("Pragma: public");
	//header("Pragma: no-cache");
	header("Expires: 0");
		echo $row['FileContent'];
	}


?>