<?php   	
		
		//session_start();	 
		ini_set('error_reporting', E_ALL & ~E_NOTICE);
		//ini_set('error_reporting', E_ALL);
		
		/*$link = mssql_connect("140.113.40.22,1633", "PTemploy", "PT41107employ") or die ("connect failed");
        @mssql_select_db("兼任人員資料庫",$link);
		ini_set('mssql.charset', 'UTF-8');*/
		//改用 pdo 套件		
		try{
			//$dsn = "sqlsrv:server=140.113.40.195,1633;database=兼任工作費測試資料庫";
			//$root = "PTsalary";
			//$pw = "PT41251salary";

			//$db = new PDO($dsn, $root, $pw);
			//$db = new PDO("sqlsrv:server=140.113.40.22,1633;database=兼任人員資料庫","PTemploy","PT41107employ");
			$db = new PDO("sqlsrv:server=140.113.41.22,1633;database=兼任人員資料庫","supercygnus","1qaz@WSX");
			$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			//print "Error: " . $e->getMessage() . "＜br/＞";
			die();
		}
		
		date_default_timezone_set('Asia/Taipei'); //時間為台灣的時間
        $Update_Time = date("Y/m/d");
		//include("filter.php");
		session_start();
?>