<?
include("ABAIT_function_file.php");
ob_start()?>
<?session_start();
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
	set_css()
?>
<style>
    td {
    	padding-top: 5px;
    	padding-bottom: 5px;
    }

	.center {
		width: 30%;
	}
	.btn.btn-lg {
	    background-color: #03DAC5;
	    border-radius: 10px;
	    font-size: 1.5em;
	    color: black;
	}
	.btn-lg:hover {
		background-color: #1FC4B4;
		box-shadow: 1px 1px 15px #888888;
	    border-style:solid;
	    border-width:1px;
	    color: black;
	}
	.footer_div {
		background-color: #F5F5F5;
	}
	.footer {
		color: black;
	}

</style>
</head>
<body class="container">
	<?			
		$names = build_page_pg();
	?>
	<h2 class="text-center mt-4">
		Resident Fact Sheet
	</h2>

	<?
	if(isset($_REQUEST['Population'])){
		$Population=str_replace('_',' ',$_REQUEST['Population']);
	}else{
		$Population=Null;
	}
	if($_SESSION['Target_Population']=='all'&&!$Population){
		$sql1="SELECT * FROM behavior_maps";
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
		$session1=mysqli_query($conn,$sql1);
		?>
		<form action="resident_fact_sheet.php" method="post">
		<?
		print"<h3><label>Select ABAIT Scale Target Population</label></h3>";
		?>
							<select name = 'Population'>
		<?
							print"<option value =''>Choose</option>";
								$Target_Pop[]="";
								while($row1=mysqli_fetch_assoc($session1)){
									if(!array_search($row1['Target_Population'],$Target_Pop)){
										$pop=str_replace(' ','_',$row1['Target_Population']);
										$pop=mysqli_real_escape_string($conn,$pop);
										print"<option value=$pop>$row1[Target_Population]</option>";
										$Target_Pop[]=$row1['Target_Population'];
									}
								}
							print"</select>";
	?>
				<div id="submit">
					<input 	type = "submit"
							name = "submit"
							value = "Submit Target Population">
				</div>
	<?
		}//end global admin if
	else{

	?>
		<form 	name = 'form1'
						action = "ABAIT_resident_fact_sheet_table_v2.php"
						method = "post">
	<?
		$scale_array[]=null;
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());

	if($_SESSION['Target_Population']!='all'){
		$Population=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
		$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
		$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population'";
		$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population'";

	}//end target population if
	else{
		$sql1="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population' order by first";
		$sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population'";
		$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population'";

	}//end else

	$_SESSION['Population_strip']=$Population;
	//$Population=str_replace(' ','_',$Population);
	//print"<input type='hidden' value='$Population' name='Target_Population'>";
	//print $Population;

	$scale_array=array();
		$session1=mysqli_query($conn,$sql1);
		$session3=mysqli_query($conn,$sql3);
		//following makes an array of scale names
		$scale_holder='';
			while($row3=mysqli_fetch_assoc($session3)){
				if(!in_array($row3['scale_name'],$scale_array)){
					$scale_array[]=$row3['scale_name'];
				}
				$scale_holder=$row3['scale_name'];
			}
		$_SESSION['scale_array']=$scale_array;

		$counterarray=$_SESSION['scale_array'];
			print"<h3 align='center'>Select Resident</h3>\n";
			print "<table align='center'><tr><td>";
				print "<table class='table-responsive table-hover' border='1' bgcolor='white'>";
					print "<thead>";
						print"<tr align='center'>\n";
							print"<th>Click Choice</th>\n";
							print"<th>Resident ID</th>\n";
							print"<th>First Name</th>\n";
							print"<th>Last Name</th>\n";
							// print"<th>Birth Date</th>\n";
							print"<th>Population DB</th>\n";
						print"</tr>\n";
					print "</thead>";
					print "<tbody>";
						while($row1=mysqli_fetch_assoc($session1)){
							print"<tr align='center'>\n";
							print"<td><input type = 'radio'
								name = 'residentkey'
								value = $row1[residentkey]></td>\n";
							print "<td> $row1[residentkey]</td>\n";
							print "<td> $row1[first]</td>\n";
							print "<td> $row1[last]</td>\n";
							// print "<td> $row1[birthdate]</td>\n";
							print "<td> $row1[Target_Population]</td>\n";
							print "</tr>\n";
						}
							print "<tr align='center'><td><input type = 'radio'
									name = 'residentkey'
									value ='all_residents'></td>\n";
							print "<td colspan='5'>All Resident Summary</td></tr>\n";
					print "</tbody>";
				print "</table>";
			
			print "</table>";


	?>
				<div id = "submit">
					<input 	type = "submit"
							name = "submit"
							value = "Submit Resident Choice">
				</div>
				<?
	}
	?>
	</form>
	<? build_footer_pg() ?>
</body>
</html>
