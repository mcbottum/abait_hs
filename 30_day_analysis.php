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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>ABAIT 30 Day Analysis</title>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>
	table.local thead th{
		width:177px;
	}
	table.local tbody td{
		width:177px;
	}
</style>
<body>
<fieldset>
<div id="body" style="width:978px; text-align: left">
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
<form 	action = "adminhome.php"
		method = "post">

<?
$filename =$_REQUEST["submit"];
if($filename=="Submit Resident for 30 Day Analysis"){//following code provides global analysis
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));	
		$title='30 Day Analysis';
}
elseif($filename=="Submit Resident for Global Analysis"){
		$all_residents=$_REQUEST[all_residents];
		$review_time=$_REQUEST[review_time];
		$custom_time=$_REQUEST[custom_time];
		$Caregiver_time=$_REQUEST[Caregiver_time];
		$behavior_units=$_REQUEST[behavior_units];
		$behavior_units_per_time=$_REQUEST[behavior_units_per_time];
		$title='Global Analysis';
}
		
		$residentkey=$_REQUEST['residentkey'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
		mysqli_select_db($_SESSION['database']);

		if($residentkey=='all_residents'){
			$sql1="SELECT * FROM residentpersonaldata";
			$sql4="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior!=''";
		}else{
			$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
			$sql4="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior!=''";
		}
		$session1=mysqli_query($sql1,$conn);
		$row1=mysqli_fetch_assoc($session1);
		$Population_row1=mysqli_real_escape_string($row1['Target_Population'],$conn);
		
		$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_row1'";
		$session4=mysqli_query($sql4,$conn);
		$session5=mysqli_query($sql5,$conn);
		$sum_duration=0;
		$sum_PRN=0;
		$sum_episodes=0;
		if(!isset($scale_array)){
			$scale_holder='';
				while($row5=mysqli_fetch_assoc($session5)){
					if($row5['scale_name']!=$scale_holder){
							$scale_array[]=$row5['scale_name'];
					}
					$scale_holder=$row5['scale_name'];
				}
		}else{
		$scale_array=$_SESSION['scale_array'];
		}//end scale_array if else
		foreach($scale_array as $value){
			$sum_behaviorarray[$value]=0;
		}//end foreach
			while($row4=mysqli_fetch_assoc($session4)){
				$sum_duration=$row4['duration']+$sum_duration;
					foreach($scale_array as $behavior){
						if($row4['behavior']==$behavior){
							$sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+$row4['duration'];
						}
					}//end behaviorarray foreach
				$sum_PRN=$row4['PRN']+$sum_PRN;
				$sum_episodes=$sum_episodes+1;
			}//end while
		if($residentkey=='all_residents'){
			$res_first='All';
			$res_last='Residents';
		}
		else{
			$res_first=$row1['first'];
			$res_last=$row1['last'];
		}
		print"<div align='right'><FORM><INPUT TYPE=\"button\" value=\"Print Page\" onClick=\"window.print()\"></FORM></div>\n";
		print"<div id='head'><h4> $title for $res_first $res_last</h4></div>\n";
			print "<table width='100%'>";//table for more info copy this line
				print "<tr><td>";//table in table data for more info
					print "<table width='100%' class='table local' border='1' bgcolor='white'>";
						print "<thead>";
							print"<tr>\n";
								print"<th align='center'>Start Date</th>\n";
								print"<th align='center'>End Date</th>\n";
								print"<th align='center'>Total Episodes</th>\n";
								print"<th align='center'>Total Duration of Episodes</th>\n";
								print"<th align='center'>Total PRN</th>\n";		
								print"<th align='center'>Graph</th>\n";				
							print"</tr>\n";
						print "</thead>";
						print "<tbody>";
							print"<tr align='center'>\n";
								print"<td>$date_start</td>\n";
								print"<td>$date</td>\n";
								print"<td>$sum_episodes</td>\n";
								print"<td>$sum_duration</td>\n";
								print"<td>$sum_PRN</td>\n";
								print"<td><INPUT class='icon' type=\"image\" src=\"Images/chart_icon.png\" value=\"Show Graph\" onClick=\"window.open('behaviorgraphbar.png','mywindow','width=700,height=400')\"> </td>\n";					
							print"</tr>\n";
						print "</tbody>";
					print "</table>";
				print "</td>";
//table data for more info
//call graph function
	$values_bar=$sum_behaviorarray;
	$graphTitle_bar='Duration of Behavior Episodes vs. Behavior';
	$yLabel_bar='Total Duration (minutes)';
	$xLabel_bar='Behaviors';

	
ABAIT_bar_graph($values_bar, $graphTitle_bar, $yLabel_bar,$xLabel_bar,'bar');


print "<th><input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of resident $res_first $res_last.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false\">";
print "</th>";


		print "</tr>";
		print "</table>";
//end table notation for more data
	print"<h2 align='center'>Trigger and Intervetion Analysis</h2>\n";
	$r=0;
	foreach($scale_array as $behavior){

		$trigger_count=0;
		$trigger_duration=NULL;
		if($residentkey=='all_residents'){
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior'";
		}else{
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey='$residentkey'";
		}
		$session2=mysqli_query($sql2,$conn);
			
		print "<table width='100%'>";
			print "<tr><td>";
				print "<table width='100%' class='table local' border='1' bgcolor='white'>";
					print "<thead>";
						print"<tr><th colspan='5' align='center'>$behavior Behavior Episodes</th></tr>\n";
							print"<tr>\n";
								print"<th align='center'>----Trigger----</th>\n";
								print"<th align='center'>Number of Episodes</th>\n";
								print"<th align='center'>Duration of Episodes</th>\n";
								print"<th align='center'>Most Effective Intervention</th>\n";
								print"<th align='center'>Graph</th>\n";
							print"</tr>\n";
					print "</thead>";
					print "<tbody>";
					while($row2=mysqli_fetch_assoc($session2)){
						
						$intervention_array=null;
						$trigger_array[]=$row2['trig'];
						$episodes=0;
						$duration=0;
						$intv=0;
						$intv1=0;
						$intv2=0;
						$intv3=0;
						$intv4=0;
						$intv5=0;
						$intv6=0;
						print"<tr align='center'>\n";
							print "<td> $row2[trig] </td>\n";
							if($residentkey=='all_residents'){
								$sql3="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='$behavior'";
							}else{
								$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='$behavior'";
							}
							$session3=mysqli_query($sql3,$conn);
							$best=Null;
							while($row3=mysqli_fetch_assoc($session3)){
								if($row2['mapkey']==$row3['mapkey']){
									$episodes=$episodes+1;
									$duration=$duration+$row3['duration'];
									$intv1=$intv1+$row3['intervention_score_1'];
									$intv2=$intv2+$row3['intervention_score_2'];
									$intv3=$intv3+$row3['intervention_score_3'];
									$intv4=$intv4+$row3['intervention_score_4'];
									$intv5=$intv5+$row3['intervention_score_5'];
									$intv6=$intv6+$row3['intervention_score_6'];
								}
							}//end invtervention while
						
							$trigger_duration[$row2['trig']]=$duration;
							$intv=0;
							for ($s=1;$s<7;$s++){
								if(${'intv'.$s}<0){
									${'intv'.$s}=0;
								}
								if($intv<${'intv'.$s}){
									$intv=${'intv'.$s};
									$best=$s;
								}
								if($row2['intervention_'.$s]){
									$intervention_array[$row2['intervention_'.$s]]=${'intv'.$s};
								}
							}
							$values[]=$intervention_array;
							print"<td>$episodes</td>\n";
							print"<td>$duration</td>\n";
							$best_int='intervention_'.$best;
							if(isset($best)){
								print"<td>$row2[$best_int]</td>\n";
								print"<td align=center><INPUT class='icon' type=\"image\" src=\"Images/pie_icon.png\"  onClick=\"window.open('behaviorgraph'+$r+'.png','mywindow','width=700,height=500,position=fixed')\"></td>";
							}else{
								print"<td></td>\n";
								print"<td align=\"center\">No Interventions Logged</td>";
							}
							$graphTitle='Relative Effectiveness of '.$trigger_array[$r].' Interventions';
							$yLabel='Relative Effectiveness';
							ABAIT_pie_graph($values[$r], $graphTitle, $yLabel,$r);	

							print"</td>\n";
						print "</tr>";

						$trigger_count=$trigger_count+1;
						$r=$r+1;
					}//end row2 while for each trigger

					print "</tbody>";
				print "</table>";

			print "<td align='center'><input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of resident; $res_first $res_last.  The table provides information specifically related to the behaviors classifed as; $behavior.  Effectiveness is determined by the greatest reduction in behavor intensity during this period.  The graph will display all interventions applied, where slize size represents relative effectiveness.');return false\">";
			print "</td></tr>";
		print "</table>";
	}//end foreach

?>
		<div id = "submit">	
			<input 	type = "submit"
					name = "submit"
					value = "Back to Admin Home Page">
		</div>
	</fieldset>
	</form>
<?build_footer()?>
</body>
</html>
