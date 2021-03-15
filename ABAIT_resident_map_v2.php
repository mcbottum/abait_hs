<?
include("ABAIT_function_file.php");session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:".$_SESSION['logout']);
	print $_SESSION['passwordcheck'];
}
date_default_timezone_set("Europe/London");
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
<?
set_css()
?>

<script>
function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if(document.form.scale_name.selectedIndex=="0"){
		alertstring=alertstring+"\n-choose Behavior Scale-";
		document.form.scale_name.style.background = "Yellow";
		valid=false;
	}else{
		document.form.scale_name.style.background = "Lightgrey";
	}//end scale_name check
	
	if(document.form.trigger.selectedIndex=="0"){
		alertstring=alertstring+"\n-choose Behavior Intensity-";
		document.form.trigger.style.background = "Yellow";
		valid=false;
	}else{
		document.form.trigger.style.background = "Lightgrey";
	}//end trigger check

	if(document.form.intensity.selectedIndex=="0"){
		alertstring=alertstring+"\n-choose Behavior Intensity-";
		document.form.intensity.style.background = "Yellow";
		valid=false;
	}else{
		document.form.intensity.style.background = "Lightgrey";
	}//end intensity check
	
	if(document.form.behave_class.selectedIndex==""){
		alertstring=alertstring+"\n-choose Behavior Classification-";
		document.form.behave_class.style.background = "Yellow";
		valid=false;
	}else{
		document.form.behave_class.style.background = "Lightgrey";
	}//end intensity check
	
	if(document.form.specific_behavior_description.value=="")
	{
		alertstring=alertstring+"\n-enter a Specific Behavior Description-";
		document.form.specific_behavior_description.style.background = "Yellow";
		valid=false;
	}else{
		document.form.specific_behavior_description.style.background = "Lightgrey";
	}//end specific_behavior_description

	if(document.form.custom_trigger.value=="other")
	{
		alertstring=alertstring+"\n-Please provide more specific trigger-";
		document.form.custom_trigger.background = "Yellow";
		valid=false;
	}else{
		document.form.custom_trigger.style.background = "Lightgrey";
	}//end custom_trigger

	if(document.form.trigger.selectedIndex==""){
		alertstring=alertstring+"\n-choose Trigger-";
		document.form.trigger.style.background = "Yellow";
		valid=false;
	}else{
		document.form.trigger.background = "Lightgrey";
	}//end trigger check
	
	if(document.form.intervention.value=="")
	{
		alertstring=alertstring+"\n-enter an Intervention Description-";
		document.form.intervention.style.background = "Yellow";
		valid=false;
	}else{
		document.form.intervention.style.background = "Lightgrey";
	}//end intervention
	

	if (document.form.datetimepicker.value=="" ) { 
		alertstring=alertstring+"\n-date of episode-";
		document.form.datetimepicker.style.background = "Yellow";

		valid=false;
	}else{
		document.form.datetimepicker.style.background = "Lightgrey";	
	}

	
	var numericExpression = /^[0-9]+$/;
	if(!document.form.duration.value.match(numericExpression)){
		document.form.duration.style.background = 'Yellow';
		valid=false;
		alertstring=alertstring+"\n-enter duration of episode (minutes)-";
	}else{
		document.form.duration.style.background = "Lightgrey";
	}//end duration check

	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message

	document.form.submit.style.color = "#A65100";
	return valid;
}//end form validation function	

function reload(form){
	var val=form.scale_name.options[form.scale_name.options.selectedIndex].value;
	self.location='ABAIT_resident_map_v2.php?scale_name='+val;
}

function show( selTag ) {
	obj1 = document.getElementById("pre_PRN_observation_tag");
	obj = document.getElementById("pre_PRN_observation");
	customTrig = document.getElementById("custom_trigger");

	if ( selTag.value== 'other' ){
		customTrig.style.display = "block";
	} else if ( selTag.selectedIndex == 1 ) {
		obj1.style.display = "block";
		obj.style.display = "block";
		obj1.style.align="center";
	} else {
		obj1.style.display = "none";
		obj.style.display = "none";
	}
}

