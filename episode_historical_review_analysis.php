<?
session_start();
include("ABAIT_function_file.php");
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
<script>
function backButton(target_population) {
	self.location='episode_historical_review.php?tp='+target_population;
}
</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>
    fieldset{
        background-color: #fdebdf !important;
    }
    table.local thead th{
        width:145px;
        background-color: white;
        background-color: #F5F5F5;
        padding-left: 0px !important;
    }
/*    table.local tbody{
        max-height: 400px;
    }*/
    table.local tbody td{
        width:145px;
        background-color: white;
        padding-left: 0px !important;
        text-align:center;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
    p.backButton {
      float:right;
    }
    table.eoi thead th.first{
        width:180px;
    }

    table.eoi tbody td.first{
        width:180px;
    }
    table.eoi thead th{
        width:115px;
		text-align:center;
	}

    table.eoi tbody td{
        width:115px;
		text-align:center;
    }
</style>
</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<fieldset>
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

<!-- <form action="adminhome.php" method="post"> -->

<?
$filename=$_REQUEST['submit'];
$Population=$_REQUEST['Target_Population'];
$Population=str_replace('_',' ',$_SESSION['pop']);
//print $Population;
//print $_SESSION[Target_Population];
$residentkey=$_REQUEST['residentkey'];
$date=date('Y-m-d');
//print $residentkey;
if($filename=="Submit Resident for Global Analysis"){
		if(isset($_REQUEST['all_residents'])){
			$all_residents=$_REQUEST['all_residents'];
		}else{
			$all_residents=Null;
		}
		$review_time=$_REQUEST['review_time'];
		//$scale_array=$_REQUEST[scale_array];
		if(isset($_REQUEST['scale_totals'])){
			$scale_totals=$_REQUEST['scale_totals'];
		}else{
			$scale_totals=Null;
		}
		if(isset($_REQUEST['behavior_units'])){
			$behavior_units=$_REQUEST['behavior_units'];
		}else{
			$behavior_units=Null;
		}
		if(isset($_REQUEST['behavior_units_per_time'])){
			$behavior_units_per_time=$_REQUEST['behavior_units_per_time'];
		}else{
			$behavior_units_per_time=Null;
		}
		if(isset($_REQUEST['episode_time_of_day'])){
			$episode_time_of_day=$_REQUEST['episode_time_of_day'];
		}else{
			$episode_time_of_day=Null;
		}
		if(isset($_REQUEST['trigger_breakdown'])){
			$trigger_breakdown=$_REQUEST['trigger_breakdown'];
		}else{
			$trigger_breakdown=Null;
		}
		if(isset($_REQUEST['intervention_effect'])){
			$intervention_effect=$_REQUEST['intervention_effect'];
		}else{
			$intervention_effect=Null;
		}
		if(isset($_REQUEST['all_episode'])){
			$all_episode=$_REQUEST['all_episode'];
		}else{
			$all_episode=Null;
		}
		if(isset($_REQUEST['review_time'])){
			$reviewtime=$_REQUEST['review_time'];
		}else{
			$reviewtime=Null;
		}

		if(isset($_REQUEST['include_unmapped'])){
			$include_unmapped=$_REQUEST['include_unmapped'];
		}else{
			$include_unmapped=Null;
		}

		// Default date end is now
		$date_end=date("Y-m-d");

		if($reviewtime=="previous"){
			$date_start=date('Y-m-d', strtotime('first day of last month'));
			$date_end=date('Y-m-d', strtotime('last day of last month'));
		}

		if($reviewtime==1){
			$date_start=date('Y-m-d',(strtotime('- 30 days')));
		}
		if($reviewtime==3){
			$date_start=date('Y-m-d',(strtotime('- 90 days')));
		}
		if($reviewtime==6){
			$date_start=date('Y-m-d',(strtotime('- 180 days')));
		}
		if($reviewtime=='all'){
			$date_start=date('Y-m-d',(strtotime('- 10000 days')));

		}

		$title='Global Analysis';
}
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

		$scale_array=$_SESSION['scale_array'];
		foreach($scale_array as $value){
			$sum_behaviorarray[$value]=0;
		}//end foreach
		print "<table width='100%'><tr><td>";
		$residentkey_assoc_array = array();
		if($residentkey=='all_residents'){
			print"<div id='head'> $title for <em>All Residents</em></div>\n";
			$Population_strip=mysqli_real_escape_string($conn,$Population);
			$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip'";
			$session=mysqli_query($conn,$sql);
			while($row=mysqli_fetch_assoc($session)){
				$residentkey_array[]=$row['residentkey'];
				$residentkey_assoc_array[$row['residentkey']] = $row['first'].' '.$row['last'];
			}
		}elseif($residentkey&&$residentkey!='all_residents'){
			$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
			$session=mysqli_query($conn,$sql);
			$row=mysqli_fetch_assoc($session);
			$res_first=$row['first'];
			$res_last=$row['last'];
			$residentkey_array[]=$row['residentkey'];
			$residentkey_assoc_array[$row['residentkey']] = $row['first'].' '.$row['last'];
			print"<div id='head'> $title for $res_first $res_last</div>\n";
		}else{
			print"A resident selection was not made, please return to the previous page";
			die;
		}
		print "<p class='backButton'>";
			print "<input	type = 'button'
						name = ''
						id = 'backButton'
						value = 'Return to Analysis Design'
						onClick=\"backButton('$Population')\"/>\n";
		print "</p>";
		print "</td></tr><tr><td align='right'>";
				?>
					<FORM>
						<INPUT TYPE="button" value="Print Page" onClick="window.print()">
					</FORM></td></tr>
				<?
		print "</td></tr></table>";


	if($scale_totals){///////////////////////scale totals////////////////////////////
		$i=0;
		unset($sql_array);
	if($_REQUEST['all']=='all'){
		if($residentkey=='all_residents'){
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' order by date";
		}
		$sql_array[]=$sql_all;
	}else{
		foreach($_SESSION['scale_array'] as $behavior){//$i counts the sql variables!!
			$behavior=str_replace(' ','_',$behavior);
			${'behave_'.$i}=$_REQUEST[$behavior];
			if(in_array(${'behave_'.$i},$_SESSION['scale_array'])){
				if($residentkey=='all_residents'){
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='${'behave_'.$i}' order by date";
				}else{
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='${'behave_'.$i}' order by date";
				}
				$sql_array[]=${'sql'.$i};
				$i=$i+1;
			}
		}
	}//end all if

		for($j=0;$j<count($sql_array);$j++){

			$sum_duration=0;
			$sum_PRN=0;
			$sum_episodes=0;
			//$row=null;
			$session=${'session'.$j};
			$session=mysqli_query($conn,$sql_array[$j]);
			while(${'row'.$j}=mysqli_fetch_assoc($session)){
				$sum_duration=${'row'.$j}['duration']+$sum_duration;
				$sum_PRN=${'row'.$j}['PRN']+$sum_PRN;
				$sum_episodes=$sum_episodes+1;
				foreach($scale_array as $behavior){
					if(${'row'.$j}['behavior']==$behavior){
						$sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+${'row'.$j}['duration'];
					}
				}//end behaviorarray foreach
			}
			//call graph function
			$values_bar=$sum_behaviorarray;
			$graphTitle_bar='Duration of Behavior Episodes vs. Behavior';
			$yLabel_bar='Total Duration (minutes)';
			$xLabel_bar='Behaviors';
			ABAIT_bar_graph($values_bar, $graphTitle_bar, $yLabel_bar,$xLabel_bar,'bar');


			print"<table width='100%'>";
				print"<tr>";
					print"<td>";
						if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
							print"<h3 class='center_header'>Scale Totals for <em>All</em> Triggers Since <em>$date_start</em></h3>\n";
						}else{
							print"<h3 class='center_header'>Scale Totals for <em>${'behave_'.$j}</em> Triggers Since <em>$date_start</em></h3>\n";
						}
					print"</td>";
					print"<td align='right'>";
						print"<input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of your resident selected.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false\">";
					print"</td>";
				print"</tr>";
			print "</table>";

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info

					print "<table width='100%' class='table hover local' border='1' bgcolor='white'>";

						print "<thead>";
							print"<tr>\n";

								print"<th>Start Date</th>";
								print"<th>End Date</th>";
								print"<th>Episode Count</th>";
								print"<th>Total Duration</th>";
								print"<th>PRN Count</th>";
								print"<th>Graph</th>";

							print"</tr>\n";
						print "</thead>";

						print "<tbody>";
							print"<tr align='center'>\n";

									print"<td>$date_start</td>";
									print"<td>$date</td>";
									print"<td>$sum_episodes</td>";
									print"<td>$sum_duration</td>";
									print"<td>$sum_PRN</td>";
									print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraphbar.png','','width=700,height=400')\"></td>";



							print "</tr>\n";
						print "</tbody>";
					print "</table>";
				print "</td>";
		print "</tr>";
	print "</table>";


}
	}

