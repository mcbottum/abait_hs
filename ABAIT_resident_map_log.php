<?
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
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
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">

<script>
	function backButton(location, behavior, id) {
		if(location=='caregiverhome.php'){
			self.location=location;
		}else if(location=='resident_scale.php'){
			self.location=location+'?behavior='+behavior;
		}
		
	}
</script>
<style type="text/css">
	ul{
		list-style-type:none;
	}
</style>
</head>
<div class='content'>
<body>
<fieldset>
<div id="body" style="margin: auto; text-align: left">
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION['privilege'],$cgfirst);
?>
	
			<h2 align="center"><label>Behavior Characterization Session</label></h2>
	
		
<?
					// $date=$_REQUEST['date'];
					// 	if($date==1){
					// 		$date=date("Y,m,d");
					// 		//print"$date";
							
					// 	}else{
					// 		$day=$_REQUEST['day'];
					// 		$month=$_REQUEST['month'];
					// 		$year=$_REQUEST['year'];
					// 		$date=$year.','.$month.','.$day;
					// 	}
					// $hour=$_REQUEST['hour'];
					// $minute=$_REQUEST['minute'];
					// $ampm=$_REQUEST['ampm'];
					// if($ampm=="PM"){
					// 	$hour=$hour+12;
					// }
					// $seconds=00;
					// $time=$hour.":".$minute.":".$seconds;
					$raw_date=$_REQUEST['datetimepicker'];

					$date_time = explode(" ", $raw_date);
					$date = $date_time[0];
					$date = str_replace('/',',',$date);
					$time = $date_time[1];
					$duration=$_REQUEST['duration'];
					$trigger=$_REQUEST['trigger'];
					$trigger=str_replace('_',' ',$trigger);
					if($trigger=='other'){
						$trigger=$_REQUEST['custom_trigger'];
					}
					$intervention=$_REQUEST['intervention'];
					$behavior=str_replace('_',' ',$_REQUEST['scale_name']);
					$intensity=$_REQUEST['intensity'];
			
					//$intensity=str_replace('_',' ',$_REQUEST[behave_intensity]);
					$behave_class=$_REQUEST['behave_class'];
					
					$behavior_description=$_REQUEST['specific_behavior_description'];
					$intervention_avoid=$_REQUEST['intervention_avoid'];
					if(!$intervention_avoid){
						$intervention_avoid='none';
					}

					$PRN=$_REQUEST['PRN'];
					$pre_PRN_observation=$_REQUEST['pre_PRN_observation'];
			
					$residentkey=$_SESSION['residentkey'];
					
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
					$sql="SELECT Target_Population FROM residentpersonaldata WHERE residentkey='$residentkey'";
					$session=mysqli_query($conn,$sql);
					$row=mysqli_fetch_assoc($session);
					$Target_Population=mysqli_real_escape_string($conn,$row['Target_Population']);

					mysqli_query($conn, "INSERT INTO resident_mapping VALUES(null,'$residentkey','$Target_Population','$date','$time','$duration','$trigger','$intervention','$intervention_avoid','$behavior','$intensity','$behave_class','$behavior_description','$PRN','$pre_PRN_observation',null,'$_SESSION[personaldatakey]',0)");
					
					mysqli_close($conn);
					print "<h3 align='center'>  $date Mapping Data for $_SESSION[first] $_SESSION[last] has been Logged</h3>\n";	
					print "<div id='submit'>";
					print "<ul align='center'>";
						print "<li>";
							print "<input	type = 'button'
										name = ''
										id = 'backButton'
										value = \"Log Another '$behavior' Episode \"
										onClick=\"backButton('resident_scale.php','$behavior')\"/>\n";
						print "</li>";
					print "</ul>";	
					print "</div>";			
?>	

	</fieldset>

<?build_footer()?>
</body>
</div>
</html>