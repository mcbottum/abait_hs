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
<fieldset class="submit">
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
<form 	name = 'form1'
		action = "ABAIT_Scale_Edit_Log.php"
		method = "post">


<?
if($_SESSION['privilege']=='globaladmin'){
	$setup=$_REQUEST['setup'];
	$Target_Population=str_replace('_',' ',$_SESSION['Target_Population']);
	$_SESSION['Target_Population']=$Target_Population;
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	$Population_strip=mysqli_real_escape_string($Target_Population,$conn);
	$sql="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
	print"<table><tr><td>";
	if($setup=='scale1'){
		print"<h3>$Target_Population ABAIT Scales</h3>";
			print "<table border='1' bgcolor='white'>";
			print "<tr><th>Current Scale Name</th>";
			print "<th>Enter Text to Edit Scale Name</th></tr>";
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>";
						$scale_name='scale_name_'.$row['scale_name_key'];
						print "<td>$row[scale_name]</td>\n";
						print "<td><input type = 'text' name =$scale_name></td>\n";
					print"</tr>";
				}//end while
				//$_SESSION['i']=$i;
			print"</table>";
		
			print "</td>";
			print "<td>";
			print "<div id = 'submit'>";
				print"<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Scale Edit'>";
			print"</div>";
			
		}//end scale if
		elseif($setup=='scale2'){
			print "<table border='1' bgcolor='white'>";
			print "<tr><th>Scale Name</th>";
			print "<th>Intensity Level 1</th>";
			print "<th>Intensity Level 2</th>";
			print "<th>Intensity Level 3</th>";
			print "<th>Intensity Level 4</th>";
			print "<th>Intensity Level 5</th>";

				while($row=mysqli_fetch_assoc($session)){
					print"<tr>";
						$scale_name='scale_name'.$row['scale_name_key'];
						print "<td>$row[scale_name]</td>\n";
						for($j=1;$j<6;$j++){
							$comment='comment_'.$j;
							print"<td>$row[$comment]</td>";
						}
					print"</tr>";
					print"<tr>";
					print"<th>Updates --> </th>";
						for($j=1;$j<6;$j++){
							$comment='comment_'.$j.'_'.$row['scale_name_key'];
							print "<td><input type = 'text' name =$comment></td>\n";
						}
					print"</tr>";
				}//end while
				//$_SESSION['i']=$i;
			print"</table>";
		
			print "</td></tr>";
			print "<tr><td>";
			print "<div id = 'submit'>";
				print"<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Intensity Edit'>";
			print "</div></td></tr>";				
		}//end scale2 elseif	
		
		elseif($setup=='scale3'){
			print "<h3>Enter behavior classifications of increasing severity associated with each scale.</h3>";
			print "<table border='1' bgcolor='white'>";
			print "<tr><th>Scale Name</th>";
			print "<th>Behavior Level 1</th>";
			print "<th>Behavior Level 2</th>";
			print "<th>Behavior Level 3</th>";
			print "<th>Behavior Level 4</th>";
			print "<th>Behavior Level 5</th>";

				while($row=mysqli_fetch_assoc($session)){
					print"<tr>";
						$scale_name='scale_name'.$row[scale_name_key];
						print "<td>$row[scale_name]</td>\n";
						for($j=1;$j<6;$j++){
							$behave_class='behave_class_'.$j;
							print"<td>$row[$behave_class]</td>";
						}
					print"</tr>";
					print"<tr>";
					print"<th>Updates --> </th>";
						for($j=1;$j<6;$j++){
							$behave_class='behave_class_'.$j.'_'.$row['scale_name_key'];
							print "<td><input type = 'text' name =$behave_class></td>\n";
						}
					print"</tr>";
				}//end while
				//$_SESSION['i']=$i;
			print"</table>";
		
			print "</td></tr>";
			print "<tr><td>";
			print "<div id = 'submit'>";
				print"<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Behavior Classification Edit'>";
			print "</div></td></tr>";				
		}//end scale2 elseif	
		print"</td></tr></table>";
		
	}else{
		print"<h3>'To maintain the integrity of the ABAIT Scales, your access level does not allow you to edit the Scales'</h3>";
	}//end global admin elseif

?>	

</fieldset>
	</form>
	<?build_footer()?>
	</div>
	</body>
</html>
