<?php 
	
include("connectSQL.php");	

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

$year=Date(Y)-1911;
$year_start=$year-20;
$year_end=$year+10;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>請核人員建檔</title>
<style type="text/css">
</style>
<script type="text/javascript" src="JS/jquery-1.11.1.js"></script>
<script type="text/javascript">
$(function(){

$("#student_yes").css("display","none");
$("#student_no").css("display","none");
$("#job_yes").css("display","none");
$("#job_no").css("display","none");


var inputIdflag=false;
var inputNameflag=false;
var inputSchoolflag=false;
var inputComflag=false;

$("form :input").blur(function(){

		if($(this).is("input[name='id']")){
			//alert("123");

			var msg;

			if($(this).val()!=""){
				if($.MyExtend.checkinput($(this).val())){
					msg="<font color='red'>輸入包含特殊字元</font>";
					$(this).val("");
					inputIdflag=false;
					}
				else if($.MyExtend.checkid($(this).val())){
					msg="<font color='blue'>OK</font>";
					inputIdflag=true;
				}
				else{
					msg="<font color='red'>身分證字號有誤</font>";
					inputIdflag=false;
					}
				}
			else if($(this).val()==""){
					msg="<font color='red'>身分證字號為必填欄位</font>";
				}
			
			$("#idmsg").html(msg);
		}	

		if($(this).is("input[name='name']")){
			var msg;
			if($.MyExtend.checkinput($(this).val())){
				msg="<font color='red'>輸入包含特殊字元</font>";
				$(this).val("");
				inputNameflag=false;
				}
			else if($(this).val()==""){
				msg="<font color='red'>姓名為必填欄位</font>";
				inputNameflag=false;
			}
			else{
				msg="<font color='blue'>OK</font>";
				inputNameflag=true;
			}
			//alert(msg);
			$("#namemsg").html(msg);
		}

		if($(this).is("input[name='schoolName']")){
			var msg;
			if($.MyExtend.checkinput($(this).val())){
				msg="<font color='red'>輸入包含特殊字元</font>";
				$(this).val("");
				inputSchoolflag=false;
				}
			else if($(this).val()==""){
				msg="<font color='red'>學校為必填欄位</font>";
				inputSchoolflag=false;
			}
			else{
				msg="<font color='blue'>OK</font>";
				inputSchoolflag=true;
			}
			//alert(msg);
			$("#schoolmsg").html(msg);
		}

		if($(this).is("input[name='comName']")){
			var msg;
			if($.MyExtend.checkinput($(this).val())){
				msg="<font color='red'>輸入包含特殊字元</font>";
				$(this).val("");
				inputComflag=false;
				}
			else if($(this).val()==""){
				msg="<font color='red'>公司名稱為必填欄位</font>";
			}
			else{
				msg="<font color='blue'>OK</font>";
				inputComflag=true;
			}
			$("#commsg").html(msg);
		}
		
});

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

$("select[name='vbegin_month']").change(function(){

	var value=$(this).val();
	if(value==4||value==6||value==9||value==11){
		//alert("11");
		
		$("select[name='vbegin_day']").find("option[value='31']").remove();
		var ptr=$("select[name='vbegin_day']").find(":last").val();
		
		if(ptr==29){
			$("select[name='vbegin_day']").append("<option value='30'>30</option>");
		}
		
		}
	if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

		//var ptr=$("select[name='select_day']").find("option[value='31']");
		
		var ptr=$("select[name='vbegin_day']").find(":last").val();
		
		if(ptr==30){
		$("select[name='vbegin_day']").append("<option value='31'>31</option>");
		}
		
		if(ptr==29){
			$("select[name='vbegin_day']").append("<option value='30'>30</option>");
			$("select[name='vbegin_day']").append("<option value='31'>31</option>");
		}
			
		
		}
	if(value==2){

		$("select[name='vbegin_day']").find("option[value='30']").remove();
		$("select[name='vbegin_day']").find("option[value='31']").remove();
		
		}
});


$("select[name='vend_month']").change(function(){

	var value=$(this).val();
	if(value==4||value==6||value==9||value==11){
		//alert("11");
		
		$("select[name='vend_day']").find("option[value='31']").remove();
		var ptr=$("select[name='vend_day']").find(":last").val();
		
		if(ptr==29){
			$("select[name='vend_day']").append("<option value='30'>30</option>");
		}
		
		}
	if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

		//var ptr=$("select[name='select_day']").find("option[value='31']");
		
		var ptr=$("select[name='vend_day']").find(":last").val();
		
		if(ptr==30){
		$("select[name='vend_day']").append("<option value='31'>31</option>");
		}
		
		if(ptr==29){
			$("select[name='vend_day']").append("<option value='30'>30</option>");
			$("select[name='vend_day']").append("<option value='31'>31</option>");
		}
			
		
		}
	if(value==2){

		$("select[name='vend_day']").find("option[value='30']").remove();
		$("select[name='vend_day']").find("option[value='31']").remove();
		
		}
});


