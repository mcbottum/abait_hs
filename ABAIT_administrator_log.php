<?
ob_start();
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
<fieldset id="newclient">
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
									
			<h2 align='center'><label>Personal Data</label></h2>
	
<?
		$newpassword1=$_REQUEST['password1'];
		$newpassword2=$_REQUEST['password2'];
		$action=$_REQUEST['action'];
		if($newpassword1==$newpassword2){
			$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']);
			$newpassword1=mysqli_real_escape_string($conn,$newpassword1);
			$newpassword2=mysqli_real_escape_string($conn,$newpassword2);
			$sql1=("SELECT * FROM personaldata WHERE password='$newpassword1'");	
			$session1=mysqli_query($conn,$sql1);

			if(isset($_REQUEST['key'])){
				$key=$_REQUEST['key'];
			}
			if($action=="Update" or !$row1=mysqli_fetch_assoc($session1)){

					$accesslevel="admin";
					$password=$newpassword1;
					$first=$_REQUEST['first'];
					$last=$_REQUEST['last'];
					/*
					$gender=$_REQUEST['gender'];
					$_SESSION['gender']=$gender;
					$year=$_REQUEST['year'];
					$month=$_REQUEST['month'];
					$day=$_REQUEST['day'];
					$birthdate=$year.$month.$day;
					$street_address=$_REQUEST['street_address'];
					$city=$_REQUEST['city'];
					$state=$_REQUEST['state'];
					$zipcode=$_REQUEST['zipcode'];
					$phone=$_REQUEST['phone'];
					$email=$_REQUEST['email'];
					*/
					$Target_Population=str_replace('_',' ',$_REQUEST['Target_Population']);
					$Target_Population=mysqli_real_escape_string($conn,$Target_Population);
					$date=date("Y,m,d");
					$privilegekey=$_SESSION['personaldatakey'];
					$house='all';
					$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
					if($action=='Enroll'){
						mysqli_query($conn,"INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last',null,null,null,null,null,null,null,null,null,'$privilegekey','$Target_Population','$house')");

						print "<h4 align='center'>$first $last has been entered as a new Administrator.</h4>\n";
						print "<h4 align='center'><a href='administratordata.php'>Return to Enroll New Administrator Form</a></h4>\n";
					}else if($action=='Update'){
						mysqli_query($conn,"UPDATE personaldata SET first='$first', last='$last', date='$date', password='$password' WHERE personaldatakey='$key'");
						print "<h4 align='center'>Administrator $first $last has been Updated.</h4>\n";
						print "<h4 align='center'><a href='updateMembers.php'>Return to Update Members Form</a></h4>\n";	
					}
					// mysqli_query("INSERT INTO personaldata VALUES(null,'$date','$password','$accesslevel','$first','$last','$gender','$birthdate','$street_address','$city','$state','$zipcode','$phone','$email','$privilegekey','$Target_Population')");

					mysqli_close($conn);
				}			
					else{
						print "<h4 align='center'>Password taken, Please choose another.</h4>";
						print "<h4 align='center'><a href='administratordata.php'>Return to Enroll New Administrator Form</a></h4>\n";
						print "<h4 align='center'><a href='updateMembers.php'>Return to Update Members Form</a></h4>\n";
				}
		}				
		if ($newpassword1!=$newpassword2){
			print "<h4>Please re-enter passwords, they did not match.</h4>";
			print "<h4 align='center'><a href='administratordata.php'>Return to Enroll New Administrator Form</a></h4>\n";
			print "<h4 align='center'><a href='updateMembers.php'>Return to Update Members Form</a></h4>\n";
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
