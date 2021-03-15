<?
include("ABAIT_function_file.php");
ob_start()?>
<?session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
	print $_SESSION['passwordcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
</head>
<body>
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION[privilege],$cgfirst);
?>
<fieldset>

<div id="menu">
<form 	name = 'form1'
		action = "resident_scale.php"
		method = "post">
<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());	
if($_SESSION[Target_Population]=='all'){
	$sql2="SELECT * FROM scale_table";
}else{
$sql2="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
}//end sql2 if else
mysqli_select_db($_SESSION['database']);
$session2=mysqli_query($sql2,$conn);
		print"<table class='center'>\n";
		print	"<tr><td colspan='3'><h3>Choose ABAIT Scale with which to log behavior episode</h3></td></tr>\n";

			print"<tr><td>\n";
			print"<table class='center' border='1' bgcolor='white'>\n";
			while($row=mysqli_fetch_assoc($session2)){
				$scale_name=str_replace(' ','_',$row[scale_name]);
				
				print"<tr><td><input type = 'radio'
					name = 'scale_name'
					id='scale_name'
					//$scale_name=str_replace(' ','_','motor agitation anxiety');
					value = $scale_name>$row[scale_name]</td></tr>\n";
			}
			print"</table></td></tr><tr><td>\n";
			?>
					<div id = "submit">	
						<input 	type = "submit"
						name = "submit"
						value = "Submit">
					</div>
			<?
				print"</td></tr></table>";

?>
</div>
	</form>

</fieldset>

<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
</body>
</html>