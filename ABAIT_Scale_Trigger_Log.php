<?
include("ABAIT_function_file.php");session_start();session_start();
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
<div id="body">
<?
$first=$_SESSION['adminfirst'];
$last=$_SESSION['adminlast'];
$i=$_SESSION['i'];
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
build_page($_SESSION[privilege],$cgfirst);
?>

<fieldset class="submit">
<?
$filename =$_REQUEST["submit"];	
	$Population_strip=mysqli_real_escape_string($_SESSION[Target_Population],$conn);			
	$sql="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
	
	if($filename=="Submit Scale Edit"){
		print"<h3>$_SESSION[Target_Population] ABAIT Scale Name Edit</h3>";
		while($row=mysqli_fetch_assoc($session)){
			${'scale_name_'.$row[scale_name_key]}=$_REQUEST['scale_name_'.$row[scale_name_key]];
				if(${'scale_name_'.$row[scale_name_key]}){
					$sql2="UPDATE scale_table SET scale_name='${'scale_name_'.$row[scale_name_key]}' WHERE scale_name_key=$row[scale_name_key]";
					mysqli_select_db($_SESSION['database']);	
					$retval = mysqli_query( $sql2, $conn);
					print "<br><em>$row[scale_name]</em> replaced with <em>${'scale_name_'.$row[scale_name_key]}</em> in the  $_SESSION[Target_Population] Scales</br>";
					if(! $retval ){
					die('Could not connect: ' . mysqli_error());
					}//end retval if
				}//end scale_name request if	
		}//end while	
	}//end Submit Scale Edit

	elseif($filename=="Submit Intensity Edit"){
		print "<h3> Comment Edit for the $_SESSION[Target_Population] ABAIT Scales</h3>";
		while($row=mysqli_fetch_assoc($session)){
			for($j=1;$j<6;$j++){
				${'comment_'.$j.'_'.$row[scale_name_key]}=$_REQUEST['comment_'.$j.'_'.$row['scale_name_key']];
					if(${'comment_'.$j.'_'.$row[scale_name_key]}){
						$sql2="UPDATE scale_table SET comment_$j='${'comment_'.$j.'_'.$row[scale_name_key]}' WHERE scale_name_key=$row[scale_name_key]";
						mysqli_select_db($_SESSION['database']);	
						$retval = mysqli_query( $sql2, $conn);
						print "<br><em>Comment $j</em> for the <em>$row[scale_name]</em>  scale has been changed to: ${'comment_'.$j.'_'.$row[scale_name_key]}</br>";
						if(! $retval ){
						die('Could not connect: ' . mysqli_error());
						}//end retval if
					}//end comment if
			}//end for if
		}//end while if	
	}//end Submit Intensity Edit
	elseif($filename=="Submit Behavior Classification Edit"){
		print "<h3> Comment Edit for the $_SESSION[Target_Population] ABAIT Scales</h3>";
		while($row=mysqli_fetch_assoc($session)){
			for($j=1;$j<6;$j++){
				${'behave_class_'.$j.'_'.$row[scale_name_key]}=$_REQUEST['behave_class_'.$j.'_'.$row['scale_name_key']];
					if(${'behave_class_'.$j.'_'.$row[scale_name_key]}){
						$sql2="UPDATE scale_table SET behave_class_$j='${'behave_class_'.$j.'_'.$row[scale_name_key]}' WHERE scale_name_key=$row[scale_name_key]";
						mysqli_select_db($_SESSION['database']);	
						$retval = mysqli_query( $sql2, $conn);
						print "<br><em>Behavior Classification $j</em> for the <em>$row[scale_name]</em>  scale has been changed to: ${'behave_class_'.$j.'_'.$row[scale_name_key]}</br>";
						if(! $retval ){
						die('Could not connect: ' . mysqli_error());
						}//end retval if
					}//end comment if
			}//end for if
		}//end while if	
	}//end Submit classification Edit
?>	
</fieldset>
<?build_footer()?>
</div>
</body>
</html>