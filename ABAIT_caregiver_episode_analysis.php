<?session_start();
include("ABAIT_function_file.php");
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
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
<script type='text/javascript'>
function checkRadio (frmName, rbGroupName) { 
 var radios = document[frmName].elements[rbGroupName]; 
 for (var i=0; i <radios.length; i++) { 
  if (radios[i].checked) { 
   return true; 
  } 
 } 
 return false; 
} 

function valFrm() { 
 	if (!checkRadio("form1","makemap")) {
  		alert("Please select a resident");
  		return false; 
	} 
}
</script>
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
build_page($_SESSION['privilege'],$cgfirst);
?>
<div id="head"><h3>
Care Provider Episode Analysis
</h3></div>
<fieldset>

<?	
$Population=str_replace('_',' ',$_REQUEST['Population']);
//$Population_strip=mysqli_real_escape_string($_SESSION[Population],$conn);
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
if($_SESSION['Target_Population']=='all'&&!$Population){
	$sql1="SELECT * FROM residentpersonaldata";
	$sql3="SELECT * FROM scale_table";
	$session3=mysqli_query($sql3,$conn);
	$row=mysqli_fetch_assoc($session3);
	?>
<form action="ABAIT_caregiver_episode_analysis.php" method="post">
	<?
	print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
	?>
						<select name = 'Population'>
	<?
						print"<option value =''>Choose</option>";
							$Target_Pop="";
							while($row=mysqli_fetch_assoc($session3)){
								$Population_strip_row=mysqli_real_escape_string($row[Target_Population],$conn);
								if($Population_strip_row!=$Target_Pop){
									$pop=str_replace(' ','_',$Population_strip_row);
									print"<option value=$pop>$row[Target_Population]</option>";
									$Target_Pop=$Population_strip_row;
								}
							}
						print"</select>";
?>
			<div id="submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Target Population">
			</div>
		</form>
<?
	}//end global admin if
else{
	
?>
	<form 	name = 'form1'
		action = "adminhome.php"
		method = "post">
<?
//$caregiver_array_rm=array('last')('episode_count','episode_duration','PRN');
//$caregiver_array_bmd=array['last']['episode_count','episode_duration', 'inv_effect', 'PRN'];
	$Population_strip=mysqli_real_escape_string($_SESSION['Population'],$conn);
	$sql_cg="SELECT * FROM personaldata WHERE Target_Population='$Population_strip' AND accesslevel='caregiver'";
	$sql_rm="SELECT * FROM resident_mapping WHERE Target_Population='$Population_strip'";
	$sql_bmd="SELECT * FROM behavior_map_data";
	$session_cg=mysqli_query($sql_cg,$conn);
	$session_rm=mysqli_query($sql_rm,$conn);
	$session_bmd=mysqli_query($sql_bmd,$conn);
print "<table border='1' bgcolor='white' width='100%'>";	
print"<tr align='center'>\n";
print"<th>Resident ID</th>\n";
print"<th>First Name</th>\n";
print"<th>Last Name</th>\n";
print"<th>Total Episode Duration (min)</th>\n";
print"<th>Total Episodes</th>\n";	
print"<th>Ave. Episode Duration (min)</th>\n";
print"<th>PRN Count per Episode</th>\n";
print"</tr>\n";	
$last='';
	while($row_cg=mysqli_fetch_assoc($session_cg)){
		$episode_count=0;
		$episode_duration=0;
		$PRN=0;	
		while($row_rm=mysqli_fetch_assoc($session_rm)){
			//print_r($row_rm);
			if($row_cg['personaldatakey']==$row_rm['personaldatakey']){
				if($row_cg!=$last){
					$last=$row_cg['last'];
					$first=$row_cg['first'];
					$personalID=$row_cg['personaldatakey'];
				}
				$episode_duration=$episode_duration+$row_rm['duration'];
				$episode_count=$episode_count+1;
				$PRN=$PRN+$row_rm['PRN'];
			}

		}
		while($row_bmd=mysqli_fetch_assoc($session_bmd)){
			if($row_cg['personaldatakey']==$row_bmd['personaldatakey']){
				if($row_cg!=$last){
					$last=$row_cg['last'];
					$first=$row_cg['first'];
					$personalID=$row_cg['personaldatakey'];
				}
				$episode_duration=$episode_duration+$row_bmd['duration'];
				$episode_count=$episode_count+1;
				$PRN=$PRN+$row_bmd['PRN'];
			}
		}

	if($episode_count!=0&&$episode_duration!=0){
		$ave_duration=round($episode_duration/$episode_count);
		$PRN_count=round($PRN/$episode_count,2);	
		print"<tr>";
		print"<td>$personalID</td>";
		print"<td>$first</td>";
		print"<td>$last</td>";
		print"<td>$episode_duration</td>";
		print"<td>$episode_count</td>";
		print"<td>$ave_duration</td>";
		print"<td>$PRN_count</td>\n";
		print"</tr>";
	}
	}	
print"</table>\n";
?>
			<div id="submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Return to Administration Home">
			</div>

	</form>
<?
	$_SESSION['Population']=$Population;
}
?>
	</fieldset>
	<?build_footer()?>
	</body>
</html>