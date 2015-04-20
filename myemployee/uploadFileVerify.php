<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<title>個人證明文件待審列表</title>
<iframe id="getNormalPaycont" name="getNormalPaycont" src="" width="0" height="0"> </iframe>	
<script type="text/javascript" src="/JS/jquery-1.8.0.js"></script>
<script type='text/javascript' src='/JS/jquery.simplemodal_1.4.3.js'></script>
<script type="text/javascript" src="/JS/jquery.cookie.js"></script>
<script type="text/javascript" src="/JS/jquery-impromptu.js"></script>
<script type="text/javascript">
	$(function(){
		$("input[name='acceptfile']").click(function(){
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
			
		});
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
			<legend>個人證明文件待審列表</legend>
		<?
			//抓出待審A類文件
			$strSQL="select o.Eid,o.IdNo as id,o.Name as name,u1.FileTitle,u1.[type] as typeno,u1.Fid,u2.TypeName,w.ID_StartDate,w.ID_EndDate,".
					"datepart(year,w.ID_StartDate) as start_y,datepart(month,w.ID_StartDate) as start_m,datepart(day,w.ID_StartDate) as start_d,".
					"datepart(year,w.ID_EndDate) as end_y,datepart(month,w.ID_EndDate) as end_m,datepart(day,w.ID_EndDate) as end_d,".
					"u1.status ".
					"from OuterStatus o ".
					"left join UploadData u1 on o.Eid=u1.PEid and u1.status in ('0','1')".
					"left join UploadType u2 on u1.[type]=u2.TypeNo ".
					"left join working_periods w on w.Fid=u1.Fid ".
					"where o.Eid in (select distinct PEid from UploadData where [type] in (select TypeNo from UploadType where TypeClass='A') ".
					"and [status] in ('0','1')) and PEid='".filterEvil($_GET['Eid'])."' ".
					"order by o.Eid,u1.status";
			
			//echo $strSQL;
			$result=$db->query($strSQL);
			$row=$result->fetchAll();
			if(count($row)>0){
				$result=$db->query($strSQL);
				echo "<table width='1000'  cellspacing='1' cellpadding='4' bgcolor='#9194BF'>".
					 "<tr height='20' align='center' bgcolor='#C9CBE0'>".
					 "<th>Fid</th><th>姓名</th><th>身份證/居留證號</th><th>檔案連結</th><th>文件類型</th><th>檔案標題</th>".
					 "<th>工作起訖</th><th>操作</th>".
					 "</tr>";
				while($row=$result->fetch()){
					if($bgcolor=="#AFFEFF"){$bgcolor="#FFFFFF";}
					else{$bgcolor="#AFFEFF";}
					echo "<tr bgcolor='".$bgcolor."' align='center'>".
						 "<td>".$row['Fid']."</td><td>".$row['name']."</td><td>".$row['id']."</td>".
						 "<td><a href='viewfile.php?fid=".$row['Fid']."' target='_blank'>檢視檔案</a></td>".
						 "<td>".trim($row['TypeName'])."</td><td>".trim($row['FileTitle'])."</td>";
					if(trim($row['typeno'])=="4" && trim($row['start_y'])!=""){
						echo "<td>";
						echo (trim($row['start_y'])-1911).addLeadingZeros(trim($row['start_m']),2).addLeadingZeros(trim($row['start_d']),2);
						echo "-".(trim($row['end_y'])-1911).addLeadingZeros(trim($row['end_m']),2).addLeadingZeros(trim($row['end_d']),2);
						echo "</td>";
					}else{echo "<td>&nbsp;</td>";}
					if(trim($row['status'])=="0"){
						echo "<td><div id='op_".trim($row['Fid'])."'>".
							 "<input type='button' name='acceptfile' fid='".trim($row['Fid'])."' value='通過' />".
							 "<input type='button' name='unacceptfile' fid='".trim($row['Fid'])."' value='退件' />".
							 "</div></td>";
					}else{echo "<td>&nbsp;</td>";}	 
					echo "</tr>";					
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
	
	
	