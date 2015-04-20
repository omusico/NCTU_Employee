<?php
	/*  設定代理主持人
	*	NewAgent.php
	*	20140630
	*/
	include("../../connectSQL.php");
	include("../../function.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
<script type="text/javascript" src="../../JS/jquery-1.8.0.js"></script>	
<script type="text/javascript">
	$(document).ready(function(){
		loadbugetlist();
	});
	
	function loadbugetlist(){
		$.ajax({
			url:"NewAgent_back.php",
			dataType: "json",
			type:"POST",
			data:{
				t:"loadbugetlist"
			},
			error: function(xhr) { alert('loadbugetlist ERROR'+xhr.responseText);},
			success: function(json) {
				var str = '';
				var len = json.BugetList.length;
				var lenp = json.Permission.length;
				str+='<tr bgcolor="#C9CBE0"><th>經費編號</th><th>代理主持人人事代號</th><th>姓名</th><th>代理截止日</th><th></th></tr>'
				for(var i=0;i<len;i++){
					if(i%2==0){
						str+='<tr bgcolor="#ccdddd">';
					}else{
						str+='<tr bgcolor="#EDEBEB">';
					}
					str+='<td><div id="buget_'+i+'">'+json.BugetList[i]+'</div></td>';
					str+='<td><input id="Agent_'+i+'" style="width:100px;" type="text"/></td><td><div id="Name_'+i+'"></div></td><td><select id="yy_'+i+'"></select>年<select id="mm_'+i+'"></select>月<select id="dd_'+i+'"></select>日</td>';
					str+='<td><input type="hidden" id="hasData_'+i+'"/><input type="button" id="send_'+i+'" value="新增"><input type="button" id="cancel_'+i+'" value="刪除"></td>';
					str+='</tr>';
				}
				
				if(len==0){
					str='<font color="red">目前無計畫可進行授權</font>';
				}
				
				$('#bugetlisttable').html(str);
				
				var d = new Date();
				
				for(var i=0;i<len;i++){
					str='';
					for(var j=d.getFullYear();j<=d.getFullYear()+2;j++){
						str+='<option value="'+Add0(j,4)+'">'+ (parseInt(j)-1911) +'</option>';
					}
					$('#yy_'+i).html(str);
					
					str='';
					for(var j=1;j<=12;j++){
						str+='<option value="'+Add0(j,2)+'">'+ Add0(j,2) +'</option>';
					}
					$('#mm_'+i).html(str);
					
					str='';
					for(var j=1;j<=31;j++){
						str+='<option value="'+Add0(j,2)+'">'+ Add0(j,2) +'</option>';
					}
					$('#dd_'+i).html(str);
					
					
					$('#yy_'+i).val(d.getFullYear()+2);
					$('#mm_'+i).val(Add0(d.getMonth()+1,2));
					$('#dd_'+i).val(Add0(d.getDate(),2));
					$('#hasData_'+i).val('F');
					for(var j=0;j<lenp;j++){
					
						if(json.BugetList[i]==json.Permission[j].BugetNo){
							$('#Agent_'+i).val(json.Permission[j].UserAccount);
							$('#Name_'+i).html(json.Permission[j].Name);
							$('#yy_'+i).val(json.Permission[j].EndDate.substr(0,4));
							$('#mm_'+i).val(json.Permission[j].EndDate.substr(4,2));
							$('#dd_'+i).val(json.Permission[j].EndDate.substr(6,2));
							$('#hasData_'+i).val('T');
							$('#send_'+i).attr('value', '修改');
							//$('#send_'+i).val('修改');
							break;
						}
					}
					
				}
				
				
				$('#bugetlisttable').off();
				
				//載入輸入、按鈕功能
				$('input[id^=Agent_]').change(function(){
					var id = $(this).attr('id').replace('Agent_','');
					
					getAgentEmpNoName($(this).val(), id);
				});
				
				
				$('input[id^=send_]').click(function(){
					var id = $(this).attr('id').replace('send_','');
					
					var enddate = $('#yy_'+id).val()+$('#mm_'+id).val()+$('#dd_'+id).val();
					var end_y = $('#yy_'+id).val();
					var end_m = $('#mm_'+id).val();
					var end_d = $('#dd_'+id).val();
					if(isDate(end_y,end_m,end_d)){
						addAgentEmpNo($('#buget_'+id).html(), $('#Agent_'+id).val(), enddate, $('#hasData_'+id).val());
						$('#send_'+id).attr('value', '修改');
						$('#hasData_'+id).val('T');
					}else{
						alert("選擇的代理截止日不存在(ex.4/31不存在)!!");
					}
					
					id=null;
					enddate=null;
				});
				
				$('input[id^=cancel_]').click(function(){
					var id = $(this).attr('id').replace('cancel_','');
					var enddate = $('#yy_'+id).val()+$('#mm_'+id).val()+$('#dd_'+id).val();
					var end_y = $('#yy_'+id).val();
					var end_m = $('#mm_'+id).val();
					var end_d = $('#dd_'+id).val();
					if(isDate(end_y,end_m,end_d)){
						deleteAgentEmpNo($('#buget_'+id).html(), $('#Agent_'+id).val(), enddate);
						$('#send_'+id).attr('value', '新增');
						$('#hasData_'+id).val('F');
					}else{
						alert("選擇的代理截止日不存在(ex.4/31不存在)!!");
					}
					id=null;
					enddate=null;
				});
				
			}
		});
	
	}
	
	function getAgentEmpNoName(agentempno, id){
		$.ajax({
			url:"NewAgent_back.php",
			dataType: "json",
			type:"POST",
			data:{
				t:"getname",
				agentempno:agentempno
			},
			error: function(xhr) { alert('getAgentEmpNoName ERROR'+xhr.responseText);},
			success: function(json) {
				if(json.success=='1'){
					$('#Name_'+id).html(json.name);
				}else{
					alert('查無此人員');
				}
			}
			});
	}
	
	function addAgentEmpNo(bugetno, agentempno, enddate, hasdata){
		$.ajax({
			url:"NewAgent_back.php",
			dataType: "json",
			type:"POST",
			data:{
				t:"addagentempno",
				bugetno:bugetno,
				agentempno:agentempno,
				enddate:enddate,
				hasdata:hasdata,
			},
			error: function(xhr) { alert('addAgentEmpNo ERROR'+xhr.responseText);},
			success: function(json) {
				if(json.success=='1'){
					alert('新增成功：'+agentempno+'於'+bugetno+'的代理主持人權限');
					loadbugetlist();
				}else{
					alert('新增失敗'+bugetno+'--'+agentempno);
				}
			}
			});
	}
	
	function deleteAgentEmpNo(bugetno, agentempno, enddate){
		$.ajax({
			url:"NewAgent_back.php",
			dataType: "json",
			type:"POST",
			data:{
				t:"deleteagentempno",
				bugetno:bugetno,
				agentempno:agentempno,
				enddate:enddate
			},
			error: function(xhr) { alert('deleteAgentEmpNo ERROR'+xhr.responseText);},
			success: function(json) {
				if(json.success=='1'){
					alert('刪除成功：'+agentempno+'於'+bugetno+'的代理主持人權限');
					loadbugetlist();
				}else{
					alert('刪除失敗'+bugetno+'--'+agentempno);
				}
			}
		});
	}
	
	//指定位數補零 主要用在月份 
	function Add0(num, n) {
		var length = num.toString().length;
		while(length < n){
			num = "0" + num; length++;
		}
		return num; 
	}
	//確認選擇的日期是否存在
	function isDate(year, month, day){  
	   var dateStr;  
	   if (!month || !day) {  
		   if (month == '') {  
			   dateStr = year + "/1/1"  
		   }else if (day == '') {  
			   dateStr = year + '/' + month + '/1';  
		   }else {  
			   dateStr = year.replace(/[.-]/g, '/');  
		   }  
	   }else {  
		   dateStr = year + '/' + month + '/' + day;  
	   }  
	   dateStr = dateStr.replace(/\/0+/g, '/');  
	  
	   var accDate = new Date(dateStr);  
	   var tempDate = accDate.getFullYear() + "/";  
	   tempDate += (accDate.getMonth() + 1) + "/";  
	   tempDate += accDate.getDate();  
	  
	   if (dateStr == tempDate) {  
		   return true;  
	   }  
	   return false;  
	}  
</script>
</head>
<body bgcolor="#c1cfb4">
	<div id='listRegion'>
		<div style='text-align:center;'>
			每個計畫可以由計畫主持人設定一名代理主持人帳號，該帳號可以代理計畫主持人執行承辦人權限申請功能。
		</div>
		<table id='bugetlisttable' style='width:650px;margin:10px auto;text-align: center;' bgcolor='#000000' cellspacing='1' cellpadding='4'>
		</table>
	</div>

	<div >
	</div>
	
	
</body>
</html>