<?
// Script to auto update backend database with json encoded list of enrollees 
// function autoEnroller($updates){


$houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];


//******** FOR Careers *************//
        $live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/Handsale/AllStaff.json?sv=2019-10-10&se=2021-03-31T23%3A00%3A00Z&si=ABAIT&sr=b&sig=gHUZqEbA8%2F3SqaETAlY3c%2FZp4a1Co%2FWDeVMDFY4T594%3D');

	$decoded_update = json_decode($live_updates, true);



// FOR LOCAL TESTING
	// $db = 'agitation_indp';
	// $db_pwd = 'abait123!';
	// $host = 'localhost';
	// $db_user = 'abait';

// FOR DREAMHOST LIVE
 	$db = 'agitation_hs';
 	$host = 'mysqlhs.abaitscale.com';
 	$db_user = 'abaiths';
 	$db_pwd = 'v2q9as659e%tzfe';
	
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

		$house_match=false;
		if($db=="agitation_indp"){
			foreach($houses as $house){
			   if(strpos($value['communityName'],$house)!==false){
			   		$house_match=$house;
			   		break;
				}
			}
		}else{
			//$house_match=$value['communityName'];
			$house_match="all";
		}
		if(!array_key_exists("jobTitle",$value)){
			$resident=true;
		}else{
			$resident=false;
		}

		if($house_match){

			// NOTE - should take entire GUID going forward then match like in passcheck
			// $pwd = substr($value["personID"], 0, 5);
			
			

#### ONE TIME ONLY ####
			$pwd = $value['connectionID'];
			#$sql="SELECT * FROM personaldata WHERE password LIKE '$pwd'";
			$name = explode(" ", $value["workerName"]);
			if(count($name)<2){
				$name[]="";
			}else{
				$first=$name[0];
				$last=$name[1];
			}

			$sql="SELECT * FROM personaldata WHERE first LIKE '$first' AND last LIKE '$last'";

#####  ABOVE #####

			$full_pwd = $value["personID"];
			$connection_pwd = $value["connectionID"];

			$check=mysqli_query($conn,$sql);

			$accesslevel="";
			if(!$check || mysqli_num_rows($check) == 0){

				echo "DID NOT FIND ".$first." ".$last;

					// if(stripos(strtolower($value['jobTitle']),"director")!==false){
					// 	$accesslevel="admin";
					// }else if(stripos(strtolower($value['jobTitle']),"care")!==false){
					// 	$accesslevel='caregiver';
					// }
					// $name = explode(" ", $value["workerName"]);
					// if(count($name)<2){
					// 	$name[]="";
					// }else{
					// 	$first=$name[0];
					// 	$last=$name[1];
					// }

					// mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$pwd','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house_match')");
				
			}elseif(mysqli_num_rows($check) > 0 && !$resident){
				$row1=mysqli_fetch_assoc($check);
				$row_id = $row1['personaldatakey'];

				// mysqli_query($conn,"UPDATE personaldata SET password='$full_pwd' WHERE password LIKE '$pwd%'");

				if($row1['house']!='all'){

					if($row1['house']!=$house_match){
					
							mysqli_query($conn,"UPDATE personaldata SET house='all' WHERE password LIKE '$pwd'");
						
							// echo "updated  ";
					}
				}

				mysqli_query($conn,"UPDATE personaldata SET password='$connection_pwd' WHERE personaldatakey='$row_id'"); 

			}
		}
		
	}
// }
?>
