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
<div id="body" style="width:978px;margin: 0px auto 0px auto; text-align: left">
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
<div id="head"><h3>
Resident Fact Sheet
</h3></div>
<?
if(isset($_REQUEST['Population'])){
	$Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
	$Population=Null;
}
if($_SESSION['Target_Population']=='all'&&!$Population){
	$sql1="SELECT * FROM behavior_maps";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
	$session1=mysqli_query($conn,$sql1);
	?>
	<form action="resident_fact_sheet.php" method="post">
	<?
	print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
	?>
						<select name = 'Population'>
	<?
						print"<option value =''>Choose</option>";
							$Target_Pop[]="";
							while($row1=mysqli_fetch_assoc($session1)){
								if(!array_search($row1['Target_Population'],$Target_Pop)){
									$pop=str_replace(' ','_',$row1['Target_Population']);
									$pop=mysqli_real_escape_string($conn,$pop);
									print"<option value=$pop>$row1[Target_Population]</option>";
									$Target_Pop[]=$row1['Target_Population'];
								}
							}
						print"</select>";
?>
			<div id="submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Target Population">
			</div>
<?
	}//end global admin if
else{

?>
	<form 	name = 'form1'
					action = "resident_fact_sheet_table.php"
					method = "post">
<?
	$scale_array[]=null;
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());

if($_SESSION['Target_Population']!='all'){
	$Population=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population'";

}//end target population if
else{
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population'";

}//end else

$_SESSION['Population_strip']=$Population;
//$Population=str_replace(' ','_',$Population);
//print"<input type='hidden' value='$Population' name='Target_Population'>";
//print $Population;

$scale_array=array();
	$session1=mysqli_query($conn,$sql1);
	$session3=mysqli_query($conn,$sql3);
	//following makes an array of scale names
	$scale_holder='';
		while($row3=mysqli_fetch_assoc($session3)){
			if(!in_array($row3['scale_name'],$scale_array)){
				$scale_array[]=$row3['scale_name'];
			}
			$scale_holder=$row3['scale_name'];
		}
	$_SESSION['scale_array']=$scale_array;

	$counterarray=$_SESSION['scale_array'];
		print"<h3 align='center'>Select Resident</h3>\n";
		print "<table align='center'><tr><td>";
			print "<table class='center scroll local' border='1' bgcolor='white'>";
				print "<thead>";
					print"<tr align='center'>\n";
						print"<th>Click Choice</th>\n";
						print"<th>Resident ID</th>\n";
						print"<th>First Name</th>\n";
						print"<th>Last Name</th>\n";
						// print"<th>Birth Date</th>\n";
						print"<th>Population DB</th>\n";
					print"</tr>\n";
				print "</thead>";
				print "<tbody>";
					while($row1=mysqli_fetch_assoc($session1)){
						print"<tr align='center'>\n";
						print"<td><input type = 'radio'
							name = 'residentkey'
							value = $row1[residentkey]></td>\n";
						print "<td> $row1[residentkey]</td>\n";
						print "<td> $row1[first]</td>\n";
						print "<td> $row1[last]</td>\n";
						// print "<td> $row1[birthdate]</td>\n";
						print "<td> $row1[Target_Population]</td>\n";
						print "</tr>\n";
					}
						print "<tr align='center'><td><input type = 'radio'
								name = 'residentkey'
								value ='all_residents'></td>\n";
						print "<td colspan='5'>All Resident Summary</td></tr>\n";
				print "</tbody>";
			print "</table>";
		print "</td><th>";
		print "</table>";


?>
			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Choice">
			</div>
			<?
}
?>
			</fieldset>
	</form>
	<?build_footer()?>
</body>
</html>
