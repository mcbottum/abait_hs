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
<script>
function reload(form){
var val=form.residentkey.options[form.residentkey.options.selectedIndex].value;
self.location='ABAIT_trigger.php?residentkey='+val;

}
</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>
	fieldset{
		background-color: #fdebdf !important;
	}
	table td, table th{
		padding-left:10px;
		width:140px;
	}
	label {
			/* whatever other styling you have applied */
			width: 100%;
			display: inline-block;
	}
	p.backButton{
		float: right;
	}
</style>
</head>

<body>
<fieldset>
<div id="body" style="width:978px;margin: 0px auto 0px auto; text-align: left">
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
<div id = "head"><h4>
Choose Resident for Trigger/Intervention Edit
</h4></div>
<form 	action = "ABAIT_trigger_edit.php"
method = "post">

<?
if(isset($_REQUEST['residentkey'])){
	$residentkey=$_REQUEST['residentkey'];
}else{
	$residentkey='';
}
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);

if(!$residentkey){
	if($_SESSION['privilege']=='globaladmin'){
		$_SESSION['sql']="SELECT * FROM residentpersonaldata ORDER By first";
	}elseif($_SESSION['privilege']=='admin'){
		$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' ORDER By first";
	}//end get residents elseif

		$session=mysqli_query($conn,$_SESSION['sql']);
		while($row=mysqli_fetch_assoc($session)){
			$residentkey_array[]=$row['residentkey'];
		}
		$_SESSION['residentkey_list']=implode(',',$residentkey_array);	
}
elseif($residentkey){
	$sql3="SELECT * FROM behavior_maps WHERE residentkey IN ($_SESSION[residentkey_list]) ORDER By creation_date";	
	$session3=mysqli_query($conn,$sql3);
}

print"<table class='center' width='100%' align='center'>";
	print"<tr><td align='center'>";
		print "<select name='residentkey' onchange=\"reload(this.form)\"><option value=''>Select a Resident</option>"."<BR>";
		$session1=mysqli_query($conn,$_SESSION['sql']);
			while($row1= mysqli_fetch_array($session1)) { 
					if($row1['residentkey']==$residentkey){
						print "<option selected value=$residentkey>$row1[first] $row1[last]</option>";
					}else{
						print  "<option value=$row1[residentkey]>$row1[first] $row1[last]</option>";
					}
				}
		print "</select>";	
	print"</td></tr>";
	if($residentkey){
		$date=date('Y-m-d');
		$date_start=date('Y-m-d',(strtotime('- 30 days')));
		print"<tr><td align='center'><h3>Red background indicates trigger created more than 30 days ago.</td></tr>";
		print "<table class='center noScroll local hover' border='1' bgcolor='white'>";
						print "<tr>\n";
							print "<th>Select</th>\n";
							print "<th>Trigger Name</th>\n";
							print "<th>Behavior Class</th>\n";
							print "<th>Date Created</th>\n";
						print "</tr>\n";
				while($row3=mysqli_fetch_assoc($session3)){
					$trig=str_replace(' ','_',$row3['trig']);
					if($residentkey==$row3['residentkey']){
						if($date_start>$row3['creation_date']){
							$bgcol='red';
						}else{$bgcol='white';
						}
							print "<tr>\n";
								print "<td align='center'><label><input type = 'radio'
									name = 'mapkey'
									value = $row3[mapkey]></label></td>\n";
								print "<td> $row3[trig]</td>\n";
								print "<td> $row3[behavior]</td>\n";
								print "<td bgcolor='$bgcol'> $row3[creation_date]</td>\n";
							print"</tr>";
					}//end residentkey if
				}
		print"</table>";
	}
	
	print"</td></tr></table>";
						
?>
			<div id = "submit">	
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident/Trigger Choice">
			</div>
	</fieldset>
	</form>
<?build_footer()?>
</div>
</body>
</html>
