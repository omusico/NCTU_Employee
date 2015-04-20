<?php
	include("../../connectSQL.php");
	include("../../function.php");
?>
<html>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>無標題文件</title>
</head>
<script type='text/javascript' src='/js/jquery.js'></script>
<script type='text/javascript' language="javascript">
//根據輸入承辦人ID，找尋相關資料
function qryID(){
<?php
	$bugetno=isset($_GET["bugetno"])?filterEvil($_GET["bugetno"]):"";
	$order=filterEvil($_GET["order"]);
	$other_order=filterEvil($_GET["other_order"]);
	$EmpNo = filterEvil($_GET["EmpNo"]);
	$QueryStr = "sp_qry_UserMainPersonnel '".$EmpNo."','','1'";
	$rs_result = $db->query($QueryStr);
	if($rs_result){
		$rs=$rs_result->fetch();
		$Name =trim($rs["Name"]);
		$Email=filterEvil($rs["email"]);
		$Tel=filterEvil($rs["tel"]);
	}
	Else{
		$EmpNo="";
		$Name="";
		$Email="";
		$Tel="";
	}	
?>
	//alert("<?php echo $QueryStr;?>");
	parent.document.form1.UserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($EmpNo);?>';
	parent.document.form1.DivUserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Name);?>';
	parent.document.form1.UserTel_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Tel);?>';
	parent.document.form1.UserEmail_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Email);?>';
	if(parent.document.form1.UserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value=="") alert("查無資料");
}

//根據輸入承辦人姓名，找尋相關資料
function qryName(){
<?php
	$order=filterEvil($_GET["order"]);
	$Name = isset($_GET["Name"])?filterEvil($_GET["Name"]):"";
	$other_order=filterEvil($_GET["other_order"]);
	$QueryStr = "sp_qry_Personnel '".$Name."'";
	$rs1 = $db->query($QueryStr);
	if($rs1){
		$num = count($rs1);
	}else{
		$num = 0;
	}
	//有多個對應職員人員
	if($num>1){
		$String='<form name="officer_form"><h3>選擇承辦人員:<br>';
		$i=0;
		while($rs = $rs1->fetch()){
			if($i==0)	$String=$String.'<input type="radio" name="name" value="'.$rs["empno"].'/'.$rs["tel"].'/'.$rs["email"].'" checked>'.$rs["Name"]."/".$rs["empno"]."/".$rs["Dept"]."</br>";
			else   $String=$String.'<input type="radio" name="name" value="'.$rs["empno"].'/'.$rs["tel"].'/'.$rs["email"].'">'.$rs["Name"]."/".$rs["empno"]."/".$rs["Dept"]."</br>";
			$i++;
		}
		$String=$String.'</h3><input type="hidden" name="order" value="'.$order.'"><input type="hidden" name="other_order" value="'.$other_order.'"><input type="button" name="button" value="送出" OnClick="Javascript:officer_window_sumbit()"></form>';
	?>
		parent.document.getElementById("context").innerHTML = '<?php echo $String;?>';
		parent.$('#officer_window').modal();
	<?php 
	}	
	else if($num==1){ //只有一個對應人員
		$rs = $rs1->fetch();	
		if(!empty($rs)){
			$Name = filterEvil($rs["Name"]);
			$Empno = filterEvil($rs["empno"]);
			$Email=filterEvil($rs["email"]);
			$Tel=filterEvil($rs["tel"]);
		}
		Else{
			$Name="";
			$Empno="";
			$Email="";
			$Tel="";
		}	
		?>	
		parent.document.form1.UserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Empno);?>';
		parent.document.form1.UserEmail_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Email);?>';
		parent.document.form1.DivUserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Name);?>';
		parent.document.form1.UserTel_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo trim($Tel);?>';
		
		if(parent.document.form1.UserAccount_<?php echo $order;?>_<?php echo $other_order;?>.value==""){
			alert("查無資料");
		}
<?php 
	} 
	?>
}

