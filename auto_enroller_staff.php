<?
// Script to auto update backend database with json encoded list of enrollees 
// function autoEnroller($updates){


$houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];


//******** FOR Careers *************//
        $live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/CleverCare/AllStaff.json?sv=2019-10-10&se=2021-12-01T00%3A00%3A00Z&si=ABAIT&sr=b&sig=CEnc8afA9KNZT9p6g2ccste7KLZ0ilwKCif85aXCBoE%3D');

	$decoded_update = json_decode($live_updates, true);



// FOR LOCAL TESTING
	// $db = 'agitation_indp';
	// $db_pwd = 'abait123!';
	// $host = 'localhost';
	// $db_user = 'abait';

// FOR DREAMHOST LIVE hs
 	// $db = 'agitation_hs';
 	// $host = 'mysqlhs.abaitscale.com';
 	// $db_user = 'abaiths';
 	// $db_pwd = 'v2q9as659e%tzfe';
// FOR DREAMHOST LIVE cog
 	$db = 'agitation_cog';
 	$host = 'mysqlcog.abaitscale.com';
 	$db_user = 'abaitcog';
 	$db_pwd = 'abaitcog13!';
 	$set_house_to_all = false;
	
	$conn=mysqli_connect($host,$db_user,$db_pwd, $db);

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}else{
		echo "connection succeeded";
	}

	$privilegekey=$_SESSION['personaldatakey'];
	$Target_Population = $_SESSION["Target_Population"];
	$Target_Population = "Dementia";
	$privilegekey = "228";
	$gender="N";
	$date=date("Y,m,d");

	// $sql_test = "SELECT * from personaldata WHERE personaldatakey=230";
	// $check=mysqli_query($conn,$sql_test);
	// while($row=mysqli_fetch_assoc($check)){
	// 	print_r($row);
	// }

	foreach($decoded_update as $value){

		// Get House
		$house_match=false;
		if($db=="agitation_indp"){
			foreach($houses as $house){
			   if(strpos($value['communityName'],$house)!==false){
			   		$house_match=$house;
			   		break;
				}
			}
		}else if($set_house_to_all){
			$house_match="all";
		}else{
			$house_match=$value['communityName'];
		}

		// If no job title, assume record is a resident
		if(!array_key_exists("jobTitle",$value)){
			$resident=true;
		}else{
			$resident=false;
		}

		if($house_match){
			// NOTE - should take entire GUID going forward then match like in passcheck
			// $pwd = substr($value["personID"], 0, 5);
			
			// Get pwd
			$pwd = $value['connectionID'];

			// Get Name
			$name = explode(" ", $value["workerName"]);
			if(count($name)<2){
				$name[]="";
			}else{
				$first=$name[0];
				$last=$name[1];
			}

			// Get access level
			$accesslevel="";
			if(stripos($value['jobTitle'],"manager")!==false || stripos($value['roleDescription'],"manager")!==false){
				$accesslevel="admin";
			}else if(stripos($value['jobTitle'],"carer")!==false || stripos($value['roleDescription'],"carer")!==false || stripos($value['roleDescription'],"nurse")!==false){
				$accesslevel='caregiver';
			}

			// If already have record
			$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd'";
			$check=mysqli_query($conn,$sql);

			//$full_pwd = $value["personID"]; // Don't really need this any more
			// $connection_pwd = $value["connectionID"];

			if(!$check || mysqli_num_rows($check) == 0){

					$community_name = $value['communityName'];
					mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house_match','$community_name')");
				
			}elseif(mysqli_num_rows($check) > 0){

				$row1=mysqli_fetch_assoc($check);

				// mysqli_query($conn,"UPDATE personaldata SET password='$full_pwd' WHERE password LIKE '$pwd%'");

				//  NEED TO THINK MORE ABOUT THIS
				// if($row1['house']!=$house_match) && $row1['house']!='all'){
				// 	$concat_house = ",".$house_match;
				// 	mysqli_query($conn,"UPDATE personaldata SET house=concat(house,'$concat_house') WHERE password LIKE '$pwd'");
				// }

				// if($row1['house']!='all'){

					// if($row1['house']!=$house_match){
					
							// mysqli_query($conn,"UPDATE personaldata SET house='all' WHERE password LIKE '$pwd'");
				echo $value['locationID'];
				if($row1['community']){
					$concat_value = ",".$value['communityName'];
				}else{
					$concat_value = $value['communityName'];
				}
				$concat_value = ",".$value['communityName'];
				mysqli_query($conn,"UPDATE personaldata SET commuity=concat(community,'$concat_value') WHERE password='$pwd'");
				// mysqli_query($conn,"UPDATE personaldata SET community='$value[communityName]' WHERE password LIKE '$pwd'");
						
							// echo "updated  ";
					// }
				// }

				//mysqli_query($conn,"UPDATE personaldata SET password='$connection_pwd' WHERE personaldatakey='$row_id'"); 

			}
		}
	}
// }
?>
