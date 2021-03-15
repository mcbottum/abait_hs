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

<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
</head>
<body>
<!-- <div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
 -->
<fieldset>
<?
$adminfirst=$_SESSION['adminfirst'];
$last=$_SESSION['adminlast'];
$privilege=$_SESSION['privilege'];
build_page($_SESSION['privilege'],$_SESSION['adminfirst']);
$_SESSION['residentkey']=null;
?>



<div id = "head">
<?
print $_SESSION['adminfirst']."  ".$_SESSION['adminlast']."'s Administrator Home Page";

?>
</div>

<table>
	<tr>
	<td>
<div id="label">
	<h3>Please Select Your Choice</h3>
</div>
	<div id="menu">
	<ol type="I">
		<li><h4>ABAIT Behavior Plan Set-Up</h4></li>
			<ul>
				<li><a href='ABAIT_tutorials_v2.php'>ABAIT Education Module</a></li>
				<li><a href="ABAIT_Scale_Create.php">Create New ABAIT Behavior Plans</a></li>
				<!-- <li><a href="ABAIT_Scale_Setup.php">Edit Behavior Categories</a></li> -->
				<li><a href="ABAIT_trigger.php">Edit or Delete ABAIT Behavior Plans</a></li>
			</ul>
		<li><h4>Member Enrollment</h4></li>
			<ul>
				<li><a href="residentdata.php">Enroll New Resident</a></li>
				<li><a href="caregiverdata.php">Enroll New Care Provider</a></li>
				<li><a href="administratordata.php">Enroll New Administrator</a></li>
				<?
				if($privilege=='globaladmin' || $privilege=='admin'){
					print"<li><a href='updateMembers.php'>Update Admins, Caregivers or Residents</a></li>";
					print"<li><a href='removeMembers.php'>Remove Admins, Caregivers or Residents</a></li>";
				}
				 ?>
				
			</ul>
		<li><h4>Plan Creation and Maintenance</h4></li>
			<ul>
				<li><a href='ABAIT_trigger_intervention_catalog.php'>Catalog of Behavior Triggers and Interventions</a></li>
				<li><a href="chooseresident_for_map_review.php">Create and Review Residents' Behavior Plans</a></li>
				<li><a href="ABAIT_quick_scales_v2.php">Record New Behavior</a></li>
				<li><a href='ABAIT_prn_effect_v2.php'>Record PRN Effect for Resident</a></li>
			</ul>
		<li><h4>Analysis and Education</h4>
			<ul>
				<!-- <li><a href="global_analysis.php">Resident 30 Day Global Analysis</a></li> -->
				<li><a href="episode_historical_review.php">Resident Episode Historical Review</a></li>
				<li><a href="resident_for_prn.php">Resident PRN Review</a></li>
				<li><a href="resident_fact_sheet.php">Resident Fact Sheet</a></li>
				<li><a href="ABAIT_careprovider_review.php">Care Provider Analysis</a></li>
			</ul></li>

	</ol>
	</div>
	</td>
	<td>
<?
if($_SESSION['privilege']=='globaladmin'||$_SESSION['privilege']=='admin'){
	?>
<!-- <img id="datamap" src = "ABAITdatamenu.png" ismap usemap="#datamap" style="border:none;"> -->
<img id="datamap" src = "ABAITflowchart_one_button500.png" ismap usemap="#datamap" style="border:none;"></a>
<map name="datamap">
<area shape="rect"  coords="245,133,372,267" href="ABAIT_setup.php"/>
<area shape="rect"  coords="245,281,372,342" href="chooseresident_for_map_review.php"/>
<area shape="rect"  coords="245,354,372,494" href="ABAIT_analysis.php"/>
<area shape="rect"  coords="113,527,236,592" href="ABAIT_tutorials_v2.php"/>
<area shape="rect"  coords="246,527,369,592" href="chooseresident_for_map_review.php"/>
<area shape="rect"  coords="379,527,503,592" href="ABAIT_admin_report.php"/>
<?}?>
<!-- <area shape="rect"  coords="7,177,115,307" href="ABAIT_quick_scales.php"/>
<area shape="rect"  coords="7,323,115,455" href="ABAIT_quick_scales.php"/>  -->
<area shape="rect"  coords="20,177,145,465" href="ABAIT_quick_scales_v2.php"/> 
</map>
</td></tr></table>	

</fieldset>
<?build_footer()?>

</body>
</html>