//var isStudent=$("input[name='isStudent']").val();
//alert(isStudent);

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

		var tmp=$("input[name='isJob']:checked").val();
		if(tmp=="y"){
			$("#job_yes").css("display","block");
			$("#job_no").css("display","none");
			}
		else if(tmp=="n"){
			$("#job_yes").css("display","none");
			$("#job_no").css("display","block");
			}
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


var appenddiv="<div style='padding-top:10px;'>"+ 
"<input type='file' name='upload[]'>"+
"檔案類型:&nbsp;&nbsp;&nbsp;"+
"<select name='filetype'>"+
"<option>A</option>"+
"<option>B</option>"+
"<option>C</option>"+
"<option>D</option>"+
"</select>"+
"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+
"檔案標題:&nbsp;&nbsp;&nbsp;"+
"<input type='text' name='filetitle'></div>";


$("#addFile").click(function() {

	var temp=$("#abcd").clone().insertBefore(this);

			var item=$(temp).children("select[name='filetype[]']");
			var bmonth=$(temp).find("select[name='vbegin_month']");
			var bday=$(temp).find("select[name='vbegin_day']");
			var emonth=$(temp).find("select[name='vend_month']");
			var eday=$(temp).find("select[name='vend_day']");


			$(bmonth).change(function(){

				var value=$(this).val();
				if(value==4||value==6||value==9||value==11){
					//alert("11");
					
					$(bday).find("option[value='31']").remove();
					var ptr=$(bday).find(":last").val();
					
					if(ptr==29){
						$(bday).append("<option value='30'>30</option>");
					}
					
					}
				if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

					//var ptr=$("select[name='select_day']").find("option[value='31']");
					
					var ptr=$(bday).find(":last").val();
					
					if(ptr==30){
					$(bday).append("<option value='31'>31</option>");
					}
					
					if(ptr==29){
						$(bday).append("<option value='30'>30</option>");
						$(bday).append("<option value='31'>31</option>");
					}
						
					
					}
				if(value==2){

					$(bday).find("option[value='30']").remove();
					$(bday).find("option[value='31']").remove();
					
					}
			});

			$(emonth).change(function(){

				var value=$(this).val();
				if(value==4||value==6||value==9||value==11){
					//alert("11");
					
					$(eday).find("option[value='31']").remove();
					var ptr=$(eday).find(":last").val();
					
					if(ptr==29){
						$(eday).append("<option value='30'>30</option>");
					}
					
					}
				if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

					//var ptr=$("select[name='select_day']").find("option[value='31']");
					
					var ptr=$(eday).find(":last").val();
					
					if(ptr==30){
					$(eday).append("<option value='31'>31</option>");
					}
					
					if(ptr==29){
						$(eday).append("<option value='30'>30</option>");
						$(eday).append("<option value='31'>31</option>");
					}
						
					
					}
				if(value==2){

					$(eday).find("option[value='30']").remove();
					$(eday).find("option[value='31']").remove();
					
					}
			});

			
			//alert($(eday).attr("name"));
			item.next().css("display","none");
			item.change(function(){

				var itemtext=$(this).children("option:checked").html();
				
				if(itemtext=="工作許可"){
					$(this).next().css("display","block");					
					}
				else{
					$(this).next().css("display","none");	
					}
				
		});
	
});


