<?php 
	//功能:1.查詢計畫資料並於parent顯示
	//	   2.控管"請核起訖日"的可選擇範圍
	include("connectSQL.php");
	include("function.php");

	$bugno=mb_strtoupper(filterEvil(trim($_GET["bugno"])));//查詢或使用的計畫代號
	$tag=filterEvil(trim($_GET['tag']));//請核起訖日中,改變的下拉選單
	$type=filterEvil(trim($_GET['type']));//是否可回朔
	$OrderNo=filterEvil(trim($_GET['OrderNo']));
	//修改人員時使用的輸入
	$action=filterEvil(trim($_GET['action']));
	$Eid=filterEvil(trim($_GET['eid']));	
	
	$selected_y="PT".$tag."_y";
	$selected_m="PT".$tag."_m";
	$selected_d="PT".$tag."_d";
?>
<script language="javascript">
	//alert("<?echo $tag." ".$type;?>");
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>
<?php
	$bugname="";$leader="";$leaderDep="";$start="";$end="";$diff_y=0;
	$start_y=0;$start_m=0;$start_d=0;
	$end_y=0;$end_m=0;$end_d=0;
	$now_y=0;$now_m=0;$now_d=0;$now="";
	$PT_start_y="";$PT_start_m="";$PT_start_d="";
	$PT_end_y="";$PT_end_m="";$PT_end_d="";
	$message="";
	$over30days="n";
	
	if($action=="loading"){
		$strSQL="select (datepart(year,BeginDate)-1911) as start_y,datepart(month,BeginDate) as start_m,datepart(day,BeginDate) as start_d ".
				",(datepart(year,EndDate)-1911) as end_y,datepart(month,EndDate) as end_m,datepart(day,EndDate) as end_d ".
				"from PT_Employed where Eid='".$Eid."'";
		$result=$db->query($strSQL);
		$row=$result->fetch();
		$PT_start_y=$row['start_y'];
		$PT_start_m=$row['start_m'];
		$PT_start_d=$row['start_d'];
		$PT_end_y=$row['end_y'];
		$PT_end_m=$row['end_m'];
		$PT_end_d=$row['end_d'];
	}
	//$strSQL = "select * from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".	
	//		  "where v.bugetno='".$bugno."'";
	/*$strSQL = "select v.*,v2.Name as giveunit from [PERSONDBOLD].[personnelcommon].[dbo].[vi_buget] v ".
			  "left join [PROJECT].[Plan].[dbo].[View_Plan_Detail_PersonDB] v2 ".
			  "on (v.bugetno collate Chinese_Taiwan_Stroke_CI_AS)=(v2.School_Num collate Chinese_Taiwan_Stroke_CI_AS) ".
			  "where v.bugetno='".$bugno."'";
	$result=$db->query($strSQL);		*/
	$rowcount=0;
	$row=getBugInfo($bugno);
	if($row!="notfound"){	
		$bugname=trim($row['bugname']);
		$leader=trim($row['leader']);
		$leaderDep=trim($row['DepName']);
		$start=trim($row['start']);		
		if(strlen(trim($row['delay']))>0){
			$end=trim($row['delay']);
		}else{
			$end=trim($row['deadline']);
		}
		$giveunit=trim($row['giveunit']);
		
		$start_y=substr($start,0,strlen($start)-4);
		$start_m=substr($start,strlen($start)-4,2);
		$start_d=substr($start,strlen($start)-2,2);
		
		$end_y=substr($end,0,strlen($end)-4);
		$end_m=substr($end,strlen($end)-4,2);
		$end_d=substr($end,strlen($end)-2,2);
		$hasdata=0;
		$now_y=date('Y')-1911;
		$now_m=date('m');
		$now_d=date('d');		
		$now=$now_y.$now_m.$now_d;

		//確認追溯是否有特別設定
		$strSQL="select * from BugetTraceBackSetting where BugNo='".$bugno."'";
		$result=$db->query($strSQL);
		if($result && $row=$result->fetch()){
			$hasdata=1;
			$work_type=trim($row['work_TraceBackType']);
			$study_type=trim($row['study_TraceBackType']);
			if(($type=="work" && $work_type==1) || ($type=="study" && $study_type==1)){
				if($action!="loading"){//一般帶入的情況
					if((int)$now>(int)$end){//計畫訖日小於當天,已不可以再請核
						$message="已過了本計畫可以請核的時間!!";
					}else if((int)$now<(int)$start){
						//計畫起日比當天大,可以請核,直接使用計畫起訖日
					}else {//當天在計畫起訖日中間,起日改為今天
						$start_y=$now_y;
						$start_m=$now_m;
						$start_d=$now_d;
					}
				}else{//修改帶入的情況,要考慮原本的請核起日,應該會大於等於$now
					$loading_start=$PT_start_y.$PT_start_m.$PT_start_d;
					if((int)$loading_start<(int)$now){
						$start_y=$PT_start_y;
						$start_m=$PT_start_m;
						$start_d=$PT_start_d;
					}else{
						$start_y=$now_y;
						$start_m=$now_m;
						$start_d=$now_d;
					}
				}
			}else if($type=="study" && $study_type==3){
				$study_Set_startdate=trim($row['study_Set_startdate']);
				$start_y=substr($study_Set_startdate,0,strlen($study_Set_startdate)-4);
				$start_m=substr($study_Set_startdate,strlen($study_Set_startdate)-4,2);
				$start_d=substr($study_Set_startdate,strlen($study_Set_startdate)-2,2);
			}
		}else{
			if($type=="work"){//工作型不可追朔,學習型可追朔
				if($action!="loading"){//一般帶入的情況
					if((int)$now>(int)$end){//計畫訖日小於當天,已不可以再請核
						$message="已過了本計畫可以請核的時間!!";
					}else if((int)$now<(int)$start){
						//計畫起日比當天大,可以請核,直接使用計畫起訖日
					}else {//當天在計畫起訖日中間,起日改為今天
						$start_y=$now_y;
						$start_m=$now_m;
						$start_d=$now_d;
					}
				}else{//修改帶入的情況,要考慮原本的請核起日,應該會大於等於$now
					$loading_start=$PT_start_y.$PT_start_m.$PT_start_d;
					if((int)$loading_start<(int)$now){
						$start_y=$PT_start_y;
						$start_m=$PT_start_m;
						$start_d=$PT_start_d;
					}else{
						$start_y=$now_y;
						$start_m=$now_m;
						$start_d=$now_d;
					}
				}
			}
		}
		
		$diff_y=$end_y-$start_y+1;	
		$rowcount++;
	}else{
		$message="查無此計畫資料，請先洽詢主計室承辦人員。";
	}
	
