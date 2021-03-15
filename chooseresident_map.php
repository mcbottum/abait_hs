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
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />

<title>
<?
print $_SESSION['SITE']
?>
</title>
<script type='text/javascript'>
function validate_form()
{
	valid1=true;
	var alertstring=new String("");

	if(document.form1.resident_choice.selectedIndex==""){
		alertstring=alertstring+"\n-Select Resident-";
		document.form1.resident_choice.style.background = "Yellow";
		valid1=false;
	}else{
		document.form1.resident_choice.style.background = "white";
	}

if(valid1==false){
	alert("Please select;" + alertstring);
}
return valid1
}//end of main function

</script>
<?
if ($_SESSION['privilege'] == 'caregiver'){
	?>
	<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT.css">
	<?
}else{
	?>
	<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT_admin.css">
	<?
	}
?>
</head>
<body>
<fieldset>
<div id="body" style="width=700px; margin: 0px auto 0px auto; text-align: left">
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
<div id="head">
<?
Print "Residents of  ".$cgfirst ."  ".  $cglast;
?>
</div>
<form 	name = 'form1'
		action = "resident_map.php"
		onsubmit="return validate_form()"
		method = "post">

<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
if($_SESSION['privilege']=='globaladmin'){
	$sql="SELECT * FROM residentpersonaldata order by first";
	}elseif($_SESSION['privilege']=='admin'){
	$privilegekey=$_SESSION['privilegekey'];
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
	}elseif($_SESSION['privilege']=='caregiver'){
	$privilegekey=$_SESSION['privilegekey'];
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
	}//end get residents elseif

	$session=mysqli_query($conn,$sql);
			//print"<h3 align='center'>Choose resident for whom to record behavior characterization information.</h3>\n";
			// print "<table class='center' border='1' bgcolor='white'>";
			// 	print"<tr>\n";
			// 		print"<th>Click Choice</th>\n";
			// 		print"<th>Resident ID</th>\n";
			// 		print"<th>First Name</th>\n";
			// 		print"<th>Last Name</th>\n";
			// 		print"<th>Birth Date</th>\n";
			// 		if($_SESSION['privilege']=='globaladmin'){
			// 			print"<th>Target Population</th>";
			// 		}
				// print"</tr>\n";
				// while($row=mysqli_fetch_assoc($session)){
				// 	print"<tr>\n";
				// 	print"<td><input type = 'radio'
				// 		id = 'resident_choice'
				// 		name = 'resident_choice'
				// 		value = $row[residentkey]/></td>\n";
				// 	print "<td> $row[residentkey]</td>\n";
				// 	print "<td> $row[first]</td>\n";
				// 	print "<td> $row[last]</td>\n";
				// 	print "<td> $row[birthdate]</td>\n";
				// 	if($_SESSION['privilege']=='globaladmin'){
				// 		print"<td>$row[Target_Population]</td>";
				// 	}
				// 	print "</tr>\n";
				// }


		print" <table align='center'>";
		print"<tr><td><h3>Choose resident for whom to record behavior characterization information.</h3></td></tr>";

		print"<tr><td align='center'>";

		print "<select   id = 'resident_choice' name='resident_choice' ><option value=''>Select a Resident</option>"."<BR>";
		
			while($row = mysqli_fetch_array($session)) { 

				// print  "<option value=$row[residentkey]>$row[first] $row[last] ----> born: $row[birthdate]</option>";
				print  "<option value=$row[residentkey]>$row[first] $row[last]</option>";
					
			}
			
		print "</select>";	
		print"</td></tr>";
		print "</table>";					
?>
	
			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Choice">
			</div>
</fieldset>
	</form>
<!-- 	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</div> -->
	<?build_footer()?>
	</body>
</html>