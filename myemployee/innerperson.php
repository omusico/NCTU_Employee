<?php 
	
include("connectSQL.php");	

$sqlOuterTitle="select * from OuterTitle";
$rsOuterTitle=$db->query($sqlOuterTitle);

$OuterTitleArray=array();
$ptr=0;
while ( $dsOuterTitle = $rsOuterTitle->fetch() ) {
	$code = $dsOuterTitle ["TitleCode"];
	$name = $dsOuterTitle ["TitleName"];
	
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
	
}

$year=Date(Y)-1911;
$year_start=$year-80;
$year_end=$year_start+120;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>
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

$("input[name='reset']").click(function(){

	window.location.reload();
});


$("form :input").blur(function(){

		if($(this).is("input[name='id']")){
			

			var msg;

			if($(this).val()!=""){
				if($.MyExtend.checkinput($(this).val())){
					msg="<font color='red'>輸入包含特殊字元</font>";
					$(this).val("");
					inputIdflag=false;
					}
				else{	
						if($.MyExtend.checkduplicateid()==0){
							$.MyExtend.loadinnerpersondata();
							inputIdflag=true;
						}
						else{
							msg="<font color='red'>此人已有資料</font>";
							inputIdflag=false;
							
							}
					}
// 				else if($("input[name='nation']:checked").val()=="native" && $.MyExtend.CheckDomesticOrForeign()=="D"){
					
// 					if($.MyExtend.checkid($(this).val())){
// 						var count=$.MyExtend.checkduplicateid();
// 						if(count>0){
// 								msg="<font color='red'>已經有資料身分證字號重複,無法新建</font>";
// 								inputIdflag=false;
// 							}
						
// 							else{
// 							$.MyExtend.loadinnerpersondata();
// 							//msg="<font color='blue'>OK</font>";
// 							inputIdflag=true;
// 								}
// 						}
// 					else{
// 						//alert("go");
// 						msg="<font color='red'>身分證字號有誤</font>";
// 						inputIdflag=false;
// 						}
				
// 				}
// 			else if($("input[name='nation']:checked").val()=="foreign" && $.MyExtend.CheckDomesticOrForeign()=="F"){
// 				//alert("2");
// 				if($.MyExtend.checkresidencepermitno($(this).val())){
// 					//alert("enter");
// 					var count=$.MyExtend.checkduplicateid();
// 					if(count>0){
// 							msg="<font color='red'>居留證號碼重複</font>";
// 							inputIdflag=false;
// 						}
// 						else{
// 							$.MyExtend.loadinnerpersondata();
// 							//msg="<font color='blue'>OK</font>";
// 							inputIdflag=true;
// 							}
// 					}
// 				else{
// 					//alert("go");
// 					msg="<font color='red'>居留證號碼有誤</font>";
// 					inputIdflag=false;
// 					}
			
// 			}
// 			else if($.MyExtend.CheckDomesticOrForeign()=="U"){
// 					msg="<font color='red'>證號格式未知</font>";
// 					inputIdflag=false;	
// 				}
// 				else{
// 					msg="<font color='red'>身分與輸入證號不相符</font>";
// 					inputIdflag=false;	
// 					}
				}
			else if($(this).val()==""){
					msg="<font color='red'>身分證字號為必填欄位</font>";
				}
			
			$("#idmsg").html(msg);
		}	

// 		if($(this).is("input[name='name']")){
// 			var msg;
// 			if($.MyExtend.checkinput($(this).val())){
// 				msg="<font color='red'>輸入包含特殊字元</font>";
// 				$(this).val("");
// 				inputNameflag=false;
// 				}
// 			else if($(this).val()==""){
// 				msg="<font color='red'>姓名為必填欄位</font>";
// 				inputNameflag=false;
// 			}
// 			else{
// 				msg="<font color='blue'>OK</font>";
// 				inputNameflag=true;
// 			}
// 			//alert(msg);
// 			$("#namemsg").html(msg);
// 		}

// 		if($(this).is("input[name='schoolName']")){
// 			var msg;
// 			if($.MyExtend.checkinput($(this).val())){
// 				msg="<font color='red'>輸入包含特殊字元</font>";
// 				$(this).val("");
// 				inputSchoolflag=false;
// 				}
// 			else if($(this).val()==""){
// 				msg="<font color='red'>學校為必填欄位</font>";
// 				inputSchoolflag=false;
// 			}
// 			else{
// 				msg="<font color='blue'>OK</font>";
// 				inputSchoolflag=true;
// 			}
// 			//alert(msg);
// 			$("#schoolmsg").html(msg);
// 		}

// 		if($(this).is("input[name='comName']")){
// 			var msg;
// 			if($.MyExtend.checkinput($(this).val())){
// 				msg="<font color='red'>輸入包含特殊字元</font>";
// 				$(this).val("");
// 				inputComflag=false;
// 				}
// 			else if($(this).val()==""){
// 				msg="<font color='red'>公司名稱為必填欄位</font>";
// 			}
// 			else{
// 				msg="<font color='blue'>OK</font>";
// 				inputComflag=true;
// 			}
// 			$("#commsg").html(msg);
// 		}
		
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

$("select[name='vbegin_month[]']").change(function(){

	var value=$(this).val();
	var nextI=$(this).next();
	if(value==4||value==6||value==9||value==11){
		//alert("11");
		
		//$("select[name='vbegin_day']").find("option[value='31']").remove();
		
		nextI.find("option[value='31']").remove();
		
		//var ptr=$("select[name='vbegin_day']").find(":last").val();
		var ptr=nextI.find(":last").val();
		
		if(ptr==29){
			//$("select[name='vbegin_day']").append("<option value='30'>30</option>");
			nextI.append("<option value='30'>30</option>");
		}
		
		}
	if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

		//var ptr=$("select[name='select_day']").find("option[value='31']");
		
		//var ptr=$("select[name='vbegin_day']").find(":last").val();
		var ptr=nextI.find(":last").val();
		
		if(ptr==30){
		//$("select[name='vbegin_day']").append("<option value='31'>31</option>");
		nextI.append("<option value='31'>31</option>");
		}
		
		if(ptr==29){
			//$("select[name='vbegin_day']").append("<option value='30'>30</option>");
			//$("select[name='vbegin_day']").append("<option value='31'>31</option>");
			nextI.append("<option value='30'>30</option>");
			nextI.append("<option value='31'>31</option>");
		}
			
		
		}
	if(value==2){

		//$("select[name='vbegin_day']").find("option[value='30']").remove();
		//$("select[name='vbegin_day']").find("option[value='31']").remove();
		nextI.find("option[value='30']").remove();
		nextI.find("option[value='31']").remove();
		}
});


$("select[name='vend_month[]']").change(function(){
	//alert("ch");
	var value=$(this).val();
	var nextI=$(this).next();
	
	if(value==4||value==6||value==9||value==11){
		//alert("11");
		
		//$("select[name='vend_day']").find("option[value='31']").remove();
		nextI.find("option[value='31']").remove();
		//var ptr=$("select[name='vend_day']").find(":last").val();
		var ptr=nextI.find(":last").val();
		
		if(ptr==29){
			//$("select[name='vend_day']").append("<option value='30'>30</option>");
			nextI.append("<option value='30'>30</option>");
		}
		
		}
	if(value==1||value==3||value==5||value==7||value==8||value==10||value==12){

		//var ptr=$("select[name='select_day']").find("option[value='31']");
		
		//var ptr=$("select[name='vend_day']").find(":last").val();
		var ptr=nextI.find(":last").val();
		
		if(ptr==30){
		//$("select[name='vend_day']").append("<option value='31'>31</option>");
		nextI.append("<option value='31'>31</option>");
		}
		
		if(ptr==29){
			//$("select[name='vend_day']").append("<option value='30'>30</option>");
			//$("select[name='vend_day']").append("<option value='31'>31</option>");
			nextI.append("<option value='30'>30</option>");
			nextI.append("<option value='31'>31</option>");
		}
			
		
		}
	if(value==2){

		//$("select[name='vend_day']").find("option[value='30']").remove();
		//$("select[name='vend_day']").find("option[value='31']").remove();
		nextI.find("option[value='30']").remove();
		nextI.find("option[value='31']").remove();
		
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

	var temp=$("#clonediv").clone().insertBefore(this);
	//$(temp).find(":first-child").attr("name","upload[]");
	$(temp).find("input").first().attr("name","upload[]");
	//$(temp).find(":last-child").prev().attr("name","filetitle[]");
	$(temp).find("input").last().attr("name","filetitle[]");
		$(temp).css("display","block");
		$(temp).find("img[name='cross_red']").click(function(){
			$(this).parent().remove();
		});

		$(temp).find("input[name='upload[]']").change(function(){
			
			var f=this.files[0];
			var filesize=f.size;

			if(filesize>2000000){
				alert("檔案大小超過2MB限制");
				$(this).val("");
			}
			
		});
		
			var item=$(temp).children("select[name='filetype[]']");
			
			var bmonth=$(temp).find("select[name='vbegin_month[]']");
			var bday=$(temp).find("select[name='vbegin_day[]']");
			var emonth=$(temp).find("select[name='vend_month[]']");
			var eday=$(temp).find("select[name='vend_day[]']");

			var byear=$(temp).find("select[name='vbegin_year[]']");
			var eyear=$(temp).find("select[name='vend_year[]']");

			var Today=new Date();

			var year=Today.getFullYear()-1911;
			var month=Today.getMonth()+1;
			var day=Today.getDate();

			
			//=================================================================
			$(byear).find("option").filter(function() {
			    //may want to use $.trim in here
			    //alert($(this).val());
			    return $(this).val() == year; 
			}).prop('selected', true);

			$(bmonth).find("option").filter(function() {
			    //may want to use $.trim in here
			    return $(this).val() == month; 
			}).prop('selected', true);

			$(bday).find("option").filter(function() {
			    //may want to use $.trim in here
			    return $(this).val() == day; 
			}).prop('selected', true);


			$(eyear).find("option").filter(function() {
			    //may want to use $.trim in here
			    //alert($(this).val());
			    return $(this).val() == year; 
			}).prop('selected', true);

			$(emonth).find("option").filter(function() {
			    //may want to use $.trim in here
			    return $(this).val() == month; 
			}).prop('selected', true);

			$(eday).find("option").filter(function() {
			    //may want to use $.trim in here
			    return $(this).val() == day; 
			}).prop('selected', true);


			//=================================================================

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

// 	if(!inputIdflag){
// 		alert("請輸入身分證字號");
// 		return false;
// 		}

// 	if(!inputNameflag){
// 		alert("請輸入姓名");
// 		return false;
// 	}
// 	var tmp_isstudent=$("input[name='isStudent']:checked").val();
	
// 	if(!tmp_isstudent){
// 			alert("請選擇是否學生");
// 			return false;
// 		}
// 	else{
		
// 		if(tmp_isstudent=="y"){
// 			if(!inputSchoolflag){
// 				alert("請輸入學校");
// 				return false;}
// 			}
// 		else{
// 				var tmp_isjob=$("input[name='isJob']:checked").val();
// 				if(!tmp_isjob){
// 						alert("請選擇是否專職");
// 						return false;
// 					}
// 				else{	
						
// 						if(tmp_isjob=="y"){
// 							if(!inputComflag){
// 							alert("請輸入公司");
// 							return false;
// 							}
// 						}
// 					}
// 		    }
// 	     }
	
	if(!$.MyExtend.checkuploadfiletype())
		return false;
	
	var info=$.MyExtend.checkuploadfile();

// 	alert(info.flag);
// 	alert(info.msg);
// 	alert(info.pstatus);
	
	if(!info.flag){
		alert(info.msg);
		return false;
	}
	
	if(confirm("確定要送出檔案嗎?")==false)
		return false;

	$("input[name='isStudent']").prop("disabled", false);
	$("input[name='isJob']").prop("disabled", false);
	
	$("form[name='apply_form']" ).submit();
	
});



$("#verifyblock").css("display","none");

$("select[name='filetype[]']").change(function(){
		//alert($(this).html());
		var itemtext=$("select[name='filetype[]'] option:checked").html();
		if(itemtext=="工作許可"){
			$(this).next().css("display","block");
			//alert(itemtext);
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



$("input[name='nation']").click(function(){

	var nationvalue=$(this).val();

	if(nationvalue=="native"){
			$("#nationid").html("身分證字號:");
			$("#yeartitle").html("民國");
			$("select[name='select_year'] option").each(function(){
				var year=$(this).html();
				year=parseInt(year);
				if(year>1911)
				year=year-1911;
				$(this).html(year);
			});
		}
	else if(nationvalue=="foreign"){
			$("#nationid").html("居留證號碼:");
			$("#yeartitle").html("西元");
			$("select[name='select_year'] option").each(function(){
					var year=$(this).html();
					year=parseInt(year);
					if(year<200)
					year=year+1911;
					$(this).html(year);
				});
					
		}

// 	var id=$("input[name='id']").val();
	
// 	var check_nation=$.MyExtend.CheckDomesticOrForeign()
// 	if( (check_nation=="F" && nationvalue=="foreign") || (check_nation=="D" && nationvalue=="native")){
// 			$("#idmsg").html("<font color='blue'>OK</font>");
// 		}
// 	else{
// 			$("#idmsg").html("<font color='red'>身分與輸入證號不相符</font>");
// 		}
	
});


$("input[name='upload[]']").change(function(){
	
	var f=this.files[0];
	var filesize=f.size;

	if(filesize>2000000){
		alert("檔案大小超過2MB限制");
		$(this).val("");
	}
	
});


$.MyExtend.set_default_date();



$("input[name='isStudent']").prop("disabled", true);
$("input[name='isJob']").prop("disabled", true);
$("input[name='comName']").attr("readonly","readonly");
$("input[name='schoolName']").attr("readonly","readonly");
$("input[name='name']").attr("readonly","readonly");

});


$.MyExtend={
		
	ok:function(value){
			alert(value);
		},
	checkid:function(value){

		//alert("ff");
		value=value.trim();
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
},
checkduplicateid:function(){

	var id=$("input[name='id']").val();
	var ptr;
	$.ajax({

		type:"POST",
		dataType:"text",
		data:{id:id},
		url:"checkdupidforinner.php",
		async: false,
		success:function(msg){
			ptr=msg;
			},
		error:function(){

			alert("Ajax途中出錯了!!");
			
			}	
		});
	return ptr;
},
set_default_date:function(){
	//選在今天的日期
	
	var Today=new Date();

	var year=Today.getFullYear()-1911;
	var month=Today.getMonth()+1;
	var day=Today.getDate();
	
	//alert("year="+year);
	
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


	$("select[name='vbegin_year[]'] option").filter(function() {
	    //may want to use $.trim in here
	    //alert($(this).val());
	    return $(this).val() == year; 
	}).prop('selected', true);

	$("select[name='vbegin_month[]'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == month; 
	}).prop('selected', true);

	$("select[name='vbegin_day[]'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == day; 
	}).prop('selected', true);


	$("select[name='vend_year[]'] option").filter(function() {
	    //may want to use $.trim in here
	    //alert($(this).val());
	    return $(this).val() == year; 
	}).prop('selected', true);

	$("select[name='vend_month[]'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == month; 
	}).prop('selected', true);

	$("select[name='vend_day[]'] option").filter(function() {
	    //may want to use $.trim in here
	    return $(this).val() == day; 
	}).prop('selected', true);

	
},
checkresidencepermitno:function(){
	var id=$("input[name='id']").val();
	id=id.trim();
	if (id.length != 10) 
		return false;
	

	if (isNaN(id.substr(2,8)) || (id.substr(0,1)<"A" ||id.substr(0,1)>"Z") || (id.substr(1,1)<"A" ||id.substr(1,1)>"Z")){
		return false;					
	}
	
	var head="ABCDEFGHJKLMNPQRSTUVXYWZIO";
	id = (head.indexOf(id.substr(0,1))+10) +''+ ((head.indexOf(id.substr(1,1))+10)%10) +''+ id.substr(2,8)
	s =parseInt(id.substr(0,1)) + 
	parseInt(id.substr(1,1)) * 9 + 
	parseInt(id.substr(2,1)) * 8 + 
	parseInt(id.substr(3,1)) * 7 + 			
	parseInt(id.substr(4,1)) * 6 + 
	parseInt(id.substr(5,1)) * 5 + 
	parseInt(id.substr(6,1)) * 4 + 
	parseInt(id.substr(7,1)) * 3 + 
	parseInt(id.substr(8,1)) * 2 + 
	parseInt(id.substr(9,1)) + 
	parseInt(id.substr(10,1));

	//判斷是否可整除
	if ((s % 10) != 0) 
	 return false;
	//居留證號碼正確		
	return true;
},
CheckDomesticOrForeign:function(){

	var id=$("input[name='newid1']").val();
	id=id.trim();
	var asciicode=id.substr(1,1).charCodeAt();
	//alert(asciicode);
	
	if(id.length!=10)
		return "U";
	
	if((asciicode>=65 && asciicode<=90)||(asciicode>=97 && asciicode<=122))
		return "F";
	else
		return "D";
	
},
checkuploadfiletype:function(){
	var uploadlength=$("input[name='upload[]']").length;
	var uploads=$("input[name='upload[]']");
	for(var i=0;i<uploadlength;i++){
		var filename=uploads[i].value;
		if(filename==""){
				alert("第"+(i+1)+"欄請選擇檔案");
				return false;
			}
			
		
		var ext_length = filename.lastIndexOf('.');
		filename = filename.substring(ext_length+1,filename.length);  // get file type
		filename = filename.toLowerCase();

		if(filename != 'jpg' && filename != 'pdf')

		{
		alert("第"+(i+1)+"欄檔案格式有誤,限jpg,pdf檔");
		return false;
		}

	}

	var okflag=true;
	
	$("input[name='filetitle[]']").each(function(index){
				if($(this).val()==""){
						alert("第"+(index+1)+"個檔案檔案標題不可為空");
						okflag=false;
					}
		});

	if(okflag)
	return true;
},
checkuploadfile:function(){

	var id=$("input[name='id']").val();
	var filetypearray=[];
	var ptr=0;
	var identity_flag=false;
	var work_flag=false;
	var student_flag=false;
	var phd_flag=false;
	var ok_flag=false;
	var msg="選擇的身分與輸入的證號不相符";
	var person_status="default";
	
	$("select[name='filetype[]']").each(function(){

		var name=$(this).find("option:checked").html();
		filetypearray.push(name);
	}); 

	
	for(var i=0;i<filetypearray.length;i++){
			//alert(filetypearray[i]);
		}
	
	if($("input[name='nation']:checked").val()=="native" && $.MyExtend.CheckDomesticOrForeign()=="D"){
		if($("input[name='isStudent']:checked").val()=="y"){
			person_status="本國學生";
			
			
			for(var i=0;i<filetypearray.length;i++){
				
				if(filetypearray[i]=="身分證/居留證")
					identity_flag=true;
				if(filetypearray[i]=="學生證")
					student_flag=true;
				if(filetypearray[i]=="工作許可")
					work_flag=true;
				if(filetypearray[i]=="博士候選人證明")
					phd_flag=true;
			}
				var student_grade=$("select[name='student_grade'] option:checked").html();
				//alert(student_grade);	
				if(student_grade=="博士候選人"){
						if(phd_flag==true)
							ok_flag=true;
						else
							msg="需要[博士候選人證明]";
					}
				else{
						ok_flag=true;
					}

			}
		else if($("input[name='isStudent']:checked").val()=="n"){
 			person_status="本國非學生";

					
				ok_flag=true;
			}
	}
	else if($("input[name='nation']:checked").val()=="foreign" && $.MyExtend.CheckDomesticOrForeign()=="F"){
		if($("input[name='isStudent']:checked").val()=="y"){

				person_status="本校外國人學生";
				for(var i=0;i<filetypearray.length;i++){
					if(filetypearray[i]=="身分證/居留證")
						identity_flag=true;
					if(filetypearray[i]=="學生證")
						student_flag=true;
					if(filetypearray[i]=="工作許可")
						work_flag=true;
					if(filetypearray[i]=="博士候選人證明")
						phd_flag=true;
				}
				var student_grade=$("select[name='student_grade'] option:checked").html();
					
					if(student_grade=="博士候選人"){
						if(work_flag && phd_flag)
							ok_flag=true;
						else
							msg="需要[工作證明][博士候選人證明]";
					}
					else{
						if(work_flag)
							ok_flag=true;
						else
							msg="需要[工作證明]";
						}

			
		}
		else if($("input[name='isStudent']:checked").val()=="n"){

				person_status="本校外國人非學生";
				for(var i=0;i<filetypearray.length;i++){
					if(filetypearray[i]=="身分證/居留證")
						identity_flag=true;
					if(filetypearray[i]=="學生證")
						student_flag=true;
					if(filetypearray[i]=="工作許可")
						work_flag=true;
				}

					if(identity_flag && work_flag)
						ok_flag=true;
					else
						msg="需要[身分證/居留證][工作證明]";

		}
	}
	
		
		var info={"flag":ok_flag,"msg":msg,"pstatus":person_status};
		return info;
				
},
loadinnerpersondata:function(){
	var id=$("input[name='id']").val();
	id=id.trim();
	$.ajax({
		type:"POST",
		dataType:"json",
		data:{id:id},
		url:"checkinnerperson.php",
		async: false,
		success:function(json){

			
			var status=json["status"];
			var name=json["name"];
			var idcode=json["idcode"];
			var idno=json["idno"];
			var degree=json["std_degree"];
			var has=json["has"];


			if(has=="true"){
				var msg="<font color='blue'>載入資料成功</font>";
				$("#idmsg").html(msg);
				$("input[name='id']").attr("readonly","readonly");
				$("input[name='newid1']").attr("readonly","readonly");
				$("input[name='newid2']").attr("readonly","readonly");
				
				if(status=="S"){
					$("#student_yes").css("display","block");
					$("#student_no").css("display","none");
					$("#job_yes").css("display","none");
					$("#job_no").css("display","none");
					$("input[name='isStudent']").each(function(){
						
						if($(this).val()=="y"){
							this.checked=true;
							}
					});
					$("input[name='schoolName']").val("交通大學");
					}
				else{
					$("#student_yes").css("display","none");
					$("#student_no").css("display","block");
					$("#job_yes").css("display","block");
					$("#job_no").css("display","none");
					$("input[name='isStudent']").each(function(){
						
						if($(this).val()=="n"){
							this.checked=true;
							}
					});
					$("input[name='isJob']").each(function(){
						
						if($(this).val()=="y"){
							this.checked=true;
							}
					});
					$("input[name='comName']").val("交通大學");
					}
				
				$("input[name='name']").val(name);
				$("input[name='id']").val(idno);
				$("input[name='idcode']").val(idcode);

				$("input[name='newid1']").val(idno);
				$("input[name='newid2']").val(idcode);
				$("#tr1").css("display","none");
				$("#tr2").css("display","table-row");
				$("#tr3").css("display","table-row");
				var spanid=$("#idmsg");
				$("#tr2 td:eq(1)").append(spanid);
				
				$("select[name='student_grade'] option").each(function(){$(this).remove();});

				if($.MyExtend.CheckDomesticOrForeign()=="F"){
					
					$("input[name='nation']").each(function(){
								if($(this).val()=="foreign")
									this.checked=true;
						});
					$("#nationidnew").html("居留證號碼:");
				}
				else{	
					
					$("input[name='nation']").each(function(){
						if($(this).val()=="native")
							this.checked=true;
						});
				}

				$("input[name='nation']").attr("disabled","true");
					
				if(degree=="1"){
					$("select[name='student_grade']").append("<option value='0'>博士候選人</option><option value='1'>博班學生</option>");
				}
				else if(degree=="2"){
					$("select[name='student_grade']").append("<option value='2'>碩班學生</option>");
					}
				else if(degree=="3"){
					$("select[name='student_grade']").append("<option value='3'>大學部學生</option>");
					}
				
				}
			else{
				var msg="<font color='red'>輸入的身分證字號找不到資料</font>";
				$("#idmsg").html(msg);
				}	
			},
		error:function(){

			alert("Ajax途中出錯了!!");
			
			}	
		});
}
}