if($episode_time_of_day){///////////////////////////////////////time of day//////////////////////////////////////////
		$i=0;
		unset($sql_array);
	if($_REQUEST['all']=='all'){
		if($residentkey=='all_residents'){
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			${'sql_all'}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start'";
		}
		$sql_array[]=$sql_all;
	}else{
		foreach($_SESSION[scale_array] as $behavior){//$i counts the sql variables!!
			$behavior=str_replace(' ','_',$behavior);
			${'behave_'.$i}=$_REQUEST[$behavior];
			if(in_array(${'behave_'.$i},$_SESSION[scale_array])){
				if($residentkey=='all_residents'){
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='${'behave_'.$i}'";
				}else{
					${'sql'.$i}="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' AND behavior='${'behave_'.$i}'";
				}
				$sql_array[]=${'sql'.$i};
				$i=$i+1;
			}
		}
	}//end all if
				$episode_start_array=array(7,10,13,16,19,22,1,4);//hours for shifts
				//$episode_end_array=array(10,13,19,22,1,4,7);
	for($j=0;$j<count($sql_array);$j++){
				foreach($episode_start_array as $i){
					${'episode_count'.$i}=0;
					${'sum_duration'.$i}=0;
				}
				$session=${'session'.$j};
				$session=mysqli_query($conn,$sql_array[$j]);
				$sum_duration = 0;
				while(${'row'.$j}=mysqli_fetch_assoc($session)){
					$sum_duration=${'row'.$j}['duration']+$sum_duration;
						foreach($episode_start_array as $i){
							if($i*10001<=str_replace(':','',${'row'.$j}['time'])&&str_replace(':','',${'row'.$j}['time'])<=($i+3)*10000){
								${'episode_count'.$i}=${'episode_count'.$i}+1;
								${'sum_duration'.$i}=${'row'.$j}['duration']+${'sum_duration'.$i};
							}
							${'episode_count_array'.$j}[$i]=${'episode_count'.$i};
							${'sum_duration_array'.$j}[$i]=${'sum_duration'.$i};
						}
				}
// section for printing episode time of day table follows

	//call graph function
		$values_bar_e=${'episode_count_array'.$j};
		$graphTitle_bar='Count of Episodes per Three Hour Interval';
		$yLabel_bar=' Episode Count';
		$xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';
	if(!empty($values_bar_e)){
	ABAIT_bar_graph($values_bar_e, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j);
	}
	//call graph function
		$values_bar_d=${'sum_duration_array'.$j};
		$graphTitle_bar='Duration of Behavior Episodes per Three Hour Interval';
		$yLabel_bar='Total Episode Duration (minutes)';
		$xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';

		if(!empty($values_bar_d)){
			ABAIT_bar_graph($values_bar_d, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j+5);
		}


	if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
		print"<h3 class='center_header'>Episode per Time of Day for <em>All</em> Triggers</h3>\n";
	}else{
		print"<h3 class='center_header'>Episode per Time of Day for <em>${'behave_'.$j}</em> Triggers Since <em>$date_start</em></h3>\n";
	}
	print "<table width='100%'>";//table for more info copy this line
			print "<tr><td>";//table in table data for more info
				print "<table width='100%' class='table hover local' border='1' bgcolor='white'>";
					print "<thead>";
						print"<tr>\n";

								print"<th>Time Interval (Hours)</th>";
								foreach($episode_start_array as $i){
									$k=$i+3;
									if($k==25){
										$k=1;
									}
									print"<th>$i-$k</th>";
								}
								print"<th>Graph</th>";

						print"</tr>\n";
					print "</thead>";
					print "<tbody>";
						print"<tr>\n";

								print "<td>Total Episodes</td>";
								foreach($episode_start_array as $i){
									print "<td>${'episode_count'.$i}</td>";
								}
								print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+$j+'.png','','width=700px,height=400')\"></td>";

						print"</tr>\n";
						print"<tr>\n";

								print"<td>Total Episode Duration (min)</td>";
								foreach($episode_start_array as $i){
										print "<td>${'sum_duration'.$i}</td>";
								}
								$j5 = $j+5;
								print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+$j5+'.png','','width=700px,height=400')\"></td>";

						print"</tr>\n";
					print "</tbody>";
				print"</table>";
		print"</td></tr></table>";

	}//end for
}//end if

