<?php 
	include("connectSQL.php");
	//$_SESSION["UserID"]="Z0373";
	//echo "sessionID=".$_SESSION["UserID"];
	
	$span=20;
	$currentPage=1;
	$school="";
	$class="";
	$name="";
	$com="";
	$isnctu="";
	$idcode="";
	$idno="";
	
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
	if(isset($_POST['isnctu']))
		$isnctu=$_POST['isnctu'];
	if(isset($_GET['isnctu']))
		$isnctu=$_GET['isnctu'];
	if(isset($_POST['idcode']))
		$idcode=$_POST['idcode'];
	if(isset($_GET['idcode']))
		$idcode=$_GET['idcode'];
	if(isset($_POST['idno']))
		$idno=$_POST['idno'];
	if(isset($_GET['idno']))
		$idno=$_GET['idno'];
	
	$start_row=($currentPage-1)*$span + 1;
	$end_row=$start_row+$span-1;
	
	//=============================================================================
	
	$identity="";
	
	$sql_s="select *
		    from StudentData
		    where std_stdcode='".$_SESSION["UserID"]."' and 學籍之在學狀況  in ('在學','應畢','延畢')";
	
	$sql_j="select *
	   	  from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime]
		  where EmpNo ='".$_SESSION["UserID"]."' and 在離職狀態='在職'";
	
	$rs_S=$db->query($sql_s);
	if ( $ds_S = $rs_S->fetch() ) {
		$identity=$ds_S["std_pid"];
	}
	else{
		$rs_J=$db->query($sql_j);
		if ( $ds_J = $rs_J->fetch() ) {
			$identity=$ds_J["idno"];
		}
	}
	
	
	//echo "power=".$_SESSION["power"];
	//=============================================================================
	
	$sqlstringList="
	select *,convert(char, Birthday, 111)  as birthdayformat,convert(char, UpdateDate, 111)  as updatedateformat,convert(char, CreateDate, 111)  as createdateformat
	from (
	select ROW_NUMBER() over (order by UpdateDate desc) as rowid ,*
	from OuterStatus where 1=1 ";
	
	$sqlstringList.=" and RecordStatus <>'-1'";
	if($_SESSION["power"]!=1){
		$sqlstringList.=" and (CreateEmp='".$_SESSION["UserID"]."' or IdNo='".$identity."')";
	}
	if($school!="")
		$sqlstringList .= " and schoolName like '%".$school."%'";
	if($com!="")
		$sqlstringList .= " and comName like '%".$com."%'";
	if($name!="")
		$sqlstringList .= " and Name like '%".$name."%'";
	if($idno!="")
		$sqlstringList .= " and IdNo like '%".$idno."%'";
	if($idcode!="")
		$sqlstringList .= " and IdCode like '%".$idcode."%'";
	if($name!="")
		$sqlstringList .= " and Name like '%".$name."%'";
	if($class==1)
		$sqlstringList .= " and isStudent=1 ";
	if($class==2)
		$sqlstringList .= " and isJob=1 ";
	if($isnctu==1)
		$sqlstringList .= " and isNCTU=1 ";
	if($isnctu==2)
		$sqlstringList .= " and isNCTU=0 ";
	$sqlstringList .= " ) as tmptable
	where rowid between ".$start_row." and ".$end_row;
	//$sqlstringList .= " order by createdateformat desc"
	//echo $sqlstringList;
	
	$rsList=$db->query($sqlstringList);
	$count = 0;
	
	
	$sqlstringTotalRow="select count(*) as total_row from OuterStatus where 1=1 ";
	
	$sqlstringTotalRow.=" and RecordStatus <>'-1'";
	
	if($_SESSION["power"]!=1){
		$sqlstringTotalRow.=" and (CreateEmp='".$_SESSION["UserID"]."' or IdNo='".$identity."')";
	}
	
	if($school!="")
		$sqlstringTotalRow .= " and schoolName like '%".$school."%'";
	if($com!="")
		$sqlstringTotalRow .= " and comName like '%".$com."%'";
	if($name!="")
		$sqlstringTotalRow .= " and Name like '%".$name."%'";
	if($idno!="")
		$sqlstringTotalRow .= " and IdNo like '%".$idno."%'";
	if($idcode!="")
		$sqlstringTotalRow .= " and IdCode like '%".$idcode."%'";
	if($class==1)
		$sqlstringTotalRow .= " and isStudent=1 ";
	if($class==2)
		$sqlstringTotalRow .= " and isJob=1 ";
	if($isnctu==1)
		$sqlstringTotalRow .= " and isNCTU=1 ";
	if($isnctu==2)
		$sqlstringTotalRow .= " and isNCTU=0 ";
	
	$rsTotalRow=$db->query($sqlstringTotalRow);
	
	$dsTotalRow = $rsTotalRow->fetch();
	$count=$dsTotalRow["total_row"];
	
	$totalrow=$count;
	$totalpage=$totalrow % $span == 0?$totalrow / $span:$totalrow / $span+1;
	$totalpage=floor($totalpage);
	
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
<style type="text/css">
table {
	border-collapse: collapse;
	border: 2px;
}
.title{
	font-weight: bolder;
}
.table_title{
	background-color: #C9CBE0;
}
a{	
	color:#000088;
	text-decoration: none;
}

a:HOVER {
	font-weight: bold;
}
</style>
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
						alert(msg);
						if(msg=="true")
							alert("刪除成功");
						else
							alert("刪除失敗");

						location.reload()
						

						
						},
					error:function(){

						alert("Ajax途中出錯了!!");
						
						}	
					});
			});


			$("input[name='editbutton_in']").click(function(){
					var eid=$(this).attr("eid");
					window.open("innerperson_edit.php?id="+eid);
				});

			$("input[name='editbutton_out']").click(function(){
				var eid=$(this).attr("eid");
				window.open("outerperson_edit.php?id="+eid);
			});
		
			$("input[name='school']").blur(function(){
						var value=$(this).val();
						if($.MyExtend.checkinput(value)==true){
								alert("不可輸入特殊字元");
								$(this).val("");
							}
				});
			$("input[name='com']").blur(function(){
				var value=$(this).val();
				if($.MyExtend.checkinput(value)==true){
						alert("不可輸入特殊字元");
						$(this).val("");
					}
		});
			$("input[name='name']").blur(function(){
				var value=$(this).val();
				if($.MyExtend.checkinput(value)==true){
						alert("不可輸入特殊字元");
						$(this).val("");
					}
		});

			$("input[name='idno']").blur(function(){
				var value=$(this).val();
				if($.MyExtend.checkinput(value)==true){
						alert("不可輸入特殊字元");
						$(this).val("");
					}
		});

			$("input[name='idcode']").blur(function(){
				var value=$(this).val();
				if($.MyExtend.checkinput(value)==true){
						alert("不可輸入特殊字元");
						$(this).val("");
					}
		});
		
});


