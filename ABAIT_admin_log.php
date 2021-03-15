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
		href = "ABAIT_admin.css">
<script language="JavaScript">
//<![CDATA[
function setVisibility(id, visibility) {
document.getElementById(id).style.display = visibility;
}
//]]>
</script>
</head>
<body>
<div id="body">
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
<form 	action = "ABAIT_admin_log.php"
method = "post">
<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
//$resident=mysqli_real_escape_string($resident,$conn);
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
		$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);
		$Target_Population=mysqli_real_escape_string($Target_Population,$conn);
		$privilegekey=$_SESSION['personaldatakey'];
		
		mysqli_select_db($_SESSION['database'],$conn);
		mysqli_query("INSERT INTO residentpersonaldata VALUES(null,'$first','$last','$birthdate','$gender','$privilegekey','$Target_Population')");
		print "<h2>$first $last has been entered as a new resident.</h2>\n";
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
					$street_address=$_REQUEST['street_address'];
					$city=$_REQUEST['city'];
					$state=$_REQUEST['state'];
					$zipcode=$_REQUEST['zipcode'];
					$phone=$_REQUEST['phone'];
					$email=$_REQUEST['email'];
					$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);
					$Target_Population=mysqli_real_escape_string($Target_Population,$conn);
					$date=date("Y,m,d");
					$privilegekey=$_SESSION['personaldatakey'];
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
					mysqli_select_db($_SESSION['database'],$conn);
					mysqli_query("INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last','$gender','$birthdate','$street_address','$city','$state','$zipcode','$phone','$email','$privilegekey','$Target_Population')");
					mysqli_close($conn);
				}			
				else{$nextfile="passwordtaken.php";
				}
		}				
		if ($newpassword1!=$newpassword2){
		$nextfile='newcaregiver.php';
		}
		print "<h2>$first $last has been entered as a new Healthcare Provider.</h2>\n";	
	}elseif($filename=="Submit New Administrator Personal Data"){
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
					$accesslevel="admin";
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
					$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);
					$Target_Population=mysqli_real_escape_string($Target_Population,$conn);
					$privilegekey=$_SESSION['personaldatakey'];
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
					mysqli_select_db($_SESSION['database'],$conn);
					mysqli_query("INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last','$gender','$birthdate','$address','$city','$state','$zipcode','$phone','$email','$privilegekey','$Target_Population')");
					mysqli_close($conn);
				}			
				else{$nextfile="passwordtaken.php";
				}
		}				
		if ($newpassword1!=$newpassword2){
		$nextfile='newadministrator.php';
		}
		print "<h2>$first $last has been entered as a new Administrator.</h2>\n";	
		// end new admin eleseif
}elseif($filename=="Submit Resident Mapping Data"){
					$date=$_REQUEST['date'];
						if($date==1){
							$date=date("Y,m,d");
						}else{
							$date=$_REQUEST['otherdate'];
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
					$behavior=$_REQUEST['behavior'];
					$intensity=$_REQUEST['intensity'];
					$PRN=$_REQUEST['PRN'];
					$residentkey=$_SESSION['residentkey'];
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
					mysqli_select_db($_SESSION['database'],$conn);
					mysqli_query("INSERT INTO resident_mapping VALUES(null,'$residentkey','$date','$time','$duration','$trigger','$intervention','$behavior','$intensity','$PRN')");
					mysqli_close($conn);
					$nextfile='caregiverhome.php';
}elseif($filename=="Submit Resident for ABAIT Scale Creation"){//following code maps a resident
		$residentkey=$_REQUEST['residentkey'];
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$conn1=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$session1=mysqli_query($sql1,$conn1);
		$row1=mysqli_fetch_assoc($session1);
		$_SESSION['first']=$row1['first'];
		$_SESSION['last']=$row1['last'];
		$_SESSION['residentkey']=$row1['residentkey'];
		$behavior=$_REQUEST['behavior'];
		$behavior=str_replace('_',' ',$behavior);
		$_SESSION['behavior']=$behavior;
		$sql="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' and behavior='$behavior'";
		$sql1="SELECT * FROM scale_table WHERE scale_name='$behavior'";
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$session=mysqli_query($sql,$conn);
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);

		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
				print"<h2> $behavior Scale Data for $first $last</h2>\n";
			print"<fieldset>";
				print"<h4> Table Displays All Recorded $behavior Related Episodes to Date</h4>\n";
				print "<table>";//table for more info copy this line
					print "<tr><td>";//table in table data for more info
						print "<table border='1' bgcolor='white'>";
						print "<tr>\n";
							print"<th>Event Date</th>\n";
							print"<th>Event Time</th>\n";
							print"<th>Event Duration (min)</th>\n";
							print"<th>Trigger</th>\n";
							print"<th>Behavior</th>\n";
							print"<th>Intensity (5=highest)</th>\n";
							print"<th>Intervention</th>\n";
							print"<th>PRN required</th>\n";
					
						print"</tr>\n";
						$r=0;
						while($row=mysqli_fetch_assoc($session)){
							$comment="comment_".$row[intensity];
							$r=$r+1;
							print "<tr>\n";;
							print "<td> $row[date]</td>\n";
							print "<td> $row[time]</td>\n";
							print "<td> $row[duration]</td>\n";
							print "<td> $row[trigger]</td>\n";
							print "<td> $row[behavior]</td>\n";
							print "<td> $row1[$comment] ($row[intensity])</td>\n";
							print "<td> $row[intervention]</td>\n";
							print "<td> $row[PRN]</td>\n";
							print "</tr>\n";
						}
						print "</table>";
				print "</td>";//end td for table COPY FROM HERE
//table data for more info
				print "<td>";
				?>
<input type='submit' value='Tap for more Info' onClick="alert('Use the data presented in this table to create a Trigger/Intervention Scale for your resident.  Up to six interventions may be entered per trigger.  Trigger and intervention descriptions should be as brief though descriptive as possible (One to three word descriptors).');return false">
<?	
				print "</td></tr>";
		
			print "</table>";
//end table notation for more data  TO HERE
			if($r==0){
				print"<h3>No $behavior Mapping Data for $first $last has been logged</h3>\n";
			}else{
			$_SESSION['r']=$r;
		print"<h2> Enter $behavior Data Below to Create $first $last's Behavior Scale</h2>\n";
		print"<h4> Use single key words or short phrases as descriptors.</h4>\n";
			print"<div id = 'trigger'>\n";
			print "<table border=1>";
				print "<tr>\n";
				if($r>=6){
					$m=6;
				}else{
					$m=$r;
					}
				for($t=0;$t<$m;$t++){
					$count=$t+1;
					print "<td width='50'>Trigger $count\n";

						$trigger='trigger'.$t;
						$intervention_1='intervention_1'.$t;
						$intervention_2='intervention_2'.$t;
						$intervention_3='intervention_3'.$t;
						$intervention_4='intervention_4'.$t;
						$intervention_5='intervention_5'.$t;
						$intervention_6='intervention_6'.$t;
							print"<input type = 'text'
								name ='$trigger' size='20'/>\n";
							print "Intervention 1-$count<input type = 'text'
								name ='$intervention_1' size='20'/>\n";
							print "Intervention 2-$count<input type = 'text'
								name ='$intervention_2' size='20'/>\n";
							print "Intervention 3-$count<input type = 'text'
								name ='$intervention_3' size='20'/>\n";
							print "Intervention 4-$count<input type = 'text'
								name ='$intervention_4' size='20'/>\n";
							print "Intervention 5-$count<input type = 'text'
								name ='$intervention_5' size='20'/>\n";
							print "Intervention 6-$count<input type = 'text'
								name ='$intervention_6' size='20'/></td>\n";
					}
					print "</tr>\n";
				print"</div>\n";
			print "</table>\n";
			}//end else for map log
			print "<table>\n";
			print"<tr><td>Complete Another Behavior Map for $first $last?</td></tr>\n";	
				print "<tr>\n";
					print"<td><input type = 'radio'
						name = 'anothermap'
						value = 'yes'/>yes</td>\n";
					print"<td><input type = 'radio'
						name = 'anothermap'
						value = 'no'/>no</td>\n";
				print "</tr>\n";
			print "</table>\n";
			print "<div id='submit'>";
			print"	<input 	type = 'submit'
						name = 'submit'
						value = 'Submit'>\n";
			print "</div>";
			print "</fieldset>";
		
}//end behavior map elseif

