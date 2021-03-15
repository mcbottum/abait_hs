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
<link 	rel="stylesheet"
		type="text/css"
		href="datetimepicker-master/jquery.datetimepicker.css"/ >

<script type='text/javascript' src="datetimepicker-master/jquery.js"></script>
<script type='text/javascript' src="datetimepicker-master/jquery.datetimepicker.js"></script>

<script type="text/javascript" language="JavaScript">
function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if (document.form1.date.checked == false&&document.form1.datetimepicker.value=="" ) {
		alertstring=alertstring+"\n-either today or other date of episode-";
		document.getElementById("date_header").style.background = "yellow";
		// document.form1.date.style.background = "yellow url('select_if_now.png') 96% / 100% no-repeat";
		document.form1.datetimepicker.style.background = "Yellow";

		valid=false;
	}else if (document.form1.date.checked || true&&document.form1.datetimepicker.value!="" ) {
		// document.form1.date.style.background = "#33aaff url('now.png') 96% / 100% no-repeat";
		document.form1.datetimepicker.style.background = "white";

	}//end date of episode check

	if(document.form1.duration.selectedIndex==""){
		alertstring=alertstring+"\n-Duration of the Behavior Episode-";
		document.form1.duration.style.background = "Yellow";
		valid=false;
	}else{
		document.form1.duration.style.background = "white";
	}//end ampm check

	var rb=radiobutton(document.form1.intensityB);
	if(rb==false){
		alertstring=alertstring+"\n-behavior intensity BEFORE interventions-";
		//document.form1.intensityB[0].style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	var rb=radiobutton(document.form1.intensityA1);
	if(rb==false){
		alertstring=alertstring+"\n-behavior intensity After FIRST intervention-";
		//document.form1.intensityB.style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	var rb=radiobutton(document.form1.intensityA2);
	if(rb==false&&document.form1.intervention2.selectedIndex!=''){
		alertstring=alertstring+"\n-behavior intensity After SECOND intervention-";
		//document.form1.intensityB.style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	var rb=radiobutton(document.form1.intensityA3);
	if(rb==false&&document.form1.intervention3.selectedIndex!=''){
		alertstring=alertstring+"\n-behavior intensity After THIRD intervention-";
		//document.form1.intensityB.style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	var rb=radiobutton(document.form1.intensityA4);
	if(rb==false&&document.form1.intervention4.selectedIndex!=''){
		alertstring=alertstring+"\n-behavior intensity After FOURTH intervention-";
		//document.form1.intensityB.style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	var rb=radiobutton(document.form1.intensityA5);
	if(rb==false&&document.form1.intervention5.selectedIndex!=''){
		alertstring=alertstring+"\n-behavior intensity After FIFTH intervention-";
		//document.form1.intensityB.style.background = "Yellow";
		valid=false
	}	//end call for intensity Before intervention radio button check

	// checking the other way
	var rb=radiobutton(document.form1.intensityA2);
	if(rb==true&&document.form1.intervention2.selectedIndex==''){
		alertstring=alertstring+"\n-Select SECOND intervention-";
		document.form1.intervention2.style.background = "Yellow";
		valid=false
	}
	var rb=radiobutton(document.form1.intensityA3);
	if(rb==true&&document.form1.intervention3.selectedIndex==''){
		alertstring=alertstring+"\n-Select THIRD intervention-";
		document.form1.intervention3.style.background = "Yellow";
		valid=false
	}
	var rb=radiobutton(document.form1.intensityA4);
	if(rb==true&&document.form1.intervention4.selectedIndex==''){
		alertstring=alertstring+"\n-Select FOURTH intervention-";
		document.form1.intervention4.style.background = "Yellow";
		valid=false
	}
	var rb=radiobutton(document.form1.intensityA5);
	if(rb==true&&document.form1.intervention5.selectedIndex==''){
		alertstring=alertstring+"\n-Select FIFTH intervention-";
		document.form1.intervention5.style.background = "Yellow";
		valid=false
	}

	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message

	function radiobutton(rb)
	{
		var count=-1;
		for(var i=rb.length-1;i>-1;i--){
			if(rb[i].checked){
				count=i;
				i=-1;
			}
		}
		if(count>-1){
			return true;
		}else{
			return false;
		}
	}//end radiobutton

	return valid;
}//end form validation function


function show( selTag ) {
	obj1 = document.getElementById("behavior_description_tag");
	obj = document.getElementById("behavior_description");
	if ( selTag.selectedIndex == 1 ) {
		obj1.style.display = "block";
		obj.style.display = "block";
		obj1.style.align="center";
	} else {
		obj1.style.display = "none";
		obj.style.display = "none";
	}
}

function checked(id){
	return document.getElementById(id).checked;
}

function hide(id){
	var hideObj = document.getElementsByName("datetimepicker")[0];
	var displayToday = document.getElementById("datetimepicker5_cell");
	if(checked(id)){
		var today = new Date();
		hideObj.style.display = "none";
		displayToday.innerHTML = today.toString();
	}else{
		
		displayToday.innerHTML = "<input placeholder='Touch to enter' style='display: block' class='datetimepicker5' name = 'datetimepicker' type='text' >"

	}

}

function reload(selTag) {
	if (selTag.value == 'new_intervention') {
		val2 = form1.residentkey.value;


		self.location='resident_map.php?k='+val2;

	}
}

function clicked(button){
	document.getElementById('PRN').value=1;
	document.getElementById('PRN').selectedIndex=1;
	show(document.getElementById('PRN'));
}

</script>

<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
<style>
    table.local thead th{
        width:175px;
        background-color: #D8D8D8 ;
    }
    table.local tbody{
        max-height: 400px;
    }
    table.local tbody td{
        width:175px;
        background-color: #D0D0D0;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
    th{
    	background-color: #c3c3c3;
    }
    table.scaleTables{
    	background-color: #F8F8F8;
    }

</style>
</head>
<body>
<fieldset>
<!-- <div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left"> -->
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
<form 	name="form1"
		onsubmit='return validate_form()'
		action = "ABAIT_scale_datalog.php"
		method = "post">
<?
$residentkey=$_SESSION['residentkey'];
// $residentkey=$_REQUEST['residentkey'];
// $_SESSION['residentkey']=$residentkey;
$sn=str_replace('_',' ',$_REQUEST['scale_name']);
if($sn==''){
	$scale_name=$_SESSION['scale_name'];
}else{
$_SESSION['scale_name']=$sn;
$scale_name=$sn;
}

if(isset($_GET['trig'])){
	$trigger=$_GET['trig'];
}else{
	$trigger=$_REQUEST['trig'];
}
$_SESSION['trigger']=$trigger;
$conn1=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
$sql1="SELECT * FROM behavior_maps WHERE mapkey='$trigger'";
$sql2="SELECT SUM(intervention_score_1), SUM(intervention_score_2), SUM(intervention_score_3), SUM(intervention_score_4), SUM(intervention_score_5), SUM(intervention_score_6) FROM behavior_map_data WHERE mapkey='$trigger'";
$sql3="SELECT * FROM scale_table WHERE scale_name LIKE '$_SESSION[scale_name]%'";
$conn1=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
mysqli_select_db($_SESSION['database']);
$scale=mysqli_query($sql1,$conn1);
$score_sum=mysqli_query($sql2,$conn1);
$intensity=mysqli_query($sql3,$conn1);

$first=$_SESSION['first'];
$last=$_SESSION['last'];
print"<div id='head'>\n";
print $scale_name." Scale for  ".$first."  ".$last.$residentkey;
print"</div>\n";
print "<input type='hidden' id='residentkey', name='residentkey' value='".$residentkey."'>";
// print "<input placeholder='Touch to enter' style='display: block' id='datetimepicker5Holder' style='display: none' name = 'datetimepicker' type='text' >";
?>

			<h3 align='center'><label>Date and Time Information</label></h3>
			<table class='align_center scaleTables ' width='100%' border="1">
				<tr>
					<th width="307" align="center" > Select if episode is taking place now</td>
					<th width="307" align="center"> Or select date and time</td>
					<th align="center">Duration of episode</th>
				</tr>
				<tr>
					<td height='40' align="center" id = "date_header">

						<input	type = "checkbox"
								name = "date"
								id = "now"
								class="checkbox"
								onchange = "hide('now')"
								value = "1"/>
					</td>
					<td height='40' align="center" id="datetimepicker5_cell">
					<div id="datetimepicker5">
					<input placeholder='Touch to enter' style="display: block"  class="datetimepicker5" name = 'datetimepicker' type="text" >
					</div>
				</td>



				<td height='40'  align="center">

					<select name = "duration" id="durat">
					<option value = "">Choose Minutes</option>
					<?
					for($t = 5;$t <= 90;$t +=5){
						print "<option value = $t>$t</option>";
					}
					?>
						<option value = "105">Greater than 90 minutes</option>
					</select>

				</td>

				</td>
			</tr>

		</table>
	<?
	print"<h3 align='center'><label>Select Intervention Data</label></h3>\n";
	print"<table class='scaleTables' width='100%' border='1'>\n";
	print"<tr><th width='150'>Intervention 1</th><th width='150'>Intervention 2</th><th width='150'>Intervention 3</th><th width='150'>Intervention 4</th><th width='150'>Intervention 5</th><th width='150'>PRN Given</th></tr>\n";
	$row=mysqli_fetch_assoc($scale);
	$row2=mysqli_fetch_assoc($score_sum);
	$row=array($row['intervention_1'], $row['intervention_2'], $row['intervention_3'], $row['intervention_4'], $row['intervention_5'],$row['intervention_6']);
	$intervention_rank=array(1,2,3,4,5,6);
	array_multisort($row2,$row,$intervention_rank);
		print"<tr>\n";
			for($int=1;	$int<7;	$int++){
				print"<td>\n";
					if($int<6){

						//print"<select style='width: 150px' name='intervention$int'>\n";
						//new
						$t_intervention = 'intervention'.$int;

						print"<select style='width: 150px' name ='intervention$int' id='intervention' onchange='reload(this)'>";
						//end new
							if($int==1){
								$s=5;
							}else{$s=6;
							}
								for($r=$s;	$r>-1;	$r--){
									$intervention='intervention_'.$r;
									print "<option value=$intervention_rank[$r]>$row[$r]</option>\n";
								}
						?><option class='red' style='color:blue; font-weight:bold' value='new_intervention' style='color:red'>New Intervention</option><?
					}else{
						print"<select name='intervention6' id='PRN' onchange='show(this)' >";
							print"<option value='0' selected>NO</option>";
							print"<option value='1'>YES</option>";

						}

					print "</select>\n";


//new
					//$intervention_t='intervention_t_'.$int;  	//for name
					//$intervention_=$t_intervention.'_';			//for id
					//print"<input type = 'text' name ='$intervention_t' id='$intervention_' style='display: none; background-color: GreenYellow' value='Enter New Intervention' size='23' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";
// end new

				print"</td>\n";
			}
			print"</tr>";

					print"<tr id='behavior_tr1'>";
					print"<td colspan='6' align='center'>";
						print"<div id='behavior_description_tag' style='display: none; color: red;'>Enter specific description of behavior which required PRN in yellow text-box.</div>";

					print "</td></tr>";
					print "<tr id='behavior_tr2'>";
						print "<td  colspan='6' align='center'>";
						print "<div>";

						print"<input type = 'text' name='behavior_description' id='behavior_description'; style='display: none; background-color: yellow; align:center; width:99%;' value=''/>";
						?>
						</div>
					</td>
				</tr>
		</tr>
		</table>

			<div id = "intensity">
					<h3 align='center'><label>Behavior Intensity <span style='color:red'>BEFORE</span> then AFTER Intervention</label></h3>
					<table width='100%' border='1'>
					<tr>
						<th width='50%'  align='center'>Behavior Intensity</th>
						<th align='center'><span style='color:red'>BEFORE</span> Intervention</th>
						<th align='center'>AFTER 1st Intervention</th>
						<th align='center'>AFTER 2nd</th>
						<th align='center'>3rd</th>
						<th align='center'>4th</th>
						<th align='center'>5th</th>
						<th align='center'>PRN</th>
					</tr>

<?
$row3=mysqli_fetch_assoc($intensity);
$color_array = ['#FF000','#00FF00','#ADFF2F','#FFD700','#FF7F50','#FF0000'];
// $color_array = ['red','orange','yellow','lightgreen','green','blue'];
for($i=1;$i<6;$i++){
		print"<tr class='raised' style='background:$color_array[$i]'>";
		$comment='comment_'.$i;
			print"<td style='padding-left:4px'>$row3[$comment]</td>";
					print"<td align='center'><label><input type='radio'
								name='intensityB'
								id='intensity$i'
								value=$i></label></td>";
				for($j=1;$j<7;$j++){
					if($j<6){
						print"<td  align='center'><label><input type='radio'
									name='intensityA$j'
									id='intensity$i'
									value=$i></label></td>";
					}else{
						print"<td  align='center'><label><input type='radio'
									name='intensityA$j'
									id='intensity$i'
									value=$i
									onclick='clicked(this)'></label></td>";
					}
				}//end $j for
		print"</tr>";
}//end $i for

?>
			</table>
			</div>
			<div id="submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Scale Data"/>
			</div>
	</fieldset>
	<?build_footer()?>
</body>
<script type="text/javascript">
$("#datetimepicker5").load("ABAIT_scale.php #datetimepicker5 >*");
$('.datetimepicker5').datetimepicker({
 datepicker:true,
 formatTime:'g:i a',
  allowTimes:['00:00 am','00:30 am','01:00 am','01:30 am','02:00 am','01:30 am','02:30 am','03:00 am','03:30 am','04:00 am','04:30 am','05:00 am','05:30 am',
 	'06:00 am','06:30 am','07:00 am','07:30 am','08:00 am','08:30 am','09:00 am','09:30 am','10:00 am','10:30 am','11:00 am','11:30 am',
 	'12:00 pm','01:00 pm','01:30 pm','02:00 pm','01:30 pm','02:30 pm','03:00 pm','03:30 pm','04:00 pm','04:30 pm','05:00 pm','05:30 pm',
 	'06:00 pm','06:30 pm','07:00 pm','07:30 pm','08:00 pm','08:30 pm','09:00 pm','09:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm']
 // allowTimes:['00:00','00:30','01:00','01:30','02:00','01:30','02:30','03:00','03:30','04:00','04:30','05:00','05:30',
 // 	'06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30',
 // 	'13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30',
 // 	'20:00','20:30','21:00','21:30','22:00','22:30','23:00P','11:30PM']
});
</script>
<script type="text/javascript">
$('#image_button').click(function(){
  $('#datetimepicker4').datetimepicker('show'); //support hide,show and destroy command
});

</script>
</html>
