<?php
	//include("../../connectSQL2.php");
	include("../../connectSQL.php");
	include("../../function.php");
	$myError=0;	
?>
<!DOCTYPE html>
<html>
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" /> 
  <script type="text/javascript" src="../../JS/jquery.js"></script>
 <!-- Contact Form CSS files -->
<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
<script type='text/javascript' src='../../JS/jquery.simplemodal.js'></script>
<script language=JavaScript>	
	function add(current){
	    //alert(current);
		$.each( $('#tab:first tbody tr'), function(i, tr){
            if($(this).attr('id')==current){
				var num =eval("document.form1.num_"+current+".value");
				var rows= parseInt(i,10)+(num-1)+1;
				num = parseInt(num,10) + 1;
				eval("document.form1.num_"+current+".value= '"+num+"'");	
				//alert(current+" "+eval("document.form1.num_"+current+".value"));
				var currentRow=$('#tab:first tbody tr:eq('+rows+')');
				var Plan = eval("document.form1.PlanID_"+current+".value");	
				var str = "<tr bgcolor='#ddefff' height='20' align='left'>"+
					"<td nowrap>人事代號</td>"+
					"<td nowrap bgcolor='EDEBEB'><input type='text' name='UserAccount_"+current+"_"+num+"' size='8' maxlength='10' value='' onChange=\"Javascript:document.getElementById('PerInfo').src='SelectPer.php?myType=qryID&order="+current+"&other_order="+num+"&EmpNo='+document.form1.UserAccount_"+current+"_"+num+".value;\"></td>"+
					"<td nowrap>姓名</td>"+
					"<td nowrap bgcolor='EDEBEB'><input type='text' name='DivUserAccount_"+current+"_"+num+"' size='5' maxlength='10' value='' onChange=\"Javascript:document.getElementById('PerInfo').src='SelectPer.php?myType=qryName&order="+current+"&other_order="+num+"&Name='+document.form1.DivUserAccount_"+current+"_"+num+".value;\"></td>"+		
					"<td nowrap>分機</td>"+
					"<td nowrap bgcolor='EDEBEB'><input type='text' name='UserTel_"+current+"_"+num+"' size='5' maxlength='5' value=''></td>"+
					"<td nowrap>E-mail</td>"+					  
					"<td nowrap bgcolor='EDEBEB'><input type='text' name='UserEmail_"+current+"_"+num+"' size='28' maxlength='80' value=''></td>"+
					"<td nowrap bgcolor='EDEBEB'><input type='hidden' name='PlanID_"+current+"_"+num+"' value='"+Plan+"'><div id='Context"+current+"_"+num+"'><font color='#FF0000'>尚未申請</font></div></td>"+
					"<td nowrap bgcolor='EDEBEB'><img src='delete.png' height='15px' width='15px' id='delete_"+current+"_"+num+"' style='visibility:hidden;'></td></tr>";
                currentRow.after(str);
			}
		});	
    }
	function del(i,j){
		alert(j);
	}
	
//})
</script>
</head>
</html>


<?php

If(isset($_POST["NewApply"])&&$_POST["NewApply"]=='confirm')//由首頁的新申請頁面進來 comment by wslee 2012/6/1
{}	
Else//主持人由menu選擇"申請承辦助理帳號"
{	
	$UserAccount= $_SESSION["UserID"]; //Session 中記錄登入的使用者	
	If(!empty($UserAccount))
	{
		//strSQL = "select * from UserMain u, Personnel p where u.UserAccount='".UserAccount."' and u.UserAccount=p.EmpNo"
		$strSQL = " sp_qry_UserMainPersonnel '".$UserAccount."' ,'','1'";
		//echo $strSQL;
		$rsHost = $db->query($strSQL);
		$row = $rsHost->fetch();
		If(!empty($row))
		{				
			$UserName = filterEvil($row["Name"]); //主持人姓名, 由資料庫帶出		
			$UserTel = filterEvil($row["tel"]);
			$UserEmail = filterEvil($row["email"]);
		}
		Else
		{
			echo "登入錯誤，請重新登入系統！";
			$myError = 1;
		}
	}		
	Else
	{	
		$myError =1;
		echo "登入錯誤，請重新登入系統！！";
	}
}	
	