if($behavior_units){///////////////////////////////////////////// behavior units////////////////////////////////////////
	print"<table width='100%'>";
		print"<tr><td colspan='2'>";
			print"<h3 class='center_header'>Effect of Interventions on Agitated Behavior Episodes</h3>";
		print"</td></tr>";
		print"<tr><td>";
			print"<h4 class='center_header'>Intervention values are the sum of improvement levels on behavior intensity rating scale.</h4>";
		print"</td>";
		print"<td align='right' valign-'bottom'>";

			print"<input type='submit' value='Tap for more Info' onClick=\"alert('This table breaks down improved behavior by the interventions of each trigger. Numerical values represent the sum of behavior improvement as measured by the behavior rating scale.  The behavior rating scales contain five levels, ranging from extremely agitated to normal.  Each level has a value of one point.');return false\">";

		print"</td></tr>";
	print"</table>";

	$r=0;
print "<table width='100%'>";//table for more info copy this line
	print "<tr><td>";//table in table data for more info
	foreach($scale_array as $behavior){

		unset($trig_array_keys);
		$trigger_count=0;
		$trigger_duration=NULL;
		unset($trigger_array);
		if($residentkey=='all_residents'){
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
		}else{
			$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey='$residentkey'";
		}
		$session2=mysqli_query($conn,$sql2);
		$trig_array_keys=[];
			while($row2=mysqli_fetch_assoc($session2)){
				$intervention_array=null;
				$trigger_array[$behavior][]=$row2['trig'];
				$episodes=0;
				$duration=0;
				$intv=0;
				$intv1=0;
				$intv2=0;
				$intv3=0;
				$intv4=0;
				$intv5=0;
				$intv6=0;
				//print"<tr>\n";
				if($residentkey=='all_residents'){
					$sql3="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
				}else{
					$sql3="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND behavior='$behavior' AND date > '$date_start'";
				}

					$session3=mysqli_query($conn,$sql3);
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
							//$trigger_array[$trigger_duration[$row2[trig]]]=$duration;
							$intv=0;
							$best='';
							for ($s=1;$s<7;$s++){
								if($intv<${'intv'.$s}){
									$intv=${'intv'.$s};
									$best=$s;
								}
								if($row2['intervention_'.$s]){
									$intervention_array[$row2['intervention_'.$s]]=${'intv'.$s};
								}
							}
							if($intervention_array){
								arsort($intervention_array);
								$trig_array[$row2['trig']]=$intervention_array;
								$trig_array[$row2['trig']]['episodes']=$episodes;
								$trig_array[$row2['trig']]['duration']=$duration;
								$trig_array_keys[$row2['trig']]=array_keys($intervention_array);
								$values[]=$intervention_array;
								$best='intervention_'.$best;
							}
				}

		if($trig_array_keys){

            print "<table class='center scroll local eoi hover'  bgcolor='white'>";
                print "<thead>";
					print "<tr>";
						print "<th colspan='8'>$behavior Behavior Episodes Since <em>$date_start</em></th>";
					print "</tr>";
					print"<tr>";
						print"<th class='first'>Trigger (episodes/duration)</th>";
						$j=0;
						foreach($trig_array_keys as $trig){
							if($j==0){
								$trigger_array_keys=(array_keys($trig_array_keys));
								if($trig!='duration'||$trig!='episodes'){
									//for($i=1;$i<=count($trig);$i++){// print intervention number
									for($i=1;$i<=6;$i++){// print intervention number
										print"<th>Interv. $i</th>";
									}
								}
										// print"<th>Graph</th>";
							}
					print"</tr>";
				print "</thead>";
				print "<tbody>";
					print"<tr>";
							print"<td class='first'>$trigger_array_keys[$j]</td>";
							$tr=array_keys($trig_array[$trigger_array_keys[$j]]);
							// for($i=0;$i<count($tr)-2;$i++){// print intervention key
							// 	print"<span class='tab_90'>$tr[$i]</span>";
							// }
							// print_r($tr);
							for($i=0;$i<6;$i++){// print intervention key
								if(isset($tr[$i])&&$tr[$i]!='episodes'&&$tr[$i]!='duration'){
									print"<td>$tr[$i]</td>";
								}else{
									print"<td>None Set</td>";
								}
							}
							// print"<td>None</td>";//no support for this yet
					print"</tr>";
					print"<tr>";
						$a=$trigger_array_keys[$j];
							print"<em>";
								print"<td class='first'>". $trig_array[$a]['episodes'].' episodes / '. $trig_array[$a]['duration'].' minutes'."</td>";
							print"</em>";
							$trig=array_values($trig_array[$trigger_array_keys[$j]]);
								for($i=0;$i<6;$i++){// print intervention key
									if(isset($trig[$i])&&$trig[$i]){
										print"<td>$trig[$i]</td>";
									}else{
										print"<td>None</td>";
									}
								}

								// for($i=0;$i<count($trig)-2;$i++){
								// 	print"<span class='tab_90'>$trig[$i]</span>";
								// }
								// print"<td>None</td>";//no support for this yet
									unset($trig);

					print"</tr>";




				print "</tbody>";

				unset($trig);
				$j++;
				}
			print"</table>";
		}//end foreach
	}//end if tri_array_keys exists
	print"</td></tr>";
	print"</table>";
}


