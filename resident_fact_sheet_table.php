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
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
</head>
<style>
	table.local thead th{
		width:175px;
	}
	table.local tbody td{
		width:175px;
	}
</style>
<body>
<fieldset>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
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
									
			<h3><label>Resident Fact Sheet</label></h3>
<form action="adminhome.php" method="post">
		
<?
$filename=$_REQUEST['submit'];
//$Population=$_REQUEST[Target_Population];
$residentkey=$_REQUEST['residentkey'];
$date=date('Y-m-d');
//print $residentkey;
if($filename=="Submit Resident Choice"){
		//$all_residents=$_REQUEST[all_residents];

		$title='Table of all Triggers and Interventions for';
		$title2='Interventions or Responses to Avoid';	
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

		print "<table width='100%'><tr><td>";
		if($residentkey=='all_residents'){
			print"<div id='head'> $title for <em>All Residents</em></div>\n";
			$sql1="SELECT * FROM behavior_maps WHERE Target_Population='$_SESSION[Population_strip]' ORDER BY behavior";
			$sql4="SELECT * FROM resident_mapping WHERE Target_Population='$_SESSION[Population_strip]' ORDER BY behavior";
			$session1=mysqli_query($conn,$sql1);
			$session4=mysqli_query($conn,$sql4);

		}elseif($residentkey&&$residentkey!='all_residents'){
			$sql1="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' ORDER BY behavior";
			$sql3="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
			$sql4="SELECT * FROM resident_mapping WHERE residentkey='$residentkey' ORDER BY behavior";
			$session1=mysqli_query($conn,$sql1);
			$session3=mysqli_query($conn,$sql3);
			$session4=mysqli_query($conn,$sql4);
			$row3=mysqli_fetch_assoc($session3);
			
			$res_first=$row3['first'];
			$res_last=$row3['last'];
			print"<div id='head'> $title $res_first $res_last</div>\n";
		}else{
			print"A resident selection was not made, please return to the previous page";
			die;
		}
		print "</td><td align='right'>";
				?>
					<FORM>
						<INPUT TYPE="button" value="Print Page" onClick="window.print()">
					</FORM>
				<?
		print "</td></tr></table>\n";	
				
		print "<table width='100%' class='center local' border='1' bgcolor='white'>";
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
							print"<td>$row1[behavior]</td>\n";
							print"<td><em>$row1[trig]</em></td>\n";
							for($r=4;	$r>-1;	$r--){
								$intervention='intervention_'.$r;
								print "<td>$row[$r]</td>\n";
						
							}
						print"</tr>\n";
					}
			print "</tbdoy>";
			print "<p></p>";	
		print "</table>";
		print "<table width='100%'>";
			print"<tr><td><div id='head2'> $title2</div></td></tr>\n";
		print "</table>\n";
		print "<table width='100%' class='center' border='1' bgcolor='white'>";
			print"<tr>\n";
				print"<th align='center'>Behavior</th>\n";
				print"<th align='center'>Trigger</th>\n";
				print"<th align='center'>Intervention to Avoid</th>\n";					
			print"</tr>\n";

			while($row4=mysqli_fetch_assoc($session4)){
				if ($row4['intervention_avoid'] !='none'){
					print"<tr>\n";
						print"<td>$row4[behavior]</td>\n";
						print"<td>$row4[trigger]</td>\n";
						print"<td><em>$row4[intervention_avoid]</em></td>\n";
					print"</tr>\n";
				}
			}

		print "</table>";
		
}

?>
	</fieldset>


	</form>
<?build_footer()?>
</body>
</html>
