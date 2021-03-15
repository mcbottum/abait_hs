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
	valid=true;
	var alertstring=new String("");
	
	var rb=radiobutton(document.form1.resident_choice);
	if(rb==false){
		alertstring=alertstring+"\nSelect a Resident.";
		valid=false
	}	//end call for PRN radio button check
	
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message
	
function radiobutton(rb)
{
	if( typeof rb.length == 'undefined' && typeof rb != 'undefined' ) { 
	  return true;
	}else{
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
}//end radiobutton

	return valid;
}//end form validation function


function reload(value, id) {
		//var val = form1.scale_name; //if use this, don't need attribute  does not work in IE
		//self.location='resident_scale.php?behavior='+var.value;
		if(value=='new trigger'){
			self.location='choose_trigger.php?behavior='+value+'&&id='+id;
		}else{
			self.location='resident_scale.php?behavior='+value;
		}
}

</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
<style>
    table.local thead th{
        width:500px;
        padding: 0px !important;
    }
    table.local tbody{
        max-height: 400px;
    }

    table.local tbody td{
    	width:500px;
    	padding: 0px !important;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
</style>
</head>
<fieldset id="submit">
<body>
<div id="body">
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
<div id="head">
<?
if(isset($_GET['behavior'])){
	$sn=$_GET['behavior'];
}else{
	$sn=$_REQUEST['scale_name'];
}

$scale_name=str_replace('_',' ',$sn);
$_SESSION['scale_name']=$scale_name;

Print "<h4>Residents of $cgfirst $cglast</h4>";
?>
</div>

<form 	name = 'form1'
		onsubmit='return validate_form()'
		action = "choose_trigger.php" 
		method = "post">

<?
	$Target_Population=$_SESSION['Target_Population'];
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
	$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
	#$privilegekey=$_SESSION['privilegekey'];
	if($_SESSION['Target_Population']=='all'){
		$sql="SELECT * FROM behavior_maps WHERE behavior='$scale_name'";
		$session=mysqli_query($conn,$sql);
		$row=mysqli_fetch_assoc($session);
		$Target_Population=$row['Target_Population'];
		$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
	}
		$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' ORDER BY first";
		$session=mysqli_query($conn,$sql);

		print"<h3>Choose resident for $scale_name Scale</h3>";
	
			print "<table class='center scroll local hover' bgcolor='white'>";
				
					print "<thead>";
						print"<tr>";
							print"<th>";
								print"<p>";
									print"<span class='tab'>Click Choice</span><span class='tab'>First Name</span><span class='tab'>Last Name</span>";
								print"</p>";
							print"</th>";
						print"</tr>\n";
					print "</thead>";
					print "<tbody>";
						while($row=mysqli_fetch_assoc($session)){
							print"<tr>";
								print"<td><label>";
									print"<span class='tab'><input type = 'radio'
										name = 'resident_choice'
										value = $row[residentkey]></span>";	
									print "<span class='tab'>$row[first]</span>";
									print "<span class='tab'>$row[last]</span>";
								print"</label></td>";
							print "</tr>";
						
						}
						print "</tbody>";
			print "</table>";
					
?>
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Choice">

	</form>
	</fieldset>
	<?
	// build_footer()
	?>
	</body>
</html>