elseif($filename=="Submit Resident for Scale Review"){//following code reviews which residents have which scales
	 $counter=(count($_SESSION['scale_array'])*$_SESSION['residentcount']);
	for($i=0;$i<$counter;$i++){
		$makemap=$_REQUEST['makemap'];
		if($makemap){$residents_to_map=$makemap;
		}//end if
	}
	$delim='_';
	$resident_behavior=explode($delim,$residents_to_map);
	$residentkey=$resident_behavior[0];
	$behavior=$resident_behavior[1];
	$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$conn1=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$session1=mysqli_query($sql1,$conn1);
		$row1=mysqli_fetch_assoc($session1);
		$_SESSION['first']=$row1['first'];
		$_SESSION['last']=$row1['last'];
		$_SESSION['residentkey']=$row1['residentkey'];
		$_SESSION['behavior']=$behavior;
		$sql="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' and behavior LIKE'$behavior%'";
		$sql1="SELECT * FROM scale_table WHERE scale_name='$behavior'";
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$session=mysqli_query($sql,$conn);
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);

		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
				print"<h2> $behavior Scale Data for $first $last</h2>\n";
			print"<fieldset>";
				print"<h4> Table Displays All Recorded $behavior Related Episodes to Date</h4>\n";
				print "<table>";//table for more info copy this line
					print "<tr><td>";//table in table data for more info
						print "<table border='1' bgcolor='white'>";
						print "<tr>\n";
							print"<th>Event Date</th>\n";
							print"<th>Event Time</th>\n";
							print"<th>Event Duration (min)</th>\n";
							print"<th>Trigger</th>\n";
							print"<th>Behavior</th>\n";
							print"<th>Intensity (5=highest)</th>\n";
							print"<th>Intervention</th>\n";
							print"<th>PRN required</th>\n";
					
						print"</tr>\n";
						$r=0;
						while($row=mysqli_fetch_assoc($session)){
							$comment="comment_".$row[intensity];
							$r=$r+1;
							print "<tr>\n";;
							print "<td> $row[date]</td>\n";
							print "<td> $row[time]</td>\n";
							print "<td> $row[duration]</td>\n";
							print "<td> $row[trigger]</td>\n";
							print "<td> $row[behavior]</td>\n";
							print "<td> $row1[$comment] ($row[intensity])</td>\n";
							print "<td> $row[intervention]</td>\n";
							print "<td> $row[PRN]</td>\n";
							print "</tr>\n";
						}
						print "</table>";
				print "</td>";//end td for table COPY FROM HERE