function del(){
		//alert("hello");
<?php	
		$useraccount=isset($_GET["useraccount"])?filterEvil($_GET["useraccount"]):"";
		$bugetno=isset($_GET["bugetno"])?filterEvil($_GET["bugetno"]):"";
		$sql = "sp_del_Officer_Project '".$useraccount."','".$bugetno."'";
		$db->query($sql);
		$order=filterEvil($_GET["order"]);
		$other_order=filterEvil($_GET["other_order"]);
	?>
		$.each( parent.$('#tab:first tbody tr'), function(p, tr){
			if($(this).attr('id')==<?php echo $order;?>){			
				var num =eval("parent.document.form1.num_<?php echo $order;?>.value");
				var Tnum=parseInt(eval("parent.document.form1.Tnum_<?php echo $order;?>.value"),10) -1;
				eval("parent.document.form1.Tnum_<?php echo $order;?>.value= '"+Tnum+"'");
				
				for(var i=<?=$other_order?>;i<num;i++){
					j=i+1;			
					eval("parent.document.form1.UserAccount_<?php echo $order;?>_"+i+".value=parent.document.form1.UserAccount_<?php echo $order;?>_"+j+".value");
					eval("parent.document.form1.UserEmail_<?php echo $order;?>_"+i+".value=parent.document.form1.UserEmail_<?php echo $order;?>_"+j+".value");	
					eval("parent.document.form1.DivUserAccount_<?php echo $order;?>_"+i+".value=parent.document.form1.DivUserAccount_<?php echo $order;?>_"+j+".value");	
					eval("parent.document.form1.UserTel_<?php echo $order;?>_"+i+".value=parent.document.form1.UserTel_<?php echo $order;?>_"+j+".value");
					eval("parent.document.form1.PlanID_<?php echo $order;?>_"+i+".value=parent.document.form1.PlanID_<?php echo $order;?>_"+j+".value");	
					eval("parent.document.getElementById(\"Context<?php echo $order;?>_"+i+"\").innerHTML=parent.document.getElementById(\"Context<?php echo $order;?>_"+j+"\").innerHTML");
					if(i>Tnum)	parent.document.getElementById('delete_<?php echo $order;?>_'+i).style.visibility = "hidden";
					
					/*parent.document.getElementById("delete_<?php echo $order;?>_"+j).onclick=function(){
					  
						if(window.confirm('是否刪除承辦人?\n若刪除此計畫承辦人，之後新增承辦人須重新經過出納組審核')){
							alert(i);
							parent.document.getElementById('PerInfo').src='SelectPer.php?myType=del&order=1&other_order=1&bugetno='+eval("parent.document.form1.PlanID_<?php echo $order;?>_"+i+".value")+"&useraccount="+eval("parent.document.form1.UserAccount_<?php echo $order;?>_"+i+".value");
						}
					
					};*/
					eval("parent.document.form1.UserAccount_<?php echo $order;?>_"+i+".readOnly=parent.document.form1.UserAccount_<?php echo $order;?>_"+j+".readOnly");
					eval("parent.document.form1.DivUserAccount_<?php echo $order;?>_"+i+".readOnly=parent.document.form1.DivUserAccount_<?php echo $order;?>_"+j+".readOnly");
				}
				var rows= eval(parseInt(p,10)+parseInt(num,10));
				parent.$('#tab').find("tr").eq(rows).remove();				
				num = parseInt(num,10) - 1;
				eval("parent.document.form1.num_<?php echo $order;?>.value= '"+num+"'");
				
			}		 
		});
	

}

function qryInfo(){
	alert("hello");
	//alert("<%=$_GET("myType")%>--<%=$_GET("ID")%>--<%=$_GET("order")%>");
<?php
	$ID=isset($_GET["ID"])?filterEvil($_GET["ID"]):"";
	
	$order=$_GET["order"];
	$other_order=filterEvil($_GET["other_order"]);
	$QStr = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime] p,UserMain u where p.EmpNo='".$ID."' and p.EmpNo=u.UserAccount";
	$Qrs = $db->query($QStr);
	$Qrs = $Qrs->fetch();
	if(!empty($Qrs)) $Tel=filterEvil($Qrs["Tel"]);	
	$strmail = "select * from email where Useraccount='".$ID."'";
	$rsmail = $db->query($strmail);
	$rsmail = $rsmail->fetch();
	if(!empty($rsmail)&&isset($rsmail['Email'])){
		$Email=filterEvil($rsmail["Email"]);
	}
?>
	parent.document.form1.UserTel_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo $Tel;?>';
	parent.document.form1.UserEmail_<?php echo $order;?>_<?php echo $other_order;?>.value='<?php echo $Email;?>';
}
</script>
<?php		
	If(filterEvil($_GET["myType"]) == "qryID") 	$loadFunction = "qryID()";
	else if(filterEvil($_GET["myType"]) == "qryName") 	$loadFunction = "qryName()";
	else if(filterEvil($_GET["myType"]) == "del") $loadFunction = "del()";
	Else 	$loadFunction = "qryInfo()";
?>
<body onLoad="javascript:<?php echo $loadFunction;?>;">	
</body>
</html>
