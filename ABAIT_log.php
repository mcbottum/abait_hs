<?
include("ABAIT_function_file.php");session_start();
ob_start()?>
<?session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
	print $_SESSION['passwordcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type"content="text/html;
charset=utf-8"/>
<head>
<title>
<?
print $_SESSION['SITE']
?>
</title>

<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION[privilege],$cgfirst);
?>
<form 	action = "ABAIT_log.php"
method = "post">
<?
//if password is changed change script on line  259 of this file
$filename =$_REQUEST["submit"];				
	if($filename=="Submit New Resident Personal Data"){
		$first=$_REQUEST['first'];
		$last=$_REQUEST['last'];
		$gender=$_REQUEST['gender'];
		$year=$_REQUEST['year'];
		$month=$_REQUEST['month'];
		$day=$_REQUEST['day'];
		$birthdate=$year.$month.$day;
		$age=floor((time() - strtotime($birthdate))/31556926);
		$date=date("Y,m,d");
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database'],$conn);
		mysqli_query("INSERT INTO residentpersonaldata VALUES(null,'$first','$last','$birthdate','$gender')");
		print"res";
	}elseif($filename=="Submit New Caregiver Personal Data"){
		$newpassword1=$_REQUEST['password1'];
		$newpassword2=$_REQUEST['password2'];
		if($newpassword1==$newpassword2){
			$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']);
			$newpassword1=mysqli_real_escape_string($newpassword1,$conn);
			$newpassword2=mysqli_real_escape_string($newpassword2,$conn);
			$sql1=("SELECT * FROM personaldata WHERE password='$newpassword1'");	
			mysqli_select_db($_SESSION['database']);
			$session1=mysqli_query($sql1,$conn);
			if(!$row1=mysqli_fetch_assoc($session1)){
					$accesslevel="caregiver";
					$password=$newpassword1;
					$first=$_REQUEST['first'];
					$last=$_REQUEST['last'];
					$gender=$_REQUEST['gender'];
					$_SESSION['gender']=$gender;
					$year=$_REQUEST['year'];
					$month=$_REQUEST['month'];
					$day=$_REQUEST['day'];
					$birthdate=$year.$month.$day;
					$address=$_REQUEST['street_address'];
					$city=$_REQUEST['city'];
					$state=$_REQUEST['state'];
					$zipcode=$_REQUEST['zipcode'];
					$phone=$_REQUEST['phone'];
					$email=$_REQUEST['email'];
					$date=date("Y,m,d");
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
					mysqli_select_db($_SESSION['database'],$conn);
					mysqli_query("INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last','$gender','$birthdate','$address','$city','$state','$zipcode','$phone','$email')");
					mysqli_close($conn);
				}			
				else{$nextfile="passwordtaken.php";
				}
		}				
		if ($newpassword1!=$newpassword2){
		$nextfile='reenterpassword.php';
		}	
}elseif($filename=='Submit Behavior Characterization Data'){
					$date=$_REQUEST['date'];
						if($date==1){
							$date=date("Y,m,d");
							
						}else{
							$day=$_REQUEST['day'];
							$month=$_REQUEST['month'];
							$year=$_REQUEST['year'];
							$date=$year.','.$month.','.$day;
						}
					$hour=$_REQUEST['hour'];
					$minute=$_REQUEST['minute'];
					$ampm=$_REQUEST['ampm'];
					if($ampm=="PM"){
						$hour=$hour+12;
					}
					$seconds=00;
					$time=$hour.":".$minute.":".$seconds;
					$duration=$_REQUEST['duration'];
					$trigger=$_REQUEST['trigger'];
					$intervention=$_REQUEST['intervention'];
					$behavior=str_replace('_',' ',$_REQUEST['scale_name']);
					$intensity=$_REQUEST['intensity'];
			
					//$intensity=str_replace('_',' ',$_REQUEST[behave_intensity]);
					$behave_class=$_REQUEST['behave_class'];
					
					$behavior_description=$_REQUEST[specific_behavior_description];
				
				
					$PRN=$_REQUEST['PRN'];
					$residentkey=$_SESSION['residentkey'];
					
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
					mysqli_select_db($_SESSION['database'],$conn);
					mysqli_query("INSERT INTO resident_mapping VALUES(null,'$residentkey','$date','$time','$duration','$trigger','$intervention','$behavior','$intensity','$behave_class','$behavior_description','$PRN','$post_PRN_observation')");
					mysqli_close($conn);
					print "<h2>  $date Mapping Data for $_SESSION[first] $_SESSION[last] has been Logged</h2>\n";
					//$nextfile='caregiverhome.php';
}elseif($filename=="Submit Resident for Global Analysis"){//following code provides global analysis
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));
		$residentkey=$_REQUEST['residentkey'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);
		$sql4="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start'";
		$session4=mysqli_query($sql4,$conn);
		$sum_duration=0;
		$sum_PRN=0;
		$sum_episodes=0;
		while($row4=mysqli_fetch_assoc($session4)){
			$sum_duration=$row4['duration']+$sum_duration;
			$sum_PRN=$row4['PRN']+$sum_PRN;
			$sum_episodes=$sum_episodes+1;
		}
		$behavarray=array('Motor','Resistance','Vocalizations','Agressiveness');
		$_SESSION['first']=$row1['first'];
		$_SESSION['last']=$row1['last'];
		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
			print "<table border='1'>";
				print"<h2> Global Analysis for $first $last</h2>\n";
				print"<tr>\n";
					print"<th>Start Date</th>\n";
					print"<th>End Date</th>\n";
					print"<th>Total Episodes</th>\n";
					print"<th>Total Duration of Episodes</th>\n";
					print"<th>Total PRN</th>\n";					
				print"<tr>\n";
				print"<tr>\n";
					print"<td>$date_start</td>\n";
					print"<td>$date</td>\n";
					print"<td>$sum_episodes</td>\n";
					print"<td>$sum_duration</td>\n";
					print"<td>$sum_PRN</td>\n";					
				print"<tr>\n";
			print "</table>";
		print"<h2>Trigger and Intervetion Analysis</h2>\n";
	foreach($behavarray as $behavior){
		$sql2="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$behavior'";
		$session2=mysqli_query($sql2,$conn);
		$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='$behavior'";
		$session3=mysqli_query($sql3,$conn);
			$r=0;
			print "<table border='1'>";
			print"<h2><tr><th colspan='4'>$behavior Behavior Episodes</th></tr></h2>\n";
				print"<tr>\n";
					print"<th>----Trigger----</th>\n";
					print"<th>Number of Episodes</th>\n";
					print"<th>Duration of Episodes</th>\n";
					print"<th>Most Effective Intervention</th>\n";
				print"</tr>\n";
						while($row2=mysqli_fetch_assoc($session2)){
							$r=$r+1;
							$episodes=0;
							$duration=0;
							$intv=0;
							$intv1=0;
							$intv2=0;
							$intv3=0;
							$intv4=0;
							$intv5=0;
							$intv6=0;
							print"<tr>\n";
							print "<td> $row2[trigger] </td>\n";
										$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='$behavior'";
										$session3=mysqli_query($sql3,$conn);
								while($row3=mysqli_fetch_assoc($session3)){
									if($row2[mapkey]==$row3[mapkey]){
										$episodes=$episodes+1;
										$duration=$duration+$row3[duration];
										$intv1=$intv1+$row3[intervention_score_1];
										$intv2=$intv2+$row3[intervention_score_2];
										$intv3=$intv3+$row3[intervention_score_3];
										$intv4=$intv4+$row3[intervention_score_4];
										$intv5=$intv5+$row3[intervention_score_5];
										$intv6=$intv6+$row3[intervention_score_6];
									}
								}
								$intv=0;
								for ($s=1;$s<6;$s++){
									if($intv<${'intv'.$s}){
										$intv=${'intv'.$s};
										$best=$s;
									}
								}
								print"<td>$episodes</td>\n";
								print"<td>$duration</td>\n";
								$best='intervention_'.$best;
								print"<td>$row2[$best]</td>\n";
							print"</tr>\n";
						}//end row while
				print"</tr>\n";
			print "</table>";
		}//end foreach
	}//end choose resident for global analysis