//table data for more info
				print "<th>";
				?>
<input type='submit' value='Tap for more Info' onClick="alert('Use the data presented in this table to create a Trigger/Intervention Map for your resident.  Up to six interventions may be entered per trigger.  Trigger and intervention descriptions should be as brief though descriptive as possible (One to three word descriptors).');return false">
<?	
				print "</th></tr>";
		
			print "</table>";
//end table notation for more data  TO HERE
			if($r==0){
				print "<table><tr><th>"; 
				print"<h3>No $behavior Mapping Data for $first $last has been logged</h3>\n";
				print "</th><th>";
					?>
<input type='submit' value='Tap for more Info' onClick="alert('Since this type of behavior has not been logged, a behavior map can not be created. Click the Complete another Behavior Map button or return to Behavior Scales to continue.');return false">
					<?
				print "</th></tr></table>";
			}else{
			$_SESSION['r']=$r;
		print"<h2> Enter $behavior Scale Data for $first $last's Map Below</h2>\n";
		print"<h4> Use single key words or short phrases as descriptors.</h4>\n";
			print"<div id = 'trigger'>\n";
			print "<table border=1>";
				print "<tr>\n";
				if($r>=6){
					$m=6;
				}else{
					$m=$r;
					}
				for($t=0;$t<$m;$t++){
					$count=$t+1;
					print "<td width='100'>Trigger $count\n";

						$trigger='trigger'.$t;
						$intervention_1='intervention_1'.$t;
						$intervention_2='intervention_2'.$t;
						$intervention_3='intervention_3'.$t;
						$intervention_4='intervention_4'.$t;
						$intervention_5='intervention_5'.$t;
						$intervention_6='intervention_6'.$t;
							print"<input type = 'text'
								name ='$trigger' size='25'/>\n";
							print "Intervention 1-$count<input type = 'text'
								name ='$intervention_1' size='25'/>\n";
							print "Intervention 2-$count<input type = 'text'
								name ='$intervention_2' size='25'/>\n";
							print "Intervention 3-$count<input type = 'text'
								name ='$intervention_3' size='25'/>\n";
							print "Intervention 4-$count<input type = 'text'
								name ='$intervention_4' size='25'/>\n";
							print "Intervention 5-$count<input type = 'text'
								name ='$intervention_5' size='25'/>\n";
							print "Intervention 6-$count<input type = 'text'
								name ='$intervention_6' size='25'/></td>\n";
					}
					print "</tr>\n";
				print"</div>\n";
			print "</table>\n";
			}//end else for map log
			print "<table>\n";
			print"<tr><td>Complete Another Behavior Scale for $first $last?</td></tr>\n";	
				print"<tr>\n";
					print"<td><input type = 'radio'
						name = 'anothermap'
						value = 'yes'/>yes</td>\n";
					print"<td><input type = 'radio'
						name = 'anothermap'
						value = 'no'/>no</td>\n";
				print "</tr>\n";
			print "</table>\n";
			print "<div id='submit'>";
			print "	<input 	type = 'submit'
						name = 'submit'
						value = 'Submit'>\n";
			print "</div>";
			print "</fieldset>\n";
}//end submit resident for map review elesif