if($trigger_breakdown){ ////////////////////////////////////////trigger breakdown//////////////////////////////////////
    print"<h3 align=center>Trigger and Intervetion Analysis</h3>\n";
    $r=0;
    $trigger_array_index=0;
    foreach($scale_array as $behavior){

        $trigger_count=0;
        $trigger_duration=NULL;

        $behavior_maps_sql="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        //$sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey='$residentkey'";
        $behavior_maps_session=mysqli_query($conn,$behavior_maps_sql);
            print "<table width='100%'>";
            print "<tr><td>";
            print "<table align=center class='table scroll local' border='1' bgcolor='white'>";
                print "<thead>";
                    print"<tr><th colspan='5'>$behavior Behavior Episodes Since <em>$date_start</em></th></tr>";
                    print"<tr>";
                    		print"<th>----Trigger----</th>";
                    		print"<th>Number of Episodes</th>";
                    		print"<th>Duration of Episodes</th>";
                    		print"<th>Most Effective Intervention</th>";
                    		print"<th>Graph</th>";
                    print"</tr>";
            print "</thead>";
            print "<tbody>";
                while($behavior_maps_row=mysqli_fetch_assoc($behavior_maps_session)){

                                $intervention_array=null;
                                $trigger_array[]=$behavior_maps_row['trig'];
                                $episodes=0;
                                $duration=0;
                                $intv=0;
                                $intv1=0;
                                $intv2=0;
                                $intv3=0;
                                $intv4=0;
                                $intv5=0;
                                $intv6=0;

																print"<tr align='center'>";
                                    print "<td> $behavior_maps_row[trig] </td>";

																			if($residentkey=='all_residents'){
																				$behavior_map_data_sql="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
																			}else{
																				$behavior_map_data_sql="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND behavior='$behavior' AND date > '$date_start'";
																			}
								                                $behavior_map_data_session=mysqli_query($conn,$behavior_map_data_sql);


                                            $trig_episodes=False;
                                            while($behavior_map_data_row=mysqli_fetch_assoc($behavior_map_data_session)){
                                                if($behavior_maps_row['mapkey']==$behavior_map_data_row['mapkey']){
                                                    $episodes=$episodes+1;
                                                    $duration=$duration+$behavior_map_data_row['duration'];
                                                    $intv1=$intv1+$behavior_map_data_row['intervention_score_1'];
                                                    $intv2=$intv2+$behavior_map_data_row['intervention_score_2'];
                                                    $intv3=$intv3+$behavior_map_data_row['intervention_score_3'];
                                                    $intv4=$intv4+$behavior_map_data_row['intervention_score_4'];
                                                    $intv5=$intv5+$behavior_map_data_row['intervention_score_5'];
                                                    $intv6=$intv6+$behavior_map_data_row['intervention_score_6'];
                                                    $trig_episodes=True;

                                                }
                                            }//end invtervention while
                                            if($trig_episodes){
                                                $trigger_duration[$behavior_maps_row['trig']]=$duration;
                                                $intv=0;
                                                for ($s=1;$s<7;$s++){
                                                    if(${'intv'.$s}<0){
                                                        ${'intv'.$s}=0;
                                                    }
                                                    if($intv<${'intv'.$s}){
                                                        $intv=${'intv'.$s};
                                                        $best=$s;
                                                    }
                                                    if($behavior_maps_row['intervention_'.$s]){
                                                        $intervention_array[$behavior_maps_row['intervention_'.$s]]=${'intv'.$s};
                                                    }

                                                }
                                                $values[]=$intervention_array;

                                                print"<td>$episodes</td>";
                                                print"<td>$duration</td>";

                                                $best_intervention='intervention_'.$best;
                                                print"<td>$behavior_maps_row[$best_intervention]</td>";
																								print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/pie_icon.png\" onClick=\"window.open('behaviorgraph'+($r+20)+'.png','','width=700px,height=400')\"></td>";

                                                $graphTitle='Relative Effectiveness of '.$trigger_array[$trigger_array_index].' Interventions';
                                                $yLabel='Relative Effectiveness';

                                                ABAIT_pie_graph($values[$r], $graphTitle, $yLabel,$r+20);


                                                //print"<td align=center>";

                                                $r+=1;

                                            }else{
                                                print "<td>0</td><td>0</td><td>None</td><td>No Graph</td>";
                                            }
                                print"</tr>";

                    	$trigger_array_index+=1;
                	}//end row2 while for each trigger
								print "</tbody>";
            print "</table>";

     	print"</td></tr></table>";

    }//end foreach
}// end trigger_breakdown if








