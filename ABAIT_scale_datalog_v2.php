<?
include("ABAIT_function_file.php");session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
	print $_SESSION['passwordcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<?
set_css()
?>

</head>
<body class="container">

<?
$names = build_page_pg();
?>								
<h3 align='center'><label>Scale Data Log</label></h3>
		
<?
	if(isset($_REQUEST['date'])){
		$date_bool=$_REQUEST['date'];
	}else{
		$date_bool=null;
	}

	if($date_bool=="NOW"){
		$raw_date=date("Y-m-d H:i:s");
		$date_time = explode(" ", $raw_date);
		$date = $date_time[0];
		$time = $date_time[1];
		
	}else{
		$raw_date=$_REQUEST['datetimepicker'];
		$date_time = explode("T", $raw_date);
		$date = $date_time[0];
		$time = $date_time[1];
		$seconds='00';
		$time = $time.':'.$seconds;
	}

	$duration=$_REQUEST['duration'];

	$behavior_description=$_REQUEST['behavior_description'];
	if($behavior_description=='Enter specific description of behavior which required PRN here.'){
		$behavior_description='';
	}

	$intensity_before=$_REQUEST['intensityB'];
	$trig=1;
	$mapkey=$_SESSION['trigger'];
	$residentkey=$_SESSION['residentkey'];
		
	for($i=1;$i<7;$i++){
		//NOTE intensityA= intensity after intensityB =intensity before any intervention
		$intervention[]=$_REQUEST['intervention'.$i];
		if(isset($_REQUEST['intensityA'.$i])){
			$intensityA[]=$_REQUEST['intensityA'.$i];
		}else{
			$intensityA[]=0;
		}
	}
	if($intervention[5]==1){
		$PRN=1;
	}else{
		$PRN=0;
	}
	$behavior=$_SESSION['scale_name'];

	// $datacheck=array($date,$hour,$minute,$time,$trigger,$duration,$intensity_before,$intervention[0],
	// $intervention[1],$intervention[2],$intervention[3],$intervention[4],$intervention[5],$intensityA[0],$intensityA[1],$intensityA[2],$intensityA[3],$intensityA[4],$intensityA[5],$PRN);

	$intervention_score_0 = 0;
	$intervention_score_1 = 0;
	$intervention_score_2 = 0;
	$intervention_score_3 = 0;
	$intervention_score_4 = 0;
	$intervention_score_5 = 0;
	$intervention_score_6 = 0;

	${'intervention_score_'.$intervention[0]}=($intensity_before-$intensityA[0]);
	
	if(isset($intensityA[1])){
		${'intervention_score_'.$intervention[1]}=$intensityA[0]-$intensityA[1];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[0]-$intensityA[5];
	}
	
	if(isset($intensityA[2])){
		${'intervention_score_'.$intervention[2]}=$intensityA[1]-$intensityA[2];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[1]-$intensityA[5];
	}
	
	if(isset($intensityA[3])){
	${'intervention_score_'.$intervention[3]}=$intensityA[2]-$intensityA[3];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[2]-$intensityA[5];
	}
	
	if(isset($intensityA[4])){
	${'intervention_score_'.$intervention[4]}=$intensityA[3]-$intensityA[4];
	}
	elseif(isset($intensityA[5])){
		$intervention_score_6=$intensityA[3]-$intensityA[5];
	}

	$post_PRN_observation = null;
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
	//mysqli_select_db($_SESSION['database'],$conn);	

	mysqli_query($conn, "INSERT INTO behavior_map_data VALUES(null,'$mapkey','$_SESSION[residentkey]','$behavior','$date','$time','$intervention_score_1','$intervention_score_2','$intervention_score_3','$intervention_score_4','$intervention_score_5','$intervention_score_6','$duration','$PRN','$behavior_description','$intensity_before',null,'$_SESSION[personaldatakey]')");
	$first=$_SESSION['first'];
	$last=$_SESSION['last'];
	
	if($date&&$time){
		print "<h4 align='center'> Scale Data for $first $last has been Logged</h4>\n";
	}else{
		print "<h4>Some information was missing from the Scale form, please return to the previous page.</h4>\n";
	}
	print "<div id='submit'>";
					print "<input	
								type = 'button'
								name = ''
								id = 'backButton'
								value = \"Log Another '$behavior' Episode \"
								onClick=\"backButton('ABAIT_scale_v2.php','$behavior')\"/>\n";

	print "</div>"

?>

	
	</form>
<?build_footer_pg()?>
</body>
</html>