?>
 <script language="javascript">	
	//alert("start:<?echo $hasdata." ".$work_type." ".$study_type."<br>".$start_y." ".$start_m." ".$start_d." 1<br>".$end_y." ".$end_m." ".$end_d."  2<br>".$now_y." ".$now_m." ".$now_d;?>");
	//發出警語
	var msg="<?echo $message;?>";
	if(msg!=""){
		alert(msg);
		//計畫有問題時,也要清空下面的計畫和請核資料
		parent.document.getElementById('bugetno').value="";
		parent.document.getElementById('bugname').innerHTML = msg;
		parent.document.getElementById('bugleader').innerHTML = "";
		parent.document.getElementById('bugDepname').innerHTML = "";
		parent.document.getElementById('bugEntrustUnit').innerHTML = "";
		parent.document.getElementById('bugExeDate').innerHTML = "";

		parent.document.getElementById('PT_Identity').value="";
		parent.document.getElementById('PNo').value="";
		parent.document.getElementById('Pname').value="";
		parent.document.getElementById('IdNo').value="";
		parent.document.getElementById('Prank').value="";
		parent.document.addPT.Ptitle.options.length=0;
		parent.document.addPT.payitem.options.length=0;
		parent.document.getElementById('addPTUser').disabled=true;
		<?
			if($OrderNo!=""){
				$strSQL="select distinct Eid from PT_Employed where SerialNo='".$OrderNo."' and RecordStatus in ('0','-2') order by Eid";
				$result=$db->query($strSQL);
				//echo "alert('".$strSQL."')";
				$row=$result->fetchAll();
				if(count($row)>0){
					$result=$db->query($strSQL);
					while($row=$result->fetch()){
						echo "parent.document.getElementById('func_div_".trim($row['Eid'])."').innerHTML = \"\";";
					}
				}
			}
		?>
	}else{	
		var bugno="<?echo $bugno;?>";
		var rowcount="<?echo $rowcount;?>";
		var tag="<?echo $tag;?>";
		var action="<?echo $action;?>";
		
		var dt = new Date();
		// Display the month, day, and year. getMonth() returns a 0-based number.
		var month = dt.getMonth()+1;
		var day = dt.getDate();
		var year = dt.getFullYear()-1911;
		var year_full = dt.getFullYear();
		
		if(tag==""){//有輸入tag,即是拉年月日選單
			if(msg==""){//輸入計畫編號並查詢,或帶入舊請核資料
				if(action!="loading"){//一般輸入資料和查詢
					//先清空下面的請核資料
					parent.document.getElementById('PT_Identity').value="";
					parent.document.getElementById('PNo').value="";
					parent.document.getElementById('Pname').value="";
					parent.document.getElementById('IdNo').value="";
					parent.document.getElementById('Prank').value="";
					parent.document.addPT.Ptitle.options.length=0;
					parent.document.addPT.payitem.options.length=0;					
				}
				//顯示計畫資訊
				parent.document.getElementById('bugetno').value = "<?echo $bugno;?>";
				parent.document.getElementById('bugname').innerHTML = "<?echo $bugname;?>";
				parent.document.getElementById('bugleader').innerHTML = "<?echo $leader;?>";
				parent.document.getElementById('bugDepname').innerHTML = "<?echo $leaderDep;?>";
				parent.document.getElementById('bugEntrustUnit').innerHTML = "";
				parent.document.getElementById('bugExeDate').innerHTML = "<?echo $start."-".$end;?>";
				parent.document.getElementById('bugEntrustUnit').innerHTML = "<?echo $giveunit;?>";
				//起訖資料記錄
				parent.document.getElementById('buget_start').value = "<?echo $start;?>";
				parent.document.getElementById('buget_end').value = "<?echo $end;?>";
				
				if(action!="loading"){//一般輸入資料和查詢
					//修改 select tag,放入計畫起訖時間
					//起年和訖年
					parent.document.addPT.PTstart_y.options.length=0;
					parent.document.addPT.PTend_y.options.length=0;
					var y_range=<?echo $diff_y;?>;
					var y=<?echo $start_y;?>;
					for(i=0;i<y_range;i++){
						if((y+i)==year){
							parent.document.addPT.PTstart_y.options[i]=new Option((y+i), (y+i), false, true);
							parent.document.addPT.PTend_y.options[i]=new Option((y+i), (y+i), false, true);
						}else{
							parent.document.addPT.PTstart_y.options[i]=new Option((y+i), (y+i), false, false);
							parent.document.addPT.PTend_y.options[i]=new Option((y+i), (y+i), false, false);
						}				
					}
					//起月和訖月,先用start_m做為預設
					parent.document.addPT.PTstart_m.options.length=0;
					parent.document.addPT.PTend_m.options.length=0;
					if(year==<?echo $start_y?>){
						var m_range=12-<?echo $start_m;?>+1;
						var m=<?echo $start_m;?>;
					}else if(year==<?echo $end_y?>){
						var m_range=<?echo $end_m;?>;
						var m=1;
					}else{
						var m_range=12;
						var m=1;
					}
					for(i=0;i<m_range;i++){
						if((m+i)==month){
							parent.document.addPT.PTstart_m.options[i]=new Option((m+i), (m+i), false, true);
							parent.document.addPT.PTend_m.options[i]=new Option((m+i), (m+i), false, true);
						}else{
							parent.document.addPT.PTstart_m.options[i]=new Option((m+i), (m+i), false, false);
							parent.document.addPT.PTend_m.options[i]=new Option((m+i), (m+i), false, false);
						}
					}
					//起日和訖日,先用start_d做為預設
					parent.document.addPT.PTstart_d.options.length=0;
					parent.document.addPT.PTend_d.options.length=0;			
					//取得該月天數,用西元年取
					var num_days = new Date(year_full,month,0).getDate();				
					if(year==<?echo $start_y?> && month==<?echo $start_m;?>){
						var d_range=num_days-<?echo $start_d;?>+1;
						var d=<?echo $start_d;?>;
					}else if(year==<?echo $end_y?> && month==<?echo $end_m;?>){
						var d_range=<?echo $end_d;?>;
						var d=1;
					}else{
						var d_range=num_days;
						var d=1;
					}
					
					for(i=0;i<d_range;i++){
						if((d+i)==day){
							parent.document.addPT.PTstart_d.options[i]=new Option((d+i), (d+i), false, true);
							parent.document.addPT.PTend_d.options[i]=new Option((d+i), (d+i), false, true);
						}else{
							parent.document.addPT.PTstart_d.options[i]=new Option((d+i), (d+i), false, false);
							parent.document.addPT.PTend_d.options[i]=new Option((d+i), (d+i), false, false);
						}
					}
				}else{
					//帶入者的請核起訖年月日
					var PT_start_y=parseInt("<?echo $PT_start_y;?>");
					var PT_start_m=parseInt("<?echo $PT_start_m;?>");
					var PT_start_d=parseInt("<?echo $PT_start_d;?>");
					var PT_end_y=parseInt("<?echo $PT_end_y;?>");
					var PT_end_m=parseInt("<?echo $PT_end_m;?>");
					var PT_end_d=parseInt("<?echo $PT_end_d;?>");
							
					var start_y=parseInt("<?echo $start_y;?>");var start_m=parseInt("<?echo $start_m;?>");var start_d=parseInt("<?echo $start_d;?>");
					var end_y=parseInt("<?echo $end_y;?>");var end_m=parseInt("<?echo $end_m;?>");var end_d=parseInt("<?echo $end_d;?>");
							
					//重設起年/訖年選項
					//起年和訖年
					parent.document.addPT.PTstart_y.options.length=0;
					parent.document.addPT.PTend_y.options.length=0;
					var y_range=<?echo $diff_y;?>;
					var y=<?echo $start_y;?>;
					for(i=0;i<y_range;i++){
						if((y+i)==PT_start_y){
							parent.document.addPT.PTstart_y.options[i]=new Option((y+i), (y+i), false, true);
						}else{
							parent.document.addPT.PTstart_y.options[i]=new Option((y+i), (y+i), false, false);
						}				
						if((y+i)==PT_end_y){
							parent.document.addPT.PTend_y.options[i]=new Option((y+i), (y+i), false, true);
						}else{
							parent.document.addPT.PTend_y.options[i]=new Option((y+i), (y+i), false, false);
						}		
					}
					//重設起月/訖月選項
					parent.document.addPT.PTstart_m.options.length=0;
					parent.document.addPT.PTend_m.options.length=0;
					//輸入起月
					if(PT_start_y==start_y){
						var m_range=12-start_m+1;
						var m=start_m;
					}else if(PT_start_y==end_y && PT_start_y!=start_y){
						var m_range=end_m;
						var m=1;
					}else{
						var m_range=12;
						var m=1;
					}
					for(i=0;i<m_range;i++){
						if((m+i)==PT_start_m){
							parent.document.addPT.PTstart_m.options[i]=new Option((m+i), (m+i), false, true);
						}else{
							parent.document.addPT.PTstart_m.options[i]=new Option((m+i), (m+i), false, false);
						}
					}
					//輸入訖月
					if(PT_end_y==start_y){
						var m_range=12-start_m+1;
						var m=start_m;
					}else if(PT_end_y==end_y && PT_end_y!=start_y){
						var m_range=end_m;
						var m=1;
					}else{
						var m_range=12;
						var m=1;
					}
					for(i=0;i<m_range;i++){
						if((m+i)==PT_end_m){
							parent.document.addPT.PTend_m.options[i]=new Option((m+i), (m+i), false, true);
						}else{
							parent.document.addPT.PTend_m.options[i]=new Option((m+i), (m+i), false, false);
						}
					}
					//輸入起日
					//取得該月天數,用西元年取
					var num_days = new Date((PT_start_y+1911),PT_start_m,0).getDate();
					if(PT_start_y==start_y && PT_start_m==start_m){
						var d_range=num_days-start_d+1;
						var d=start_d;
					}else if(PT_start_y==end_y && PT_start_m==end_m && PT_start_y!=end_y){
						var d_range=end_d-1;
						var d=1;
					}else{
						var d_range=num_days;
						var d=1;
					}
					for(i=0;i<d_range;i++){
						if((d+i)==PT_start_d){
							parent.document.addPT.PTstart_d.options[i]=new Option((d+i), (d+i), false, true);
						}else{
							parent.document.addPT.PTstart_d.options[i]=new Option((d+i), (d+i), false, false);
						}
					}
					//輸入訖日
					//取得該月天數,用西元年取
					var num_days = new Date((PT_end_y+1911),PT_end_m,0).getDate();
					if(PT_end_y==start_y && PT_end_m==start_m){
						var d_range=num_days-start_d+1;
						var d=start_d;
					}else if(PT_end_y==end_y && PT_end_m==end_m && PT_end_y!=start_y){
						var d_range=end_d-1;
						var d=1;
					}else{
						var d_range=num_days;
						var d=1;
					}
					for(i=0;i<d_range;i++){
						if((d+i)==PT_end_d){
							parent.document.addPT.PTend_d.options[i]=new Option((d+i), (d+i), false, true);
						}else{
							parent.document.addPT.PTend_d.options[i]=new Option((d+i), (d+i), false, false);
						}
					}
				}
			}else{		
				parent.document.getElementById('bugname').innerHTML = msg;
				parent.document.addPT.PTstart_y.options.length=0;
				parent.document.addPT.PTend_y.options.length=0;
				parent.document.addPT.PTstart_m.options.length=0;
				parent.document.addPT.PTend_m.options.length=0;
				parent.document.addPT.PTstart_d.options.length=0;
				parent.document.addPT.PTend_d.options.length=0;
				
				parent.document.addPT.PTstart_y.options[0]=new Option("","", false, false);
				parent.document.addPT.PTend_y.options[0]=new Option("","", false, false);
				parent.document.addPT.PTstart_m.options[0]=new Option("","", false, false);
				parent.document.addPT.PTend_m.options[0]=new Option("","", false, false);
				parent.document.addPT.PTstart_d.options[0]=new Option("","", false, false);
				parent.document.addPT.PTend_d.options[0]=new Option("","", false, false);
			}
		}else{//下拉年月日產生的動作		
			//留下選定的年月日值
			var selected_y=parseInt(parent.document.addPT.<?echo $selected_y;?>.value);
			var selected_m=parseInt(parent.document.addPT.<?echo $selected_m;?>.value);
			var selected_d=parseInt(parent.document.addPT.<?echo $selected_d;?>.value);
			//alert(selected_y+" "+selected_m+" "+selected_d);
			var start_y=parseInt("<?echo $start_y;?>");var start_m=parseInt("<?echo $start_m;?>");var start_d=parseInt("<?echo $start_d;?>");
			var end_y=parseInt("<?echo $end_y;?>");var end_m=parseInt("<?echo $end_m;?>");var end_d=parseInt("<?echo $end_d;?>");
			//alert(end_m);
			//若是換年時,需確認該年有目前月份
			if(selected_y==start_y && selected_m<start_m){selected_m=start_m;selected_d=start_d;}
			if(selected_y==end_y && selected_m>end_m && selected_y!=start_y){selected_m=end_m;selected_d=end_d;}
			//重設月日選項,年不需要變動
			parent.document.addPT.<?echo $selected_m;?>.options.length=0;
			parent.document.addPT.<?echo $selected_d;?>.options.length=0;
			//輸入月
			if(selected_y==start_y){
				var m_range=12-start_m+1;
				var m=start_m;
			}else if(selected_y==end_y && selected_y!=start_y){
				var m_range=end_m;
				var m=1;
			}else{
				var m_range=12;
				var m=1;
			}		
			for(i=0;i<m_range;i++){
				if((m+i)==selected_m){
					parent.document.addPT.<?echo $selected_m;?>.options[i]=new Option((m+i), (m+i), false, true);
					
				}else{
					parent.document.addPT.<?echo $selected_m;?>.options[i]=new Option((m+i), (m+i), false, false);
				}
			}
			//輸入日
			//取得該月天數,用西元年取
			var num_days = new Date(selected_y+1911,selected_m,0).getDate();
			//alert((parseInt(selected_y)+1911)+" "+selected_m+" "+num_days);
			if(selected_y==start_y && selected_m==start_m){
				var d_range=num_days-start_d+1;
				var d=start_d;
			}else if(selected_y==end_y && selected_m==end_m && selected_y!=start_y){
				var d_range=end_d;
				var d=1;
			}else{
				var d_range=num_days;
				var d=1;
			}
			for(i=0;i<d_range;i++){
				if((d+i)==selected_d){
					parent.document.addPT.<?echo $selected_d;?>.options[i]=new Option((d+i), (d+i), false, true);
				}else{
					parent.document.addPT.<?echo $selected_d;?>.options[i]=new Option((d+i), (d+i), false, false);
				}
			}
		}
	}
	//alert("<?echo $bugno;?>4");
</script>
<body>
</body>
</html>