if(typeof String.prototype.trim !== 'function') {
	  String.prototype.trim = function() {
	    return this.replace(/^\s+|\s+$/g, ''); 
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
		<legend>校內人士申請</legend>
		<form action="innerperson_add.php" name="apply_form" method="post"
			enctype="multipart/form-data">
			<table width="1000px" border="1" bordercolor="black">
			<tr height="50px">
			<td colspan="2">國籍選擇<br/>
			本國籍<input type="radio" name="nation"
						value="native" checked="checked"/>&nbsp;&nbsp;&nbsp;&nbsp; 外國籍<input type="radio"
						name="nation" value="foreign" />
			</td>
			</tr>
				<tr id="tr1">
					<td width="50%"><span id="nationid">身分證字號/居留證號碼/工號/學號:</span></td>
					<td width="50%"><input type="text" name="id" class="required" />
					<input type="hidden" name="idcode" />
					<span id="idmsg"></span></td>
				</tr>
				<tr id="tr2" style="display: none;">
					<td width="50%"><span id="nationidnew">身分證字號:</span></td>
					<td width="50%"><input type="text" name="newid1" class="required" />
					<input type="hidden" name="idcode" />
					<span id="idmsg"></span></td>
				</tr>
				<tr id="tr3" style="display: none;">
					<td width="50%"><span id="nationid">工號/學號:</span></td>
					<td width="50%"><input type="text" name="newid2" class="required" />
					<input type="hidden" name="idcode" />
					<span id="idmsg"></span></td>
				</tr>
				<tr>
					<td>姓名:</td>
					<td><input type="text" name="name" class="required" /><span
						id="namemsg"></span></td>
				</tr>
				<!-- 
				<tr>
					<td>出生年月日</td>
					<td><span id="yeartitle">民國</span>  <select name="select_year">
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
				</tr> -->
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
						<!--  
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
<select name="vbegin_year[]">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vbegin_month[]">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vbegin_day[]">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
到期:
<select name="vend_year[]">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vend_month[]">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vend_day[]">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
</div>
檔案標題:&nbsp;&nbsp;&nbsp; <input
									type="text" name="filetitle[]" />
									
									<img src="cross_red.jpg" name="cross_red" width="13px" height="13px" style="cursor: pointer;" />
							</div>
-->
							<input type="button" value="新增上傳欄位" id="addFile">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="button"
						value="儲                        存" name="save" /> 
						<input name="reset" value="重                        置" type="button" />
						</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save" /> <input
				type="hidden" name="savetype" value="" />
		</form>
	</fieldset>

	<br />
	
<div id="clonediv" style="padding-top: 10px;display: none;">
<!-- <input type="file" name="upload[]"> 檔案類型:&nbsp;&nbsp;&nbsp;  -->
			<input type="file" /> 檔案類型:&nbsp;&nbsp;&nbsp; 
<!-- <select> -->
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
<select name="vbegin_year[]">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vbegin_month[]">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vbegin_day[]">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
到期:
<select name="vend_year[]">
<?php 
for($i=$year_start;$i<=$year_end;$i++){
	echo "<option value='".$i."'>".$i."</option>";
}
?>
</select>年
<select name="vend_month[]">
<?php 
	for($i=1;$i<=12;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>月
<select name="vend_day[]">
<?php 
	for($i=1;$i<=31;$i++){
		echo "<option value='".$i."'>".$i."</option>";
	}
?>
</select>日
</div>
檔案標題:&nbsp;&nbsp;&nbsp; 
<!-- <input type="text" name="filetitle[]" /> -->
<input type="text" />
									
<img src="cross_red.jpg" name="cross_red" width="13px" height="13px" style="cursor: pointer;" />
							</div>
	
</body>
</html>