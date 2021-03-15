<?
include("ABAIT_function_file.php");session_start();
//session_start();
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


<script>

function reload2(Population,number_of_scales, payload){
    self.location='ABAIT_create_map.php?Population='+Population+'&'+payload;
}
function backButton(target_population) {
	self.location='chooseresident_for_map_review.php?tp='+target_population;
}
</script>
<style>
fieldset{
	background-color: #fdebdf !important;
}
table{
	border-collapse: collapse;

}
table td, table th{
	padding-left:4px;
	width:140px;
}

table.hover tbody tr:hover{
		background-color: #D3D3D3;
}
label {
	width: 100%;
	display: inline-block;
}
table th{
	background-color: 	#C8C8C8;
}
p.backButton{
	float: right;
}
</style>



<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
</head>
<body>
<fieldset>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<?


if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION['privilege'],$cgfirst);





//Need to have some type of exeption here TODO
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
if (isset($_REQUEST['Population'])) {
	$Population=str_replace('_',' ',$_REQUEST['Population']);
	$Population_strip=mysqli_real_escape_string($conn,$Population);
}else{
	$Population = '';
}

if(isset($_GET['Population'])){
	$Population=$_GET['Population'];
}

if(isset($_GET['package'])){
	$package=$pop=str_replace('@','&',$_GET['package']);   $_GET['package'];
}

if(isset($_GET['number_of_scales'])){
	$number_of_scales=$_GET['number_of_scales'];
}


if(isset($_GET['id'])&&$_GET['id']!=='null'){
	mysqli_select_db($_SESSION['database']);
	$residentkey = $_GET['id'];
	$res_sql="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
	$res_query=mysqli_query($conn,$res_sql);
	$res = mysqli_fetch_assoc($res_query);
	$resident_name = $res['first']. "  ".$res['last'];
}
if(isset($residentkey)&&$residentkey!=null){
	print "<div id='head'>";
	print "<h4><label>Trigger and Intervention Catalog for $resident_name</label></h4>";
	print "</div>";
}else{
	print "<div id='head'>";
	print "<h4><label>Trigger and Intervention Catalog</label></h4>";
	print "</div>";
}

if($_SESSION['Target_Population']=='all'&&!$Population){
	if(isset($residentkey)&&$residentkey!=null){
		$sql1="SELECT * FROM behavior_maps WHERE residentkey='$residentkey'";
	}else{
		$sql1="SELECT * FROM behavior_maps";
	}
			$session1=mysqli_query($conn,$sql1);

			?>
		<form action="ABAIT_trigger_intervention_catalog.php" method="post">
			<?
			print"<h3><label>Select ABAIT Target Population</label></h3>";
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
}else{
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

if($_SESSION['Target_Population']!='all'){
	$Population=$_SESSION['Target_Population'];
}else{
	$_SESSION['Population']=$Population;
}//end else

$Population_strip=mysqli_real_escape_string($conn,$Population);
if(isset($residentkey)&&$residentkey!=null){
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip' and residentkey='$residentkey' ORDER BY behavior";
}else{
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip' ORDER BY behavior";
}

print"<table align='center' width='95%'>";
print"<tr><td><h4>Triggers and Interventions for the <em>$Population </em> Population</td>";

?>
<td align='right'><INPUT TYPE="button" value="Print Page" onClick="window.print()"></td></tr>

<?
if(isset($package)&&$package){
	print "<tr><td colspan='2' align='right'>";
	print "<input	type = 'button'
			name = 'subtract_one'
			id = 'subtract_one'
			value = 'Return to Plan Creation Page'
			onClick=\"reload2('$Population','$number_of_scales','$package')\"/></td></tr></table>";
}else{
	print "<p class='backButton'>";
		print "<input	type = 'button'
					name = ''
					id = 'backButton'
					value = 'Go To Plan Edit Page'
					onClick=\"backButton('$Population')\"/>\n";
	print "</p>";
}
print "<table class='noScroll local hover' border='1' bgcolor='white'>";
	$behavior_ar[]=null;
	$session2=mysqli_query($conn,$sql2);
	$scale_holder='';
		while($row2=mysqli_fetch_assoc($session2)){
			if(!array_search($row2['behavior'],$behavior_ar)){
				$behavior_ar[]=$row2['behavior'];
			}
		}
		$i=1;
		foreach($behavior_ar as $behavior_holder){
			$sql='sql'.$i;
			if(isset($residentkey)&&$residentkey!=null){
				$sql="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip' AND behavior='$behavior_holder' AND residentkey='$residentkey' ORDER BY trig";
			}else{
				$sql="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip' AND behavior='$behavior_holder' ORDER BY trig";
			}
			$row='row'.$i;
			$session='session'.$i;
			$session=mysqli_query($conn,$sql);
			if($behavior_holder){
				print"<tr><th colspan='7' align='center'><em>$behavior_holder Plans</em></th></tr>";
				print"<tr><th>Trigger</th><th>Intv. 1</th><th>Intv. 2</th><th>Intv. 3</th><th>Intv. 4</th><th>Intv. 5</th><th>Intv. 6</th></tr>";
					while($row=mysqli_fetch_assoc($session)){
						if($row['behavior']==$behavior_holder){
							print"<tr>";
							print"<td>$row[trig]</td>";
							print"<td>$row[intervention_1]</td>";
							print"<td>$row[intervention_2]</td>";
							print"<td>$row[intervention_3]</td>";
							print"<td>$row[intervention_4]</td>";
							print"<td>$row[intervention_5]</td>";
							print"<td>$row[intervention_6]</td>";
							print"</tr>";
						}
				}
				$i=$i+1;
			}
		}
		print"</table>";
		if(isset($package)&&$package){
			print "<h3 align='right'>Return to Plan Creation Page (click):";
			print "<input	type = 'button'
					name = 'subtract_one'
					id = 'subtract_one'
					value = 'Return to Plan Creation Page'
					onClick=\"reload2('$Population','$number_of_scales','$package')\"/></h3>\n";
		}else{
			print "<p class='backButton'>";
				print "<input	type = 'button'
							name = ''
							id = 'backButton'
							value = 'Go To Plan Edit Page'
							onClick=\"backButton('$Population')\"/>\n";
			print "</p>";
		}


}

?>
	</fieldset>
<?build_footer()?>
</body>
</html>
