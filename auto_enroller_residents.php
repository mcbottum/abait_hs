<?
// Script to auto update backend database with json encoded list of enrollees 
// function autoEnroller($updates){

$houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];


//******** FOR RESIDENTS ***********//
    $live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/CleverCare/AllResidents.json?sv=2019-10-10&se=2021-12-01T00%3A00%3A00Z&si=ABAIT&sr=b&sig=WBdFa2Al5L1ev8%2FASYx6DALS0GCS7hIEuOUpoh%2FbpIA%3D');

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
  	$set_house_to_all = true;
	
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

		if($house_match){

			$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[personID]' ORDER by first";
			
			$check=mysqli_query($conn,$sql);

			if(!$check || mysqli_num_rows($check) == 0){
				$gender=$value["gender"];
				if(array_key_exists('preferredName',$value) && $value['preferredName']){
					$first_name=$value['preferredName'];
				}else{
					$first_name=$value['firstName'];
				}
				mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$first_name','$value[lastName]',null,'$gender','$privilegekey','$Target_Population','$value[community]','$value[personID]','$value[community]')");
			}elseif(mysqli_num_rows($check) > 0){
				// $row1=mysqli_fetch_assoc($check);
				// if($row1['house']!=$house_match) && $row1['house']!='all'){
				// 	$concat_house = ",".$house_match;
				// 	mysqli_query($conn,"UPDATE residentpersonaldata SET house=concat(community,'$concat_house') WHERE residentkey='$row_id'");
				// }

				mysqli_query($conn,"UPDATE residentpersonaldata SET community='$value[community]' WHERE guid='$value[personID]'");
			}
		}	
	}
?>
