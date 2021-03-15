<?ob_start()?>
<?session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type"content="text/html;
charset=utf-8"/>
<head>
<title>datalog.php </title>
<!--<link 	rel = "stylesheet"
		type = "text/css"
		href = "datalog.css">
-->
<style type="text/css">
</style>
</head>
<body><?
//if host, user or database password is changed change script on lines 28  54-58 of this file
//for local
$db = 'agitation';
$_SESSION['db'] = 'agitation';
//$host = 'michael-bottums-MacBook-Pro.local';
$host = 'localhost';
$db_user = 'abait';
$db_pwd = 'abait123!';
//for remote
// $db = 'agitation';
// $host = 'localhost';
// $db_user = 'admin';
// $db_pwd = 'abait';
$_SESSION['passwordcheck']='fail';
// $_SESSION['HOME']='index.php';
$_SESSION['HOME']='ABAIT_home_v2.php';
$_SESSION['favicon'] = 'favicon3.ico';
$_SESSION['provider_resident'] = 'Care Recipient';
$_SESSION['logout']='ABAIT_logout_v2.php'
$filename =$_REQUEST["submit"];

	if ($filename=="Submit Login ID"){
		$_SESSION['graphical_interface']=$_REQUEST['graphical_interface'];
		$password=$_REQUEST["password"];
		#if($mail!=""&&$password!=""){

		if($password!=""){
			#$conn=mysqli_connect($host,$db_user,$db_pwd);
			$conn=mysqli_connect($host,$db_user,$db_pwd,$db);
			if (mysqli_connect_errno()) {
    			printf("Connect failed: %s\n", mysqli_connect_error());
    			exit();
			}
			$password=mysqli_real_escape_string($conn, $password);
			$sql1="SELECT * FROM personaldata WHERE password='$password'";	
			#mysqli_select_db($db);
			$session1=mysqli_query($conn,$sql1);
			if($row1=mysqli_fetch_assoc($session1)){
				$_SESSION['SITE']='ABAIT Home';
				
					if($row1['accesslevel']=='globaladmin'&&$row1['password']==$password){
						$_SESSION['adminfirst']=$row1['first'];
						$_SESSION['adminlast']=$row1['last'];
						$_SESSION['cgfirst'] = '';
						$_SESSION['cglast'] = '';
						$_SESSION['personaldatakey']=$row1['personaldatakey'];
						$nextfile="adminhome.php";
						$_SESSION['passwordcheck']='pass';
						$_SESSION['privilege']='globaladmin';
						$_SESSION['Target_Population']='all';
						$_SESSION['house']='all';
					}
					elseif($row1['accesslevel']=='admin'&&$row1['password']==$password){
						$_SESSION['adminfirst']=$row1['first'];
						$_SESSION['adminlast']=$row1['last'];
                        $_SESSION['cgfirst'] = '';
                        $_SESSION['cglast'] = '';
						$_SESSION['personaldatakey']=$row1['personaldatakey'];
						$nextfile="adminhome.php";
						$_SESSION['home_page']='ABAIT_adminhome_v2';
						$_SESSION['passwordcheck']='pass';
						$_SESSION['privilege']='admin';
						$_SESSION['privilegekey']=$row1['privilegekey'];
						$_SESSION['Target_Population']=$row1['Target_Population'];
						$_SESSION['house']='all';
						//following makes an array of scale names
						$sql3="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
						$session3=mysqli_query($sql3,$conn);
						$scale_holder='';
							while($row3=mysqli_fetch_assoc($session3)){
								if($row3[scale_name]!=$scale_holder){
									$scale_array[]=$row3['scale_name'];
								}
								$scale_holder=$row3[scale_name];
							}
						$_SESSION[scale_array]=$scale_array;						
					}					
					elseif($row1['accesslevel']=='caregiver'&&$row1['password']==$password){
						$nextfile="ABAIT_caregiverhome_v2.php";
						$_SESSION['home_page']='ABAIT_caregiverhome_v2';
						$_SESSION['passwordcheck']='pass';
						$_SESSION['personaldatakey']=$row1['personaldatakey'];
						$_SESSION['password']=$password;
						$_SESSION['cgfirst']=$row1['first'];
						$_SESSION['cglast']=$row1['last'];
						$_SESSION['privilege']='caregiver';
						$_SESSION['privilegekey']=$row1['privilegekey'];
						$_SESSION['Target_Population']=$row1['Target_Population'];
						$_SESSION['house']=$row1['house'];
						//following makes an array of scale names
						$sql3="SELECT * FROM scale_table WHERE Target_Population='$_SESSION[Target_Population]'";
						$session3=mysqli_query($sql3,$conn);
						$scale_holder='';
							while($row3=mysqli_fetch_assoc($session3)){
								if($row3['scale_name']!=$scale_holder){
									$scale_array[]=$row3['scale_name'];
								}
								$scale_holder=$row3['scale_name'];
							}
						$_SESSION['scale_array']=$scale_array;
					}else{$nextfile=$_SESSION['HOME'];
				}
			}else{$nextfile=$_SESSION['HOME'];
		}	
	}
	else{$nextfile=$_SESSION['HOME'];
		}
	}
	if($_SESSION['passwordcheck']=='pass'){
			$_SESSION['hostname']=$host;
			$_SESSION['user']=$db_user;
			$_SESSION['mysqlpassword']=$db_pwd;
			$_SESSION['database']=$db;
	}
	header("Location:$nextfile");
	?>
	</body>
	</html>
