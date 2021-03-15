<?
// Script to auto update backend database with json encoded list of enrollees 
function autoEnroller($updates){
	$updates='{
		"pss_update": "2020-05-13T11:15:15Z",
		"updates": [{
				"name": "Jane admin",
				"guid": "123qweasdzxc1",
				"role": "admin",
				"facility": [
					"1233456qweasdzxc"
				]
			},
			{
				"name": "Jane carer",
				"guid": "123qweasdzxc2",
				"role": "carer",
				"facility": [
					"1233456qweasdzxc"
				]
			},
			{
				"name": "Jane resident",
				"guid": "123qweasdzxc3",
				"role": "resident",
				"facility": [
					"1233456qweasdzxc"
				]
			},
			{
				"name": "Jane",
				"guid": "123qweasdzxc4",
				"role": "carer",
				"facility": [
					"1233456qweasdzxc"
				]
			}
		]
	}';

	$decoded_update = json_decode($updates, true);

	// $conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']);

	$db = 'agitation';
	$_SESSION['db'] = 'agitation';
	//$host = 'michael-bottums-MacBook-Pro.local';
	$host = 'localhost';
	$db_user = 'abait';
	$db_pwd = 'abait123!';
	$conn=mysqli_connect($host,$db_user,$db_pwd, $db);

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$privilegekey=$_SESSION['personaldatakey'];
	$Target_Population = $_SESSION["Target_Population"];
	$gender="N";
	$date=date("Y,m,d");

	foreach ($decoded_update["updates"] as $value){
		$resident=false;
		echo $value["name"];
		if ($value["role"]=="resident"){
			$sql="SELECT * FROM residentpersonaldata WHERE guid='$value[guid]' ORDER by first";
			$resident=true;
		}else{
			echo $value[guid];
			$sql="SELECT * FROM personaldata WHERE password='$value[guid]'";
		}
		$check=mysqli_query($conn,$sql);

		if(!$check || mysqli_num_rows($check) == 0){
			$name = explode(" ", $value["name"]);
			if(count($name)<2){
				$name[]="";
			}
			
			if($resident){
				mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$name[0]','$name[1]',null,'$gender','$privilegekey','$Target_Population','$facility[0]','$value[guid]')");
			}else{
				mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$value[guid]','$value[role]','$name[0]','$name[1]',null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$facility[0]','$value[guid]')");
			}
		}

	};
}
?>