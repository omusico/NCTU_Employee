<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>個人證明文件待審人列表</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/JS/jquery-impromptu.js"></script>
<script type="text/javascript">
	$(function(){
		$("input[name='verifyfiles']").click(function(){
			var eid=$(this).attr("eid");
								
			//alert(eid+" acceptfile");
			var url="uploadFileVerify.php?Eid="+eid;
			window.open(url,"","");
					
		});
		/*$("input[name='acceptfile']").click(function(){
			if(confirm("確定要通過此文件嗎?")==false)
				return false;
			var fid=$(this).attr("fid");
								
			//alert(fid+" acceptfile");
			$.ajax({

				type:"POST",
				dataType:"text",
				data:{fid:fid,action:"acceptfile"},
				url:"fileoperation.php",
				
				success:function(msg){
					if(msg=="true"){
						alert("審核通過成功");
						var opstr="#op_"+fid;
						//alert(opstr);
						$(opstr).html("審核通過");
					}else{
						alert("審核通過失敗");					
						//alert(msg);					
					}},
				error:function(){
					alert("審核操作錯誤!!");
				}	
			});

					
		});

		$("input[name='unacceptfile']").click(function(){
			if(confirm("確定要退回此文件嗎?")==false)
				return false;
			var fid=$(this).attr("fid");
			$.ajax({

				type:"POST",
				dataType:"text",
				data:{fid:fid,action:"unacceptfile"},
				url:"fileoperation.php",
				
				success:function(msg){
					if(msg=="true"){
						alert("退回文件成功");
						var opstr="#op_"+fid;
						//alert(opstr);
						$(opstr).html("已退回");
					}else{
						alert("退回文件失敗");					
						//alert(msg);					
					}},
				error:function(){

					alert("退回操作錯誤!!");
					
					}	
				});
			
		});*/
	});
</script>
</head>

<?php 
	include("connectSQL.php");
	include("function.php");
	
?>

<body bgcolor='#c1cfb4'>
	<div id='upload'>
		<fieldset>
			<legend>個人證明文件待審人列表</legend>
		<?
			//先抓出有待審A類文件的人員
			//$strSQL="select o.Eid,o.IdNo as id,o.Name as name,u1.FileTitle,u1.[type] as typeno,u1.Fid,u2.TypeName,w.ID_StartDate,w.ID_EndDate,".
			//		"datepart(year,w.ID_StartDate) as start_y,datepart(month,w.ID_StartDate) as start_m,datepart(day,w.ID_StartDate) as start_d,".
			//		"datepart(year,w.ID_EndDate) as end_y,datepart(month,w.ID_EndDate) as end_m,datepart(day,w.ID_EndDate) as end_d ".
			//		"from OuterStatus o ".
			//		"left join UploadData u1 on o.Eid=u1.PEid and u1.status='0'".
			//		"left join UploadType u2 on u1.[type]=u2.TypeNo ".
			//		"left join working_periods w on w.Fid=u1.Fid ".
			//		"where o.Eid in (select distinct PEid from UploadData where [type] in (select TypeNo from UploadType where TypeClass='A') ".
			//		"and [status]='0') order by o.Eid";
			$strSQL="select distinct o.Eid,o.IdNo as id,o.Name as name ".
					"from OuterStatus o ".
					"left join UploadData u1 on o.Eid=u1.PEid and u1.status='0'".
					"left join UploadType u2 on u1.[type]=u2.TypeNo ".
					"left join working_periods w on w.Fid=u1.Fid ".
					"where o.Eid in (select distinct PEid from UploadData where [type] in (select TypeNo from UploadType where TypeClass='A') ".
					"and [status]='0') order by o.Eid";
			//echo $strSQL;
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){
				$result=$db->query($strSQL);
				echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
					 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
					 "<th>人員序號</th><th>姓名</th><th>身份證/居留證號</th><th>說明</th><th>操作</th>".
					 "</tr>";
				while($row=$result->fetch()){
					if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
					else{$bgcolor="#AFFEFF";}
					echo "<tr bgcolor='".$bgcolor."' align='center'>".
						 "<td>".$row['Eid']."</td><td>".$row['name']."</td><td>".$row['id']."</td>";
					$strSQL2="select u.[type],u2.TypeName from UploadData u ".
							 "left join UploadType u2 on u.[type]=u2.TypeNo ".
							 "where u.[type] in (select TypeNo from UploadType  ".							 
							 "where TypeClass='A') and u.[status]='0' and u.PEid='".trim($row['Eid'])."'";
					$result2=$db->query($strSQL2);
					$row2=$result2->fetchAll();
					$num=count($row2);
					$result2=$db->query($strSQL2);
					$typename=array();
					$index=0;
					while($row2=$result2->fetch()){
						if(!in_array(trim($row2['TypeName']),$typename)){
							array_push($typename,trim($row2['TypeName']));							
						}
					}
					echo "<td>共 ".$num." 件待審,含 ".implode(",",$typename)."</td>";
					echo "<td><div id='op_".trim($row['Eid'])."'>".
						 //"<input type='button' name='acceptfile' fid='".trim($row['Fid'])."' value='通過' />".
						 //"<input type='button' name='unacceptfile' fid='".trim($row['Fid'])."' value='退件' />".
						 "<input type='button' name='verifyfiles' eid='".trim($row['Eid'])."' value='審核'".
						 "</div></td>".
						 "</tr>";
					
				}
				echo "</table>";
			}else{echo "無";}
		?>
		</fieldset>
	</div>
</body>
</html>
<script language="javascript">

</script>
	
	
	