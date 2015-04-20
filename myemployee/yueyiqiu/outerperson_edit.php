<?php 

include("connectSQL.php");

$peid=$_GET['id'];


$year=Date(Y)-1911;
$year_start=$year-80;
$year_end=$year_start+60;



$sqlOuterTitle="select * from OuterTitle";
$rsOuterTitle=$db->query($sqlOuterTitle);

$OuterTitleArray=array();
$ptr=0;
while ( $dsOuterTitle = $rsOuterTitle->fetch() ) {
	$code = $dsOuterTitle ["TitleCode"];
	$name = $dsOuterTitle ["TitleName"];
	//echo $code.$name;
	$OuterTitleArray[$ptr][0]=$code;
	$OuterTitleArray[$ptr][1]=$name;
	$ptr++;
}


$sql="select * from outerstatus where Eid='".$peid."'";

$rs=$db->query($sql);
$ds = $rs->fetch();
$isStudent=$ds["isStudent"];
$isJob=$ds["isJob"];
$birth=$ds["Birthday"];
$id=$ds["IdNo"];
$personname=$ds["Name"];
$school=$ds["schoolName"];
$com=$ds["comName"];
$edu=$ds["Education"];
$studentgrade=$ds["studentGrade"];

if($school=="-1")
	$school="";

if($com=="-1")
	$com="";

$year=substr($birth, 0, 4 );
$month=substr($birth, 5 , 2 );
$day=substr($birth, 8 , 2 );

if(substr($month, 0,1)==0)
	$month=substr($month, 1,1);

if(substr($day, 0,1)==0)
	$day=substr($day, 1,1);

$year=$year-1911;

//echo $year."//".$month."//".$day."<br/>";

//echo $birth;

//echo $isStudent;


$FileTypeArray=array();

$sqlFileType="select * from UploadType where TypeClass='A'";
$rsFileType=$db->query($sqlFileType);
$ptr=0;
while ( $dsFileType = $rsFileType->fetch() ) {

	$no=$dsFileType["TypeNo"];
	$name=$dsFileType["TypeName"];
	$FileTypeArray[$ptr][0]=$no;
	$FileTypeArray[$ptr][1]=$name;
	$ptr++;
	//array_push($FileTypeArray, $dsFileType["TypeName"]);
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>修改請核人員資料</title>
<script type="text/javascript" src="JS/jquery-1.11.1.js"></script>
<script type="text/javascript">

$(function(){

	var student=$("input[name='isStudentValue']").val();
	var job=$("input[name='isJobValue']").val();	

	//alert(student);
	//alert(job);
	//$("input[name='isStudent']")[0].checked=true;
	
	if(student==1){

		$("#student_yes").css("display","block");
		$("#student_no").css("display","none");
		$("#job_yes").css("display","none");
		$("#job_no").css("display","none");
		
		$("input[name='isStudent']").each(function(){
				//alert($(this).val());
				if($(this).val()=="y"){
					this.checked=true;
					}
			});
		
		}
	else{
		$("input[name='isStudent']").each(function(){
			//alert($(this).val());
			if($(this).val()=="n"){
				this.checked=true;
				}
		});
		
		$("#student_yes").css("display","none");
		$("#student_no").css("display","block");

		if(job==1){
			$("#job_yes").css("display","block");
			$("#job_no").css("display","none");
			$("input[name='isJob']").each(function(){
				//alert($(this).val());
				if($(this).val()=="y"){
					this.checked=true;
					}
			});
			}
		else{
			$("#job_yes").css("display","none");
			$("#job_no").css("display","block");
			$("input[name='isJob']").each(function(){
				//alert($(this).val());
				if($(this).val()=="n"){
					this.checked=true;
					}
			});
			}
		
		}


	$("input[name='isStudent']").click(function(){
		
		var isStudent=$("input[name='isStudent']:checked").val();
		
		//alert(isStudent);
		
		if(isStudent=="y"){
			$("#student_yes").css("display","block");
			$("#student_no").css("display","none");
			$("#job_yes").css("display","none");
			$("#job_no").css("display","none");
			}
		else if(isStudent=="n"){
			$("#student_yes").css("display","none");
			$("#student_no").css("display","block");
			}
	});


	$("input[name='isJob']").click(function(){
		
		var isJob=$("input[name='isJob']:checked").val();
		
		//alert(isStudent);
		
		if(isJob=="y"){
			$("#job_yes").css("display","block");
			$("#job_no").css("display","none");
			}
		else if(isJob=="n"){
			$("#job_yes").css("display","none");
			$("#job_no").css("display","block");
			}
	});
	
	

	var year=$("input[name='year']").val();
	var month=$("input[name='month']").val();
	var day=$("input[name='day']").val();
	var edu=$("input[name='edu']").val();
	var studentgrade=$("input[name='studentgrade']").val();
	
	$("select[name='select_year'] option").filter(function() {
	    //may want to use $.trim in here
	    //alert($(this).val());
	    return $(this).val() == year; 
	}).prop('selected', true);

	$("select[name='select_month'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == month; 
	}).prop('selected', true);

	$("select[name='select_day'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == day; 
	}).prop('selected', true);

	$("select[name='job_grade']:eq(0) option").filter(function() {
	    //may want to use $.trim in here
	    //alert($(this).val());
	    return $(this).val() == edu; 
	}).prop('selected', true);

	$("select[name='job_grade']:eq(1) option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == edu; 
	}).prop('selected', true);

	$("select[name='student_grade'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == studentgrade; 
	}).prop('selected', true);
	
	//var rr=$("select[name='job_grade']:eq(1) option").length;
	//var ww=$("select[name='student_grade']").length;
	
	
	
	alert("rr="+edu);
	//alert("ww="+studentgrade);
