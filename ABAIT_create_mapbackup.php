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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>Create Scale</title>
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
		$creation_date=null;
		$residentkey=$_REQUEST['residentkey'];
		$makemap=$_REQUEST['makemap'];
		$behavior=$_REQUEST['behavior'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);
		if($makemap){$residents_to_map=$makemap;
			$delim='_';
			$resident_behavior=explode($delim,$residents_to_map);
			$residentkey=$resident_behavior[0];
			$behavior=$resident_behavior[1];
			//print $behavior;
			//die;
		}//end if
		$sql_date="SELECT creation_date FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$behavior'";
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);
		$_SESSION['first']=$row1['first'];
		$_SESSION['last']=$row1['last'];
		$_SESSION['residentkey']=$row1['residentkey'];
		$behavior=str_replace('_',' ',$behavior);
		$_SESSION['behavior']=$behavior;
		$sql="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' and behavior='$behavior' ORDER by date";
		$sql1="SELECT * FROM scale_table WHERE scale_name='$behavior'";
		$session_date=mysqli_query($sql_date,$conn);
		while($row_date=mysqli_fetch_assoc($session_date)){
			if(strtotime($creation_date)<strtotime($row_date[creation_date])){
				$creation_date=$row_date[creation_date];
			}
		}
		$session=mysqli_query($sql,$conn);
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);

		$first=$_SESSION['first'];
		$last=$_SESSION['last'];
				print"<h2> $behavior Scale Data for $first $last</h2>\n";
		print"<fieldset>";
			print"<form action = 'ABAIT_create_map_log.php' method = 'post'>";

		print "<table>";//table for more info copy this line
				print "<tr><td><h3> Table Displays All Recorded $behavior Related Episodes to Date</h3></td>\n";
				print "<td align='right'>";
?>
<input type='submit' value='Tap for more Info' onClick="alert('Use the data presented in this table to create a Trigger/Intervention Scale for your resident.  Up to six interventions may be entered per trigger.  Trigger and intervention descriptions should be as brief though descriptive as possible (One to three word descriptors).');return false">
<?	
				print "</td></tr>";
				print "<tr><td  style='color:red'><h4>Red Data Indicates Unscaled Behavior Recorded <em>After</em> Most Recent Scale Creation ($creation_date)</h4></td></tr>\n";
					print "<tr><td colspan='2'>";//table in table data for more info
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
							if(strtotime($creation_date)<strtotime($row[date])&&$creation_date!=null){
								$r=$r+1;
								$col='red';
							}elseif($creation_date==null){
								$col='black';
								$r=$r+1;
							}
							//$rt=strtotime($row[date]);
								print "<tr style='color:$col'>\n";;
								print "<td> $row[date]</td>\n";
								print "<td> $row[time] </td>\n";
								print "<td> $row[duration]</td>\n";
								print "<td> $row[trigger]</td>\n";
								print "<td> $row[behavior]</td>\n";
								print "<td> $row1[$comment] ($row[intensity])</td>\n";
								print "<td> $row[intervention]</td>\n";
								if($row[PRN]=='1'){
								print "<td>Yes</td>\n";
								}else{
									print "<td>No</td>\n";
								}
								print "</tr>\n";
						}
						print "</table>";
				print "</td></tr>";//end td for table COPY FROM HERE
//table data for more info

		
			print "</table>";
//end table notation for more data  TO HERE
			if($r==0){
				print"<h4>No unmapped <em>$behavior</em> behavior for $first $last has been logged since the scale has been created.</h4>\n";
				print"<h4>Please select the Edit Triggers option under Scale Set-up on the Admin Home Page to edit existing scales.</h4>\n";
			}else{
				print"<input type='hidden' name='scale_behavior' value='$behavior'>";
				print"<input type='hidden' name='scale_resident' value='$first $last'>";
				$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$_SESSION[Target_Population]'";
				$sql3="SELECT * FROM behavior_map_data";
				$session_bm_mapkey=mysqli_query($sql2,$conn);
				$session_bmd_mapkey=mysqli_query($sql3,$conn);
		while($row_bm_mapkey=mysqli_fetch_assoc($session_bm_mapkey)){
			
			for($j=1;$j<7;$j++){
				if(!(in_array($row_bm_mapkey['intervention_'.$j],$intervention_array_name))){
					print $row_bm_mapkey['intervention_'.$j];
					$intervention='intervention_'.$j;
					$intervention_array_name[]=$row_bm_mapkey[$intervention];
				}
			}
			
			while($row_bmd_mapkey=mysqli_fetch_assoc($session_bmd_mapkey)){
				if($row_bm_mapkey[mapkey]==$row_bmd_mapkey[mapkey]){
					for($i=1;$i<7;$i++){
						if($row_bm_mapkey['intervention_'.$i]){
							$intervention_array=array($row_bm_mapkey['intervention_'.$i]=>array($row_bmd_mapkey['intervention_score_'.$i]));
							//$intervention_array[$row_bm_mapkey['intervention_'.$i]][]=$row_bmd_mapkey['intervention_score_'.$i];
						}
					}
				}
			}
		}
		print 'hi';
		print_r($intervention_array);
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
			print "</table>\n";
			print"</div>\n";
			}//end else for map log
			//print "<table>\n";
			//print"<tr><td colspan='2'>Complete Another Behavior Map for $first $last?</td></tr>\n";	
				//print "<tr>\n";
					//print"<td><input type = 'radio'
						//name = 'anothermap'
						//value = 'Yes'/>yes</td>\n";
					//print"<td><input type = 'radio'
						//name = 'anothermap'
						//value = 'No'/>no</td>\n";
				//print "</tr>\n";
			//print "</table>\n";
			print_r($intervention_array);
?>
<div id='submit'>
			<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Behavior Scale'/>
</div>
</form>		
</fieldset>

	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</div>
	</body>
</html>