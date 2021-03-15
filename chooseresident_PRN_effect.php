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
		href = "ABAIT.css">
</head>
<body>
<div id="body" style="width:978px; margin: 0px auto 0px auto; text-align: left">
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
<div id="head"><h3>
<?
Print "Residents of  ".$cgfirst ."  ".  $cglast;
?>
</h3></div>
<form 	name = 'form1'
		action = "PRN_effect.php"
		onsubmit='return valFrm()'
		method = "post">
<fieldset>
<?
if($_SESSION['privilege']=='globaladmin'){
	$sql="SELECT * FROM residentpersonaldata";
	}elseif($_SESSION['privilege']=='admin'){
	$privilegekey=$_SESSION['privilegekey'];
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$_SESSION[Target_Population]'";
	}elseif($_SESSION['privilege']=='caregiver'){
	$privilegekey=$_SESSION['privilegekey'];
	$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$_SESSION[Target_Population]'";
	}//end get residents elseif
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
			print "<table class='center' border='1' bgcolor='white'>";
				print"<h3>Choose resident for whom to record behavior characterization information.</h3>\n";
				print"<tr>\n";
					print"<th>Click Choice</th>\n";
					print"<th>Resident ID</th>\n";
					print"<th>First Name</th>\n";
					print"<th>Last Name</th>\n";
					print"<th>Birth Date</th>\n";
					if($_SESSION['privilege']=='globaladmin'){
						print"<th>Target Population</th>";
					}
				print"</tr>\n";
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>\n";
					print"<td><input type = 'radio'
						id = 'resident_choice'
						name = 'resident_choice'
						value = $row[residentkey]/></td>\n";
					print "<td> $row[residentkey]</td>\n";
					print "<td> $row[first]</td>\n";
					print "<td> $row[last]</td>\n";
					print "<td> $row[birthdate]</td>\n";
					if($_SESSION['privilege']=='globaladmin'){
						print"<td>$row[Target_Population]</td>";
					}
					print "</tr>\n";
				}
			print "</table>";					
?>
	
			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Choice">
			</div>
</fieldset>
	</form>
	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</div>
	</body>
</html>