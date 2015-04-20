<?php

include("connectSQL.php");


$id=$_POST["id"];


$returnArray=array();


// $sql="select *
// 		  from StudentData
// 		  where std_pid='".$id."' and 學籍之在學狀況  in ('在學','應畢','延畢')";

// $rsList=$db->query($sql);


// if ( $ds = $rsList->fetch() ) {
// 	$returnArray["has"]="true";
// 	$returnArray["name"]=$ds["std_cname"];
// 	$returnArray["idcode"]=$ds["std_stdcode"];
// 	$returnArray["idno"]=$ds["std_pid"];
// 	$returnArray["status"]="S";
// 	$returnArray["std_degree"]=$ds["std_degree"];
// }
// else{
	
// 	$sql="select *
// 		  from StudentData
// 		  where std_stdcode='".$id."' and 學籍之在學狀況  in ('在學','應畢','延畢')";
	
	
// 	$rsList=$db->query($sql);
	
	
	
// 	$sql="select *
// 	   	  from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime]
// 		  where idno ='".$id."' and 在離職狀態='在職'";
	
// 	$rsList=$db->query($sql);
	
	
// 	if ( $ds = $rsList->fetch() ) {
// 		$returnArray["has"]="true";
// 		$returnArray["name"]=$ds["Name"];
// 		$returnArray["idcode"]=$ds["EmpNo"];
// 		$returnArray["idno"]=$ds["idno"];
// 		$returnArray["status"]="J";
// 		$returnArray["std_degree"]="";
// 	}
// 	else{
// 		$returnArray["has"]="false";
// 		$returnArray["name"]="";
// 		$returnArray["idcode"]="";
// 		$returnArray["idno"]="";
// 		$returnArray["status"]="U";
// 		$returnArray["std_degree"]="";
// 	}
	
// }


		while(true){
			
				$sql="select *
			  	from StudentData
			  	where std_pid='".$id."' and 學籍之在學狀況  in ('在學','應畢','延畢')";
				
				$rsList=$db->query($sql);
				
				
				if ( $ds = $rsList->fetch() ) {
					$returnArray["has"]="true";
					$returnArray["name"]=$ds["std_cname"];
					$returnArray["idcode"]=$ds["std_stdcode"];
					$returnArray["idno"]=$ds["std_pid"];
					$returnArray["status"]="S";
					$returnArray["std_degree"]=$ds["std_degree"];
					break;
				}
				
				
				$sql="select *
		  		from StudentData
		  		where std_stdcode='".$id."' and 學籍之在學狀況  in ('在學','應畢','延畢')";
				
				
				$rsList=$db->query($sql);
				if ( $ds = $rsList->fetch() ) {
					$returnArray["has"]="true";
					$returnArray["name"]=$ds["std_cname"];
					$returnArray["idcode"]=$ds["std_stdcode"];
					$returnArray["idno"]=$ds["std_pid"];
					$returnArray["status"]="S";
					$returnArray["std_degree"]=$ds["std_degree"];
					break;
				}
				
				
				$sql="select *
	   	  		from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime]
		  		where idno ='".$id."' and 在離職狀態='在職'";
				
				$rsList=$db->query($sql);
				
				
				if ( $ds = $rsList->fetch() ) {
					$returnArray["has"]="true";
					$returnArray["name"]=$ds["Name"];
					$returnArray["idcode"]=$ds["EmpNo"];
					$returnArray["idno"]=$ds["idno"];
					$returnArray["status"]="J";
					$returnArray["std_degree"]="";
					break;
				}
				
				$sql="select *
	   	  		from [PERSONDBOLD].[personnelcommon].[dbo].[vi_Personnel4PartTime]
		  		where EmpNo ='".$id."' and 在離職狀態='在職'";
				
				$rsList=$db->query($sql);
				
				
				if ( $ds = $rsList->fetch() ) {
					$returnArray["has"]="true";
					$returnArray["name"]=$ds["Name"];
					$returnArray["idcode"]=$ds["EmpNo"];
					$returnArray["idno"]=$ds["idno"];
					$returnArray["status"]="J";
					$returnArray["std_degree"]="";
					break;
				}
				
				
				$returnArray["has"]="false";
				$returnArray["name"]="";
				$returnArray["idcode"]="";
				$returnArray["idno"]="";
				$returnArray["status"]="U";
				$returnArray["std_degree"]="";
				break;
		}

// $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);



// $city = array();
// $city["0"] = array ("name"=>"Jason", "tel"=>"0919000000", "address"=> "新竹");
// $city["1"] = array ("name"=>"May", "tel"=>"0928222222", "address"=> "桃園");

// $city["1"]["name"]="Max";
// $city["1"]["tel"]="0932528459";
// $city["1"]["address"]="台北市";



//echo json_encode($city);
echo json_encode($returnArray);

?>