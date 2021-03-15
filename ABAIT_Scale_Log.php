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
<div id="body">
<?
$first=$_SESSION['adminfirst'];
$last=$_SESSION['adminlast'];
	print"<table width='100%'>\n";
		print"<tr>\n";
			print"<td valign='bottom' align='right'>$first Logged in</td>\n";
		print"</tr>\n";
	print"</table>\n";
?>
<div id="globalheader">
	<ul id="globalnav">
		<li id="gn-home"><a href="adminhome.php"</a></li>
		<li id="gn-maps"><a href="adminhome.php">Maps</a></li>
		<li id="gn-contact"><a href="mailto:bott1@centurytel.net?subject=Feedback on ABAIT">Contact ABAIT</a></li>
		<li id="gn-logout"><a href="logout.php">Logout</a></li>
	</ul>
</div><!--/globalheader-->
<fieldset class="submit">
<form 	name = 'form1'
		action = "ABAIT_Scale_Create_Log.php"
		method = "post">
<?
if($_SESSION['privilege']=='globaladmin'){
	
	$Target_Population=$_REQUEST['Target_Population'];
	//$Population_strip=mysqli_real_escape_string($Target_Population,$conn);
	$scale_number=$_REQUEST['scale_number'];
	print"<h3><br>Each scale requires FIVE behavior descriptions of INCREASING intensity</br></h3>";
	print"<h4><br>Enter scale names and brief behavior descriptions in the text boxes provided</br></h4>";
	print "<table border='1' bgcolor='white'>";
	print"<tr><th>Scale Name</th><th>Itensity Level 1</th><th>Itensity Level 2</th><th>Itensity Level 3</th><th>Itensity Level 4</th><th>Itensity Level 5</th></tr>";
	for($i=0;$i<$scale_number;$i++){
		$scale_name=$scale_name.$i;
		
		print "<tr>";
		print "<td><input type = 'text' width='75' name =$scale_name size='40'/></td>\n";
		for($j=0;$j<5:$j++){
			$comment=$comment.$i.$j;
			print "<td><input type = 'text' width='75' name =$comment/></td>";
		}//end comment for loop
		print "</tr>";
	}//end scale name for loop
	print"</table>";
	
	
	
	
	
	$_SESSION['Target_Population']=$Target_Population;
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	$Population_strip=mysqli_real_escape_string($Target_Population,$conn);
	$sql="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
	mysqli_select_db($_SESSION['database']);
	$session=mysqli_query($sql,$conn);
	print"<table><tr><td>";
	if($setup=='scale1'){
		print"<h3>$Target_Population ABAIT Scales</h3>";
			print "<table border='1' bgcolor='white'>";
			print "<tr><th>Current Scale Name</th>";
			print "<th>Enter Text to Edit Scale Name</th></tr>";
				//$i=1;
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>";
						$scale_name='scale_name_'.$row[scale_name_key];
						print "<td>$row[scale_name]</td>\n";
						print "<td><input type = 'text' width='75' name =$scale_name size='40'/>$i</td>\n";
					print"</tr>";
				//$i=$i+1;
				}//end while
				$_SESSION['i']=$i;
			print"</table>";
		
			print "</td>";
			print "<td>";
			print "<div id = 'submit'>";
				print"<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Scale Create'>";
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
						$scale_name='scale_name'.$row[scale_name_key];
						//$scale_nam='scale_name'.$row['scale_name_key'].$i;
						//print $scale_nam;
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
							//print $comment;
							//print"<td>$row[$comment]</td>";
							print "<td><input type = 'text' width='75' name =$comment size='25'/></td>\n";
						}
					print"</tr>";
				}//end while
				$_SESSION['i']=$i;
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
			print "<table border='1' bgcolor='white'>";
			print "<tr><th>Current Scale Name</th>";
			print "<th>Enter Text to Edit Scale Name</th></tr>";
				$i=1;
				while($row=mysqli_fetch_assoc($session)){
					print"<tr>";
						$scale_name='scale_name'.$i;
						$scale_nam='scale_name'.$row['scale_name_key'].$i;
						print"hi";
						print $scal_name;
						print "<td>$row[scale_name]</td>\n";
						print "<td><input type = 'text' width='75' name =$scale_name size='40'/>$i</td>\n";
					print"</tr>";
				$i=$i+1;
				}//end while
				$_SESSION['i']=$i;
			print"</table>";
		
			print "</td>";
			print "<td>";
			print "<div id = 'submit'>";
				print"<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Scale Edit'>";
			print"</div>";		
		}//end scale3 elseif	
		print"</td></tr></table>";
		
	}else{
		print"<h3>'To maintain the integrity of the ABAIT Scales, your access level does not allow you to edit the Scales'</h3>";
	}//end global admin elseif
print "</form>";
?>	
</fieldset>


<?build_footer()?>
</div>
</body>
</html>