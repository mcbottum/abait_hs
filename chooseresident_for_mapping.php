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
 	if (!checkRadio("form1","residentkey")) {
  		alert("Please select a resident");
  		return false; 
	} 
}
function valFrm() { 
 	if (!checkRadio("form1","behavior")) {
  		alert("Please select a Behavior");
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
build_page($_SESSION[privilege],$cgfirst);
?>
<div id="head"><h4>
Resident List For Behavior Scale Creation
</h4></div>
<fieldset class="submit">
<?
$Population=str_replace('_',' ',$_REQUEST[Population]);
if($_SESSION['Target_Population']=='all'&&!$Population){
	$sql1="SELECT * FROM residentpersonaldata";
	$sql3="SELECT * FROM scale_table";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session3=mysqli_query($sql3,$conn);
	?>
<form action="chooseresident_for_mapping.php" method="post">
	<?
	print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
	?>
						<select name = 'Population'>
	<?
						print"<option value =''>Choose</option>";
							$Target_Pop="";
							while($row3=mysqli_fetch_assoc($session3)){
								if($row3[Target_Population]!=$Target_Pop){
									$pop=str_replace(' ','_',$row3[Target_Population]);
									print"<option value=$pop>$row3[Target_Population]</option>";
									$Target_Pop=$row3[Target_Population];
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
		onsubmit='return valFrm()'
		action = "ABAIT_create_map.php"
		method = "post">
<?	

$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
mysqli_select_db($_SESSION['database']);
if($_SESSION[Target_Population]!='all'){
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$_SESSION[Target_Population]'";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$_SESSION[Target_Population]'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
	$Population=$_SESSION[Target_Population];
}//end target population if
else{
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population'";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population'";
	$_SESSION[Population]=$Population;

}//end else
	$scale_array[]=null;
	$session1=mysqli_query($sql1,$conn);
	$session3=mysqli_query($sql3,$conn);
	$scale_holder='';
		while($row3=mysqli_fetch_assoc($session3)){
			if(!in_array($row3[scale_name],$scale_array)){
				$scale_array[]=$row3[scale_name];
			}
			$scale_holder=$row3[scale_name];
		}
	$_SESSION[scale_array]=$scale_array;
	print"<h3>Select Resident from <em>$Population</em> Population Database</h3>\n";
		print "<table>";//table for more info copy this line
			print "<tr><td>";//table in table data for more info	
			print "<table border='1' bgcolor='white'>";
						print"<tr>\n";
							print"<th>Click Choice</th>\n";
							print"<th>Resident ID</th>\n";
							print"<th>First Name</th>\n";
							print"<th>Last Name</th>\n";
							print"<th>Birth Date</th>\n";
						print"</tr>\n";
						while($row1=mysqli_fetch_assoc($session1)){
							print"<tr>\n";
							print"<td><input type = 'radio'
								name = 'residentkey'
								value = $row1[residentkey]/></td>\n";
							print "<td> $row1[residentkey]</td>\n";
							print "<td> $row1[first]</td>\n";
							print "<td> $row1[last]</td>\n";
							print "<td> $row1[birthdate]</td>\n";
							print "</tr>\n";
						}
					print "</table>";		
				print "</td>";//end td for table COPY FROM HERE
//table data for more info
				print "<th>";
				?>
<input type='submit' value='Tap for more Info' onClick="alert('Listed are the residents for whom you may create or alter Behavior Scales.  Choose a resident, then choose the behavior you will map.');return false">
<?	
				print "</th></tr>";
		
			print "</table>";
//end table notation for more data  TO HERE	
	print "<h3><label>Behavior Scale to Create</label></h3>\n";	
			foreach($scale_array as $sa){
				print"<p>\n";
				if($sa!=''){
					$as=str_replace(' ','_',$sa);
				print"<input type = 'radio'
					name = 'behavior'
					value = $as>$sa\n";
				print "</p>\n";
				}
			}
$scale_array=null;	
?>
			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident for ABAIT Scale Creation">
			</div>
<?
}//end else
?>
			</form>
			</fieldset>

	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</div>
	</body>
</html>