<?session_start();
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
<body id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<div id="globalheader">
	<ul id="globalnav">
		<li id="gn-home"><a href='adminhome.php'</a></li>
		<li id="gn-maps"><a href="caregiverhome.php">Maps</a></li>
		<li id="gn-contact"><a href="mailto:bott1@centurytel.net?subject=Feedback on ABAIT">Contact ABAIT</a></li>
		<li id="gn-logout"><a href="logout.php">Logout</a></li>
	</ul>
</div><!--/globalheader-->
<?
print "<h2>Sorry, Password Taken, Please Select Another.</h2>\n";
build_footer()?>
</body>
</html>