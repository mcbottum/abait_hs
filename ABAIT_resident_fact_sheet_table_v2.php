<?
include("ABAIT_function_file.php");
ob_start()?>
<?session_start();
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
ABAIT Resident Triggers and Interventions
</title>
<?
	set_css()
?>
<style>
    td {
    	padding-top: 5px;
    	padding-bottom: 5px;
    }

	.center {
		width: 30%;
	}
	.btn.btn-lg {
	    background-color: #03DAC5;
	    border-radius: 10px;
	    font-size: 1.5em;
	    color: black;
	}
	.btn-lg:hover {
		background-color: #1FC4B4;
		box-shadow: 1px 1px 15px #888888;
	    border-style:solid;
	    border-width:1px;
	    color: black;
	}
	.footer_div {
		background-color: #F5F5F5;
	}
	.footer {
		color: black;
	}

</style>
</head>
<body class="container">
	<?			
		$names = build_page_pg();
	?>
									
<h2 class="text-center mt-4"><label>Resident Fact Sheet</label></h2>

<form action="adminhome.php" method="post">
		
<?
$filename=$_REQUEST['submit'];
//$Population=$_REQUEST[Target_Population];
$residentkey=$_REQUEST['residentkey'];
$date=date('Y-m-d');

if($filename=="Submit Resident Choice"){
		//$all_residents=$_REQUEST[all_residents];

		$title='Table of all Trigger and Intervention Maps for';
		$title2='Interventions or Responses to Avoid';	
		$title3='Slow Trigger Episodes';
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

		print "<table width='100%'><tr><td>";
		if($residentkey=='all_residents'){
			$sql1="SELECT * FROM behavior_maps WHERE Target_Population='$_SESSION[Population_strip]' ORDER BY behavior";
			$sql_slow_trigger="SELECT * FROM resident_mapping WHERE Target_Population='$_SESSION[Population_strip]' AND slow_trigger='1' ORDER BY behavior";
			$sql_int_avoid="SELECT * FROM resident_mapping WHERE Target_Population='$_SESSION[Population_strip]' AND intervention_avoid<>'none' ORDER BY behavior";

			$session1=mysqli_query($conn,$sql1);

			$session_slow_trigger=mysqli_query($conn,$sql_slow_trigger);
			$session_int_avoid=mysqli_query($conn,$sql_int_avoid);
			if(mysqli_num_rows($session_slow_trigger)){
				$slow_trigger=$session_slow_trigger->fetch_all(MYSQLI_ASSOC);
			}else{
				$slow_trigger = Null;
			}
			if(mysqli_num_rows($session_int_avoid)){
				$int_avoid=$session_int_avoid->fetch_all(MYSQLI_ASSOC);
			}else{
				$int_avoid=Null;
			}


		}elseif($residentkey&&$residentkey!='all_residents'){

			$sql1="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' ORDER BY behavior";
			$sql3="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
			$sql_slow_trigger="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' AND slow_trigger='1' ORDER BY behavior";
			$sql_int_avoid="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' AND intervention_avoid<>'none' ORDER BY behavior";

			$session1=mysqli_query($conn,$sql1);
			$session3=mysqli_query($conn,$sql3);

			$session_slow_trigger=mysqli_query($conn,$sql_slow_trigger);
			$session_int_avoid=mysqli_query($conn,$sql_int_avoid);

			$row3=mysqli_fetch_assoc($session3);

			$session_slow_trigger=mysqli_query($conn,$sql_slow_trigger);
			$session_int_avoid=mysqli_query($conn,$sql_int_avoid);
			if(mysqli_num_rows($session_slow_trigger)){
				$slow_trigger=$session_slow_trigger->fetch_all(MYSQLI_ASSOC);
			}else{
				$slow_trigger = Null;
			}
			if(mysqli_num_rows($session_int_avoid)){
				$int_avoid=$session_int_avoid->fetch_all(MYSQLI_ASSOC);
			}else{
				$int_avoid=Null;
			}


			$res_first=$row3['first'];
			$res_last=$row3['last'];


		}else{
			print"A resident selection was not made, please return to the previous page";
			die;
		}


		print "</td><td align='right'>";
				?>
					<FORM>
						<INPUT TYPE="button" class="btn btn-light" value="Print Page" onClick="window.print()">
					</FORM>
				<?
		print "</td></tr></table>\n";

		if(mysqli_num_rows($session1)>0){
				

                    if($residentkey=='all_residents'){
                        print"<div id='head'> $title for <em>All Residents</em></div>\n";
                    }else{
                            print"<div id='head'> $title $res_first $res_last</div>\n";         
                    }
			
			print "<table class='center table-responsive-md table-hover table-sm' border='1' bgcolor='white' style='width:auto'>";
				print "<thead>";
					print"<tr>\n";
						print"<th align='center'>Scale</th>\n";
						print"<th align='center'>Trigger</th>\n";
						print"<th align='center'>Intv. 1</th>\n";
						print"<th align='center'>Intv. 2</th>\n";
						print"<th align='center'>Intv. 3</th>\n";
						print"<th align='center'>Intv. 4</th>\n";
						print"<th align='center'>Intv. 5</th>\n";											
					print"</tr>\n";
				print "</thead>";
				print "<tbody>";
						while($row1=mysqli_fetch_assoc($session1)){
							$row=array($row1['intervention_1'], $row1['intervention_2'], $row1['intervention_3'], $row1['intervention_4'], $row1['intervention_5']);
							$sql2="SELECT SUM(intervention_score_1), SUM(intervention_score_2), SUM(intervention_score_3), SUM(intervention_score_4), SUM(intervention_score_5) FROM behavior_map_data WHERE mapkey ='$row1[mapkey]'";
							$score_sum=mysqli_query($conn,$sql2);	
							$row2=mysqli_fetch_assoc($score_sum);
								$intervention_rank=array(1,2,3,4,5);
							array_multisort($row2,$row,$intervention_rank);
							print"<tr>\n";
								print"<td  class='pl-2'>$row1[behavior]</td>\n";
								print"<td  class='pl-2'><em>$row1[trig]</em></td>\n";
								for($r=4;	$r>-1;	$r--){
									$intervention='intervention_'.$r;
									print "<td  class='pl-2'>$row[$r]</td>\n";
							
								}
							print"</tr>\n";
						}
				print "</tbody>";
				print "<p></p>";	
			print "</table>";
		}else{
			print"<h3 class='text-center mt-4'>Triggers and Intervention Maps for $res_first $res_last have not been recorded.</h3>\n";
		}

// Interventions to avoid
		print "<p></p>";
		print "<p></p>";
		if($int_avoid){

			print"<div id='head'> $title2 </div>\n"; 
			

			print "<table class='center  table-hover table-sm' border='1' bgcolor='white' style='width:100%'>";
				print"<tr>\n";
					print"<th align='center'>Behavior</th>\n";
					print"<th align='center'>Trigger</th>\n";
					print"<th align='center'>Intervention to Avoid</th>\n";					
				print"</tr>\n";

				foreach ($int_avoid as $key => $value){

						print"<tr>\n";
							print"<td class='pl-2'>$value[behavior]</td>\n";
							print"<td class='pl-2'>$value[trigger]</td>\n";
							print"<td class='pl-2'><em>$value[intervention_avoid]</em></td>\n";
						print"</tr>\n";
					
				}

			print "</table>";
		}else{
			print"<h3 class='text-center mt-4'>Interventions to avoid for $res_first $res_last have not been recorded.</h3>\n";
		}
	unset($value);

	print "<p></p>";
	print "<p></p>";
// SLOW TRIGGER
		
		if($slow_trigger){
			print"<div id='head'> $title3 </div>\n";
			print "<table class='center table-responsive-sm table-hover table-lg' border='1' bgcolor='white' style='width:100%'>";
				print"<tr>\n";
					print"<th align='center'>Behavior</th>\n";
					print"<th align='center'>Trigger</th>\n";
					print"<th align='center'>Intervention</th>\n";
				
				print"</tr>\n";

				foreach ($slow_trigger as $key => $value){

						print"<tr >\n";
							print"<td class='pl-2'>$value[behavior_description]</td>\n";
							print"<td class='pl-2'>$value[trigger]</td>\n";
							print"<td class='pl-2'>$value[intervention]</td>\n";

						print"</tr>\n";
					
				}

			print "</table>";
		}
		// else{
		// 	print"<h3 class='text-center mt-4'>Slow Triggers for $res_first $res_last have not been recorded.</h3>\n";
		// }


		
}

?>
	</fieldset>


	</form>
	<? build_footer_pg() ?>
</body>
</html>
