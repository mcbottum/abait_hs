<?
include("ABAIT_function_file.php");
ob_start()?>
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
		href = "ABAIT_admin.css">
</head>
<fieldset>
<body>
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
<div id="menu">
	<h3 align='center'>Administrative Level Reporting Options</h3>
	<table class='center'>
	<tr><td align='center'>
				<table class='center'>
				<tr><td align='center'><a href="resident_for_prn.php">Resident PRN Review</a></td></tr>
				<tr><td align='center'><a href="resident_fact_sheet.php">Resident Fact Sheet</a></td></tr>
				<tr><td align='center'><a href='ABAIT_prn_effect_v2.php'>Record PRN Effect for Resident</a></td></tr>
				<tr><td align='center'><a href='ABAIT_careprovider_review.php'>Review Care Provider Activity</a></td></tr>
				</table>
				</td></tr>
	</table>
</div>
</fieldset>
<?build_footer()?>
</body>
</html>