// 	$("select[name='select_day'] option").filter(function() {
// 	    //may want to use $.trim in here
// 	    return $(this).val() == day; 
// 	}).prop('selected', true);

// 	$("select[name='select_day'] option").filter(function() {
// 	    //may want to use $.trim in here
// 	    return $(this).val() == day; 
// 	}).prop('selected', true);
	

	$("select[name='select_month']").change(function(){

		var value=$(this).val();
		if(value==4||value==6||value==9||value==11){
			//alert("11");
			
			$("select[name='select_day']").find("option[value='31']").remove();
			var ptr=$("select[name='select_day']").find(":last").val();
			
			if(ptr==29){
				$("select[name='select_day']").append("<option value='30'>30</option>");
			}
			
			}
		if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

			//var ptr=$("select[name='select_day']").find("option[value='31']");
			
			var ptr=$("select[name='select_day']").find(":last").val();
			
			if(ptr==30){
			$("select[name='select_day']").append("<option value='31'>31</option>");
			}
			
			if(ptr==29){
				$("select[name='select_day']").append("<option value='30'>30</option>");
				$("select[name='select_day']").append("<option value='31'>31</option>");
			}
				
			
			}
		if(value==2){

			$("select[name='select_day']").find("option[value='30']").remove();
			$("select[name='select_day']").find("option[value='31']").remove();
			
			}
});
	
			$("input[name='editfile']").click(function(){
					var id=$(this).attr("blockid");
					var fid=$(this).attr("fid");
					var title=$("input[name='filetitle']").eq(id).val();
					var type=$("select[name='filetype']").eq(id).val();
					
					//alert(fid);
					$.ajax({

						type:"POST",
						dataType:"text",
						data:{fid:fid,title:title,type:type,action:"editfile"},
						url:"fileoperation.php",
						
						success:function(msg){

							if(msg=="true")
								alert("修改成功");
							else
								alert("修改失敗");
							
							},
						error:function(){

							alert("Ajax途中出錯了!!");
							
							}	
						});

					
				});

			$("input[name='delfile']").click(function(){
				if(confirm("確定要刪除檔案嗎?")==false)
					return false;
				var id=$(this).attr("blockid");
				var bkid="#fileblock"+id;
				$(bkid).css("display","none");
				var fid=$(this).attr("fid");
				$.ajax({

					type:"POST",
					dataType:"text",
					data:{fid:fid,action:"delfile"},
					url:"fileoperation.php",
					
					success:function(msg){

						if(msg=="true")
							alert("刪除成功");
						else
							alert("刪除失敗");
						
						},
					error:function(){

						alert("Ajax途中出錯了!!");
						
						}	
					});
				
			});


			$("#addFile").click(function() {
				
				$("#clonediv").clone().css("display","block").insertAfter(this);
			});

			var inputSchoolflag=false;
			var inputComflag=false;
			
			$("input[name='update']").click(function(){
				var student=$("input[name='isStudent']:checked").val();
				var job=$("input[name='isJob']:checked").val();
				alert(job);
				if(!student){
						alert("請選擇是否學生");
						return false;
					}
				else{
						if(student=="y"){
							
							if($("input[name='schoolName']").val()==""||$.MyExtend.checkinput($("input[name='schoolName']").val())){
									alert("學校名稱包含特殊字元或為空");
									$("input[name='schoolName']").val("");
									return false;
								}
						}
						else{	
								if(!job){
									alert("請選擇是否專職");
									return false;
								}
								else{
									if(job=="y"){
										if($("input[name='comName']").val()==""||$.MyExtend.checkinput($("input[name='comName']").val())){
											alert("公司名稱包含特殊字元或為空");
											$("input[name='comName']").val("");
											return false;
											}
										}	
									}					
							}

				}
				alert("end!!");
				$("form[name='apply_form']" ).submit();
			});



			$("select[name='job_grade']:eq(0)").change(function(){
				var value=$(this).val();
				$("select[name='job_grade']:eq(1) option").filter(function() {
				    
				    return $(this).val() == value; 
				}).prop('selected', true);
			});

			$("select[name='job_grade']:eq(1)").change(function(){
				var value=$(this).val();
				$("select[name='job_grade']:eq(0) option").filter(function() {
				    
				    return $(this).val() == value; 
				}).prop('selected', true);
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
<input type="hidden" name="isStudentValue" value="<?php echo $isStudent;?>" />
<input type="hidden" name="isJobValue" value="<?php echo $isJob;?>" />
<input type="hidden" name="year" value="<?php echo $year;?>" />
<input type="hidden" name="month" value="<?php echo $month;?>" />
<input type="hidden" name="day" value="<?php echo $day;?>" />
<input type="hidden" name="edu" value="<?php echo $edu;?>" />
<input type="hidden" name="studentgrade" value="<?php echo $studentgrade?>" />

<fieldset>
<legend>校外人士申請</legend>
<form action="outerpersonoperation.php" name="apply_form" method="post" enctype="multipart/form-data">
<table width="1000px" border="1" bordercolor="black">
<tr>
<td width="50%">身分證字號:</td>
<td width="50%"><input type="text" readonly="readonly" name="id" class="required" value="<?php echo $id;?>" /><span id="idmsg"></span></td>
</tr>
<tr>
<td>姓名:</td>
<td><input type="text" readonly="readonly" name="name" class="required" value="<?php echo $personname;?>" /><span id="namemsg"></span></td>
</tr>
<tr>
<td>出生年月日</td>
<td>
民國
<select name="select_year">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>
年
<select name="select_month">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?></select>月
<select name="select_day">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?></select>日

</select>
</td>
</tr>
<tr>
<td colspan="2">
詳細資料
</td>
</tr>
<tr>
<td colspan="2">
是否為學生<br/>
是<input type="radio" name="isStudent" value="y"/>&nbsp;&nbsp;&nbsp;&nbsp;
否<input type="radio" name="isStudent" value="n"/>
<hr/>
<div id="student_yes">
<table>
<tr>
<td>
學校名稱:<input type="text" name="schoolName" value="<?php echo $school;?>" /><span id="schoolmsg"></span>
</td>
</tr>
<tr>
<td>
選擇級別:
<select name="student_grade">
<option value="0">博士候選人</option>
<option value="1">博班學生</option>
<option value="2">碩班學生</option>
<option value="3">大學部學生</option>
</select>
</td>
</tr>
</table>

</div>
<div id="student_no">
是否為專職<br/>
是<input type="radio" name="isJob" value="y"/>&nbsp;&nbsp;&nbsp;&nbsp;
否<input type="radio" name="isJob" value="n"/>
<br/>

<hr/>
</div>
<div id="job_yes">
公司名稱:<input type="text" name="comName" value="<?php echo $com;?>" /><span id="commsg"></span>
<br/>
選擇學歷:
<select name="job_grade">
<?php

$option_item;
for($i=0;$i<count($OuterTitleArray);$i++){
	$option_item="<option value='".$OuterTitleArray[$i][0]."'>".$OuterTitleArray[$i][1]."</option>";
	echo $option_item;
} 
?>
</select>
</div>
<div id="job_no">
選擇學歷:
<select name="job_grade">
<?php

$option_item;
for($i=0;$i<count($OuterTitleArray);$i++){
	$option_item="<option value='".$OuterTitleArray[$i][0]."'>".$OuterTitleArray[$i][1]."</option>";
	echo $option_item;
} 
?>
</select>
</div>
</td>
</tr>
<tr>
<td colspan="2">
<div id="upload_div">
<?php 
	$sqlUploadFile="select * from UploadData where PEid='".$peid."'";
	//echo $sqlUploadFile;
	$rsUploadFile=$db->query($sqlUploadFile);
	$ptr=0;
	while ( $dsUploadFile = $rsUploadFile->fetch() ) {

			$fid=$dsUploadFile["Fid"];
			$type=$dsUploadFile["type"];
			$title=$dsUploadFile["FileTitle"];
		?>
			
<div id="fileblock<?php echo $ptr;?>" style="padding-top:10px;">
<!-- <input type="file" name="upload[]"> -->
<a href="viewfile.php?fid=<?php echo $fid?>" target="_blank">檢視檔案</a>&nbsp;&nbsp;&nbsp;
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option ";
		if($type==$FileTypeArray[$i][0])
			$option_item.="selected='selected' ";			
		$option_item.="value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle" value="<?php echo $title;?>">
<input type="button" name="editfile" blockid="<?php echo $ptr;?>" fid="<?php echo $fid;?>" value="修改" />
<input type="button" name="delfile" blockid="<?php echo $ptr;?>" fid="<?php echo $fid;?>" value="刪除" />
</div>
	<?php $ptr++;}	?>
</div>
<hr />
<input type="button" value="新增上傳欄位" id="addFile">
<div>
<br />
<input type="file" name="upload[]">
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype[]">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle[]" />
<br />
</div>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<!--<input type="submit" value="儲                        存" name="save" />-->
<input type="button" value="儲                        存" name="update" />
</td>
</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="savetype" value="" />
<input type="hidden" name="peid" value="<?php echo $peid;?>" />
</form>
</fieldset>

<div id="clonediv" style="display: none;">
<br />
<input type="file" name="upload[]">
檔案類型:&nbsp;&nbsp;&nbsp;
<select name="filetype[]">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
檔案標題:&nbsp;&nbsp;&nbsp;
<input type="text" name="filetitle[]" />
<br />
</div>
</body>
</html>