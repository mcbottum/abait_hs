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
<title>global_analysis.php</title>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>
	table.local thead th{
		width:175px;
	}
	table.local tbody td{
		width:175px;
	}

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
</style>
</head>
<body>
<fieldset>
<div id="body" style="width:978px;margin: 0px auto 0px auto; text-align: left">
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
<div id='head'><h4>Residents for Thirty Day Global Analysis</h4></div>
<form 	action = "30_day_analysis.php"
method = "post">

<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
if($_SESSION['privilege']=='globaladmin'){
	$sql="SELECT * FROM residentpersonaldata order by first";
	}elseif($_SESSION['privilege']=='admin'){
	$Population_strip=mysqli_real_escape_string($_SESSION[Target_Population],$conn);
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
	}//end get residents elseif
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
			print "<table align='center' class='scroll local hover' border='1' bgcolor='white'>";
				print "<thead>";
					print"<tr align='center'>\n";
						print"<th>Click Choice</th>\n";
						// print"<th>Resident ID</th>\n";
						print"<th>First Name</th>\n";
						print"<th>Last Name</th>\n";
						// print"<th>Birth Date</th>\n";
						// print"<th>Population DB</th>\n";
					print"</tr>\n";
				print "</thead>";
				print "</tbody>";
						print "<tr align='center'><td><label><input type = 'radio'
							name = 'residentkey'
							value ='all_residents'></td>\n";
						print "<td colspan='5'>All Resident Summary</label></td></tr>\n";
					while($row=mysqli_fetch_assoc($session)){
						print"<tr align='center'>\n";
						print"<td>";
						print "<label>";
						print "<input type = 'radio'
							name = 'residentkey'
							value = $row[residentkey]>";
						print "</label>";
						print "</td>\n";
						// print "<td> $row[residentkey]</td>\n";
						print "<td> $row[first]</td>\n";
						print "<td> $row[last]</td>\n";
						// print "<td> $row[birthdate]</td>\n";
						// print "<td> $row[Target_Population]</td>\n";
						print "</tr>\n";
					}
				print "</tbody>";
			print "</table>";					
?>
			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident for 30 Day Analysis">
			</div>
	</fieldset>
	</form>
<?build_footer()?>
</div>
</body>
</html>