elseif($filename=="Submit Resident for 30 Day Analysis"){//following code provides global analysis
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));	
		$residentkey=$_REQUEST['residentkey'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);
		$sql4="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior!=''";
		$session4=mysqli_query($sql4,$conn);
		$sum_duration=0;
		$sum_PRN=0;
		$sum_episodes=0;
		$behaviorarray=array('Motor','Resistance','Vocalizations','Agressiveness');
		$sum_behaviorarray=array('Motor'=>0,'Resistance'=>0,'Vocalizations'=>0,'Agressiveness'=>0);
			while($row4=mysqli_fetch_assoc($session4)){
				$sum_duration=$row4['duration']+$sum_duration;
					foreach($behaviorarray as $behavior){
						if($row4['behavior']==$behavior){
							$sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+$row4['duration'];
						}
					}//end behaviorarray foreach
				$sum_PRN=$row4['PRN']+$sum_PRN;
				$sum_episodes=$sum_episodes+1;
			}//end while
		$res_first=$row1['first'];
		$res_last=$row1['last'];
		print"<fieldset>";
		print"<h2> 30 Day Analysis for $res_first $res_last</h2>\n";
			print "<table>";//table for more info copy this line
				print "<tr><td>";//table in table data for more info
					print "<table border='1'>";
						print"<tr>\n";
							print"<th>Start Date</th>\n";
							print"<th>End Date</th>\n";
							print"<th>Total Episodes</th>\n";
							print"<th>Total Duration of Episodes</th>\n";
							print"<th>Total PRN</th>\n";					
						print"</tr>\n";
						print"<tr>\n";
							print"<td>$date_start</td>\n";
							print"<td>$date</td>\n";
							print"<td>$sum_episodes</td>\n";
							print"<td>$sum_duration</td>\n";
							print"<td>$sum_PRN</td>\n";					
						print"</tr>\n";
					print "</table>";
				print "</td>";
