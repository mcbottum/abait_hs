<?
// Script to auto update backend database with json encoded list of enrollees 
// function autoEnroller($updates){

$houses = ["Mount Pleasant","Old House","Westwood", "Howard","Oxford","Pembroke"];


//******** FOR RESIDENTS ***********//
    $live_updates = file_get_contents('https://pcspublicfiles.blob.core.windows.net/integration-poc/abait/Handsale/AllResidents.json?sv=2019-10-10&se=2021-03-31T23%3A00%3A00Z&si=ABAIT&sr=b&sig=FOseKmpRyP%2FRzjjb%2BsQNHdUFx1V7d%2BuHHQSy0puYhE0%3D');

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
				$house_match=$value['community'];
		}

		if($house_match){

			$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[personID]' ORDER by first";
			
			$check=mysqli_query($conn,$sql);

			if(!$check || mysqli_num_rows($check) == 0){

				$gender=$value["gender"];
				mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$value[firstName]','$value[lastName]',null,'$gender','$privilegekey','$Target_Population','$value[community]','$value[personID]')");
				
			}
		}	
	}
?>
