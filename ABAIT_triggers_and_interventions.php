<?
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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>

<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	?>
		<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT.css">
		<?
}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
		?>
		<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT_admin.css">
		<?
}
?>

</head>
<body>
<fieldset>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">

<?
build_page($_SESSION['privilege'],$cgfirst);
?>
<div id="head">
<h4><label>Trigger and Intervention Catalog</label></h4>
</div>
	
<?

//Need to have some type of exeption here TODO
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if (isset($_REQUEST['Population'])) {
	$Population=str_replace('_',' ',$_REQUEST['Population']);
	$Population_strip=mysqli_real_escape_string($conn,$Population);
}else{
	$Population = '';
}

if($_SESSION['Target_Population']=='all'&&!$Population){
			$sql1="SELECT * FROM behavior_maps";


			$session1=mysqli_query($conn,$sql1);

			?>
		<form action="ABAIT_triggers_and_interventions.php" method="post">
			<?
			print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
			?>
								<select name = 'Population'>
			<?
								print"<option value =''>Choose</option>";
									$Target_Pop[]="";
									while($row1=mysqli_fetch_assoc($session1)){
										if(!array_search($row1['Target_Population'],$Target_Pop)){
											$pop=str_replace(' ','_',$row1[Target_Population]);
											$pop_strip=mysqli_real_escape_string($conn,$pop);
											print"<option value=$pop>$row1[Target_Population]</option>";
											$Target_Pop[]=$row1[Target_Population];
										}
									}
								print"</select>";
		?>
					<div id="submit">
						<input 	type = "submit"
								name = "submit"
								value = "Submit Target Population">
					</div>
				</form>
		<?
	}//end global admin if



else{

	if($_SESSION['Target_Population']!='all'){
		$Population=$_SESSION['Target_Population'];
	}//end target population if
	else{
		$_SESSION['Population']=$Population;
	}//end else

	$Population_strip=mysqli_real_escape_string($conn,$Population);

	$sql2="SELECT * FROM triggers_and_interventions WHERE Target_Population='$Population_strip' ORDER BY Trigger_Class";

	print"<table align='center' width='95%'>";
	print"<tr><td><h4>Triggers and Interventions for the <em>$Population </em> Population</td>";

	?>
		<td align='right'><INPUT TYPE="button" value="Print Page" onClick="window.print()"></td></tr></table>
	<?
	print "<table class='table' align='center' width='95%'border='1' bgcolor='white'>";

		$session2=mysqli_query($conn,$sql2);

					print"<tr><th>Trigger Class</th><th>Trigger</th><th>Intervention</th>";
						while($row=mysqli_fetch_assoc($session2)){
								print"<tr>";
								print"<td>$row[Trigger_Class]</td>";
								print"<td>$row[Trigger_Example]</td>";
								print"<td>$row[Intervention]</td>";
								print"</tr>";
						}
	print"</table>";
}
?>
	</fieldset>
<?build_footer()?>
</body>
</html>
