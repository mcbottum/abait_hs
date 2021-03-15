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

	var rb=radiobutton(document.form1.makemap);
	if(rb==false){
		alertstring=alertstring+"\n-Click on a Behavior Typle to Create-";
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
		width:160px;
	}
	table.local tbody td{
		width:160px;
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
<body>
<div id="body" style="margin: 0px auto 0px auto; text-align: left">
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
<div id="head"><h3>
Resident List of Created And Not Yet Created Behavior Plans
</h3></div>

<?
if(isset($_GET['tp'])){
	$Population=str_replace('_',' ',$_GET['tp']);
}elseif(isset($_REQUEST['Population'])){
	$Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
	$Population='';
}
//print $_SESSION[Target_Population];
//print $Population;
//print "hi";
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$Population_strip=mysqli_real_escape_string($conn,$Population);
if($_SESSION['Target_Population']=='all'&&!$Population){
	$sql1="SELECT * FROM residentpersonaldata order by first";
	$sql3="SELECT * FROM scale_table";
	//print "bye";
	$session3=mysqli_query($conn,$sql3);
	$row=mysqli_fetch_assoc($session3);
	?>
<form 	name='form'
		onsubmit='return validate_form()'
		action="chooseresident_for_map_review.php"
		method="post">
	<?
	print"<h3><label>Select ABAIT Plan Target Population</label></h3>";
	?>
						<select name = 'Population'>
	<?
						print"<option value =''>Choose</option>";
							$Target_Pop="";
							while($row=mysqli_fetch_assoc($session3)){
								if($row[Target_Population]!=$Target_Pop){
									$pop=str_replace(' ','_',$row[Target_Population]);
									$pop_strip=mysqli_real_escape_string($conn,$pop);
									print"<option value=$pop_strip>$row[Target_Population]</option>";
									$Target_Pop=$row[Target_Population];
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

?>
	<form 	name = 'form1'
			action = "ABAIT_create_map.php"
			onsubmit='return validate_form()'
			method = "post">
<?
	$scale_array[]=null;

if($_SESSION['Target_Population']!='all'){
	$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' ORDER by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$sql4="SELECT * FROM resident_mapping WHERE Target_Population='$Population_strip' ORDER by date";
	$Population=$Population_strip;
}//end target population if
else{
	$Population_strip=mysqli_real_escape_string($conn,$Population);
	$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' ORDER by first";
	$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
	$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$sql4="SELECT * FROM resident_mapping WHERE Target_Population='$Population_strip' ORDER by date";
	$_SESSION['Population']=$Population_strip;

}//end else
$scale_array=array();
	$session1=mysqli_query($conn,$sql1);
	$session3=mysqli_query($conn,$sql3);
	//$session4=mysqli_query($sql4,$conn);
	//following makes an array of scale names
	$scale_holder='';
		while($row3=mysqli_fetch_assoc($session3)){
			if(!in_array($row3['scale_name'],$scale_array)){
				$scale_array[]=$row3['scale_name'];
			}
			$scale_holder=$row3['scale_name'];
		}
	$_SESSION['scale_array']=$scale_array;
	//$session2=mysqli_query($sql2,$conn);
	//print"<h4>Residents from <em>$Population</em> Population Database</h4>\n";
	print "<table width='100%'>\n";//table for more info copy this line
	print"<tr><td><h3 align='center'>Select Resident's Behavior to Review</h3></td>\n";
	print"<td align='right'>\n";
?>
<input style='float:inline-right' type='submit' value='Info' onClick="alert('This list presents created and not yet created behavior maps.  Clickable buttons appear where maps have not yet been created.');return false">
<?
				print "</td>";
				print "</tr>";
				print "<tr><td  align='center' style='color:red'><h4>Red Indicates Un-mapped, Un-checked Behavior recorded <em>after</em> Last Plan Creation</h4></td></tr>\n";
				print "<tr><td colspan='2'>";//table in table data for more info
					print "<table class='scroll local hover'  border='1' bgcolor='white'>";
						print "<thead>";
							print"<tr align='center'>\n";
								print"<th>First Name</th>\n";
								print"<th>Last Name</th>\n";
								// print"<th>Birth Date</th>\n";
								foreach($scale_array as $sa){
									print"<th>$sa</th>\n";
								}
							print"</tr>\n";
						print "</thead>";
						print "<tbody>";
							$residentcount=0;
							while($row1=mysqli_fetch_assoc($session1)){
								$residentcount=$residentcount+1;
								print"<tr align='center height='40px'>\n";
								//print "<td> $row1['residentkey']</td>\n";
								print "<td align='center'> $row1[first]</td>\n";
								print "<td align='center'> $row1[last]</td>\n";
								// print "<td> $row1[birthdate]</td>\n";
								$cellcounter=0;
									for($i=0;$i<count($scale_array);$i++){
										print"<td align='center'>\n";
											$counter=0;
											$episode_checked = True;

											$creation_counter=0;
											$creation_date=null;
											$row4_date=null;
											$session2=mysqli_query($conn,$sql2);
											$session4=mysqli_query($conn,$sql4);
											while($row2=mysqli_fetch_assoc($session2)){
												if($row1['residentkey']==$row2['residentkey']&&$row2['behavior']==$scale_array[$i]){
													if(strtotime($creation_date)<strtotime($row2['creation_date'])){
														$creation_date=$row2['creation_date'];
													}
													if($counter==0){
														print "<label>";
															print"<input type = 'radio'
															id='makemap';
															name = 'makemap'
															value = '$row1[residentkey]_$scale_array[$i]'/>";
														print "</label>";

														$counter++;
													}
												}
											}//end row2 while
											while($row4=mysqli_fetch_assoc($session4)){
												if($row1['residentkey']==$row4['residentkey']&&$row4['behavior']==$scale_array[$i]){
													if(strtotime($row4_date)<strtotime($row4['date'])&&$row4['date']){
														$row4_date=$row4['date'];
														if(!$row4['scale_created']){
															$episode_checked = False;
														}
													}

														if($counter==0){
															print "<label>";
																print"<input type = 'radio'
																id='makemap';
																name = 'makemap'
																value = '$row1[residentkey]_$scale_array[$i]'/>";
															print "</label>";
															$counter++;
														}

												}
											}//end row4 while

											if(strtotime($row4_date)>strtotime($creation_date)&&$counter==1&&$creation_date!=null){
												if(!$episode_checked){
													print"<span style='color:red;'><br><b>Un-Mapped Episodes</b></span>\n";
													print"<span style='color:red;'><br><b>Map Created:</b></span>\n";
													print"<span style='color:red;'><br><b>$creation_date</b></span>\n";
												}else{
													print"<span style='color:black;'><br><b>Checked Episodes</b></span>\n";
													print"<span style='color:black;'><br><b>Map Created:</b></span>\n";
													print"<span style='color:black;'><br><b>$creation_date</b></span>\n";
												}

												$counter++;
											}
											elseif(strtotime($row4_date)<=strtotime($creation_date)&&$counter==1){
												print"<span style='color:black;'><br><b>Map Created:</b></span>\n";
												print"<span style='color:black;'><br><b>$creation_date</b></span>\n";
												$counter++;
											}
											elseif($counter>0){
												// print"<input type = 'radio'
												// id='makemap';
												// name = 'makemap'
												// value = '$row1[residentkey]_$scale_array[$i]'/>";
												print"<span style='color:black;'><br><b>Un-Mapped Episodes</b>\n";
											}
											if($counter==0){
												print "<label>";
												print"<input type = 'radio'
												id='makemap';
												name = 'makemap'
												value = '$row1[residentkey]_$scale_array[$i]'/>";
												print "</label>";
												print"<span style='color:black;'><br><em>No Recorded Episodes</em>\n";
											}
										print"</td>\n";
									}//end for scale array
								print "</tr>\n";
							}//end while row1
							$_SESSION['residentcount']=$residentcount;
						print "</table>";
					print "</td>";//end td for table COPY FROM HERE
				print "</tbody>";
			print "</table>";
//end table notation for more data  TO HERE

?>
			<div id="submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident for Behavior Review">
			</div>

	</form>
<?
	}//end get residents elseif for local admin
	$_SESSION['Population']=$Population;
?>
	</fieldset>
	<?build_footer()?>
	</body>
</html>
