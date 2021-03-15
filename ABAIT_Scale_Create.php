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
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
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
	
	if(document.form1.Target_Population.value=="")
	{
		alertstring="\nTarget Population;";
		document.form1.Target_Population.style.background = "Yellow";
		valid=false;
	}else{
		document.form1.Target_Population.style.background = "white";
	}
	if(document.form1.scale_number.selectedIndex=="0"){
		alertstring=alertstring+"\nnumber of Scales.";
		document.form1.scale_number.style.background = "Yellow";
		valid=false;
	}
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}
	return valid;
}
</script>
<style>
	input[type="text"]{
		background-color: lightgreen;
	}

</style>

<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css"/>

</head>
<body>
<fieldset>
<div id="body" style="margin: 0px auto 0px auto; text-align: left">
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

<form 	name = 'form1'
		action = "ABAIT_Scale_Create_Log.php"
		method = "post"
		onsubmit="return validate_form ( );">
<div id='head'>
ABAIT Scale Creation Page
</div>
<h4 align="center"><em>This page will allow the creation of Scales for new target populations.</em></h4>
<h4 align='center'><em>Existing Scale information will not be overwritten.</em></h4>

<table class='table center hover' border='1' bgcolor='white'>
<tr><th> Existing Target Populations</th>
<th> Existing Scales</th></tr>
<?
	$sql="SELECT * FROM scale_table";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

	$session=mysqli_query($conn,$sql);
	$Target_Population="";
while($row=mysqli_fetch_assoc($session)){
	print"<tr><td>";
	if($row['Target_Population']!=$Target_Population){
		print$row['Target_Population'];
		$Target_Population=$row['Target_Population'];
	}//end if
	print"</td>";
	print"<td>$row[scale_name]</td></tr>";
}//end while
?>
</table>
<p>
<table  class='table center hover' border='1' bgcolor='white'>
<tr><th>Please Enter the Following Information</th></tr>
<tr><td>New Target Population
<input type = 'text' width='7' name =Target_Population size='50'/>
</td></tr>
<tr><td>Number of Scales (4 recommended)
	<select name = "scale_number" id = "scale_number">
		<option value = "">Choose</option>
		<option value = "1">1</option>
		<option value = "2">2</option>
		<option value = "3">3</option>
		<option value = "4">4</option>
		<option value = "5">5</option>
		<option value = "6">6</option>
	</select>
</td></tr>
</table>
<p>

			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit New Scale Information"/>
			</div>

</form>
</fieldset>
	<?build_footer()?>
	</div>
	</body>
</html>