$("input[name='save']").click(function(){
	
	$("input[name='savetype']").val("save");

	if(!inputIdflag){
		alert("請輸入身分證字號");
		return false;
		}

	if(!inputNameflag){
		alert("請輸入姓名");
		return false;
	}
	var tmp_isstudent=$("input[name='isStudent']:checked").val();
	
	if(!tmp_isstudent){
			alert("請選擇是否學生");
			return false;
		}
	else{
		
		if(tmp_isstudent=="y"){
			if(!inputSchoolflag){
				alert("請輸入學校");
				return false;}
			}
		else{
				var tmp_isjob=$("input[name='isJob']:checked").val();
				if(!tmp_isjob){
						alert("請選擇是否專職");
						return false;
					}
				else{	
						
						if(tmp_isjob=="y"){
							if(!inputComflag){
							alert("請輸入公司");
							return false;
							}
						}
					}
		}
	}
	
	
	var uploadlength=$("input[name='upload[]']").length;
	var uploads=$("input[name='upload[]']");
	for(var i=0;i<uploadlength;i++){
		var filename=uploads[i].value;
		if(filename=="")
			continue;
		
		

		var ext_length = filename.lastIndexOf('.');
		filename = filename.substring(ext_length+1,filename.length);  // get file type
		filename = filename.toLowerCase();

		if(filename != 'jpg' && filename != 'docx' && filename != 'pdf')

		{
		alert("檔案格式有誤");
		return false;
		}

	}

	alert("檔案格式正確");

	
	$("form[name='apply_form']" ).submit();
	
});


$("input[name='saveandnext']").click(function(){
	
	$("input[name='savetype']").val("saveandnext");

	if(!inputIdflag){
		alert("請輸入身分證字號");
		return false;
		}

	if(!inputNameflag){
		alert("請輸入姓名");
		return false;
	}
	var tmp_isstudent=$("input[name='isStudent']:checked").val();
	
	if(!tmp_isstudent){
			alert("請選擇是否學生");
			return false;
		}
	else{
		
		if(tmp_isstudent=="y"){
			if(!inputSchoolflag){
				alert("請輸入學校");
				return false;}
			}
		else{
				var tmp_isjob=$("input[name='isJob']:checked").val();
				if(!tmp_isjob){
						alert("請選擇是否專職");
						return false;
					}
				else{	
						
						if(tmp_isjob=="y"){
							if(!inputComflag){
							alert("請輸入公司");
							return false;
							}
						}
					}
		}
	}
	
	var uploadlength=$("input[name='upload[]']").length;
	var uploads=$("input[name='upload[]']");
	for(var i=0;i<uploadlength;i++){
		var filename=uploads[i].value;
		if(filename=="")
			continue;
		
		

		var ext_length = filename.lastIndexOf('.');
		filename = filename.substring(ext_length+1,filename.length);  // get file type
		filename = filename.toLowerCase();

		if(filename != 'jpg' && filename != 'docx' && filename != 'pdf')

		{
		alert("檔案格式有誤");
		return false;
		}

	}

	alert("檔案格式正確");

	
	$("form[name='apply_form']" ).submit();
});


$("#verifyblock").css("display","none");

$("select[name='filetype[]']").change(function(){
		//alert($(this).html());
		var itemtext=$("select[name='filetype[]'] option:checked").html();
		if(itemtext=="工作許可"){
			$(this).next().css("display","block");
			alert(itemtext);
			}
		else{
			$(this).next().css("display","none");	
			}
		
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


$("#fgh").click(function(){

	var rr=$("select[name='vbegin_month']").length;
	alert(rr);
});

});