function checkDate(){
    var todaysDate = new Date();
    var selDate = new Date(form.datetimepicker.value)
    if(selDate > todaysDate){
        alert("The Selected date may not be in the future (" + todaysDate +")");
        document.form.datetimepicker.value = "";
    }else{
        document.form.datetimepicker.style.background = "White";
    }
}

</script>


<style>

	.center {
		width: 70%;
	}
	.btn.btn-lg {
	    background-color: #03DAC5;
	    border-radius: 10px;
	    font-size: 1.5em;
	    color: black;
	}
    .form-control {
        background-color: #03DAC5;
    }
	.form-control-ta {
		background-color: #F5F5F5;
	}

	.btn-lg:hover {
		background-color: #1FC4B4;
		box-shadow: 1px 1px 15px #888888;
	    border-style:solid;
	    border-width:1px;
	    color: black;
	}
	.custom-select {
		background: #03DAC5;
	}
	.custom-select-background {
        background: #03DAC5;
        width:200px;
    }
	.custom-select:hover {
		background: #1FC4B4;
	}
	#datetimepicker2{
		width: auto !important
	}

</style>
</head>
<body class="container">

	<?
	$names = build_page_pg();
	if(isset($_REQUEST['scale_name'])){
		$scale_name=str_replace('_',' ',$_REQUEST['scale_name']); // Use this line or below line if register_global is off
	}else{
		$scale_name=null;
	}
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
	if(isset($_GET["k"])){
		$residentkey=$_GET["k"];
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$resident=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($resident);
		$_SESSION['row1']=$row1;
		$_SESSION['residentkey'] = $residentkey;
	}elseif(isset($_REQUEST["resident_choice"])){
		$residentkey=$_REQUEST["resident_choice"];
		$_SESSION['residentkey'] = $residentkey;
	}else{
		$residentkey=null;
	}

	if(isset($_GET["scale_name"])){
		$scale_name=$_GET["scale_name"];
	}

	if(!$scale_name){
		$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'";
		$resident=mysqli_query($conn,$sql1);
		$row1=mysqli_fetch_assoc($resident);
		$_SESSION['row1']=$row1;
		$Population_strip=mysqli_real_escape_string($conn,$row1['Target_Population']);
		$sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
		$session2=mysqli_query($conn,$sql2);
		$session3="";
	}
	elseif($scale_name){
		$sn=str_replace('_',' ',$scale_name);

		$Target_Population=$_SESSION['row1']['Target_Population'];

		// $_SESSION['Target_Population_holder']=$row1['Target_Population'];
		$Population_strip=mysqli_real_escape_string($conn,$Target_Population);
		$sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip' AND scale_name='$sn'";
		$sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
		$session2=mysqli_query($conn,$sql2);	
		$session3=mysqli_query($conn,$sql3);
	}

		$_SESSION['first']=$_SESSION['row1']['first'];
		$_SESSION['last']=$_SESSION['row1']['last'];
		
		?>

	<form	name= 'form'
			onsubmit='return validate_form()'
			action = "ABAIT_resident_map_log_v2.php"
			method = "post">

	<table class="center">
		<tr>
			<td align="center">
				<h3 class="m-4">
					<?
					print"Behavior Episode Characterization Form for $_SESSION[first] $_SESSION[last]";
					?>
				</h3>
			</td>
		</tr>
		<tr>
			<td align="center">
				<h4 class="m-4" style='color:grey'>Behavior and Intervention Information</h4>
			</td>
		</tr>	
		<tr>
			<td align="center">
				<h3 style='color: grey'>STEP 1</h3> 
			</td>
		</tr>
		<tr>
			<td align="center">
				<h4>General category of behavior</h4>
			</td>
		</tr>
		<tr>
			<td align="center">		
			<?
			$scale_name=str_replace('_',' ',$scale_name);
				// print "<div class = 'tooltip'>";
						
					print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='scale_name' id='scale_name' onchange=\"reload(this.form)\"><option value=''>Select a Behavior Classification</option>";
						while($row2 = mysqli_fetch_array($session2)) { 
							$sn=str_replace(' ','_',$row2['scale_name']);

							if($row2['scale_name']==$scale_name){
								
								print "<option selected value='$sn'>$row2[scale_name]</option>";
								}
							else{
								print  "<option value='$sn'>$row2[scale_name]</option>";
							}
						}
					print "</select>";
				// print "</div>";
			print"</td>";
					if($session3){
						$row3 = mysqli_fetch_array($session3);
						$triggers = explode(',',$row3['triggers']);
						} 
		print "</tr>";
		print "<tr>";
			print "<td colspan='2' align='center'>";
				print "<h3 style='color: grey'>STEP 2</h3> <h4> What caused the behavior?</label></h4>";
				// print "<div class = 'tooltip' id = 'trigger'>";
					
					print "<span class='tooltiptext'>Choose whichever best identifies what you believe caused your resident to act out.</span>";
					print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='trigger' id='trigger' onchange='show(this)'><option value=''>Select Cause (trigger)</option>";
						print "<option value='other'>None of the below</option>";
						foreach($triggers as $trigger){
							$trigger_strip=str_replace(' ','_',$trigger);
							print "<option value=$trigger_strip>$trigger</option>";
						}
						
					print "</select>";
					print "<input type = 'text' name ='custom_trigger' id='custom_trigger' class='textBox' style='display: none; background-color: GreenYellow' placeholder='Enter cause here' value=''  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";

				
				// print "</div>";
			print "</td>";
				if($row3){	
					reset($row3);
				}
		print "</tr>";
		print"<tr>";
			print"<td colspan='2'  align='center' >";
				print"<h3 style='color: grey'>STEP 3</h3> <h4> Behavior Description</h4>";
					echo "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='behave_class' id='behave_class'><option value=''>Choose a Behavior Descriptor</option>";
						reset($row3);
						for ($i=1;$i<6;$i++){
							$behave_class_number='behave_class_'.$i;
							if($row3[$behave_class_number]){
								echo  "<option value='$i'>$row3[$behave_class_number]</option>";
							}
						}
					echo "</select>";
			print"</td>";
		print "</tr>";
		print "<tr>";
			print "<td colspan='2' width= '50%' align='center'>";
				print"<h3 style='color: grey'>STEP 4</h3> <h4> Identify Behavior Intensity</h4>";
					echo "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='intensity' id='intensity'><option value=''>Select a Behavior Intensity</option>";
					
						for ($i=1;$i<6;$i++){
							$comment_number='comment_'.$i;
							if($row3[$comment_number]){
								echo  "<option value='$i'>$row3[$comment_number]</option>";
							}
						}
					echo "</select>";
					
			print"</td>";
				if($row3){	
					reset($row3);
				}
		print "</tr>";
	?>
		<tr>
			<td colspan='1' align='center'>
				<div id = "trigger">
					<h3 style='color: grey'>STEP 5</h3><h4>Any other unique comments about behavior?</h4>
					<textarea class="form-control form-control-ta" placeholder="Required..."  id ="specific_behavior_description" name = "specific_behavior_description"/></textarea>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='1' align='center'>
				<div id = "intervention">
					<h3 style='color: grey'>STEP 6</h3><h4>How did you manage the episode?</h4>
					<textarea class="form-control form-control-ta" placeholder="Required..."  id ="intervention" name = "intervention"/></textarea>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='1'align='center' valign='bottom'>
				<div id = "intervention_avoid">
					<h3 style='color: grey'>STEP 7</h3><h4>Did anything make the behavior more severe?</h4><br>
					<!-- <em style="font-size:10pt; line-height:0pt; color:red">Optional</em><br> -->
					<textarea class="form-control form-control-ta" placeholder="Optional..." id ="interv_a" name = "intervention_avoid"/></textarea>
				</div>
			</td>
		</tr>
		<tr>
			<td align='center'>
				<div id = "PRN">
					<h3 style='color: grey'>STEP 8</h3><h4><label> PRN Given?</label></h4>
					<?
					print"<select data-width='auto' class='selBox custom-select-background custom-select-lg mb-3'  name='PRN' onchange='show(this)' >";
						print "<optGroup>";
							print"<option value='0' selected>NO</option>";
							print"<option value='1'>YES</option>";
						print "</optGroup>";
					print"</select>";

					?>	
				</div>
			</td>
		</tr>
		<tr>
			<td align='center', colspan='6'>
				<div id='pre_PRN_observation_tag' style='display: none; color: red;'>Enter specific description of behavior which required PRN in yellow text-box.</div>
			</td>
		</tr>
		<tr>
			<td align='center', colspan='6'>
				<div>
					<textarea class="form-control form-control-ta" name='pre_PRN_observation' id='pre_PRN_observation'; style='display: none; background-color: yellow;  value=''/></textarea>
				</div>
			</td>
		</tr>
		<tr>
			<td align="center">
				<h3  style='color:grey'>Date and Time Information</h3>
			</td>
		</tr>
