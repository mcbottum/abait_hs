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
30 Minute Post PRN Report
</title>
<?
set_css()
?>

</head>
<body class="container">

<?
$names = build_page_pg();
?>
<form 	action = "ABAIT_prn_effect_log_v2.php"
		method = "post">
<?
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));	
		//print"$date_start";
		$title1='Thirty Minute Post PRN Response Report';
		$title2='Residents Receiving PRNs Within the Last Thirty Days';
		#$residentkey=$_REQUEST['resident_choice'];

		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
		if($_SESSION['Target_Population']!='all'){
		$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
		$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip'";
		}else{$sql1="SELECT * FROM residentpersonaldata";
		}
		$session1=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($session1);
		$Population_row1_strip=mysqli_real_escape_string($conn,$row1['Target_Population']);
		if($_SESSION['privilege']=='caregiver'){
			$sql_rm4="SELECT * from resident_mapping WHERE date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' ORDER BY date";
			$sql4="SELECT * FROM behavior_map_data WHERE  date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' ORDER BY date";
			$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_row1_strip'";
		}else{
			$sql_rm4="SELECT * from resident_mapping WHERE date > '$date_start' AND PRN='1'";
			$sql4="SELECT * FROM behavior_map_data WHERE  date > '$date_start' AND PRN='1'";
			$sql5="SELECT * FROM scale_table WHERE Target_Population='$Population_row1_strip'";
		}
		$session4=mysqli_query($conn,$sql4);
		$session_rm4=mysqli_query($conn,$sql_rm4);
		$session5=mysqli_query($conn,$sql5);
		$row5=mysqli_fetch_assoc($session5);
		$res_first=$row1['first'];
		$res_last=$row1['last'];
		//print(mysqli_num_rows ( $session4 ));
		print"<table width='100%' class='center'>";
			print"<tr>";
				print"<td>";
					print"<h2 align='center'> $title1 </h2>\n";
				print"</td>";
			print"</tr>";
			
			print"<tr>";
				print"<td>";
					print"<h3 align='center'> $title2 </h3>\n";
				print"</td>";
			print"</tr>";
?>
			<tr>
				<td align='center'>
					<input type='button' value='Tap for more Info' onClick="alert('The effectivness and or side effects of a PRN must be observed and recorded thirty minutes after it is administered.  Record observations here.');return false">
					<input type='button' value='Print Page' onClick='window.print()'>
				</td>
			</tr>
<?
			print"<tr>";
				print"<td>";
					print "<table class='table center'  bgcolor='white'>";
						print"<tr>";
							print"<th>Choose Episode</th>\n";
							print"<th>Episode Date</th>\n";
							print"<th>Episode Time</th>\n";
							$provider_resident = $_SESSION['provider_resident'];
							print"<th>$provider_resident</th>\n";
							print"<th class='pl-2'>Behavior Description</th></tr>\n";

							$session8=mysqli_query($conn,$sql_rm4);
							$PRN_description=0;
							if(mysqli_num_rows($session8)!=0){
								while($row8=mysqli_fetch_assoc($session8)){//non-mapped  PRNs
									print"<tr>";
										print"<td>";
										if(!$row8['post_PRN_observation']){
											print"<input name='episode_choice_rm' id='episode_choice' type='radio' value='$row8[mapkey]'>";
											$PRN_description=1;
										}else{
											print"recorded $row8[date]";
										}
										print "</td>";
										print"<td>$row8[date]</td>";
										print"<td>$row8[time]</td>";
										$session2=mysqli_query($conn,$sql1);
										while($row2=mysqli_fetch_assoc($session2)){
											if($row2['residentkey']==$row8['residentkey']){
										print"<td>$row2[first] $row2[last]</td>";
											}
										}
										print"<td class='pl-2'>$row8[behavior_description]</td>";
									print"</tr>";
								}
							}

							$session6=mysqli_query($conn,$sql4);
							if(mysqli_num_rows($session6)!=0){
								while($row6=mysqli_fetch_assoc($session6)){//mapped PRNs
									print"<tr>";
										print"<td>";
										if(!$row6['post_PRN_observation']){
											print"<input name='episode_choice' id='episode_choice' type='radio' value='$row6[behaviormapdatakey]'>";
											$PRN_description=1;
										}else{
											print"recorded $row6[date]";
										}
										print"</td>";
										print"<td>$row6[date]</td>";
										print"<td>$row6[time]</td>";
										$session3=mysqli_query($conn,$sql1);
										while($row3=mysqli_fetch_assoc($session3)){
											if($row3['residentkey']==$row6['residentkey']){
										print"<td>$row3[first] $row3[last]</td>";
											}
										}
										print"<td>$row6[behavior_description]</td>";
									print"</tr>";
									
								}

							}else{
								print"<tr><td align='center' colspan='5'>";
									print("No medicated episodes have been recorded.");
								print"</td></tr>";
							}
					print"</table>";
					?>
					</td>

					
				</td></tr>
<?if($PRN_description<>0){
	?>
	<tr><td  colspan='4' align='center'>Enter specific description of post PRN response in the yellow text box below.</td></tr>
	<tr><td  colspan='4' align='center'>
		<input	type = "text" name="post_PRN_observation" id="post_PRN_observation" style=" background-color: yellow; font-size: 14px; width:99%;" value="" size="130"/>
	</td></tr>
	
</table>				
					
		


			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit">
			</div>
<?}else{
	print'</table>';
	}?>
	</form>
<?build_footer_pg()?>
</body>
</html>
