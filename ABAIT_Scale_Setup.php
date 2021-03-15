<?session_start();
include("ABAIT_function_file.php");
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

	valid1=true;
	var alertstring=new String("");

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
}
var rb=radiobutton(document.form1.setup);
if(rb==false){
	alertstring="\na Set-up Action\nand Target Population;";
	valid1=false
}
/*var Target_Populationcheck=radiobutton(document.form1.Target_Population);
if(Target_Populationcheck==false){
	alertstring="\nselect a Target Population Action;";
	valid1=false
}*/
if(valid1==false){
	alert("Please be sure to select;" + alertstring);
}
return valid1
}//end of main function

</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">

</head>
<body>
<fieldset class="submit">
<div id="body">
<?
if($_SESSION['cgfirst']!=''){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION['privilege'],$cgfirst);

	$sql="SELECT * FROM scale_table";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
	$Target_Population="";
?>
<form 	name = "form1"
		action = "ABAIT_Scale_Edit.php"
		onsubmit="return validate_form()"
		method = "post">


<h2>ABAIT Scale Set Up Page</h2>
<h4>Please Note, Any changes made in Set Up may affect how data is querried, logged or presented.</h4>
<h4>Please proceed with care.</h4>
<table>
<tr><td>
<table border='1' bgcolor='white'>

<tr><th>Please Select Set Up Action</th></tr>
	<tr><td><input type = 'radio'
					name = 'setup'
					id='setup'
					value = 'scale1'>Edit Scale Choices
	</td></tr>
	<tr><td><input type = 'radio'
					name = 'setup'
					id='intensity'
					value = 'scale2'>Edit Behavior Intensity Choices on ABAIT Scales
	</td></tr>
	<tr><td><input type = 'radio'
					name = 'setup'
					id='setup'
					value = 'scale3'>Edit Behavior Classifications for pre Scale Screening
	</td></tr>

</table></td>
<?
		print "<td><table align='right' border='1' bgcolor='white'>";
		print "<tr><th>Please Choose ABAIT Scale Target Population</th></tr>";
			while($row=mysqli_fetch_assoc($session)){
					if($row['Target_Population']!=$Target_Population){
						$Target_Population=str_replace(' ','_',$row['Target_Population']);
						$Target_Population=mysqli_real_escape_string($Target_Population,$conn);
						print "<tr><td><input type = 'radio' 
									name ='Target_Population'
									id='Target_Population'
									value= $Target_Population>$row[Target_Population]</td></tr>\n";			
					}
				$Target_Population=$row['Target_Population'];
			}//end while
		print "</table></td></tr>";
?>
</table>
			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Choice for ABAIT Scale Edit">
			</div>
</fieldset>
	</form>
	<?build_footer()?>
	</div>
	</body>
</html>