$.MyExtend={
		
	ok:function(value){
			alert(value);
		},
	checkid:function(value){

		//alert("ff");
	 	var a = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'W', 'Z', 'I', 'O');
	    var b = new Array(1, 9, 8, 7, 6, 5, 4, 3, 2, 1);
	    var c = new Array(2);
	    var d;
	    var e;
	    var f;
	    var g = 0;
	    var h = /^[a-z](1|2)\d{8}$/i;
	    if (value.search(h) == -1)
	    {
	        return false;
	    }
	    else
	    {
	        d = value.charAt(0).toUpperCase();
	        f = value.charAt(9);
	    }
	    for (var i = 0; i < 26; i++)
	    {
	        if (d == a[i])//a==a
	        {
	            e = i + 10; //10
	            c[0] = Math.floor(e / 10); //1
	            c[1] = e - (c[0] * 10); //10-(1*10)
	            break;
	        }
	    }
	    for (var i = 0; i < b.length; i++)
	    {
	        if (i < 2)
	        {
	            g += c[i] * b[i];
	        }
	        else
	        {
	            g += parseInt(value.charAt(i - 1)) * b[i];
	        }
	    }
	    if ((g % 10) == f)
	    {
	        return true;
	    }
	    if ((10 - (g % 10)) != f)
	    {
	        return false;
	    }
	    return true;	
},
checkinput:function(input){
	var pattern = new RegExp("[_<>^:;%/&*-+=<>#$@.,?()'\"\\]\\[]");
	var res = pattern.test(input);
	return res;
}
}
</script>
<style type="text/css">
table {
	border-collapse: collapse;
}
</style>
</head>
<body bgcolor='#c1cfb4'>

	<fieldset>
		<legend>校外人士申請</legend>
		<form action="outerperson_add.php" name="apply_form" method="post"
			enctype="multipart/form-data">
			<table width="1000px" border="1" bordercolor="black">
				<tr>
					<td width="50%">身分證字號:</td>
					<td width="50%"><input type="text" name="id" class="required" /><span
						id="idmsg"></span></td>
				</tr>
				<tr>
					<td>姓名:</td>
					<td><input type="text" name="name" class="required" /><span
						id="namemsg"></span></td>
				</tr>
				<tr>
					<td>出生年月日</td>
					<td>民國 <select name="select_year">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select> 年 <select name="select_month">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?></select>月 <select name="select_day">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?></select>日 </select>
					</td>
				</tr>
				<tr>
					<td colspan="2">詳細資料</td>
				</tr>
				<tr>
					<td colspan="2">是否為學生<br /> 是<input type="radio" name="isStudent"
						value="y" />&nbsp;&nbsp;&nbsp;&nbsp; 否<input type="radio"
						name="isStudent" value="n" />
						<hr />
						<div id="student_yes">
							<table>
								<tr>
									<td>學校名稱:<input type="text" name="schoolName" /><span
										id="schoolmsg"></span>
									</td>
								</tr>
								<tr>
									<td>選擇級別: <select name="student_grade">
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
							是否為專職<br /> 是<input type="radio" name="isJob" value="y" />&nbsp;&nbsp;&nbsp;&nbsp;
							否<input type="radio" name="isJob" value="n" /> <br />

							<hr />
						</div>
						<div id="job_yes">
							公司名稱:<input type="text" name="comName" /><span id="commsg"></span>
							<br /> 選擇學歷: <select name="job_grade">
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
							選擇學歷: <select name="job_grade">
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
							<div id="abcd" style="padding-top: 10px;">
								<input type="file" name="upload[]"> 檔案類型:&nbsp;&nbsp;&nbsp; 
<select name="filetype[]">
<?php 
	for($i=0;$i<count($FileTypeArray);$i++){
		$option_item="<option value='".$FileTypeArray[$i][0]."'>".$FileTypeArray[$i][1]."</option>";
		echo $option_item;
	}
?>
</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<div id="verifyblock" style="display: block;">
起始:
<select name="vbegin_year">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vbegin_month">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vbegin_day">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
到期:
<select name="vend_year">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vend_month">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vend_day">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
</div>
檔案標題:&nbsp;&nbsp;&nbsp; <input
									type="text" name="filetitle[]" />
							</div>

							<input type="button" value="新增上傳欄位" id="addFile">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="button"
						value="儲                        存" name="save" /> <input
						type="button" value="儲存並新增下一筆" name="saveandnext" /></td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save" /> <input
				type="hidden" name="savetype" value="" />
		</form>
	</fieldset>

	<br />
</body>
</html>