if($all_episode){//////////////////////////////////////////all_episode/////////////////////////////////////////
		if($residentkey=='all_residents'){
			$sql="SELECT * FROM behavior_map_data WHERE date > '$date_start' ORDER BY date, residentkey";
		}else{
			$sql="SELECT * FROM behavior_map_data WHERE residentkey='$residentkey' AND date > '$date_start' ORDER BY date";
		}
		$session=mysqli_query($conn,$sql);

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info
				print"<h3 class='center_header'>All Episode Report</h3>";
				print"</td></tr>";

        print "<table class='center noScroll local hover'  bgcolor='white'>";
          print "<thead>";
						print "<tr>";

							print "<th></th>";
							print "<th>Start Date</th>";
							print "<th>$date_start</th>";
							print "<th>End Date</th>";
							print "<th>$date</th>";
							print "<th></th>";

							print "</tr>";
						print "<tr>";

							print "<th>Resident</th>";
							print "<th>Date</th>";
							print "<th>Time</th>";
							print "<th>Behavior Classification</th>";
							print "<th>Trigger</th>";
							print "<th>PRN Given</th>";

					print "</tr>";
				print"</thead>";
				print"<tbody>";
				while($row=mysqli_fetch_assoc($session)){
					$sql1="SELECT trig, residentkey FROM behavior_maps WHERE mapkey='$row[mapkey]'";
					$session1=mysqli_query($conn,$sql1);
					$row1=mysqli_fetch_assoc($session1);
					$rk = $row1['residentkey'];
					//$residentkey_assoc_array[$rk]
					print "<tr>";

							print"<td>$residentkey_assoc_array[$rk]</td>";
							print"<td>$row[date]</td>";
							print"<td>$row[time]</td>";
							print"<td>$row[behavior]</td>";
							print"<td>$row1[trig]</td>";
							if($row['PRN']==1){
								print"<td>Yes</td>";
							}else{
								print"<td>None</td>";
							}

					print "</tr>";
				}
				print"</tbody>";
			print "</table>";
		print "</td></tr>";
	print "</table>";


}//end all_epsisode if

