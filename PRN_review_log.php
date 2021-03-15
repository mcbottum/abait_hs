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
</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
print"<fieldset>";
build_page($_SESSION['privilege'],$cgfirst);
?>

		<form 	action = "adminhome.php"
						method = "post">
			<h2><label>PRN Report</label></h2>

<?
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

		$Population=$_REQUEST['population'];
		$request_residentkey=$_REQUEST['residentkey'];
		if($request_residentkey=='all'){
			$sql="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population'";
		}else{
			$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$request_residentkey'";
		}

		$session=mysqli_query($conn,$sql);
		//yikes, loop through all residents in population !!

		while($row=mysqli_fetch_assoc($session)){
			$residentkey=$row['residentkey'];
			$PRNreport_reskey='PRNreport_'.$residentkey;

				$PRN_report=$_REQUEST[$PRNreport_reskey];

				$date=date("Y,m,d");
				$privilegekey=$_SESSION['personaldatakey'];
				if($PRN_report){
					mysqli_query($conn,"INSERT INTO PRN_report VALUES(null,'$privilegekey','$residentkey','$Population','$date','$PRN_report')");
					echo mysqli_error($conn);
					print "<h3>PRN Review Report for ".$row[first]." ".$row[last]." has been logged.</h3>\n";
				}else{
					print"<h3 style='color:red'>Please return to PRN Report Page, PRN report was missing for ".$row[first]." ".$row[last].".<h3>";
				}
		}// END WHILE.
?>

				<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Return to Administrator Home"/>
				</div>
	</fieldset>
	</form>
<?build_footer()?>
</body>
</html>
