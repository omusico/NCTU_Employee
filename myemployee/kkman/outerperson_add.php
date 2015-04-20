<?php

include ("connectSQL.php");


if (isset ( $_POST ['action'] ) && $_POST ['action'] == "save") {
	
		
	$empid=$_SESSION["empno"];
	$empid="Z0373";
	$id = $_POST ['id'];
	$name = $_POST ['name'];
	$year = $_POST ['select_year'];
	if($year<1911)
	$year = $year + 1911;
	$month = $_POST ['select_month'];
	$day = $_POST ['select_day'];
	$isStudent = $_POST ['isStudent'];
	$isJob = $_POST ['isJob'];
	$comName = $_POST ['comName'];
	$schoolName = $_POST ['schoolName'];
	$student_grade = $_POST ['student_grade'];
	$job_grade = $_POST ['job_grade'];
	$birth = $year . "-" . $month . "-" . $day;
	$filetypes=$_POST ['filetype'];
	$filetitles=$_POST ['filetitle'];
	
	$vbegin_year=$_POST ['vbegin_year'];
	$vbegin_month=$_POST ['vbegin_month'];
	$vbegin_day=$_POST ['vbegin_day'];
	
	$vend_year=$_POST ['vend_year'];
	$vend_month=$_POST ['vend_month'];
	$vend_day=$_POST ['vend_day'];
	
	if ($isStudent == "y")
		$isStudent = 1;
	else if ($isStudent == "n")
		$isStudent = 0;
	
	if ($isJob == "y")
		$isJob = 1;
	else if ($isJob == "n")
		$isJob = 0;
	
	if($isStudent==0){
		$student_grade=-1;
	}
	else{
		$isJob = 0;
		$job_grade=-1;
	}

	if($comName=="")
		$comName="-1";
	if($schoolName=="")
		$schoolName="-1";
	
//  	echo $filetitles[0]."<br/>";
//  	echo $filetitles[1]."<br/>";
// 	echo $filetypes[0]."<br/>";
// 	echo $filetypes[1]."<br/>";
	//$sqlstringInsert="insert into OuterStatus(IdNo,Name,OutSideUnit,UpdateDate,UpdateEmpNo,CreateDate,CreateEmp,RecordStatus,isStudent,isJob,schoolName,comName,Education,studentGrade,Birthday) 
	//		values ()";
	
	$goflag=true;
	
 	try {
 		
 		
 	
 	
	$db->beginTransaction();
	
	
	$sqlInsert="insert into OuterStatus (SerialNo,IdNo,Name,Birthday,RecordStatus,isStudent,isJob,schoolName,comName,Education,studentGrade,UpdateDate,UpdateEmpNo,CreateDate,CreateEmp)";
	$sqlInsert.=" values('0000','".$id."','".$name."','".$birth."','0','".$isStudent."','".$isJob."','".$schoolName."','".$comName."','".$job_grade."','".$student_grade."',getdate(),'".$empid."',getdate(),'".$empid."')";
	echo $sqlInsert;
	$db->exec($sqlInsert);
	
	
	$sqlMaxEid="select Max(Eid) as MaxEid from OuterStatus";
	
	
	$rsMaxEid=$db->query($sqlMaxEid);
	$dsMaxEid = $rsMaxEid->fetch();
	$maxeid=$dsMaxEid["MaxEid"];
	
	function check($var) { //驗證陣列的傳回值是否為空
		return ($var != "");
	}
	
	$arrayindex=0;
	$array = array_filter ( $_FILES ["upload"] ["name"], "check" ); //去除陣列中空值
	
	
	
	
	
	foreach ( $array as $key => $value ) { //循環讀取陣列中資料
		$path = 'upfile/' . time () . $key . strtolower ( strstr ( $value, "." ) );

		
		$dot_position=strpos($_FILES ["upload"] ["name"] [$key],".");

		$name=substr($_FILES ["upload"] ["name"] [$key], 0,$dot_position);
		$type=substr($_FILES ["upload"] ["name"] [$key], $dot_position+1,strlen($_FILES ["upload"] ["name"] [$key])-$dot_position);
		$filetitle=$filetitles[$arrayindex];
		$filetype=$filetypes[$arrayindex];
		
		$v_begin="1900-1-1";
		$v_end="1900-1-1";
		
		
		
		$b_year=$vbegin_year[$arrayindex];
		$b_year=$b_year+1911;
		$b_month=$vbegin_month[$arrayindex];
		$b_day=$vbegin_day[$arrayindex];
		
		$e_year=$vend_year[$arrayindex];
		$e_year=$e_year+1911;
		$e_month=$vend_month[$arrayindex];
		$e_day=$vend_day[$arrayindex];
		
		
		if($filetype==4){
			$v_begin=$b_year."-".$b_month."-".$b_day;
			$v_end=$e_year."-".$e_month."-".$e_day;
		}

		echo "b=".$v_begin;
		echo "e=".$v_end;
		
		$arrayindex++;
		$status=0;//未審核為0
		echo $_FILES ["upload"] ["name"] [$key]."<br/>";
		echo "value=".$value."<br/>";
		echo "key=".$key."<br/>";
		echo "name=".$name."<br/>";
		echo "type=".$type."<br/>";
		

		
		$filecontent=file_get_contents($_FILES['upload']['tmp_name'][$key]);
		$queryIn = $db->prepare("
        INSERT INTO     UploadData (PEid ,FileContent,FileName,SubFileType,FileTitle,type,status,CreateEmp,UpdateEmp,CreateDate,UpdateDate,working_period_begin,working_period_end)
        VALUES          (:peid , :content,:name,:type ,:filetitle,:filetype,:status,:createemp,:updateemp,getdate(),getdate(),:vbegin,:vend)");
		//$queryIn->bindParam(':id', $rr );
		$queryIn->bindParam(':peid',$maxeid );
		$queryIn->bindParam(':name', $name );
		$queryIn->bindParam(':type',$type );
		$queryIn->bindParam(':filetitle',$filetitle );
		$queryIn->bindParam(':status',$status );
		$queryIn->bindParam(':filetype',$filetype );
		$queryIn->bindParam(':createemp',$empid );
		$queryIn->bindParam(':updateemp',$empid );
		$queryIn->bindParam(':content', $filecontent , PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
		$queryIn->bindParam(':vbegin',$v_begin );
		$queryIn->bindParam(':vend',$v_end );
		$queryIn->execute();
		
		
		
		if($filetype==4){
			
			$sqlMaxFid="select Max(Fid) as MaxFid from UploadData";
			
			
			$rsMaxFid=$db->query($sqlMaxFid);
			$dsMaxFid = $rsMaxFid->fetch();
			$maxfid=$dsMaxFid["MaxFid"];
			
			$v_begin=$b_year."-".$b_month."-".$b_day;
			$v_end=$e_year."-".$e_month."-".$e_day;
			
			
			$sqlInsertPeriod="insert into working_periods (Fid,ID_StartDate,ID_EndDate,status,UpdateEmpNo,UpdateDate)";
			$sqlInsertPeriod.= " values('".$maxfid."','".$v_begin."','".$v_end."','0','Z0373',getdate())";
			$db->exec($sqlInsertPeriod);
		}
		
		
		
	}
	
	
	$db->commit();
	
	
	
	} catch (Exception $e) {
		
		$db->rollBack();
		echo "新增資料出錯了";
		$goflag=false;
	}
	
	
	$savetype=$_POST ['savetype'];
	
			if($goflag==true){
			if($savetype=="save")
				header("location:outerperson_list.php");
			else if($savetype=="saveandnext")
				header("location:outerperson.php");
			}
	
}

?>




<?php 



//echo "date:" . $year . "-" . $month . "-" . $day;
//echo "<br/>hh=" . $isStudent;

	//file_get_contents;
	
	//$fp = fopen($_FILES ["upload"] ["tmp_name"] [$key], "rb"); //以二進位形式開啟圖片
	//$image = fread($fp, $_FILES ["upload"] ["size"] [$key]);
	
	
	//$sqlFile="insert into UploadData (Fid,FileContent) values ('222','$image')";
	//$sqlFile="insert into UploadData (Fid) values ('234')";
	// 		$prepareST=$db->prepare($sqlFile);
	// 		$prepareST->bindParam(":id", $ee);
	// 		$prepareST->bindParam(":content", file_get_contents($_FILES['upload']['tmp_name'][$key]));
	// 		$prepareST->execute();
	
	//$db->exec($sqlFile);
	//echo $path."<br/>";
	//move_uploaded_file ( $_FILES ["upload"] ["tmp_name"] [$key], "ahhh.kkman" );
	//$query = "insert into tb_up_file (file_test,data,file_name) values ('$path','$data','$files[$key]')";
	//$result = mysql_query ( $query );
	//$fp = fopen($_FILES ["upload"] ["tmp_name"] [$key], "rb"); //以二進位形式開啟圖片
	//$image = addslashes(
	//		fread($fp, filesize($cover))); //讀取二進位的資料
	
	//$cover_type = strstr($_FILES ["upload"] ["name"] [$key], ".");
	//$name=substr($_FILES ["upload"] ["name"] [$key],0,$dot_position-1);
?>