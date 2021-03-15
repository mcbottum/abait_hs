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
 	if (!checkRadio("form1","resident_choice")) {
  		alert("Please select a resident");
  		return false; 
	} 
}
</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
</head>
<body>
<div id="body">
<?
if($_SESSION['cgfirst']&&$_SESSION['cglast']){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
}
	print"<table width='100%'>\n";
		print"<tr>\n";
			print"<td valign='bottom' align='right'>$cgfirst Logged in</td>\n";
		print"</tr>\n";
	print"</table>\n";
?>
<div id="globalheader">
	<ul id="globalnav">
		<li id="gn-home"><a href='ABAIThome.html'</a></li>
		<li id="gn-maps"><a href="caregiverhome.php">Maps</a></li>
		<li id="gn-contact"><a href="mailto:bott1@centurytel.net?subject=Feedback on ABAIT">Contact ABAIT</a></li>
		<li id="gn-logout"><a href="logout.php">Logout</a></li>
	</ul>
</div><!--/globalheader-->
<div id="head">
<?
$_SESSION['scal']='Resistance';
Print "<h2>Residents of $cgfirst $cglast</h2>\n";
?>
</div>
<form 	name = 'form1'
		onsubmit='return valFrm()'
		action = "ABAIT_log.php" 
		method = "post">
			<fieldset id="submit">
<?
	$sql="SELECT * FROM residentpersonaldata";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
			print "<table class='center' border='1' bgcolor='white'>";
				print"<h3>Choose resident for Resistance to Care Scale</h3>\n";
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
						name = 'resident_choice'
						value = $row[residentkey]></td>\n";
					print "<td> $row[residentkey]</td>\n";
					print "<td> $row[first]</td>\n";
					print "<td> $row[last]</td>\n";
					print "<td> $row[birthdate]</td>\n";
					print "</tr>\n";
				}
			print "</table>";					
?>
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Choice">
			</fieldset>
	</form>
	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</body>
</html>