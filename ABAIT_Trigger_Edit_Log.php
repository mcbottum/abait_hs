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

<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<script type="text/javascript">
	function backButton(target_population) {
		self.location='ABAIT_trigger.php';
	}
</script>
<style type="text/css">
	p.backButton{
		float: right;
	}
</style>
</head>
<body>
<fieldset>
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

<?

	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

	$sql="SELECT * FROM behavior_maps WHERE mapkey='$_SESSION[mapkey]'";
	$session=mysqli_query($conn,$sql);
	$row=mysqli_fetch_assoc($session);
	
	$trig=$_REQUEST['trig'];
	if(isset($_REQUEST['delete_scale'])){
		$del_scale=$_REQUEST['delete_scale'];
		mysqli_query($conn,"DELETE FROM behavior_maps WHERE mapkey = '$_SESSION[mapkey]'");
		print"<em><b>Behavior Scale for: $row[behavior] $row[trig] DELETED</b></em>\n";

	}else{
		for($i=1;$i<6;$i++){
			${'intervention'.$i}=$_REQUEST['intervention_'.$i];
		}
		print"<h3>Scale Update</h3>";
		if($trig){
			$sql6="UPDATE behavior_maps SET trig='$trig' WHERE mapkey='$_SESSION[mapkey]'";
			$retval = mysqli_query($conn,$sql6);
			print"<em>$row[trig]</em> replaced with <em>$trig</em> as updated trigger.<br>";
		}
		for($i=1;$i<6;$i++){
			if(${'intervention'.$i}){
				$intervention='intervention_'.$i;
				$sqlv='sql'.$i;
				$sqlv="UPDATE behavior_maps SET $intervention='${'intervention'.$i}' WHERE mapkey='$_SESSION[mapkey]'";
				$retval = mysqli_query($conn,$sqlv);
				print"<em>$row[$intervention]</em> replaced with <em>${'intervention'.$i}</em> as updated $intervention<br>";
			}
		}
	}//END ELSE
	
	print "<p class='backButton'>";
		print "<input	type = 'button'
					name = ''
					id = 'backButton'
					value = 'Back to Scale Edit Page'
					onClick=\"backButton('')\"/>\n";
	print "</p>";
?>	
</fieldset>
	<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
	</div>
	</body>
</html>
