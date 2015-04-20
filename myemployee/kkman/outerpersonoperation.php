<?php

		include ("connectSQL.php");
		
		
		$action=$_POST["action"];
		$returnValue="";
		
		if($action=="delperson"){
			
			$eid=$_POST["eid"];
			$flag=0;
			$sqlUploadData="select count(*) as countnum from uploaddata where peid='".$eid."'";
			$rs=$db->query($sqlUploadData);
			
			if($ds=$rs->fetch())
			$count=$ds["countnum"];
			
			$sqlDelOuterStatus="delete from OuterStatus where Eid='".$eid."'";
			
			if($count>0)
				$sqlDelUploadData="delete from uploaddata where peid='".$eid."'";
			else 
				$sqlDelUploadData="delete from uploaddata where 1!=1";
			
			//echo $sqlDelUploadData;
			try {
					
				$db->beginTransaction();
				
				$db->exec($sqlDelOuterStatus);
				$db->exec($sqlDelUploadData);
				
				
				$db->commit();
				$returnValue="true";
			} catch (Exception $e) {
				$db->rollBack();
				$returnValue="false";
			}
			
			echo $returnValue;
		}
		else if($action=="update"){
			
			
			$empid=$_SESSION["empno"];
			$empid="Z0373";
			//$id = $_POST ['id'];
			//$name = $_POST ['name'];
			$year = $_POST ['select_year'];
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
			$filetypes=$_POST ['filetypeNew'];
			$filetitles=$_POST ['filetitleNew'];
			
			$vbegin_year=$_POST ['vbegin_yearNew'];
			$vbegin_month=$_POST ['vbegin_monthNew'];
			$vbegin_day=$_POST ['vbegin_dayNew'];
			
			$vend_year=$_POST ['vend_yearNew'];
			$vend_month=$_POST ['vend_monthNew'];
			$vend_day=$_POST ['vend_dayNew'];
			
			echo $filetypes[0];
			echo $filetitles[0];
			$peid=$_POST ['peid'];
			echo $birth;
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
			
			if($isJob==0)
				$comName="-1";
			if($isStudent==0)
				$schoolName="-1";
			
			try {
				
				
				$goflag=true;
				$db->beginTransaction();
				//$sqlUpdateOuterstatus="update outerstatus set where eid='".$peid."'";
				
				
				$queryIn = $db->prepare("update outerstatus set Birthday=:birth,isStudent=:isstudent,isJob=:isjob,schoolName=:school,comName=:com,Education=:edu,studentGrade=:grade,UpdateDate=getdate(),UpdateEmpNo=:empno where eid=:eid");
				//$queryIn->bindParam(':id', $rr );
				
				$queryIn->bindParam(':birth', $birth );
				$queryIn->bindParam(':isstudent',$isStudent );
				$queryIn->bindParam(':isjob',$isJob );
				$queryIn->bindParam(':school',$schoolName );
				$queryIn->bindParam(':com',$comName );
				$queryIn->bindParam(':edu',$job_grade );
				$queryIn->bindParam(':grade',$student_grade );
				$queryIn->bindParam(':empno',$empid);
				$queryIn->bindParam(':eid',$peid);
				$queryIn->execute();
				
				
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
					
					$arrayindex++;
					$status=0;//未審核為0
					echo $_FILES ["upload"] ["name"] [$key]."<br/>";
					echo "value=".$value."<br/>";
					echo "key=".$key."<br/>";
					echo "name=".$name."<br/>";
					echo "type=".$type."<br/>";
				
				
					$filecontent=file_get_contents($_FILES['upload']['tmp_name'][$key]);
					$queryIn = $db->prepare("
       		 INSERT INTO     UploadData (PEid ,FileContent,FileName,SubFileType,FileTitle,type,status,CreateEmp,UpdateEmp,CreateDate,UpdateDate)
       		 VALUES          (:peid , :content,:name,:type ,:filetitle,:filetype,:status,:createemp,:updateemp,getdate(),getdate())");
					//$queryIn->bindParam(':id', $rr );
					$queryIn->bindParam(':peid',$peid );
					$queryIn->bindParam(':name', $name );
					$queryIn->bindParam(':type',$type );
					$queryIn->bindParam(':filetitle',$filetitle );
					$queryIn->bindParam(':status',$status );
					$queryIn->bindParam(':filetype',$filetype );
					$queryIn->bindParam(':createemp',$empid );
					$queryIn->bindParam(':updateemp',$empid );
					$queryIn->bindParam(':content', $filecontent , PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
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
				$goflag=false;
				//echo "更新資料出錯了";
		
			}
			
			//if($goflag==true)
			//header("location:outerperson_list.php");
			
		}
		
		
?>