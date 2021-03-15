<?
include("ABAIT_function_file.php");session_start();
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
		href = "ABAIT_admin.css">
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
			print"<h3> $behavior Scale Data for $first $last</h3>\n";
			print"<fieldset>";
			print"<form action = 'ABAIT_create_map_log.php' method = 'post'>";
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
?>
<div id='submit'>
			<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Behavior Scale'/>
</div>
</form>		
</fieldset>

	<?build_footer()?>
	</div>
	</body>
</html>