//table data for more info
# ------- The graph values in the form of associative array
	$values=$sum_behaviorarray;

 
	$img_width=450;
	$img_height=300; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	$img=imagecreate($img_width,$img_height);

 
	$bar_width=20;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$bar_color=imagecolorallocate($img,0,64,128);
	$background_color=imagecolorallocate($img,240,240,255);
	$border_color=imagecolorallocate($img,200,200,200);
	$line_color=imagecolorallocate($img,220,220,220);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

 
	# ------- Max value is required to adjust the scale	-------
	$max_value=max($values);
		if ($max_value==0){
		$max_value=.1;
	}
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=20;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,2,$x1+3,$y1-10,$value,$bar_color);
		imagestring($img,2,$x1+3,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	imagepng($img,'behaviorgraph.png');
	
				?>
<th><input type='submit' value='Tap for more Info' onClick="alert('This is the thirty day global analysis of your resident selected.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false">
</th><th> 
<INPUT type="button" value="Tap for Graph" onClick="window.open('behaviorgraph.png','mywindow','width=600,height=400')"> 
</th><th>
<FORM>
<INPUT TYPE="button" value="Print Page" onClick="window.print()">
</FORM>
</th>
<?	
		print "</tr>";
		print "</table>";
//end table notation for more data
//$trigger_duration=array();
$behavarray=array('Motor','Resistance','Vocalizations','Agressiveness');
		print"<h2>Trigger and Intervetion Analysis</h2>\n";
	foreach($behavarray as $behavior){
		$trigger_duration=NULL;
		$sql2="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$behavior'";
		$session2=mysqli_query($sql2,$conn);
		$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='$behavior'";
		$session3=mysqli_query($sql3,$conn);
			$r=0;
			print "<table>";
			print "<tr><td>";
			print "<table border='1'>";
			print"<tr><th colspan='4'>$behavior Behavior Episodes</th></tr>\n";
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
							
								$trigger_duration[$row2[trigger]]=$duration;
								//print_r ($trigger_duration);
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
						//print_r ($trigger_duration);
				print"</tr>\n";
			print "</table>";
			print "</td>";
			print "<td>";
	$values=$trigger_duration;

 
	$img_width=450;
	$img_height=300; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	$img=imagecreate($img_width,$img_height);

 
	$bar_width=20;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$bar_color=imagecolorallocate($img,0,64,128);
	$background_color=imagecolorallocate($img,240,240,255);
	$border_color=imagecolorallocate($img,200,200,200);
	$line_color=imagecolorallocate($img,220,220,220);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

 
	# ------- Max value is required to adjust the scale	-------
	if($values){
	$max_value=max($values);
	}
	if ($max_value==0){
		$max_value=.1;
	}
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=20;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,0,$x1+3,$y1-10,$value,$bar_color);
		imagestring($img,0,$x1+3,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	imagepng($img, $behavior.'.png');
?>
<FORM> 
<INPUT type="button" value="Tap for Graph" onClick="window.open($behavior.'.png','mywindow','width=800,height=400')"> 
</FORM>
<?				
			print "</td></tr>";
			print "</table>";
		}//end foreach
print "</fieldset>";
}//end choose resident for 30 day analysis


