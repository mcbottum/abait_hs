<?session_start();
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
		href = "chooseresident.css">
<style type="text/css">
 
</style>
</head>
<body>

<div id="head"><h1>
Residents for Mapping
</h1></div>
<!--<img id="logo"src="logo.png" />--!>
<!--<img id="logo1"src="logo.png" />--!>
<form 	action = "ABAIT_log.php"
method = "post">
<?
	$sql="SELECT * FROM residentpersonaldata";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
			print "<table border='1'>";
				print"<h2>Select Resident</h2>\n";
				print"<tr>\n";
					print"<td>Click Choice</td>\n";
					print"<td>Resident ID</td>\n";
					print"<td>First Name</td>\n";
					print"<td>Last Name</td>\n";
					print"<td>Birth Date</td>\n";
				print"</tr>\n";
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>\n";
					print"<td><input type = 'radio'
						name = 'residentkey'
						value = $row[residentkey]/></td>\n";
					print "<td> $row[residentkey]</td>\n";
					print "<td> $row[first]</td>\n";
					print "<td> $row[last]</td>\n";
					print "<td> $row[birthdate]</td>\n";
					print "</tr>\n";
				}
			print "</table>";					
?>
<div id = "behavior">	
					<h3><label>Behavior To Map</label></h3>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "Motor"
								value = "Motor"/>Motor Agitation Anxiety
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "resisistance"
								value = "Resistance"/>Resistance to Care
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "vocalizations"
								value = "Vocalizations"/>Vocalizations
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "agressiveness"
								value = "Agressiveness"/>Agressiveness
					</p>
			</div>
			<fieldset id="submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident for Global Analysis">
			</fieldset>

	</form>


		<h5 id="footer">
		<ul>
			<li><a href = "logout.php">Preempt Home</a></li>
			<li><a href = "clienthomepage.php">Client Home Page</a></li>
			<li><a href = "mailto:bott1@centurytel.net?subject=Feedback on Previous Session List Page">Contact Preempt</a></li>
			<li><a href = "logout.php">Logout and exit</a></li>
		</ul>
		</h5>
	<?build_footer()?>
	</body>
</html>