if($include_unmapped){//////////////////////////////////////////include_unmapped/////////////////////////////////////////
		if($residentkey=='all_residents'){
			$sql="SELECT * FROM resident_mapping WHERE date > '$date_start' AND date <= '$date_end' ORDER BY date, residentkey";
		}else{
			$sql="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' AND date > '$date_start' AND date <= '$date_end' ORDER BY date";
		}


		$session=mysqli_query($conn,$sql);

			print "<table width='100%'>";//
				print "<tr><td>";//table in table data for more info
				print"<h3 class='center_header'>Unmapped Behavior Episode Report</h3>";
				print"</td></tr>";

        print "<table class='center noScroll local hover'  bgcolor='white'>";
          print "<thead>";
						print "<tr>";

							print "<th></th>";
							print "<th>Start Date</th>";
							print "<th>$date_start</th>";
							print "<th>End Date</th>";
							print "<th>$date_end</th>";
							print "<th></th>";

							print "</tr>";
						print "<tr>";

							print "<th>Resident</th>";
							print "<th>Date</th>";
							print "<th>Time</th>";
							print "<th>Behavior Classification</th>";
							print "<th>Trigger</th>";
							print "<th>PRN Given</th>";

					print "</tr>";
				print"</thead>";
				print"<tbody>";
				$total_duration = 0;
				while($row=mysqli_fetch_assoc($session)){
					$total_duration += $row['duration'];
					$rk = $row['residentkey'];
					//$residentkey_assoc_array[$rk]
					print "<tr>";

							print"<td>$residentkey_assoc_array[$rk]</td>";
							print"<td>$row[date]</td>";
							print"<td>$row[time]</td>";
							print"<td>$row[behavior]</td>";
							print"<td>$row[trigger]</td>";
							if($row['PRN']==1){
								print"<td>Yes</td>";
							}else{
								print"<td>None</td>";
							}

					print "</tr>";
				}
					print "<tr>";
						print "<td colspan=3>Total Duration of Episodes (min) </td>";
						print "<td colspan=3>$total_duration</tr>";
					print "</tr>";
				print"</tbody>";
			print "</table>";
		print "</td></tr>";
	print "</table>";


}//end include_unmapped if

print "<p class='backButton'>";
	print "<input	type = 'button'
				name = ''
				id = 'backButton'
				value = 'Return to Analysis Design'
				onClick=\"backButton('$Population')\"/>\n";
print "</p>";

?>
<!--
			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Back to Admin Home Page">
			</div>

	</form> -->
	</fieldset>
<?build_footer()?>
</body>
</html>
