<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=MS950">
<title>Insert title here</title>
<script type="text/javascript">

</script>
</head>
<body>
</body>
</html>
<?php 

//$snumber = trim($_GET["number"]);
$action= trim($_GET["action"]);
//if(isset($_GET["empno"]))
//$empno= trim($_GET["empno"]);

$snumber=trim($_GET["OrderNo"]);
if($action=="form2"){
	$url="https://receipt-test.nctu.edu.tw/parttime_employ_new/form2.php?OrderNo=".$snumber;
	//echo $url;
}
//echo $url;
/*if($action=="form2_"){
	//約用請核單
	$url="https://140.113.41.94/sample/form2_.asp?SerialNumber=".$snumber;
}
else if($action=="form1"){
	//約用人員資料表
	$url="https://140.113.41.94/sample/form1.asp?SerialNumber=".$snumber;	
}
else if($action=="form2_Q"){
	//研發替代役請核單
	$url="https://140.113.41.94/sample/form2_Q.asp?SerialNumber=".$snumber;
}
else if($action=="LeavePrint"){
	//勞退金調查表
	$url="https://140.113.41.94/sample/LeavePrint.asp?SerialNumber=".$snumber;
}
else if($action=="LeavePrint_bydr"){
	//勞退金調查表(約聘助教)
	$url="https://140.113.41.94/sample/LeavePrint_bydr.asp?SerialNumber=".$snumber;
}
else if($action=="form7"){
	//計畫約用契約書
	$url="https://140.113.41.94/sample/form7.asp?SerialNumber=".$snumber;
}
else if($action=="formbydr"){
	//博士後研究人員契約書
	$url="https://140.113.41.94/sample/formbydr.asp?SerialNumber=".$snumber;
}
else if($action=="formbyRF"){
	//約聘研究人員契約書
	$url="https://140.113.41.94/sample/formbyRF.asp?SerialNumber=".$snumber;
}
else if($action=="formbyRF"){
	//約聘研究人員契約書,無範例
	$url="https://140.113.41.94/sample/formbyRF.asp?SerialNumber=".$snumber;
}
else if($action=="formbyUniTA"){
	//約聘助教契約書
	$url="https://140.113.41.94/sample/formbyUniTA.asp?SerialNumber=".$snumber;
}
else if($action=="form6"){
	//圖書館申請單
	$url="https://140.113.41.94/sample/form6.asp?SerialNumber=".$snumber;
}
else if($action=="form10"){
	//計畫約用人員具結書
	$url="https://140.113.41.94/sample/form10.asp?SerialNumber=".$snumber;
}
else if($action=="form11"){
	//約用人員具結書
	$url="https://140.113.41.94/sample/form11.asp?SerialNumber=".$snumber;
}
else if($action=="print_mod"){
	//異動申請單
	$url="https://140.113.41.94/sample/print_mod.asp?serialno=".$snumber."&empno=".$empno;
}
else if($action=="form7_constatus2_mod"){
	//計畫約用契約書
	$url="https://140.113.41.94/sample/form7_constatus2_mod.asp?serialno=".$snumber."&empno=".$empno;
}
else if($action=="formbyUniTA_constatus6_mod"){
	//約聘助教契約書,無範例
	$url="https://140.113.41.94/sample/formbyUniTA_constatus6_mod.asp?serialno=".$snumber."&empno=".$empno;
}
else if($action=="formbyRF_constatus7_mod"){
	//約聘研究人員契約書,無範例
	$url="https://140.113.41.94/sample/formbyRF_constatus7_mod.asp?serialno=".$snumber."&empno=".$empno;
}
else if($action=="formbydr_constatus4_mod"){
	//博士後研究人員契約書
	$url="https://140.113.41.94/sample/formbydr_constatus4_mod.asp?serialno=".$snumber."&empno=".$empno;
}*/



//$url="http://140.113.41.94/sample/form2_.asp?SerialNumber=3936";
$f = fopen($url, 'r');
if (!$f)  die("Couldn't open input stream\n");

$data = '';

while ($buffer =  fread($f, 8192)) 
	$data .= $buffer;

//$file_data_str = stream_get_contents($f);

//echo $file_data_str;

date_default_timezone_set('Asia/Taipei');
$time=date("Y-m-d_His");

$rand_num=rand(1,10000);

$output_doc_name=$snumber."_".$action."_".$time.$rand_num.".doc";
//$output_doc_url="D:/".$output_doc_name;
//$output_doc_folder="to_pdf/docfile/PTfile/".$action;
$output_doc_folder="to_pdf/docfile/form1";
if(!is_dir($output_doc_folder))
	mkdir($output_doc_folder,0777,true);
$output_doc_url=$output_doc_folder."/".$output_doc_name;
$output_doc_url_absolute="C:/Apache2.2/htdocs/test/".$output_doc_url;

$stop_flag=false;

try{
	file_put_contents($output_doc_url,$data);
}
catch(Exception $e){
	echo "無檔案內容";
	$stop_flag=true;
}
//$write=fopen('D:/f1.doc', 'R');
//fwrite($write,$file_data_str);


if($data==""){
	$stop_flag=true;
}

fclose($f);



$output_pdf_name=$snumber."_".$action."_".$time.$rand_num.".pdf";
//$output_pdf_url="D:/".$output_pdf_name;
//$output_pdf_folder="to_pdf/pdffile/PTfile/".$action;
$output_pdf_folder="to_pdf/pdffile/form1";
if(!is_dir($output_pdf_folder))
	mkdir($output_pdf_folder,0777,true);
$output_pdf_url=$output_pdf_folder."/".$output_pdf_name;
$output_pdf_url_absolute="C:/Apache2.2/htdocs/test/".$output_pdf_url;

ini_set("com.allow_dcom","true");


if(!$stop_flag){

	
try{
	$word = new com('word.application') or die('MS Word could not be loaded');
}
catch (com_exception $e)
{
	$nl = "<br />";
	echo $e->getMessage() . $nl;
	echo $e->getCode() . $nl;
	echo $e->getTraceAsString();
	echo $e->getFile() . " LINE: " . $e->getLine();
	$word->Quit();
	$word = null;
	die;

}

$word->Visible = 0;
$word->DisplayAlerts = 0;





try{
	$doc = $word->Documents->Open($output_doc_url_absolute);
}
catch (com_exception $e)
{
	$nl = "<br />";
	echo $e->getMessage() . $nl;
	echo $e->getCode() . $nl;
	echo $e->getFile() . " LINE: " . $e->getLine();
	$word->Quit();
	$word = null;
	die;
}
//echo "doc opened";
try{
	$doc->ExportAsFixedFormat($output_pdf_url_absolute, 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);
}
catch (com_exception $e)
{
	$nl = "<br />";
	echo $e->getMessage() . $nl;
	echo $e->getCode() . $nl;
	echo $e->getTraceAsString();
	echo $e->getFile() . " LINE: " . $e->getLine();
	$word->Quit();
	$word = null;
	die;
}

//echo "created pdf";
$word->Quit();
$word = null;





header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=".$output_pdf_name);
header("Content-Transfer-Encoding: binary");
header("Cache-Control: cache, must-revalidate");
//header("Cache-Control: no-cache");
header("Pragma: public");
//header("Pragma: no-cache");
header("Expires: 0");



set_time_limit(0);
$file = @fopen($output_pdf_url,"rb");
while(!feof($file))
{
	print(@fread($file, 1024*8));
	ob_flush();
	flush();
}




}


?>