elseif($filename=="Submit Resident for Global Analysis"){//following code provides global analysis
		$date=date('Y-m-d');
		//print $date;
		$reviewtime=$_REQUEST['reviewtime'];
		if($reviewtime==3){
		$date_start=date('Y-m-d',(strtotime('- 90 days')));
		}
		if($reviewtime==6){
		$date_start=date('Y-m-d',(strtotime('- 180 days')));
		}
		if($reviewtime==10){
		$date_start=date('Y-m-d',(strtotime('- 10000 days')));
		}
		if($reviewtime!=3 && $reviewtime!=6 && $reviewtime!=10){
			$reviewtime=$_REQUEST[customtime];
		}
		if(empty($reviewtime)){
		$date_start=date('Y-m-d',(strtotime('- 30 days')));	
		}	
		$residentkey=$_REQUEST['residentkey'];
		//$behavior=$_REQUEST['behavior'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);
		$sql4="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior!=''";
		$session4=mysqli_query($sql4,$conn);
		$sum_duration=0;
		$sum_PRN=0;
		$sum_episodes=0;
		$behaviorarray=array('Motor','Resistance','Vocalizations','Agressiveness');
		$sum_behaviorarray=array('Motor'=>0,'Resistance'=>0,'Vocalizations'=>0,'Agressiveness'=>0);
			while($row4=mysqli_fetch_assoc($session4)){
				$sum_duration=$row4['duration']+$sum_duration;
					foreach($behaviorarray as $behavior){
						if($row4['behavior']==$behavior){
							$sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+$row4['duration'];
						}
					}//end behaviorarray foreach
				$sum_PRN=$row4['PRN']+$sum_PRN;
				$sum_episodes=$sum_episodes+1;
			}//end while
		
		//print_r ($behaviorarray);
		//print_r ($sum_behaviorarray);
		$res_first=$row1['first'];
		$res_last=$row1['last'];
		//$_SESSION['behavior']=$behavior;
		$res_first=$_SESSION['first'];
		$res_last=$_SESSION['last'];
		print"<fieldset>";
		print"<h2> Global Analysis for $res_first $res_last</h2>\n";
			print "<table>";//table for more info copy this line
				print "<tr><td>";//table in table data for more info
					print "<table border='1'>";
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
				print "</td>";
//table data for more info
# ------- The graph values in the form of associative array
	$values=$sum_behaviorarray;

 
	$img_width=450;
	$img_height=300; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	$img=imagecreate($img_width,$img_height);

 
	$bar_width=20;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$bar_color=imagecolorallocate($img,0,64,128);
	$background_color=imagecolorallocate($img,240,240,255);
	$border_color=imagecolorallocate($img,200,200,200);
	$line_color=imagecolorallocate($img,220,220,220);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

 
	# ------- Max value is required to adjust the scale	-------
	$max_value=max($values);
		if ($max_value==0){
		$max_value=.1;
	}
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=20;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,2,$x1+3,$y1-10,$value,$bar_color);
		imagestring($img,2,$x1+3,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	imagepng($img,'behaviorgraph.png');
	
				?>
<th><input type='submit' value='Tap for more Info' onClick="alert('This is the thirty day global analysis of your resident selected.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false">
</th><th> 
<INPUT type="button" value="Tap for Graph" onClick="window.open('behaviorgraph.png','mywindow','width=600,height=400')"> 
</th><th>
<FORM>
<INPUT TYPE="button" value="Print Page" onClick="window.print()">
</FORM>
</th>
<?	
				print "</th></tr>";
		
			print "</table>";
//end table notation for more data
//$trigger_duration=array();
$behavarray=array('Motor','Resistance','Vocalizations','Agressiveness');
		print"<h2>Trigger and Intervetion Analysis</h2>\n";
	foreach($behavarray as $behavior){
		$trigger_duration=NULL;
		$sql2="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$behavior'";
		$session2=mysqli_query($sql2,$conn);
		$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='$behavior'";
		$session3=mysqli_query($sql3,$conn);
			$r=0;
			print "<table>";
			print "<tr><td>";
			print "<table border='1'>";
			print"<tr><th colspan='4'>$behavior Behavior Episodes</th></tr>\n";
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
							
								$trigger_duration[$row2[trigger]]=$duration;
								//print_r ($trigger_duration);
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
						//print_r ($trigger_duration);
				print"</tr>\n";
			print "</table>";
			print "</td>";
			print "<td>";
	$values=$trigger_duration;

 
	$img_width=450;
	$img_height=300; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	$img=imagecreate($img_width,$img_height);

 
	$bar_width=20;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$bar_color=imagecolorallocate($img,0,64,128);
	$background_color=imagecolorallocate($img,240,240,255);
	$border_color=imagecolorallocate($img,200,200,200);
	$line_color=imagecolorallocate($img,220,220,220);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

 
	# ------- Max value is required to adjust the scale	-------
	if($values){
	$max_value=max($values);
	}
	if ($max_value==0){
		$max_value=.1;
	}
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=20;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,0,$x1+3,$y1-10,$value,$bar_color);
		imagestring($img,0,$x1+3,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	imagepng($img, $behavior.'.png');
?>
<FORM> 
<INPUT type="button" value="Tap for Graph" onClick="window.open($behavior.'.png','mywindow','width=800,height=400')"> 
</FORM>
<?				
			print "</td></tr>";
			print "</table>";
		}//end foreach
print "</fieldset>";
}//end choose resident for global analysis