elseif($filename=="Submit Map"){//following code collects and logs data for another map
		$anothermap=$_REQUEST['anothermap'];
		$r=$_SESSION['r'];
		if($r>0){
		$residentkey=$_SESSION['residentkey'];
		$behavior=$_SESSION['behavior'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database'],$conn);
			for ($dataline=0; $dataline<$r;$dataline ++){
				$trigger=NULL;	
				$trigger=$_REQUEST['trigger'.$dataline];
				if($trigger){
					$intervention_1=$_REQUEST['intervention_1'.$dataline];
					$intervention_2=$_REQUEST['intervention_2'.$dataline];
					$intervention_3=$_REQUEST['intervention_3'.$dataline];
					$intervention_4=$_REQUEST['intervention_4'.$dataline];
					$intervention_5=$_REQUEST['intervention_5'.$dataline];
					$intervention_6=$_REQUEST['intervention_6'.$dataline];		
					mysqli_query("INSERT INTO behavior_maps VALUES(null,'$residentkey','$behavior','$trigger','$intervention_1','$intervention_2','$intervention_3','$intervention_4','$intervention_5','$intervention_6')");
				}
			}// end for
			$anothermap=$_REQUEST['anothermap'];
			if($anothermap=='yes'){
				$nextfile='chooseresident_for_mapping.php';
			}
			else{$nextfile='adminhome.php';
			}
		}
	}//end submit behavior map elseif
elseif($filename=='Submit Resident Scale Data'){
					$date=$_REQUEST['date'];
					$date=$_REQUEST['date'];
						if($date==1){
							$date=date("Y,m,d");
							
						}else{
							$day=$_REQUEST['day'];
							$month=$_REQUEST['month'];
							$year=$_REQUEST['year'];
							$date=$year.','.$month.','.$day;
						}
					print $date;
					$hour=$_REQUEST['hour'];
					$minute=$_REQUEST['minute'];
					$ampm=$_REQUEST['ampm'];
					if($ampm=="PM"){
						$hour=$hour+12;
					}
					$seconds=00;
					$time=$hour.":".$minute.":".$seconds;
					//$trigger=$_REQUEST['trigger'];
					$duration=$_REQUEST['duration'];
					$behavior_description=$_REQUEST[behavior_description];
					if($behavior_description=='Enter specific description of behavior which required PRN here.'){
						$behavior_description='';
					}
					$intensity_before=$_REQUEST['intensityB'];
					$trig=1;
					$mapkey=$_SESSION['trigger'];
					$residentkey=$_SESSION['residentkey'];
					
					for($i=1;$i<7;$i++){
						$intervention[]=$_REQUEST['intervention'.$i];
						$intensityA[]=$_REQUEST['intensityA'.$i];
					}
					if($intervention[5]==1){
						$PRN=1;
					}else{$PRN=0;
					}
			$behavior=$_SESSION['scale_name'];
			$datacheck=array($date,$hour,$minute,$time,$trigger,$duration,$intensity_before,$intervention[0],
			$intervention[1],$intervention[2],$intervention[3],$intervention[4],$intervention[5],$intensityA[0],$intensityA[1],$intensityA[2],$intensityA[3],$intensityA[4],$intensityA[5],$PRN);
					//print_r($datacheck);
					//print $intensityA[2];

			${'intervention_score_'.$intervention[0]}=($intensity_before-$intensityA[0]);
			${'intervention_score_'.$intervention[1]}=$intensityA[0]-$intensityA[1];
			${'intervention_score_'.$intervention[2]}=$intensityA[1]-$intensityA[2];
			${'intervention_score_'.$intervention[3]}=$intensityA[2]-$intensityA[3];
			${'intervention_score_'.$intervention[4]}=$intensityA[3]-$intensityA[4];
			$intervention_score_6=$intensityA[5]-$intensityA[6];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database'],$conn);	
		mysqli_query("INSERT INTO behavior_map_data VALUES(null,'$mapkey','$_SESSION[residentkey]','$behavior','$date','$time','$intervention_score_1','$intervention_score_2','$intervention_score_3','$intervention_score_4','$intervention_score_5','$intervention_score_6','$duration','$PRN','$behavior_description','$intensity','$post_PRN_observation')");
		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
	print "<h2> Scale Data for $first $last has been Logged</h2>\n";
	$intervention_score_array=array($intervention_score_1,$intervention_score_2,$intervention_score_3,$intervention_score_4,$intervention_score_5,$intervention_score_6);
	//print $intervention[5];
	//print $intervention_score_1;
	//print_r($intervention_score_array);
	//$array_multisort($intervention_score_array,$intervention);
}					
header("Location:$nextfile");
?>
<?build_footer()?>
</body>
</html>