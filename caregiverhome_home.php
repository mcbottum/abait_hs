<?session_cache_limiter('nocache');
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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<script type='text/javascript'>
function validate_form()
{
	valid=true;
	var alertstring=new String("");
	
	var rb=radiobutton(document.form1.scale_name);
	if(rb==false){
		alertstring=alertstring+"\nSelect a Behavior Scale.";
		valid=false
	}	//end call for PRN radio button check
	
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message
	
function radiobutton(rb)
{
	var count=-1;
	for(var i=rb.length-1;i>-1;i--){
		if(rb[i].checked){
			count=i;
			i=-1;
		}
	}
	if(count>-1){
		return true;
	}else{
		return false;
	}
}//end radiobutton

	return valid;
}//end form validation function	

function reload(value) {
		//var val = form1.scale_name; //if use this, don't need attribute  does not work in IE
		//self.location='resident_scale.php?behavior='+var.value;

		self.location='resident_scale.php?behavior='+value;
}



</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
</head>
<body>
<fieldset>
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

<div id = "head">
<?
print $cgfirst."  ". $cglast. "'s Caregiver Home Page";
?>
</div>

<table>
	<tr>
	<td>
<div id="label">
	<h3>Please Select Your Choice</h3>
</div>
<div id="menu">
<form 	name = 'form1'
		onsubmit='return validate_form()'
		action = "resident_scale.php"
		method = "post">
<?
$_SESSION['residentkey']='';
$_SESSION['scale_name']='';
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
mysqli_select_db($_SESSION['database']);	
if($_SESSION['Target_Population']=='all'){
	$sql2="SELECT * FROM scale_table";
}else{
	$Population_strip=mysqli_real_escape_string($_SESSION['Target_Population'],$conn);
	$sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$sql_rm="SELECT * FROM resident_mapping WHERE personaldatakey='$_SESSION[personaldatakey]' AND PRN='1'";
	$sql_bmd="SELECT * FROM behavior_map_data WHERE personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' ";
	$session_rm=mysqli_query($sql_rm,$conn);
	$session_bmd=mysqli_query($sql_bmd,$conn);
}//end sql2 if else

$session2=mysqli_query($sql2,$conn);
	print"<ol type='I'>";

		print"<li><h4>Pre ABAIT Scale Education</h4></li>";
			print"<ul>";
				print "<li><a href='ABAIT_education.php'>ABAIT Education Module</a></li>";
				print "<li><a href='ABAIT_trigger_intervention_catalog.php'>Catalog of Scale Triggers and Interventions</a></li>";
				print "<li><a href='resident_fact_sheet.php'>Resident Fact Sheet</a></li>";
			print"</ul>";
			
		// print"<li><h4>Pre ABAIT Scale Set-Up/Non-Scale Behaviors</h4></li>";
		// 	print"<ul>";
		// 		print "<li><a href='chooseresident_map.php'>Two Week Resident Observation</a></li>";	
		// 	print"</ul>";
	

		print	"<li><h4>Log Behavior Episode (Choose Behavior Classification)</h4></li>\n";
			print"<dl>\n";
			print"<table>\n";
			print"<tr><td>\n";
				while($row=mysqli_fetch_assoc($session2)){
					$scale_name=str_replace(' ','_',$row['scale_name']);
					print "<p>";
					print "<div class='tooltip'>";
						print "<label>";
							print"<dt><input type = 'radio'
								name = 'scale_name'
								id='scale_name'
								onchange='reload(value)'
								value = $scale_name>$row[scale_name]</dt>\n";
							print "<span class='tooltiptext'>$row[scale_name_description]</span>";
						print "</label>";
					print "</div>";
					print "</p>";

					
					
				}
			print "</td>\n";
			// print "<td>\n";
			?>

<!-- 			 		<div id = "submit">	
			 			<input 	type = "submit"
						name = "submit"
						value = "Submit">
			 		</div> -->
			 <?
			// print"</td>\n";
			print "</tr></table>\n";
			print "</dl>\n";
		print	"<li><h4>Post Behavior Episode Analysis</h4></li>\n";
			print"<ul>";
				$PRN_effect=0;
				
				while($row_rm=mysqli_fetch_assoc($session_rm)){

					if (!$row_rm['post_PRN_observation']){
						$PRN_effect=1;
					}
				}
				if ($PRN_effect==0){
					while($row_bmd=mysqli_fetch_assoc($session_bmd)){
						if (!$row_bmd['post_PRN_observation']){
							$PRN_effect=1;
						}
					}
				}
				if($PRN_effect>0){
					print "<li style='color:red;'><a href='PRN_effect.php' style=color:red;>Record  Post PRN Observation</a></li>";
				}else{
					print "<li><a href='PRN_effect.php'> Post PRN Observations</a></li>";
				}
			print"</ul>";
		print"</ol>";
?>
</div>
	</form>
	
	</td>
	<td>
	<div id="logo3">
<!-- <img id="datamap" src = "ABAITflowchart_one_button500.png" ismap usemap="#datamap" style="border:none;"></a> -->
<img id="datamap" src = "ABAITflowchart2.png" ismap usemap="#datamap" style="border:none;"></a>
<map name="datamap">
<?
if($_SESSION['privilege']=='globaladmin'||$_SESSION['privilege']=='admin'){?>
<area shape="rect"  coords="245,133,372,267" href="caregiverhome.php"/>
<area shape="rect"  coords="245,281,372,342" href="caregiverhome.php"/>
<area shape="rect"  coords="245,354,372,494" href="caregiverhome.php"/>
<area shape="rect"  coords="113,527,236,592" href="caregiverhome.php"/>
<area shape="rect"  coords="246,527,369,592" href="caregiverhome.php"/>
<area shape="rect"  coords="379,527,503,592" href="caregiverhome.php"/>
<?}?>
<!-- <area shape="rect"  coords="7,177,115,307" href="ABAIT_quick_scales.php"/>
<area shape="rect"  coords="7,323,115,455" href="ABAIT_quick_scales.php"/>  -->
<area shape="rect"  coords="20,177,145,465" href="ABAIT_choose_behavior.php"/> 
</map>	
	</div>
	</td>
	</tr>
	</table>

</fieldset>
<?build_footer()?>

</body>
</html>