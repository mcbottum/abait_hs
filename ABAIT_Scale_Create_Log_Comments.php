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
<style>
    table.local thead th{
        width:130px;
        background-color: white;
        background-color: #F5F5F5;
    }
    table.local tbody th{
        width:130px;
        background-color: white;
        background-color: #F5F5F5;
    }
    table.local tbody{
        max-height: 400px;
    }
    table.local tbody td{
        width:130px;
        background-color: white;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
	input {
	    width: 80%;
	    padding: 10px;
	    margin: 3px;
	}
</style>

</head>
<body>
<div id="body">
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
?>
	<fieldset class="submit">
<?
build_page($_SESSION['privilege'],$cgfirst);

$filename =$_REQUEST["submit"];
if($filename=='Submit New Scales'){

	$Target_Population=$_SESSION['Target_Population_new'];
	$scale_number=$_SESSION['scale_number'];

	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
	$Target_Population=mysqli_real_escape_string($conn,$Target_Population,$conn);

	//figure out what to request:
	$sql = "SELECT * FROM scale_table";
	$session = mysqli_query($sql, $conn);

	while($row=(mysqli_fetch_assoc($session))){
		$scale_name=str_replace(' ','_',$row['scale_name']);
		if(isset($_REQUEST[$scale_name])){
			$scale_name_description=mysqli_real_escape_string($row['scale_name_description']);
			$triggers=mysqli_real_escape_string($row['triggers']);
			$comment_1=mysqli_real_escape_string($row['comment_1']);
			$comment_2=mysqli_real_escape_string($row['comment_2']);
			$comment_3=mysqli_real_escape_string($row['comment_3']);
			$comment_4=mysqli_real_escape_string($row['comment_4']);
			$comment_5=mysqli_real_escape_string($row['comment_5']);
			$behave_class_1=mysqli_real_escape_string($row['behave_class_1']);
			$behave_class_2=mysqli_real_escape_string($row['behave_class_2']);
			$behave_class_3=mysqli_real_escape_string($row['behave_class_3']);
			$behave_class_4=mysqli_real_escape_string($row['behave_class_4']);
			$behave_class_5=mysqli_real_escape_string($row['behave_class_5']);

			mysqli_query('INSERT INTO scale_table VALUES(null,"'.$Target_Population.'","'.$row['scale_name'].'","'.$scale_name_description.'","'.$triggers.'","'.$comment_1.'","'.$comment_2.'","'.$comment_3.'","'.$comment_4.'","'.$comment_5.'","'.$behave_class_1.'","'.$behave_class_2.'","'.$behave_class_3.'","'.$behave_class_4.'","'.$behave_class_5.'")');
			echo mysqli_error($conn);
			print "<h3>An ABAIT Scale has been created using information from <em>$scale_name</em> for \"$Target_Population\" </h3>";
			break;

		}
	}

	if(isset($_REQUEST['scale_name_0'])){
		if(isset($_REQUEST['scale_name_description_0']) && $_REQUEST['scale_name_description_0']!=''){
			print "<h3>An ABAIT Scale has been created for <em>$Target_Population</em> with the following Information: </h3>";
			print "<table width='100%' columns='6' class='table local' border='1' bgcolor='white'>";
					for($i=0;$i<$scale_number;$i++){
						if(isset($_REQUEST['scale_name_'.$i])){
							$scale_name=$_REQUEST['scale_name_'.$i];
							print "<thead>";
								print "<tr>\n";
									print "<th colspan='2'>Scale Name</th>";
									print "<th colspan='2'>Scale Description</th>";
									print "<th colspan='2'>Typical Triggers</th>";
								print "</tr>";
							print "</thead>";
							print "<tbody>";

							if(isset($_REQUEST['scale_name_description_'.$i])){
								$scale_name_description=$_REQUEST['scale_name_description_'.$i];
							}else{
								$scale_name_description='';
							}
							if(isset($_REQUEST['triggers_'.$i])){
								$triggers=$_REQUEST['triggers_'.$i];
							}else{
								$triggers='';
							}

								print "<tr>";
									print "<td colspan='2'>$scale_name</td>";
									print "<td colspan='2'>$scale_name_description</td>";
									print "<td colspan='2'>$triggers</td>";
								print "</tr>";
								print "<tr>";
									print "<th>Intensity</th>";
									for($j=1;$j<6;$j++){
										if(isset($_REQUEST['intensity_'.$i.'_'.$j])){
											${'comment_'.$j}=$_REQUEST['intensity_'.$i.'_'.$j];
										}else{
											${'comment_'.$j}='';
										}
										print "<td>${'comment_'.$j}</td>";
									}
								print "</tr>";
								print "<tr>";
									print "<th>Behavior Description</th>";
									for($j=1;$j<6;$j++){
										if(isset($_REQUEST['behavior_description_'.$i.'_'.$j])){
											${'behave_class_'.$j}=$_REQUEST['behavior_description_'.$i.'_'.$j];
										}else{
											${'behave_class_'.$j}='';
										}
										print "<td>${'behave_class_'.$j}</td>";
									}//end comment for loop

										$scale_name=mysqli_real_escape_string($scale_name);
										$scale_name_description=mysqli_real_escape_string($scale_name_description);
										$triggers=mysqli_real_escape_string($triggers);
										$comment_1=mysqli_real_escape_string($comment_1);
										$comment_2=mysqli_real_escape_string($comment_2);
										$comment_3=mysqli_real_escape_string($comment_3);
										$comment_4=mysqli_real_escape_string($comment_4);
										$comment_5=mysqli_real_escape_string($comment_5);
										$behave_class_1=mysqli_real_escape_string($behave_class_1);
										$behave_class_2=mysqli_real_escape_string($behave_class_2);
										$behave_class_3=mysqli_real_escape_string($behave_class_3);
										$behave_class_4=mysqli_real_escape_string($behave_class_4);
										$behave_class_5=mysqli_real_escape_string($behave_class_5);

										mysqli_query('INSERT INTO scale_table VALUES(null,"'.$Target_Population.'","'.$scale_name.'","'.$scale_name_description.'","'.$triggers.'","'.$comment_1.'","'.$comment_2.'","'.$comment_3.'","'.$comment_4.'","'.$comment_5.'","'.$behave_class_1.'","'.$behave_class_2.'","'.$behave_class_3.'","'.$behave_class_4.'","'.$behave_class_5.'")');
										echo mysqli_error($conn);

								print "</tr>";
						}
					}//end scale number for
				print "</tbody>";
			print "</table>";
		}else{
			print "<h3>A scale containing new information has not been created.</h3>";
		}
	} // if isset scale_name_1
}//end submit new scales if
?>

	<?build_footer()?>
	</fieldset>
	</div>
	</body>
</html>