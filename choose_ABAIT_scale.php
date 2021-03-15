<?ob_start()?>
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
		href = "ABAIT_admin.css">
</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<?
$adminfirst=$_SESSION['adminfirst'];
$adminlast=$_SESSION['adminlast'];
	print"<table width='100%'>\n";
		print"<tr>\n";
			print"<td valign='bottom' align='right'>$adminfirst Logged in</td>\n";
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
<div id = "head"><h2>
<?
print $_SESSION['adminfirst']."  ".$_SESSION['adminlast']." Choose Scale";
?>
</h2></div>
<fieldset>
<div id="label">
	<h3>Please Select Your Choice</h3>
</div>
<div id="menu">
<?
	print	"<ul>\n";
			print	"<ul>\n";
				print	"<li><a href='chooseresident_scale_agressive.php'>Agressiveness Agitation Scale</a>\n";
				print	"</li>\n";
				print	"<li><a href='chooseresident_scale_motor.php'>Motor Agitation Anxiety Scale</a>\n";
				print	"</li>\n";
				print	"<li><a href='chooseresident_scale_resistance.php'>Resistance to Care Scale</a>\n";
				print	"</li>\n";
				print	"<li><a href='chooseresident_scale_vocalization.php'>Vocalization Scale</a>\n";
				print	"</li>\n";
			print	"</ul>\n";
	print	"<ul>\n";
?>
</div>
</fieldset>
<?build_footer()?>
</div>
</body>
</html>