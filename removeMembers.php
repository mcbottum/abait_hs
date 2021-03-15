<?session_start();
include("ABAIT_function_file.php");
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
date_default_timezone_set('America/Chicago');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?> -->
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
	
	var rb=radiobutton(document.form1.makemap);
	if(rb==false){
		alertstring=alertstring+"\n-Click on a Behavior Scale to Create-";
		//document.form.hour.style.background = "Yellow";
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

</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>

    table.local thead th{
        width:250px;
        text-align:center;
    }

    table.local tbody td{
        width:250px;
        text-align:center;
    }
    span.tab{
        width:75px !important;
    }

    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
</style>

</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
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


<?	
if(isset($_REQUEST['Population'])){
	$Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
	$Population='';
}
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

$Population_strip=mysqli_real_escape_string($conn,$Population);
$success = null;

if(isset($_REQUEST['resident_count'])){
	print"<div id='head'><h3>Remove Program Members</h3></div>";

	if(isset($_REQUEST['delete_resident'])){
		$checked_residents=$_REQUEST['delete_resident'];
		foreach ($checked_residents as $delete_resident){
			$success = mysqli_query($conn,"DELETE FROM residentpersonaldata WHERE residentkey=$delete_resident");
		}
	}
	if(isset($_REQUEST['delete_caregiver'])){
		$checked_caregiver=$_REQUEST['delete_caregiver'];
		foreach ($checked_caregiver as $delete_caregiver){
			$success = mysqli_query($conn,"DELETE FROM personaldata WHERE personaldatakey=$delete_caregiver");
		}
	}
	if(isset($_REQUEST['delete_admin'])){
		$checked_admin=$_REQUEST['delete_admin'];
		foreach ($checked_admin as $delete_admin){
			$success = mysqli_query($conn,"DELETE FROM personaldata WHERE personaldatakey=$delete_admin");
		}
	}
	if($success){
		print"<h4 align='center'>Member(s) Successfully Removed from ABAIT</h4>";
		print "<h4 align='center'><a href='removeMembers.php'>Return to Remove Members Form</a></h4>\n";
	}else{
		print"<h4>Member(s) Successfully Deleted</h4></div>";
	}
	
}else{

		?>
	<div id="head">
		<h3>Remove Program Members</h3>
	</div>
	<h5 align='center'>Check all that apply</h5>

		<form 	name='form'
				onsubmit='return validate_form()'
				action="removeMembers.php" 
				method="post">
		<?

		if($_SESSION['Target_Population']=='all'&&!$Population){
			$residentpersonaldata=mysqli_query($conn,"SELECT * FROM residentpersonaldata order by first");
			$caregiverdata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='caregiver' order by first");
			$admindata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='admin' order by first");
		}else if($_SESSION['privilege']=='admin'){
            $residentpersonaldata=mysqli_query($conn,"SELECT * FROM residentpersonaldata order by first");
            $caregiverdata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='caregiver' order by first");
            $admindata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='admin' order by first");            
        }
		print "<table align='center' cellpadding='10'>";
			print"<tr align='center'>";
				print"<th style='background-color:transparent'><label>Enrolled Residents</label></th>";
				print"<th style='background-color:transparent'><label>Enrolled Care Providers</label></th>";
				print"<th style='background-color:transparent'><label>Enrolled Administrators</label></th>";
			print"</tr>";

			print "<tr valign='top'>";
				print"<td>";
					print"<table class=' table scroll hover local'  bgcolor='white' cellpadding='5'>";
						print "<thead>";
							print"<tr><th>";
								print"<span class='tab'>First Name</span>";
								print"<span class='tab'>Last Name</span>";
								print"<span class='tab'>Check</span>";
							print"</th></tr>";
						print "</thead>";
						print "<tbody>";
							$resident_count=0;
							while($resident=mysqli_fetch_assoc($residentpersonaldata)){
								
								print"<tr>";
									print"<td><p><label>";
										print"<span class='tab'>$resident[first]</span>";
										print"<span class='tab'>$resident[last]</span>";
										print"<span class='tab'>";
										
										print "<input	type = 'checkbox'
											name = 'delete_resident[]'
											id = 'delete_resident'
											value = '$resident[residentkey]'
											class = 'delete_checkbox'/></span>";
									print "</label></p></td>";
								print"</tr>";
								$resident_count++;
							}
						print "</tbody>";
					print"</table>";
				print"</td>";

				print"<td>";
					print"<table class=' table scroll hover local'  bgcolor='white' cellpadding='5'>";

						print "<thead>";
							print"<tr>";
								print"<th>";
									print"<span class='tab'>First Name</span>";
									print"<span class='tab'>Last Name</span>";
									print"<span class='tab'>Check</span>";
								print"</th>";
							print"</tr>";
						print "</thead>";
						print "<body>";
							$caregiver_count=0;
							while($caregiver=mysqli_fetch_assoc($caregiverdata)){
								
								print"<tr>";
									print"<td><p><label>";
										print"<span class='tab'>$caregiver[first]</span>";
										print"<span class='tab'>$caregiver[last]</span>";
										print"<span class='tab'>";

											print "<input	type = 'checkbox'
												name = 'delete_caregiver[]'
												id = 'delete_caregiver'
												value = '$caregiver[personaldatakey]'
												class = 'delete_checkbox'/>";
										print"</span>";
									print "</label></p></td>";
								print"</tr>";
								$caregiver_count++;
							}
						print "</body>";
					print"</table>";
				print"</td>";

				print"<td>";
					print"<table class=' table scroll hover local'  bgcolor='white' cellpadding='5'>";
						print "<thead>";
							print"<tr>";
								print"<th>";
									print"<span class='tab'>First Name</span>";
									print"<span class='tab'>Last Name</span>";
									print"<span class='tab'>Check</span>";
								print"</th>";
							print"</tr>";
						print "</thead>";
						print "<tbody>";
							$admin_count=0;
							while($admin=mysqli_fetch_assoc($admindata)){
								
								print"<tr>";
									print"<td><p><label>";
										print"<span class='tab'>$admin[first]</span>";
										print"<span class='tab'>$admin[last]</span>";
										
										print "<span class='tab'>";
										print "<input	type = 'checkbox'
											name = 'delete_admin[]'
											id = 'delete_admin'
											value = '$admin[personaldatakey]'
											class = 'delete_checkbox'/>";
										print"</span>";
									print "</label></p></td>";

								print"</tr>\n";
								$admin_count++;
							}
						print "</body>";
					print"</table>";
				print"</td>";
			print "</tr>";
		print"</table>";
		print"<input type='hidden' name='resident_count' value='$resident_count'>";
		print"<input type='hidden' name='caregiver_count' value='$caregiver_count'>";
		print"<input type='hidden' name='admin_count' value='$admin_count'>";
		?>
					<div id="submit">	
						<input 	type = "submit"
								name = "submit"
								value = "Remove!">
					</div>
				</form>

	<?
}//END ELSE FOR ISSET SUBMITTED FORM




	?>
	</fieldset>
	<?build_footer()?>
	</body>
</html>