<!-- 		<tr>
			<td  width='50%'align='center'>
				<h3 style='color: grey'>STEP 9</h3> <h4><label> When Did Episode Take Place?</label></h4>
				<input onchange="checkDate()" class="form-control custom-select-lg custom-select-background" id="datetimepicker5" name="datetimepicker" type="text" placeholder='Touch to enter' data-width='auto'/>

			</td>
		</tr> -->
		<tr>
			<td  align='center'>
				<h3 style='color: grey'>STEP 9</h3> <h4><label> When Did Episode Take Place?</label></h4>


					<?
					####  Parsing time formats for html5 datetime-local form element
					$now = date("Y-m-d H:i");
					$now = str_replace(" ", "T",$now);
					$now = str_replace(":pm", "", $now);

					$min_time = date("Y-m-d H:i",strtotime('-5 days'));
					$min_time = str_replace(" ", "T",$min_time);
					$min_time = str_replace(":pm", "", $min_time);
					?>

					<input  type="datetime-local"
							onchange="checkDate()"
							class="form-control custom-select-lg custom-select-background col-xs-4" 
							width="auto" 
							id="datetimepicker2"
							name="datetimepicker" value="<? echo $now ?>"
							min="<? echo $min_time ?>" 
							max="<? echo $now ?>">
			</td>
		</tr>


		<tr>
			<td width='50%'align='center'>
				<h3 style='color: grey'>STEP 10</h3><h4><label> How Long Did Episode Last?</label></h4>
				<select class='custom-select-background custom-select-lg mb-3' data-width='auto' name = "duration" id="duration" onchange='validate_form()'>
				<option value = "">Choose Minutes</option>
				<?
				for($t = 5;$t <= 90;$t +=5){
					print "<option value = $t>$t</option>";
				}
				?>
					<option value = "105">Greater than 90 minutes</option>
				</select>
			</td>
		</tr>
	</table>
	<div id = "submit">
		<input 	type = "submit"
				name = "submit"
				value = "Record Episode"/>
	</div>	
	</form>
<?build_footer_pg()?>
</body>
<script type="text/javascript">jQuery('#datetimepicker5').datetimepicker({
 datepicker:true,
 formatTime:'g:i a',
  allowTimes:['00:00 am','00:30 am','01:00 am','01:30 am','02:00 am','01:30 am','02:30 am','03:00 am','03:30 am','04:00 am','04:30 am','05:00 am','05:30 am',
 	'06:00 am','06:30 am','07:00 am','07:30 am','08:00 am','08:30 am','09:00 am','09:30 am','10:00 am','10:30 am','11:00 am','11:30 am',
 	'12:00 pm','01:00 pm','01:30 pm','02:00 pm','01:30 pm','02:30 pm','03:00 pm','03:30 pm','04:00 pm','04:30 pm','05:00 pm','05:30 pm',
 	'06:00 pm','06:30 pm','07:00 pm','07:30 pm','08:00 pm','08:30 pm','09:00 pm','09:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm']
});
</script>
<script type="text/javascript">$('#cal_button').click(function(){
  $('#datetimepicker5').datetimepicker('show'); //support hide,show and destroy command
});
</script>
</html>
