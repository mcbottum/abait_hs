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
</head>
<body>
<fieldset id="newclient" align='center'>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
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
		
		<form 	action = "adminhome.php"
				method = "post">
									
			<h2 align='center'><label>Resident Data</label></h2>

<?
		$first=$_REQUEST['first'];
		$last=$_REQUEST['last'];
		if(isset($_REQUEST['gender'])){
			$gender=$_REQUEST['gender'];
		}
		$action=$_REQUEST['action'];
		if(isset($_REQUEST['residentkey'])){
			$residentkey=$_REQUEST['residentkey'];
		}
		$house=$_REQUEST['sel_house'];
		if($house=='other'){
			$house=str_replace(' ','_',$_REQUEST['custom_house']);
		};
	
		//$year=$_REQUEST['year'];
		//$month=$_REQUEST['month'];
		//$day=$_REQUEST['day'];
		//$birthdate=$year.$month.$day;
		//$age=floor((time() - strtotime($birthdate))/31556926);
		$date=date("Y,m,d");
		$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);

		$privilegekey=$_SESSION['personaldatakey'];

		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']);

		if($first&&$last&&$gender&&$Target_Population&&$action=='Enroll'){
			$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
			mysqli_query($conn, "INSERT INTO residentpersonaldata VALUES(null,'$first','$last',null,'$gender','$privilegekey','$Target_Population','$house',null)");
			print "<h4 align='center'>$first $last has been entered as a new resident.</h4>\n";
			print "<h4 align='center'><a href='residentdata.php'>Return to Enroll New Resident Form</a></h4>\n";
		}else if($first&&$last&&$gender&&$Target_Population&&$action=='Update'){
			$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
			mysqli_query($conn, "UPDATE residentpersonaldata SET first='$first', last='$last', gender='$gender', Target_Population='$Target_Population', house='$house' WHERE residentkey='$residentkey'");
			print "<h4 align='center'>Resident $first $last has been Updated.</h4>\n";
			print "<h4 align='center'><a href='residentdata.php'>Return to Enter a new Resident Form</a></h4>\n";
			print "<h4 align='center'><a href='updateMembers.php'>Return to Update Members Form</a></h4>\n";


		}else{
			print"<h4 align='center'>Please return to resident data page, some information was missing.</h4>";
			print "<h4 align='center'><a href='residentdata.php'>New Resident Form</a></h4>\n";
			print "<h4 align='center'><a href='updateMembers.php'>Update Resident Data</a></h4>\n";

		}
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