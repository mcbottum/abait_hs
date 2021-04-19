<?
include("ABAIT_function_file.php");
ob_start()?>
<?session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
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
<script>
function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if(document.form.residentkey.selectedIndex==""){
		alertstring=alertstring+"\nResident.";
		document.form.residentkey.style.background = "Yellow";
		valid=false;
	}else if(document.form.scale_name.selectedIndex==""){
		alertstring=alertstring+"\nBehavior Scale.";
		document.form.scale_name.style.background = "Yellow";
		valid=false;
	}else if(document.form.trig.selectedIndex==""){
		alertstring=alertstring+"\nTrigger.";
		document.form.trig.style.background = "Yellow";
		valid=false;
	}
	
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}
	return valid;
}
function reload_rk(){
	var val1=document.getElementById("hidden_rk").value
	self.location='ABAIT_quick_scales_v2.php?k='+val1;
}

function reload(form){
	var val1=form.residentkey.options[form.residentkey.options.selectedIndex].value;
	self.location='ABAIT_quick_scales_v2.php?k='+val1;
}
function reload1(form){
	var val2=form.scale_name.options[form.scale_name.options.selectedIndex].value;

	if (val2 == 'unmapped_behavior'){
		var val1=form.residentkey.options[form.residentkey.options.selectedIndex].value;
		self.location='ABAIT_resident_map_v2.php?k='+val1;
	}else{
		self.location='ABAIT_quick_scales_v2.php?scale_name='+val2;
	}
}
function reload3(form){
	var val3=form.trig.options[form.trig.options.selectedIndex].value;
	document.form.residentkey.style.background = "Lightgrey";
	document.form.scale_name.style.background = "Lightgrey";
	document.form.trig.style.background = "Lightgrey";
	if (val3 == 'unmapped_trigger'){
		var val1=form.id.value;
		var val2=form.sn.value;
		self.location='ABAIT_resident_map_v2.php?k='+val1+'&scale_name='+val2;

	}
}

</script>
<?
set_css()
?>
<style>
	.center {
		width: 30%;
	}
	.btn.btn-lg {
	    background-color: #03DAC5;
	    border-radius: 10px;
	    font-size: 1.5em;
	    color: black;
	}
	.btn-lg:hover {
		background-color: #1FC4B4;
		box-shadow: 1px 1px 15px #888888;
	    border-style:solid;
	    border-width:1px;
	    color: black;
	}
	.footer_div {
		background-color: #F5F5F5;
	}
	.footer {
		color: black;
	}
	.custom-select {
		background: #03DAC5;
	}
	.custom-select:hover {
		background: #1FC4B4;
	}