$.MyExtend={

		checkinput:function(input){
			var pattern = new RegExp("[_<>^:;%/&*-+=<>#$@.,?()'\"\\]\\[]");
			var res = pattern.test(input);
			return res;
		}

}

</script>
</head>
<body bgcolor='#c1cfb4'>
<form action="outerperson_list.php" method="post">
<div align="left" style="padding-left: 200px;">
姓名:<input type="text" name="name" value="<?php if($name!=""){echo $name;}?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
學校:<input type="text" name="school" value="<?php if($school!=""){echo $school;}?>"/>&nbsp;&nbsp;&nbsp;&nbsp;
公司:<input type="text" name="com" value="<?php if($com!=""){echo $com;}?>" />
<br/>
<br/>
身分證字號/居留證號碼:<input type="text" name="idno" value="<?php if($idno!=""){echo $idno;}?>" />&nbsp;&nbsp;&nbsp;&nbsp;
學號/工號:<input type="text" name="idcode" value="<?php if($idcode!=""){echo $idcode;}?>" />&nbsp;&nbsp;&nbsp;&nbsp;
<br/>
<br/>
身分選擇:
<input type="radio" name="class" value="defaultvalue" <?php if($class==defaultvalue){echo "checked";}?> />不分&nbsp;&nbsp;
<input type="radio" name="class" value="1" <?php if($class==1){echo "checked";}?> />學生&nbsp;&nbsp;
<input type="radio" name="class" value="2" <?php if($class==2){echo "checked";}?> />專職&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br/>
<br/>
校內或校外:
<input type="radio" name="isnctu" value="nctudefault" <?php if($isnctu==nctudefault){echo "checked";}?> />不分&nbsp;&nbsp;
<input type="radio" name="isnctu" value="1" <?php if($isnctu==1){echo "checked";}?> />校內 &nbsp;&nbsp;
<input type="radio" name="isnctu" value="2" <?php if($isnctu==2){echo "checked";}?> />校外 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="搜尋">
</div>
</form>
<br/>
<table border="1" align="center" width="1000px">
<tr class="table_title">
<td class="title">姓名</td>
<td class="title">生日</td>
<td class="title">學生</td>
<td class="title">學校</td>
<td class="title">專職</td>
<td class="title">公司</td>
<td class="title">身分別</td>
<td class="title">建立時間</td>
<td class="title">修改時間</td>
<td class="title">編輯</td>
<td class="title">刪除</td>
</tr>
                                                                                     
