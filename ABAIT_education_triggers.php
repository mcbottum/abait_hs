<?session_cache_limiter('nocache');
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
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">


<?

if($_SESSION['privilege']=='globaladmin'||$_SESSION['privilege']=='admin'){
	$first=$_SESSION['adminfirst'];
	$last=$_SESSION['adminlast'];
	$privilege=$_SESSION['privilege'];
	$_SESSION['residentkey']=null;
}elseif($_SESSION['privilege']=='caregiver'){
	$first=$_SESSION['cgfirst'];
	$cgfirst=$_SESSION['cgfirst'];
	$last=$_SESSION['cglast'];
}else{
	$first = '';
	$last = '';
}
build_page($_SESSION['privilege'],$first);
?>



<div id = "head"><h4>
<?
print $first."'s Interactive Education Module";

?>
</h4></div>
<fieldset>
<table>
	<tr>
	<td>
<div id="label">
	<h3>Observation and Problem Solving of Behaviors</h3>
</div>
	<div id="menu">
	<ol type="I">
		<li><h4>When Behaviors Occur</h4></li>
			<ul>
				<li><a href='chooseresident_map.php'>STEP 1 - Record in 2 Week Resident Observation</a></li>
					<ol>
						<li>What behaviors occured?</li>
						<li>Where behaviors happended?</li>
						<li>When behavior took place (date and time)?</li>
						<li>How long did behavior last?</li>
						<li>Who was involved?</li>
						<li>Why did behavior happend (triggers)?</li>
						<li>What worked and what did not work to intervene?</li>
					</ol>
				<li><a href="ABAIT_trigger_intervention_catalog.php">STEP 2 - What to Look For</a></li>
					<ol>
						<li>Pain</li>
						<li>Fever</li>
						<li>Signs of dehydration</li>
						<li>Change in levels of consciousness</li>
						<li>Ambulation changes</li>
						<li>Medical changes</li>
						<li>Depression</li>

					</ol>
				<li><a href="ABAIT_trigger_intervention_catalog.php">Possible Triggers and Interventions</a></li>

			</ul>
	</ol>
	</div>
	</td>
	<td>
<img id="datamap" src = "ABAITdatamenu.png" ismap usemap="#datamap" style="border:none;">
<map name="datamap">
<?
if($_SESSION['privilege']=='globaladmin'||$_SESSION['privilege']=='admin'){
	?>

<area shape="rect"  coords="245,133,372,267" href="ABAIT_setup.php"/>
<area shape="rect"  coords="245,281,372,342" href="chooseresident_for_map_review.php"/>
<area shape="rect"  coords="245,354,372,494" href="ABAIT_analysis.php"/>
<area shape="rect"  coords="113,527,236,592" href="ABAIT_education.php"/>
<area shape="rect"  coords="246,527,369,592" href="chooseresident_for_map_review.php"/>
<area shape="rect"  coords="379,527,503,592" href="ABAIT_admin_report.php"/>
<?}?>
<area shape="rect"  coords="7,177,115,307" href="chooseresident_map.php"/>
<area shape="rect"  coords="7,323,115,455" href="ABAIT_quick_scales.php"/> 
</map>
</td></tr></table>	

</fieldset>
<?build_footer()?>

</body>
</html>