</style>
</head>
<body class="container">
	<?
		$names = build_page_pg();
	?>
	<form 	name = 'form'
			id = 'form'
			onsubmit='return validate_form()'
			action = "ABAIT_scale_v2.php"
			method = "post">
	<?
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']);
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);

	$resident = '';
	$house=$_SESSION['house'];
	if($_SESSION['privilege']=='caregiver' && !isset($_GET["k"])){
		if(isset($_SESSION['community'])){
			echo "in community   ";
			echo $_SESSION['community'];
			$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE community='$_SESSION[community]' order by first";
		}else if($house=='all'){
			$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
		}else{
			$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' AND house='$house' order by first";
		}
		$session1=mysqli_query($conn, $_SESSION['sql']);

		if(mysqli_num_rows($session1)==1){
			$row1= mysqli_fetch_array($session1);
			$residentkey=$row1['residentkey'];
			$_SESSION['residentkey']=$residentkey;
			$sql_name="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey' ORDER by first";
			$name=mysqli_query($conn,$sql_name);
			$row_name=mysqli_fetch_assoc($name);
			$_SESSION['first']=$row_name['first'];
			$_SESSION['last']=$row_name['last'];
		}

	}

	if(isset($_GET["k"])){
		$residentkey=$_GET["k"];
		$_SESSION['residentkey']=$residentkey;
		$sql_name="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey' ORDER by first";
		$name=mysqli_query($conn,$sql_name);
		$row_name=mysqli_fetch_assoc($name);
		$_SESSION['first']=$row_name['first'];
		$_SESSION['last']=$row_name['last'];
	}else{
		$row_name=null;
	}
	if(isset($_GET["scale_name"])){
		$scale_name=$_GET["scale_name"];
	}

	if (isset($_REQUEST['residentkey'])){
		$resident=$_REQUEST['residentkey'];
	}

	if($resident){
		$residentkey=$resident;
		$_SESSION['residentkey']=$residentkey;
		$sql_name="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey' ORDER by first";
		$name=mysqli_query($conn,$sql_name);
		$row_name=mysqli_fetch_assoc($name);
		$_SESSION['first']=$row_name['first'];
		$_SESSION['last']=$row_name['last'];
	}else{
		unset($_SESSION['scale_name']);
	}

	$residentkey=$_SESSION['residentkey'];

	//$scale_name_key=str_replace('_',' ',$_REQUEST['scale_name']);
	if (isset($_REQUEST['scale_name'])){
		$scale_name_key=str_replace('_',' ',$_REQUEST['scale_name']);
	}else{
		$scale_name_key = '';
	}
	if($scale_name_key){
		$_SESSION['scale_name']=$scale_name_key;
		$scale_name=$_SESSION['scale_name'];
	}else{
		$scale_name='';
	}

	//$trig=$_REQUEST['trig'];
	if (isset($_REQUEST['trig'])){
		$trig=$_REQUEST['trig'];
	}else{
		$trig = '';
	}
	if($trig){
		$_SESSION['trig']=$trig;
	}
	if(!$residentkey){
		
		if($_SESSION['privilege']=='globaladmin'){
			$_SESSION['sql']="SELECT * FROM residentpersonaldata ORDER By first";
		}elseif($_SESSION['privilege']=='admin'){
			$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
		}elseif($_SESSION['privilege']=='caregiver'){
			if($house=='all'){
				$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' order by first";
			}else{
				$_SESSION['sql']="SELECT * FROM residentpersonaldata WHERE Target_Population='$Population_strip' AND house='$house' order by first";
			}
		}//end get residents elseif	
	}
	elseif($residentkey&&!$scale_name){
		$_SESSION['sql2']="SELECT * FROM behavior_maps WHERE residentkey='$residentkey'";
		if($_SESSION['privilege']=='globaladmin'){
			$_SESSION['sql3']="SELECT scale_name FROM scale_table";
		}else{	
			$_SESSION['sql3']="SELECT scale_name FROM scale_table WHERE Target_Population='$Population_strip'";
		}
	}
	elseif($scale_name){

		$_SESSION['sql4']="SELECT * FROM behavior_maps WHERE residentkey='$residentkey'";
	}
	if(isset($_SESSION['scale_name'])){
		$scale_name=$_SESSION['scale_name'];
	}
	print" <table align='center'>";
		print"<tr><td><h3 class='m-4'>Behavior Episode Selector</h3></td></tr>";
		print"<tr><td>";
			$provider_resident = $_SESSION['provider_resident'];
			print"<b><em>$provider_resident</em></b>";
		print"</td></tr>";
		print"<tr><td>";
			$session1=mysqli_query($conn,$_SESSION['sql']);
			$resident_count = mysqli_num_rows($session1);
			if($row_name){
				$select_option=$row_name['first']." ".$row_name['last'];
			}else{
				$select_option="Select a Resident";
			}

				print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='residentkey' onchange=\"reload(this.form)\"><optGroup><option value=''>$select_option</option></optGroup>"."<BR>";

					while($row1= mysqli_fetch_array($session1)) { 
							if($row1['residentkey']==$residentkey){
								print "<optGroup>";
									print "<option selected value=$residentkey>$row1[first] $row1[last]</option>";
								print "</optGroup>";

							}else{
								print "<optGroup>";
									print  "<option value=$row1[residentkey]>$row1[first] $row1[last]</option>";
								print "</optGroup>";
							}
						}
					
				print "</select>";	
			
	if($residentkey){	

		print"</td></tr>";
		print"<tr><td>";
		print"<b><em>Behavior Category</em></b>";
		print"</td></tr>";		
		print"<tr><td>";
			// print"<input type='hidden' name='residentkey' value=$residentkey>";		
			print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='scale_name' onchange=\"reload1(this.form)\"><optGroup><option value=''>Select a Behavior Classification</option></optGroup>";
			
				$session3=mysqli_query($conn,$_SESSION[sql3]);
				unset($sn_array);
					while($row3 = mysqli_fetch_array($session3)) { 
						
						$sn=str_replace(' ','_',$row3['scale_name']);
							if($row3['scale_name']==$scale_name&&!in_array($row3['scale_name'],$sn_array)){
								print "<optGroup>";
									print "<option selected value='$sn'>$row3[scale_name]</option>";
								print "</optGroup>";
							}	
							elseif(!in_array($row3['scale_name'],$sn_array)){
								print "<optGroup>";
									print  "<option value='$sn'>$row3[scale_name]</option>";
								print "</optGroup>";		
							}
		//					else{
		//						print "<option style='color:red' value='$sn'>$row3[behavior]</option>";
		//					}
						$sn_array[]=$row3['scale_name'];
					}
					// print "<optGroup>";
					// 	print  "<option class='red_text' value='unmapped_behavior'>Record a new Behavior</option>";
					// print "</optGroup>";
				print "</select>";
				print"</td></tr>";
	}
		
	if($residentkey&&$scale_name){//select for trigger

			$sql7="SELECT * FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$scale_name'";
			$session7=mysqli_query($conn,$sql7);



	print"<tr><td>";
	print"<b><em>Trigger</em></b>";
	print"</td></tr>";


	if(!$row=mysqli_fetch_assoc($session7)){
	    header('Location: ABAIT_resident_map_v2.php?k='.$residentkey.'&&scale_name='.$scale_name);
	    exit();
	}
	mysqli_data_seek($session7, 0);


		print"<tr><td>";
		print"<input type='hidden' name='id' value=$residentkey>";
		print"<input type='hidden' name='sn' value=$scale_name>";
			print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='trig' onchange=\"reload3(this.form)\"><optGroup><option value=''>Select a Trigger</option></optGroup>";
					while($row7= mysqli_fetch_array($session7)) { 
						$trigger_sr=str_replace(' ','_',$row7['trig']);
						if($row7['mapkey']==$trig){
							print "<optGroup>";
								print "<option selected value='$row7[mapkey]'>$row7[trig]</option>";
							print "</optGroup>";
						}else{
							print "<optGroup>";
								print  "<option value=$row7[mapkey]>$row7[trig]</option>";
							print "</optGroup>";
						}
					}
				
				print "<optGroup>";
					print  "<option class='red_text' value='unmapped_trigger'>Record a new Trigger</option>";
				print "</optGroup>";

			print "</select>";
		print"</td></tr>";	
		}
	if($trig){
		unset($_SESSION['residentkey']);
		unset($_SESSION['scale_name']);
	}	
	print"</table>";	
	?>
		<div  id = "submit">	
			<input  	type = "submit"
			name = "submit"
			value = "Submit Behavior Plan Request" />
		</div>

	</form>

	<? build_footer_pg() ?>
</body>

</html>