<?php 
$rsList=$db->query($sqlstringList);
while ( $dsList = $rsList->fetch() ) {?>
		
		<tr>
		<td><?php echo $dsList["Name"]?></td>
		<td><?php 
			if($dsList["birthdayformat"]=="1900/01/01"){
				echo "---";
			}
			else{
				echo $dsList["birthdayformat"];
			}
		?></td>
		<td><?php if($dsList["isStudent"]==1) echo "Yes"; else echo "No";?></td>
		<td><?php if($dsList["schoolName"]!=-1) echo $dsList["schoolName"];else echo "---";?></td>
		<td><?php if($dsList["isJob"]==1) echo "Yes"; else echo "No";?></td>
		<td><?php if($dsList["comName"]!=-1) echo $dsList["comName"];else echo "---";?></td>
		<td>
		<?php 
			if($dsList["isNCTU"]==1)
				echo "本校";
			else
				echo "校外"
		?>
		</td>
		<td><?php echo $dsList["createdateformat"]?></td>
		<td><?php echo $dsList["updatedateformat"]?></td>
		
		<td>
		<!-- <a href="outerperson_edit.php?id=<?php echo $dsList["Eid"];?>" target="_blank">進入編輯</a> -->
		<?php if($dsList["isNCTU"]==1){?>
		<input type="button" name="editbutton_in" value="編輯" eid="<?php echo $dsList["Eid"];?>" />
		<?php 	
		}
		else{?>
		<input type="button" name="editbutton_out" value="編輯" eid="<?php echo $dsList["Eid"];?>" />	
		<?php
		} ?>
		
		</td>
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

if($idno!=""){
	$first_link.="&idno=".$idno;
	$prev_link.="&idno=".$idno;
	$next_link.="&idno=".$idno;
	$last_link.="&idno=".$idno;
}

if($idcode!=""){
	$first_link.="&idcode=".$idcode;
	$prev_link.="&idcode=".$idcode;
	$next_link.="&idcode=".$idcode;
	$last_link.="&idcode=".$idcode;
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

if($isnctu!=""){
	$first_link.="&isnctu=".$isnctu;
	$prev_link.="&isnctu=".$isnctu;
	$next_link.="&isnctu=".$isnctu;
	$last_link.="&isnctu=".$isnctu;
}

?>
<div align="center"><span>目前在第   <?php echo $currentPage?>   頁</span>
<span>總共有<?php echo $totalpage?>頁</span>
<span>   總共有<?php echo $totalrow?>筆</span>
</div>
<div align="center">
<a href="<?php echo $first_link;?>">第一頁&nbsp;&nbsp;&nbsp;</a>
<?php if($currentPage>1){echo "<a href='".$prev_link."'>上一頁&nbsp;&nbsp;&nbsp;</a>";}?>
<?php if($currentPage<$totalpage){echo "<a href='".$next_link."'>下一頁</a>&nbsp;&nbsp;&nbsp;";}?>
<a href="<?php echo $last_link;?>">最末頁</a>
</div>
</body>
</html>