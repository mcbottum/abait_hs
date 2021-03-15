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
<script type="text/javascript">
	function backButton(location) {
		if(location=='PRN'){
			self.location='PRN_effect.php';
		}else if(location=='Home'){
			self.location='caregiverhome.php'
		}
		
	}
</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
<style>
	ul{
		list-style-type:none;
	}
</style>
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
<h3 align='center'><label>Medication Effect Report</label></h3>

<?
	$episode_choice_rm=$_REQUEST['episode_choice_rm'];
	$episode_choice=$_REQUEST['episode_choice'];
	$post_PRN_observation=$_REQUEST['post_PRN_observation'];
	if($post_PRN_observation=="Enter specific description of response to medication here."){
		$post_PRN_observation=null;
	}
	if($episode_choice_rm){
		$sql9="UPDATE resident_mapping SET post_PRN_observation='$post_PRN_observation' WHERE mapkey='$episode_choice_rm'";
	}elseif($episode_choice){
		$sql9="UPDATE behavior_map_data SET post_PRN_observation='$post_PRN_observation' WHERE behaviormapdatakey='$episode_choice'";
	}
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());	
	$retval = mysqli_query($conn,$sql9);
	if($post_PRN_observation){
		print "<h4 align='center'>  The observation: <em> $post_PRN_observation</em> has been logged</h4>";
	}else{
		print "<br><em>An observation has not been logged.  Please return to the previous page and enter a post medicated behavior observation.</em></br>";
	}
	if(! $retval ){
		die('Could not connect: ' . mysqli_error());
	}//end retval if
	print "<span><ul align='center'>";
		print "<li>";
			print "<div id='submit'>";
				print "<input	type = 'button'
							name = ''
							value = 'Log Another Comment'
							onClick=\"backButton('PRN')\"/>\n";
			print "</div>";
		print "</li>";

	print"</ul></span>";

?>
</fieldset>
<?build_footer()?>
</body>
</html>