elseif($filename=="Submit"){//following code collects and logs data for another map
		$anothermap=$_REQUEST['anothermap'];
		$r=$_SESSION['r'];
		if($r>0){
		$residentkey=$_SESSION['residentkey'];
		$creation_date=date('Y,m,d');
		$behavior=$_SESSION['behavior'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database'],$conn);
		if($_SESSION[Target_Population]=='all'){
			$Population=$_SESSION[Population];
		}else{$Population=$_SESSION[Target_Population];
			}//end Target Population if else
			$Population=mysqli_real_escape_string($Population,$conn);
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
					mysqli_query("INSERT INTO behavior_maps VALUES(null,'$Population','$residentkey','$creation_date','$behavior','$trigger','$intervention_1','$intervention_2','$intervention_3','$intervention_4','$intervention_5','$intervention_6')");
				}
			}// end for

		}//end if check for existing maps
	if($anothermap=='yes'){
		$nextfile='chooseresident_for_map_review.php';
	}else{$nextfile='adminhome.php';
	}
	}//end submit behavior map elseif
elseif($filename=='Submit Resident Scale Data'){
					$date=$_REQUEST['date'];
						if($date==1){
							$date=date("Y-m-d");
						}else{
							$date=$_REQUEST['otherdate'];
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
					$trigger=$_REQUEST['trigger'];
					$duration=$_REQUEST['duration'];
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
			$behavior=$_SESSION['scale'];
			$datacheck=array($date,$hour,$minute,$time,$trigger,$duration,$intensity_before,$intervention[0],
			$intervention[1],$intervention[2],$intervention[3],$intervention[4],$intervention[5],$intensityA[00],$intensityA[1],$intensityA[2],$intensityA[3],$intensityA[4],$intensityA[5],$PRN);
					//print_r($datacheck);
			${'intervention_score_'.$intervention[0]}=($intensity_before-$intensityA[0]);
			${'intervention_score_'.$intervention[1]}=$intensityA[0]-$intensityA[1];
			${'intervention_score_'.$intervention[2]}=$intensityA[1]-$intensityA[2];
			${'intervention_score_'.$intervention[3]}=$intensityA[2]-$intensityA[3];
			${'intervention_score_'.$intervention[4]}=$intensityA[3]-$intensityA[4];
			$intervention_score_6=$intensityA[5]-$intensityA[6];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database'],$conn);	
		mysqli_query("INSERT INTO behavior_map_data VALUES(null,'$mapkey','$residentkey','$behavior','$date','$intervention_score_1','$intervention_score_2','$intervention_score_3','$intervention_score_4','$intervention_score_5','$intervention_score_6','$duration','$PRN')");
		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
	print "<h2> Scale Data for $first $last has been Logged</h2>\n";
	$intervention_score_array=array($intervention_score_1,$intervention_score_2,$intervention_score_3,$intervention_score_4,$intervention_score_5,$intervention_score_6);
	//$array_multisort($intervention_score_array,$intervention);
}				
elseif($filename=='Submit Resident Choice'){	
	$behavior=$_SESSION['scale'];
	$residentkey=$_REQUEST['resident_choice'];
	$_SESSION['residentkey']=$residentkey;
	$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	$resident=mysqli_real_escape_string($resident,$conn);
	mysqli_select_db($_SESSION['database']);
	$resident=mysqli_query($sql,$conn);
	$row=mysqli_fetch_assoc($resident);
	$_SESSION['first']=$row['first'];
	$_SESSION['last']=$row['last'];	
	$nextfile='choose_trigger.php';
}	
header("Location:$nextfile");
ob_end_flush()
?>
</form>
<?build_footer()?>
</div>
</body>
</html>