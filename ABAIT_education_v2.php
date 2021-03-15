<?session_cache_limiter('nocache');
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
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
	set_css();
?>

</head>
<script type="text/javascript">
function reload(form){
	var val1=form.Trigger_Class.options[form.Trigger_Class.options.selectedIndex].value;
	self.location='ABAIT_education_v2.php?Trigger_Class='+val1;
}	
</script>
<body class="container"
<?
	$names = build_page_pg();
?>
<form 	name = 'form'
		method = "post">


<?
if (isset($_REQUEST['Trigger_Class'])){
	$Trigger_Class=$_REQUEST['Trigger_Class'];
}else{
	$Trigger_Class = '';
}

$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if($_SESSION['Target_Population']!='all'){
	$Population=$_SESSION['Target_Population'];
}//end target population if
else{
	$_SESSION['Population']='Dementia/Alzheimers Disease';
	$Population='Dementia/Alzheimers Disease';
}//end else

$Population_strip=mysqli_real_escape_string($conn,$Population);
$sql="SELECT * FROM triggers_and_interventions WHERE Target_Population='$Population_strip' ORDER BY Trigger_Class";

print "<h2 class='m-3' align='center'>";
print $names[0]."'s Interactive Education Module";
print "</h2>";

?>

<table class="m-auto">
	<tr>
	<td>
<div id="label">
	<h4 class='m-3'>Observing and Addressing Difficult Behaviors</h3>
</div>
	<div id="menu">
	<ol type="I">
		<li><h4>When Behaviors Occur</h4></li>
			<ul class="m-2">
				<li class="m-2"><a href='chooseresident_map.php'>STEP 1 - Record in 2 Week Resident Observation</a></li>
					<ol>
						<li>What behaviors occured?</li>
						<li>Where behaviors happened?</li>
						<li>When behavior took place (date and time)?</li>
						<li>How long did behavior last?</li>
						<li>Who was involved?</li>
						<li>Why did behavior happen (triggers)?</li>
						<li>What worked and what did not work to intervene?</li>
					</ol>
				<li class="m-2">STEP 2 - What to Look For</li>
					<ol>
						<li>Pain</li>
						<li>Fever</li>
						<li>Signs of dehydration</li>
						<li>Change in levels of consciousness</li>
						<li>Ambulation changes</li>
						<li>Medical changes</li>
						<li>Depression</li>

					</ol>
				<li class="m-2">STEP 3 - Look for Triggers and Interventions</li>
				
				<li class="m-2" style="list-style-type:none">
					<ul style="list-style-type:none">
					<?
						//SELECT STUFF HERE
						$session=mysqli_query($conn,$sql);


						print "<li class='align-left'>";
						print "<select class='selectpicker m-3' data-width='auto' name='Trigger_Class' onchange=\"reload(this.form)\"><option value=''>Select a Trigger Class</option>"."<BR>";
						$trigger_holder=[];
						while($row=mysqli_fetch_assoc($session)){
							if(!in_array($row[Trigger_Class], $trigger_holder)){

								if($row[Trigger_Class]==$Trigger_Class){
									$clean=str_replace('_', ' ', $Trigger_Class);
									print "<option selected value=$Trigger_Class>$clean</option>";
								}else{
									$clean=str_replace('_', ' ', $row[Trigger_Class]);
									print  "<option value=$row[Trigger_Class]>$clean</option>";
								}

								$trigger_holder[]=$row[Trigger_Class];
							}
						}//end while
						print "</select>";
						print "</li>";

						
						if($Trigger_Class){
							print "<li>";
							$sql2="SELECT * FROM triggers_and_interventions WHERE Trigger_Class='$Trigger_Class'";
							$session2=mysqli_query($conn,$sql2);
								print "<table class='table table-sm-responsive' style='margin-left:-35px'; width='110%' bgcolor='white'>";
									print"<tr><th>Trigger</th><th>Intervention</th>";
									while($row2=mysqli_fetch_assoc($session2)){
										print"<tr>";
										print"<td align='center'>$row2[Trigger_Example]</td>";
										print"<td align='center'>$row2[Intervention]</td>";
										print"</tr>";
									}
								print"</table>";
							print "</li>";
					}


					?>
					</ul>
				</li>
			</ul>
		<li><h4>Addressing the Behavior </h4></li>
			<ol>
                <li><a href="https://www.loom.com/share/cce71315910e481880969a2b54ff3d82" target="_blank">Phase1: Positive Interactions Bootcamp</a></li>
                <li><a href="https://www.loom.com/share/78b7edc903fd4bbebec4334fa8a4749c" target="_blank">Phase2: Using the Caregiver Platform</a></li>

<? if($_SESSION['privilege']=='globaladmin' || $_SESSION['privilege']=='admin'){
	?>
	             <li><a href="Phase3.pdf" target="_blank">Phase3: Administrative Set-up</a></li>
<?
	}
?>
			</ol>


	</ol>
	</div>
	</td>
	</tr>
	<tr>
	<td>
		<img class="center" id="datamap" src = "ABAITflowchart_one_button500.png" ismap usemap="#datamap" style="border:none;">
		<map name="datamap">
<!-- 			<?
				if($_SESSION['privilege']=='globaladmin'||$_SESSION['privilege']=='admin'){
			?> -->
<!-- 				<area shape="rect"  coords="245,133,372,267" href="ABAIT_setup.php"/>
				<area shape="rect"  coords="245,281,372,342" href="chooseresident_for_map_review.php"/>
				<area shape="rect"  coords="245,354,372,494" href="ABAIT_analysis.php"/>
				<area shape="rect"  coords="113,527,236,592" href="ABAIT_education.php"/>
				<area shape="rect"  coords="246,527,369,592" href="chooseresident_for_map_review.php"/>
				<area shape="rect"  coords="379,527,503,592" href="ABAIT_admin_report.php"/> -->
			<!-- <?}?> -->
<!-- 				<area shape="rect"  coords="7,177,115,307" href="chooseresident_map.php"/>
				<area shape="rect"  coords="7,323,115,455" href="ABAIT_quick_scales.php"/>  -->
		</map>
	</td>
</tr></table>	


<?build_footer_pg()?>

</body>
</html>
