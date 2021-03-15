<?
include("ABAIT_function_file.php");
session_start();
$intervention_1='';
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
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<?
if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
	}
print "<fieldset>";
build_page($_SESSION['privilege'],$cgfirst);
?>
		
		<form 	action = "adminhome.php"
				method = "post">
		
<?
		//$anothermap=$_REQUEST['anothermap'];
		$r=$_SESSION['r'];

		if($r>=0){
		$residentkey=$_SESSION['residentkey'];
		$creation_date=date('Y,m,d');
		$behavior=$_SESSION['behavior'];
		$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
		if($_SESSION['Target_Population']=='all'){
			$Population=mysqli_real_escape_string($conn,$_SESSION['Population']);
			$Population=$_SESSION['Population'];
		}else{
			$Population=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
		}//end Target Population if else
		// NOTE dateline corresponds to trigger, $i corresponds to intervention
		if (isset($_POST['scales_created'])) {
		    $scales_created = $_POST['scales_created'];
		    foreach($scales_created as $mapkey){
		    	mysqli_query($conn,"UPDATE resident_mapping SET scale_created=TRUE WHERE mapkey='$mapkey'");
		    }
		    // $colors is an array of selected values
		}

		if (isset($_POST['delete_obs'])) {
		    $delete_obs = $_POST['delete_obs'];
		    foreach($delete_obs as $mapkey){
		    	mysqli_query($conn,"DELETE FROM resident_mapping WHERE mapkey='$mapkey'");
		    }
		    // $colors is an array of selected values
		}

		for ($dataline=0; $dataline<=$r;$dataline ++){
			$trigger=NULL;	
			$trigger=$_REQUEST['trigger'.$dataline];
			if($trigger){
				for($i=1;$i<7;$i++){
					$new_intervention=$_REQUEST['intervention_t_'.$i.$dataline];
					if($new_intervention!='Enter New Intervention'){
						${'intervention_'.$i}=$new_intervention;
					}else{
						if($_REQUEST['intervention_'.$i.$dataline]){	
							${'intervention_'.$i}=str_replace('_',' ',$_REQUEST['intervention_'.$i.$dataline]);
						}else{
							${'intervention_'.$i}="";
						}
					}
				}		
				mysqli_query($conn,"INSERT INTO behavior_maps VALUES(null,'$Population','$residentkey','$creation_date','$behavior','$trigger','$intervention_1','$intervention_2','$intervention_3','$intervention_4','$intervention_5','$intervention_6')");
			}
		}// end for

		}//end if check for existing maps
	//if($anothermap=='yes'){
		//$nextfile='chooseresident_for_map_review.php';
	//}else{$nextfile='adminhome.php';
	
	//}//end submit behavior map elseif
if($intervention_1){
	print"<h4 align='center'>A <em>$behavior</em> Scale has been logged for <em>$_SESSION[first] $_SESSION[last]</em>.</h4>";
	print "<h4 align='center'><a href='chooseresident_for_map_review.php'>Back to Scale Creation</a></h4>\n";
}else{
	print"<h4 align='center'>A trigger was not recored, please return to the scale creation page.</h4>\n";
	print "<h4 align='center'><a href='chooseresident_for_map_review.php'>Back to Scale Creation</a></h4>\n";
}
	?>
	
	<div id='submit'>
			<input 	type = 'submit'
						name = 'submit'
						value = 'Return to Administration Home'/>
	</div>
	</form>
	</fieldset>

<?build_footer()?>
</body>
</html>