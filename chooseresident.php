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
<?
$first=$_SESSION[first]; 
Print "Residents of".$_SESSION['first'] ."  ".  $_SESSION['last'];
?>
</h1></div>
<!--<img id="logo"src="logo.png" />--!>
<!--<img id="logo1"src="logo.png" />--!>
<form 	action = "accessresident.php"
method = "post">
<?
//print_r ($_SESSION);
	$pdkey=$_SESSION['pdkey'];
	$sql="SELECT * FROM residentpersonaldata";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db("agitation");
	$session=mysqli_query($sql,$conn);
				
			print "<table>";
			print"<h2>Choose resident</h2>\n";
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>\n";
					print"<td><input type = 'radio'
						name = 'session1'
						value = $row[first]/></td>\n";
					print"<td><input type = 'radio'
						name = 'session2'
						value = $row[idkey]/></td>\n";
					print "<td> $row[residentkey]</td>\n";
					print "<td> $row[date]</td>\n";
					print "</tr>\n";
				}
			print "</table>";				
				
				
?>
			<fieldset id="submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Previous Comparison Request">
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
	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</body>
</html>