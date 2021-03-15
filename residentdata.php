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

	if(document.form.first.value=="")
	{
		alertstring=alertstring+"\n-First Name-";
		document.form.first.style.background = "Yellow";
		valid=false;
	}else{
		document.form.first.style.background = "white";
	}//end first name
	
	if(document.form.last.value=="")
	{
		alertstring=alertstring+"\n-Last Name-";
		document.form.last.style.background = "Yellow";
		valid=false;
	}else{
		document.form.last.style.background = "white";
	}//end first name

	
	var rb=radiobutton(document.form.gender);
	if(rb==false){
		alertstring=alertstring+"\n-Gender-";
		valid=false
	}	//end gender check
	var Target_Home = document.getElementById("Target_Home")
	if(Target_Home.options[Target_Home.selectedIndex].value=="Choose*")
	{
		alertstring=alertstring+"\n-Resident Home-";
		document.form.Target_Home.style.background = "Yellow";
		valid=false;
	
	}else{
		document.form.Target_Home.background = "white";
	}//end Target_Home check


	var Target_Population = document.getElementById("Target_Population")
	if(Target_Population.options[Target_Population.selectedIndex].value=="")
	{
		alertstring=alertstring+"\n-Target Population-";
		document.form.Target_Population.style.background = "Yellow";
		valid=false;
	
	}else{
		document.form.Target_Population.background = "white";
	}//end Target Population check
	
	if (valid==false){
		alert("Please enter the following data;" + alertstring);
	}
	return valid;
}

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

function show( selTag ) {
	obj = document.getElementById("custom_house");
	customTrig = document.getElementById("custom_house");

	if ( selTag.value== 'other' ){
		customTrig.style.display = "block";
	} else {
		obj1.style.display = "none";
	}
}

</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
</head>
<body>
<fieldset id="newclient">
<div id="body" style="width:500px;margin: 0px auto 0px auto; text-align: left">
<?

if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION['privilege'],$cgfirst);

$sql="SELECT * FROM scale_table";
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$session=mysqli_query($conn,$sql);

# select distinct values from two tables
$home_sql="SELECT DISTINCT house FROM (
    SELECT house FROM personaldata
    UNION 
    SELECT house FROM residentpersonaldata
)t";

$home_session=mysqli_query($conn,$home_sql);

if(isset($_GET["rk"])){
		$residentkey=$_GET["rk"];
		$action="Update";
		$sql1=mysqli_query($conn,"SELECT * FROM residentpersonaldata WHERE residentkey=$residentkey");
		$data=mysqli_fetch_assoc($sql1);
	}else{
		$action="Enroll";
		$data='';
	}
?>
		
		<form 	
				name="form"
				onsubmit="return validate_form()"		
				action = "ABAIT_residentdata_log.php"
				method = "post">
<?									
	print"<div id = 'head'>";
		print"<h4> $action  Resident</h4>";
	print"</div>";
	
	
print"<h3 align='center'><label id='formlabel'>Resident Data Form (*required)</label></h3>";
		print"<tr><td colspan='100%'><div id = 'greyline'></div></td></tr>";
	print"<div id = 'dataform'>";

	print"<input type='hidden' name='action' value='$action'>";	
	
			print"<table class='form' align='center'>";
			
			print"<div id = 'name'>";	
			
			print"<tr><td>";
			print"<h3><label>Name</label></h3>";
			print"</td></tr>";
					print"<tr>";
						print"<td>";

							if($data){
								print"<input type='hidden' name='residentkey' value=$residentkey>";
								print"<input type = 'text'
										placeholder = $data[first]
										value = $data[first]
										name = 'first'/>";
							}else{
								print"<input type = 'text'
										placeholder = 'First Name*'
										name = 'first'/>";						
							}
						print"</td>";
					print"</tr>";
					print"<tr>";
						print"<td>";
							if($data){	
								print"<input	type = 'text'
									placeholder = $data[last]
									value = $data[last]
									name = 'last'/>";
							}else{
								print"<input	type = 'text'
										placeholder = 'Last Name*'
										name = 'last'/>";	
							}
						print"</td>";
					print"</tr>";
			print"</div>";

			print"<tr><td>";
				print"<h3><label>Gender*</label></h3>";
				if($data){
					if($data['gender']=='female'){
						print"<input type = 'radio'
								checked='checked'
								name = 'gender'
								value = 'female'/>Female";
						print"<input	type = 'radio'
								name = 'gender'
								id = 'male'
								value = 'male'/>Male";

					}else{
						print"<input type = 'radio'
								name = 'gender'
								value = 'female'/>Female";
						print"<input	type = 'radio'
								checked='checked'
								name = 'gender'
								id = 'male'
								value = 'male'/>Male";
						}
				}else{

					print"<input type = 'radio'
							name = 'gender'
							value = 'female'/>Female";
					print"<input	type = 'radio'
							name = 'gender'
							id = 'male'
							value = 'male'/>Male";
				}
			print"</td></tr>";
					
			print"<tr><td colspan='100%'><div id = 'greyline'></div></td></tr>";

			print"<div id = 'home' class = 'tooltip'>";
				print"<tr><td colspan=2>";
					print"<h3><label>Select or Enter Resident Home ID*</label></h3>";

				print"</td></tr>";
				print"<tr><td colspan=2>";
						
							print"<select class='selBox' name = 'sel_house' id='Target_Home' onchange='show(this)'>";
							if($data){
								print"<option value=$data[house]>$data[house]</option>";
							}else{
								print"<option value = 'Choose*'>Choose*</option>";
							}
									while($row=mysqli_fetch_assoc($home_session)){
										if($row['house']==$data['house']&&$row['house']!=''){
											print "<option selected value=$row[house]>$row[house]</option>";
										}else{
											print  "<option value=$row[house]>$row[house]</option>";
										}
									}
								print "<option value='other'>Enter NEW House</option>";
							print"</select>";
						print "<input type = 'text' name ='custom_house' id='custom_house' class='textBox selBox' style='display: none; background-color: GreenYellow' placeholder='Enter new house here' value=''  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";
				print"</td></tr>";
			print"</div>";	

			print"<div id = 'target_population'>";
				print"<tr><td colspan=2>";
					print"<h3><label>Select ABAIT Target Population*</label></h3>";

				print"</td></tr>";
				print"<tr><td colspan=2>";
			
						print"<select class='selBox' name = 'Target_Population' id = 'Target_Population'>";
							if($data){
								print"<option value = '$data[Target_Population]'>$data[Target_Population]</option>";
							}else{
								print"<option value = ''>Choose*</option>";
							}

							if($_SESSION['privilege']=='globaladmin'){
								$Target_Population="";
								while($row=mysqli_fetch_assoc($session)){
									if($row[Target_Population]!=$Target_Population){
										$Target_Population=str_replace(' ','_',$row[Target_Population]);
										$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
										print"<option value=$Population_strip>$row[Target_Population]</option>";
										$Target_Population=$row[Target_Population];
									}
								}
							}elseif($_SESSION['privilege']=='admin'){
								$Target_Population=str_replace(' ','_',$_SESSION['Target_Population']);
								//$Target_Population=$_SESSION['Target_Population'];
								$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
								$Population=$_SESSION['Target_Population'];
								print"<option value=$Population_strip>$Population</option>";
							}

						print"</select>";
				print"</td></tr>";
			print"</div>";
?>
									
			</table>
<p><div id = "greyline""></div></p>		
				<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit  Resident  Data"/>
				</div>
	</fieldset>
	</form>
<?build_footer()?>
</body>
</html>
