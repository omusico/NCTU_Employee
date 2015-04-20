<?php 
	include("connectSQL.php");
	
	
	
	$span=20;
	$currentPage=1;
	$school="";
	$class="";
	$name="";
	$com="";
	
	if(isset($_GET['currentPage']))
		$currentPage=$_GET['currentPage'];
	if(isset($_POST['school']))
		$school=$_POST['school'];
	if(isset($_GET['school']))
		$school=$_GET['school'];
	if(isset($_POST['name']))
		$name=$_POST['name'];
	if(isset($_GET['name']))
		$name=$_GET['name'];
	if(isset($_POST['class']))
		$class=$_POST['class'];
	if(isset($_GET['class']))
		$class=$_GET['class'];
	if(isset($_POST['com']))
		$com=$_POST['com'];
	if(isset($_GET['com']))
		$com=$_GET['com'];
	
	$start_row=($currentPage-1)*$span + 1;
	$end_row=$start_row+$span-1;
	
	
	//echo "school=".$school."<br/>";
	//echo "class=".$class."<br/>";
	
// 	$sqlstringList="select top(10) * ".
// 			" from OuterStatus ".
// 			" order by CreateDate desc ";
	
	$sqlstringList="
	select *,convert(char, Birthday, 111)  as birthdayformat,convert(char, UpdateDate, 111)  as updatedayformat
	from (
	select ROW_NUMBER() over (order by UpdateDate desc) as rowid ,*
	from OuterStatus where 1=1 ";
	
	if($school!="")
		$sqlstringList .= " and schoolName like '%".$school."%'";
	if($com!="")
		$sqlstringList .= " and comName like '%".$com."%'";
	if($name!="")
		$sqlstringList .= " and Name like '%".$name."%'";
	if($class==1)
		$sqlstringList .= " and isStudent=1 ";
	if($class==2)
		$sqlstringList .= " and isJob=1 ";
	$sqlstringList .= " ) as tmptable
	where rowid between ".$start_row." and ".$end_row;
	
	//echo $sqlstringList;
	
	$rsList=$db->query($sqlstringList);
	$count = 0;
	
	
	$sqlstringTotalRow="select count(*) as total_row from OuterStatus where 1=1 ";
	if($school!="")
		$sqlstringTotalRow .= " and schoolName like '%".$school."%'";
	if($com!="")
		$sqlstringTotalRow .= " and comName like '%".$com."%'";
	if($name!="")
		$sqlstringTotalRow .= " and Name like '%".$name."%'";
	if($class==1)
		$sqlstringTotalRow .= " and isStudent=1 ";
	if($class==2)
		$sqlstringTotalRow .= " and isJob=1 ";
	
	//echo "<br/>".$sqlstringTotalRow;
	
	$rsTotalRow=$db->query($sqlstringTotalRow);
	
	$dsTotalRow = $rsTotalRow->fetch();
	$count=$dsTotalRow["total_row"];
	
	$totalrow=$count;
	$totalpage=$totalrow % $span == 0?$totalrow / $span:$totalrow / $span+1;
	$totalpage=floor($totalpage);
	
	//echo "row=".$totalrow."<br/>";
	//echo "span=".$span;
	//echo "total=".$totalpage;
			

	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<script type="text/javascript" src="JS/jquery-1.11.1.js"></script>
<script type="text/javascript">
$(function(){

		$("input[name='delbutton']").click(function(){
				if(confirm("確定要刪除資料嗎?")==false)
					return false;
				var eid=$(this).attr("eid");
				$.ajax({

					type:"POST",
					dataType:"text",
					data:{eid:eid,action:"delperson"},
					url:"outerpersonoperation.php",
					
					success:function(msg){

						if(msg=="true")
							alert("刪除成功");
						else
							alert("刪除失敗");

						location.reload()
						//window.href="outerperson_list.php";
						//alert(msg);

						
						},
					error:function(){

						alert("Ajax途中出錯了!!");
						
						}	
					});
			});
	
});
</script>
</head>
<body>
<form action="outerperson_list.php" method="post">
<div align="center">
姓名:<input type="text" name="name" value="<?php if($name!=""){echo $name;}?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
學校:<input type="text" name="school" value="<?php if($school!=""){echo $school;}?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
公司:<input type="text" name="com" value="<?php if($com!=""){echo $com;}?>" />
<br/>
身分選擇:
<input type="radio" name="class" value="defaultvalue" <?php if($class==defaultvalue){echo "checked";}?> />預設&nbsp;&nbsp;
<input type="radio" name="class" value="1" <?php if($class==1){echo "checked";}?> />學生&nbsp;&nbsp;
<input type="radio" name="class" value="2" <?php if($class==2){echo "checked";}?> />專職&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="搜尋">
</div>
</form>
<br/>
<table border="1" align="center" width="800px">
<tr>
<td>姓名</td>
<td>生日</td>
<td>學生</td>
<td>學校</td>
<td>專職</td>
<td>公司</td>
<td>建立時間</td>
<td>編輯</td>
<td>刪除</td>
</tr>
                                                                                     
<?php 
$rsList=$db->query($sqlstringList);
while ( $dsList = $rsList->fetch() ) {?>
		
		<tr>
		<td><?php echo $dsList["Name"]?></td>
		<td><?php echo $dsList["birthdayformat"]?></td>
		<td><?php if($dsList["isStudent"]==1) echo "Yes"; else echo "No";?></td>
		<td><?php if($dsList["schoolName"]!=-1) echo $dsList["schoolName"];else echo "---";?></td>
		<td><?php if($dsList["isJob"]==1) echo "Yes"; else echo "No";?></td>
		<td><?php if($dsList["comName"]!=-1) echo $dsList["comName"];else echo "---";?></td>
		<td><?php echo $dsList["updatedayformat"]?></td>
		<td><a href="outerperson_edit.php?id=<?php echo $dsList["Eid"];?>" target="_blank">進入編輯</a></td>
		<td><input type="button" name="delbutton" value="刪除" eid="<?php echo $dsList["Eid"];?>" /></td>
		</tr>
		
<?php		
}
?>
</table>
<?php 
$first_link="outerperson_list.php?currentPage=1";
$prev_link="outerperson_list.php?currentPage=".($currentPage - 1);
$next_link="outerperson_list.php?currentPage=".($currentPage + 1);
$last_link="outerperson_list.php?currentPage=".$totalpage;

if($school!=""){
	$first_link.="&school=".$school;
	$prev_link.="&school=".$school;
	$next_link.="&school=".$school;
	$last_link.="&school=".$school;
}

if($com!=""){
	$first_link.="&com=".$com;
	$prev_link.="&com=".$com;
	$next_link.="&com=".$com;
	$last_link.="&com=".$com;
}

if($name!=""){
	$first_link.="&name=".$name;
	$prev_link.="&name=".$name;
	$next_link.="&name=".$name;
	$last_link.="&name=".$name;
}

if($class!=""){
	if($class==1){
		$first_link.="&class=".$class;
		$prev_link.="&class=".$class;
		$next_link.="&class=".$class;
		$last_link.="&class=".$class;
	}
	else if($class==2){
		$first_link.="&class=".$class;
		$prev_link.="&class=".$class;
		$next_link.="&class=".$class;
		$last_link.="&class=".$class;
	}
	else if($class=="defaultvalue"){
		$first_link.="&class=".$class;
		$prev_link.="&class=".$class;
		$next_link.="&class=".$class;
		$last_link.="&class=".$class;
	}
}

?>
<div align="center"><span>目前在第   <?php echo $currentPage?>   頁</span>
<span>總共有<?php echo $totalpage?>頁</span>
<span>   總共有<?php echo $totalrow?>筆</span>
</div>
<div align="center">
<a href="<?php echo $first_link;?>">第一頁</a>
<?php if($currentPage>1){echo "<a href='".$prev_link."'>上一頁</a>";}?>
<?php if($currentPage<$totalpage){echo "<a href='".$next_link."'>下一頁</a>";}?>
<a href="<?php echo $last_link;?>">最末頁</a>
</div>
</body>
</html>