If($myError!=1)
{
	//另外找出，此計畫主持人的所有計畫, 如以原有承辦人，則列出該承辦人，並提醒主持人
	$myYear=date("Y")-1911;
	$myMonth=date("m");
	$myDay=date("d");
  	$myDate = $myYear.$myMonth.$myDay;		
	//strSQL = "select * from buget where (leaderid='".UserAccount."') and  (cast(deadline as integer)>= '".myDate."') AND (cast(start as integer) <= '".myDate."')"		
	//strSQL = "sp_qrybugetUseraccount '".UserAccount."' , '".myDate."' , '".myDate."','T',''"
	$strsqL = "sp_qry_bugetByLeaderid '".$UserAccount."' , '".$myDate."' , '".$myDate."'";
	//echo $strsqL;
	$result_rsPlan = $db->query($strsqL);
?>
	<html>
	<head>
	<title>申請計畫承辦人帳號</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<script language="JavaScript">
		$('#officer_window').hide();
		function myCheck(){
			var error=0;
			if (trim(document.form1.HostUserTel.value)== "") {window.alert("請輸入主持人分機號碼");	error=1;}
			if (trim(document.form1.HostUserEmail.value) == "") {window.alert("請輸入主持人E-Mail");	error=1;}
			//alert(document.form1.TotalNumber.value);
			if (document.form1.TotalNumber.value > 0){
				for(i=1;i<=document.form1.TotalNumber.value;i++){
					for(j=1;j<=eval("document.form1.num_"+i+".value");j++){
						if(trim(eval("document.form1.UserAccount_"+i+"_"+j+".value"))!=""){			
							if (trim(eval("document.form1.UserEmail_"+i+"_"+j+".value"))==""){	window.alert(j+"請輸入"+eval("form1.UserAccount_"+i+"_"+j+".value")+"的E-mail");	error=1;}
						}
					}
				}	
			}
			if(error==0){
				form1.button1.disabled=true; 
				form1.Store_Ass.value="送出申請"			
				form1.submit();
			}
		}
		function officer_window_sumbit(){
		    var string="";
			for (var i=0; i<officer_form.name.length; i++){
			  if (officer_form.name[i].checked){
				string=officer_form.name[i].value;
				break;
			  }
			}
			var Officer_Array = new Array();
			Officer_Array = string.split("/");
			var i=officer_form.order.value;
			var j=officer_form.other_order.value;
			eval('document.form1.UserAccount_'+i+'_'+j+'.value="'+Officer_Array[0]+'"');
			eval('document.form1.UserTel_'+i+'_'+j+'.value="'+Officer_Array[1]+'"');				
			eval('document.form1.UserEmail_'+i+'_'+j+'.value="'+Officer_Array[2]+'"');
						
			$.modal.close();
			document.getElementById("context").innerHTML="";
		}
		function trim(s){
		  return s.replace(/^\s*|\s*$/g,"")
		}
	</script>

	<body>
	<form name='form1' action='Store_New.php' method='post' target="_self">
	<iframe id="PerInfo" name="PerInfo" src="" width="0" height="0"> </iframe>	<!--載入個人資料//-->
	<TABLE  cellspacing="1" cellpadding="1"  width="100%" >
		<tr>
			<td width='0%'></td>
			<td width='80%' align='left'><h1 style="font-family:'標楷體' ">申請計畫承辦人帳號</h1></td>
			<td width='20%'></td>
		</tr>	
		<tr><td></td>		
			<td align='left'>
				<TABLE cellspacing="1" cellpadding="5" bgcolor="#FFFFFF"   style="border:3px dotted #7ca0c0">
					<TR>
						<TD bgcolor="#FFFED0"><b>Step 1</b>. 請核對或填寫主持人基本資料。若核對有誤，請填寫正確的資訊，以便更新。
						</TD>
					</TR>
				</TABLE>
				&nbsp;<br>

				<TABLE cellspacing="1" cellpadding="5" bgcolor="#7ca0c0" width="30%" >
					<tr bgcolor="#b5dcff" height="10" align="left" >
					<td nowrap colspan='8' align="center"><font size="5" style="font-family:'標楷體'" ><B>計畫主持人 - 基本資料</b></font></td>
				</tr>	
				<tr bgcolor="#ddefff" height="20" align="left">
					<td nowrap>人事代號</td>
					<td nowrap bgcolor='EDEBEB'><?php echo $UserAccount;?><input type='hidden' name='HostUserAccount' size='5' maxlength='5'  value='<?php echo $UserAccount;?>'></td>
					<td nowrap>姓名</td>
					<td nowrap bgcolor='EDEBEB'><?php echo $UserName;?></td>  				
					<td nowrap>分機</td>
					<?php echo "<td nowrap bgcolor='EDEBEB'><input type='text' name='HostUserTel' size='5' maxlength='5'  value='".trim($UserTel)."'></td>";?>  				  				
					<td nowrap >E-mail</td>  		
					<td nowrap bgcolor='EDEBEB'><input type='text' name='HostUserEmail' size='30' maxlength='70' value='<?php echo trim($UserEmail);?>'></td>  		
				</tr>
				</table>  		
			</td>
		</tr>
	<?php
		If(!empty($result_rsPlan))
		{
	?>
			<tr>
				<td></td>
				<td>
					&nbsp;<p>
					<TABLE cellspacing="1" cellpadding="5" bgcolor="#FFFFFF"   style="border:3px dotted #7ca0c0" >
					<TR>
						<TD bgcolor="#FFFED0"><b>Step 2</b>. 請輸入各計畫之承辦人的人事代號和E-mail。若該計畫無承辦人，請空白即可。<br>
														
						</TD>
					</TR>
					</TABLE>
					&nbsp;<br>
					<TABLE cellspacing="1" cellpadding="5" bgcolor="#7ca0c0" width="500" id="tab" style="position:relative;" >      
					<tr bgcolor="#b5dcff" height="10" align="left">
						<td nowrap colspan='10'><font size="5" style="font-family:'標楷體'" ><B>&nbsp;&nbsp;計畫承辦人 - 基本資料</b></font></td>
					</tr>	
					<?php
						$i=0;
						while($rsPlan = $result_rsPlan->fetch()){
							$i=$i+1;						
							$strsql  ="sp_qry_OfficerProject_byEmpBudget '','".$rsPlan["bugetno"]."'";
							//echo $strsql." ";
							$rsOfficer1=$db->query($strsql);							
							$row=$rsOfficer1->fetchAll();
							$num=count($row);
							//echo $num."<br>";
							$rsOfficer1=$db->query($strsql);							
							$j=0;							
							echo '<tr bgcolor="#FFFED0" id="'.$i.'"><td nowrap colspan="10" style="position:relative;" width="700"><font size="4" font-family:\'標楷體\'><B>計畫編號:'.$rsPlan["bugetno"].'</B></font>';	
							echo '<input id="'.$i.'" type="button" style="position:absolute;right:0px;"  value="新增承辦人" onclick="add(this.id);">';
							echo '<input type="hidden" Name="num_'.$i.'" value="'.$num.'">';
							echo "<input type='hidden' name='PlanID_".$i."' value='".trim($rsPlan["bugetno"])."'>";
							echo '<input type="hidden" Name="Tnum_'.$i.'" value="'.$num.'"></td></tr>';
							
							while($rsOfficer1 && $rsOfficer=$rsOfficer1->fetch()){
								$j++;
								if(!empty($rsOfficer)){								
									$Name = $rsOfficer["Name"];
									$ProjOfficer = $rsOfficer["UserAccount"];
									$Email = $rsOfficer["email"];
									$Tel = $rsOfficer["Tel"];
									$Permission = $rsOfficer["Permission"];
								}
								else {
									$Name = "";
									$ProjOfficer = "";
									$Email = "";
									$Tel = "";
									$Permission = "";
								}

						?>
								<tr bgcolor="#ddefff" height="20" align="left">	
									<td nowrap>人事代號</td>
									<td nowrap bgcolor='EDEBEB'><input type='text' name='UserAccount_<?php echo $i;?>_<?php echo $j;?>' size='8' maxlength='10' value='<?php echo trim($ProjOfficer);?>' onChange="Javascript:document.getElementById('PerInfo').src='SelectPer.php?myType=qryID&order=<?php echo $i;?>&other_order=<?php echo $j;?>&EmpNo='+document.form1.UserAccount_<?php echo $i;?>_<?php echo $j;?>.value;" readonly></td>
									<td nowrap>姓名</td>
									<td nowrap bgcolor='EDEBEB'><input type='text' name='DivUserAccount_<?php echo $i;?>_<?php echo $j;?>' size='5' maxlength='10' value='<?php echo trim($Name);?>' onChange="Javascript:document.getElementById('PerInfo').src='SelectPer.php?myType=qryName&order=<?php echo $i;?>&other_order=<?php echo $j;?>&Name='+document.form1.DivUserAccount_<?php echo $i;?>_<?php echo $j;?>.value;" readonly></td>				
									<td nowrap>分機</td>
									<td nowrap bgcolor='EDEBEB'><input type='text' name='UserTel_<?php echo $i;?>_<?php echo $j;?>' size='5' maxlength='5' value='<?php echo trim($Tel);?>'></td>
									<td nowrap>E-mail</td>  		
									<td nowrap bgcolor='EDEBEB'>
									<input type='text' name='UserEmail_<?php echo $i;?>_<?php echo $j;?>' size='28' maxlength='80' value='<?php echo trim($Email);?>'>
								    <td nowrap bgcolor='EDEBEB'align="right"><input type='hidden' name='PlanID_<?php echo $i;?>_<?php echo $j;?>' value='<?php echo trim($rsPlan["bugetno"]);?>'><div id="Context<?php echo $i;?>_<?php echo $j;?>">
									<?php 
									//if($Permission=="V") echo "<font color='#FF0000'>審核中</font>";									 
									//else echo "<font color='#FF0000'>已審核</font>"
									echo "已承辦";
									?>
									</div></td>
									<td nowrap bgcolor='EDEBEB'><img src='delete.png' height='15px' width='15px' id='delete_<?php echo $i;?>_<?php echo $j;?>' title='刪除此承辦人' onclick="Javascript:if(window.confirm('是否刪除承辦人?')){ document.getElementById('PerInfo').src='SelectPer.php?myType=del&order=<?php echo $i;?>&other_order=<?php echo $j;?>&bugetno='+document.form1.PlanID_<?php echo $i;?>_<?php echo $j;?>.value+'&useraccount='+document.form1.UserAccount_<?php echo $i;?>_<?php echo $j;?>.value;} ">
									</td>
								</tr>
				<?php   
							}
						}//while  			?>
					<input type='hidden' name='TotalNumber' value='<?php echo $i;?>'>  		
					</table>  		
				</td>
				<td width=""></td>
			</tr>
	<?php
		}
		Else echo "<tr><td></td><td><font color='FF0000'>目前無執行中計畫資料</font></td><td></td></tr>";
	?>
	</table><br>
	<input type='button' name='button1' value='確認' OnClick='Javascript:myCheck()'>
	<input type='hidden' name='Store_Ass' value=''> 
	</form>
	
	
	<!-- 輸入姓名，有多個職員 window 頁面	-->
	<div id="officer_window">
		<Div id="context"></div>			
	</div>
	</body>
	</html>
	<?php
}
?>
