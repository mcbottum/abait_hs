<?
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
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<script type="text/javascript" language="JavaScript">
function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if(document.create_map.trigger.value=="")
	{
		alertstring=alertstring+"\n-enter at least one Trigger-";
		document.form.trigger.style.background = "Yellow";
		valid=false;
	}else{
		document.form.trigger.style.background = "white";
	}//end specific_behavior_description

	if(document.form.intervention.selectedIndex==""){
		alertstring=alertstring+"\n-choose at least one Intervention-";
		document.form.intervention.style.background = "Yellow";
		valid=false;
	}else{
		document.form.intervention.style.background = "white";
	}//end scale_name check
	
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message
	return valid;
}//end form validation function	

function verifyDelete(id){
	if(document.getElementById(id).checked != false){
		alert("Please note, record will be permanently removed!!")
	}
}

function reload(form, id, population){
	var checkedValue = document.getElementById(id).value;
    var intervention_array = [];
    var location_vars = '&id=' + checkedValue;
    var trigger = document.create_map.trigger0.value
    for(var i=0; i<checkedValue; i++){
    	location_vars += '&trigger' + i + '=' + document.getElementById('trigger'+i).value;
    	for(var j=1; j<7; j++){
    		if(document.getElementById('intervention_'+j+i).value==1){
    			location_vars += '&intervention_t_' + j + i + '=' + document.getElementById('intervention_'+j+i+'_').value;
    		}else{
    			location_vars += '&intervention_' + j + i + '=' + document.getElementById('intervention_'+j+i).value;
    		}
    	}
    }
    self.location='ABAIT_create_map.php?'+ id +'='+ checkedValue + '&Population='+ population + location_vars;


    // self.location='ABAIT_create_map.php?'+ id +'='+ checkedValue + '&trigger' + '=' + trigger;
	// self.location='ABAIT_create_map.php?add_next='+ checkedValue  + (var k for k in intervention_array);
}

function isInArray(value, array) {
  return array.indexOf(value) > -1;
}

function show(selTag) {
	var id=selTag.id;
	var obj=document.getElementById(id);
	var selectedValue = obj.value; 
	console.log(selectedValue);
	if(selectedValue==1) {
		var idText=selTag.id+'_';
		var objText=document.getElementById(idText);
		objText.style.display = "block";
	} else {
		obj.style.display = "block";
	}
}

function seeAllTriggers(population, id, checkedValue, residentkey=null) {
	// var checkedValue = document.getElementById(id).value;
    var location_vars = id+'='+checkedValue;
    for(var i=0; i<checkedValue; i++){
    	location_vars += '@trigger' + i + '=' + document.getElementById('trigger'+i).value;
    	for(var j=1; j<7; j++){
    		if(document.getElementById('intervention_'+j+i).value==1){
    			location_vars += '@intervention_t_' + j + i + '=' + document.getElementById('intervention_'+j+i+'_').value;
    		}else{
    			location_vars += '@intervention_' + j + i + '=' + document.getElementById('intervention_'+j+i).value;
    		}
    	}
    }
	self.location='ABAIT_trigger_intervention_catalog.php?Population='+population+'&number_of_scales='+checkedValue+'&package='+location_vars+'&id='+residentkey;
}



</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT_admin.css">
<style>
	 .red {
		  color: red;
	 }


    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    table.local thead th{
        width:100%;
        text-align:center;
    }

    table.local tbody td{
        width:100%;
        text-align:center;
    }

    s_span.wide{
		width:170px !important;
		padding-right: 5px;
    }
    span.wide{
		width:130px !important;
		padding-right: 5px;
    }
    span.mid{
		width:90px !important;
		padding-right: 5px;
    }
    span.narrow{
    	width:75px !important;
    }
    span.vnarrow{
    	width:50px !important;
    }

        table.local tbody{
        max-height: 400px;
    }

    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
/*	table.scroll tbody {
	  max-height: 200px !important;
	  overflow-y: auto;
	  overflow-x: hidden;
	}*/

</style>
</head>

<body>
<div id="body" style="margin: 0px auto 0px auto; text-align: left">
<?

//FOR RELOAD FORM
if(isset($_REQUEST['add_one'])){
	$end = $_REQUEST['add_one']+1;
}elseif(isset($_REQUEST['subtract_one'])){
	$end = $_REQUEST['subtract_one']-1;
}elseif(isset($_REQUEST['add_none'])){
	$end = $_REQUEST['add_none'];
}else{
	$end = 1;
}
if($end>6){
	$end=6;
}

// if(isset($_SESSION['r'])){
if(isset($end)){

		for ($dataline=0; $dataline<=$end;$dataline ++){
			if(isset($_REQUEST['trigger'.$dataline])){
				${'trigger_val'.$dataline} = $_REQUEST['trigger'.$dataline];
			}
				for($i=1;$i<7;$i++){
					if(isset($_REQUEST['intervention_t_'.$i.$dataline])){
						$new_intervention=$_REQUEST['intervention_t_'.$i.$dataline];
						if($new_intervention!='Enter New Intervention'){

							${'intervention_val'.$i.$dataline}='custom';
							${'intervention_val_t'.$i.$dataline}=$new_intervention;
						}
					
					}elseif(isset($_REQUEST['intervention_'.$i.$dataline])){
						${'intervention_val'.$i.$dataline}=str_replace('_',' ',$_REQUEST['intervention_'.$i.$dataline]);
					}
				} //end for
		// 	} //end if
		} //end dataline for
} //end isset session r


if($_SESSION['cgfirst']!=""){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
	}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
}
print"<fieldset>";
build_page($_SESSION['privilege'],$cgfirst);

//print $_SESSION['Target_Population'];

$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
$Population_temp=$_SESSION['Target_Population'];
//$_SESSION['Target_Population']=$Population_strip;

$creation_date=null;
// $residentkey=$_REQUEST['residentkey'];
if(isset($_REQUEST['makemap'])){
	$makemap=$_REQUEST['makemap'];
}else{
	$makemap='';
}

if($makemap){
	$residents_to_map=$makemap;
	$delim='_';
	$resident_behavior=explode($delim,$residents_to_map);
	$residentkey=$resident_behavior[0];
	$behavior=$resident_behavior[1];
	$_SESSION['residentkey']=$residentkey;
	$behavior=str_replace('_',' ',$behavior);
	$_SESSION['behavior']=$behavior;
	$resident=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM residentpersonaldata WHERE residentkey='$residentkey'"));
	$scale=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM scale_table WHERE scale_name='$behavior'"));
	$_SESSION['first']=$resident['first'];
	$_SESSION['last']=$resident['last'];
	if(isset($_GET['Population'])&&$_GET['Population']){
		$Population=$_GET['Population'];
	}else{
		$Population=$resident['Target_Population'];
	}
}else{
	$behavior=$_SESSION['behavior'];
	$residentkey=$_SESSION['residentkey'];
}

if(isset($_GET['Population'])&&$_GET['Population']){
	$Population=$_GET['Population'];
}

$first=$_SESSION['first'];
$last=$_SESSION['last'];

$scale=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM scale_table WHERE scale_name='$behavior'"));
$un_maps=mysqli_query($conn,"SELECT * FROM resident_mapping WHERE residentkey='$residentkey' and behavior='$behavior' ORDER by date");

$un_maps_check=mysqli_fetch_assoc($un_maps);
// this resets index
mysqli_data_seek($un_maps, 0);

//CHECK IF DATA EXISTS
if(empty($un_maps_check)){
	$data=False;
}else{
	$data=True;
}

//Get creation data of latest scale or set to really early
$creation_date=mysqli_fetch_assoc(mysqli_query($conn,"SELECT creation_date FROM behavior_maps WHERE residentkey='$residentkey' AND behavior='$behavior' ORDER BY creation_date DESC LIMIT 1"));

$creation_date=$creation_date['creation_date'];

if(empty($creation_date)){
	$creation_date='2010-01-01';
}

//array of all interventions mapped to behavior
$intervention_sum=array();
$inters = mysqli_query($conn,"SELECT *  FROM interventions WHERE behavior_class='$behavior'");
while ($inter=mysqli_fetch_assoc($inters)){
	$intervention_sum[$inter['intervention']]=1;
}
// arsort($intervention_sum); //For future

print"<div id='head'><h4> $behavior Behavior Episodes for $first $last</h4></div>\n";


print"<form name='create_map' action = 'ABAIT_create_map_log.php' onsubmit='return validate_form()' method = 'post'>";

$raw_triggers = array();

$r=0;
		print "<table align='center'>";//table for more info copy this line

				if($data){
					print "<tr><td align='center'><h3> Table Displays All Recorded $behavior Related Episodes to Date</h3></td></tr>\n";
					print "<tr><td align='right'>";
					?>
					<input type='submit' value='Info' onClick="alert('Use the data presented in this table to create a Trigger/Intervention Scale for your resident.  Up to six interventions may be entered per trigger.  Trigger and intervention descriptions should be as brief though descriptive as possible (One to three word descriptors). NOTE: Please avoid using any resident identifying information such as NAMES or IDs.');return false">
					<?	
					print "</td></tr>";
					print "<tr><td  align='center' style='color:red'><h4>Red Data Indicates Unscaled Behavior Recorded <em>After</em> Most Recent Scale Creation ($creation_date)</h4></td></tr>\n";
						print "<tr><td colspan='2'>";//table in table data for more info

                    print"<table class='scroll hover local'  bgcolor='white' cellpadding='3'>";
                        print "<thead>";
                            print"<tr>";
                                print"<th>";
                                    print "<span class='tab mid'>Event Date Duration (min)</span>";
                                    print"<span class='tab wide'>Trigger</span>";
                                    print"<span class='tab wide'>Behavior Description</span>";
                                    print"<span class='tab wide'>Intensity</span>";
                                    print"<span class='tab wide'>Most Effective Intervention</span>";
                                    print"<span class='tab wide'>Intervention to Avoid</span>";
                                    print"<span class='tab vnarrow'>PRN</span>";
                                    print"<span class='tab vnarrow'>Plan Created</span>";
                                    print"<span class='tab narrow'>Del</span>";
                                print"</th>";
                            print"</tr>";
                        print "</thead>";



                        print "<tbody>";
							
							while($un_map=mysqli_fetch_assoc($un_maps)){
								$comment="comment_".$un_map['intensity'];
								$delete_id="delete_".$un_map['mapkey'];

								if(strtotime($creation_date)<strtotime($un_map['date'])&&$creation_date!=null&&$un_map['scale_created']==0){
								
									$r=$r+1;
									$col='red';
									$raw_triggers[] = $un_map['trigger'];
								}elseif($creation_date==null){
									$col='black';
								}else{
									$col='black';
								}
                                print"<tr style='color:$col'>";
                                    print"<td>";
                                        print "<label>";

                                            print"<span class='tab mid'>$un_map[date] $un_map[time] ($un_map[duration])</span>";
                                            print"<span class='tab wide'>$un_map[trigger]</span>";
                                            print"<span class='tab wide'>$un_map[behavior_description]</span>";
                                            print"<span class='tab wide'>$scale[$comment] ($un_map[intensity])</span>";
                                            print"<span class='tab wide'>$un_map[intervention]</span>";
                                            print"<span class='tab wide'>$un_map[intervention_avoid]</span>";
                                           

											if($un_map['PRN']=='1'){
                                            	print"<span class='tab vnarrow'>Yes</span>";
                                            }else{
                                            	print"<span class='tab vnarrow'>No</span>";
                                            }
                                           
                                            print"<span class='tab vnarrow'><input type='checkbox' name='scales_created[]' id='$un_map[mapkey]' value='$un_map[mapkey]' " .(($un_map['scale_created']) ? " checked='checked'" : "") . "></span>";
                                         
                                            print"<span onClick='verifyDelete(\"$delete_id\")' class='tab narrow'><input type='checkbox' name='delete_obs[]' id='$delete_id' value='$un_map[mapkey]'></span>";
                                        print"</label>";
                                    print"</td>";
                                print"</tr>";
                            }
                            print"</tbody>";
						print "</table>";
					print "</td></tr>";//end td for table COPY FROM HERE

				}else{
					print "<tr><td><h4>No Behavior Data of the type: $behavior has been recorded</h4></td></tr>\n";
				}

			print "</table>"; //end table notation for more data  TO HERE

			print"<input type='hidden' name='scale_behavior' value='$behavior'>";
			print"<input type='hidden' name='scale_resident' value='$first $last'>";

				$_SESSION['r']=$r;

			

			print "<table  width='100%' cellpadding='10'>";
				print "<tr align='center'>";
					// print "<td colspan='2'>";
					// 	print"<h3> Enter $behavior Data Below to Create $first $last's Behavior Scale</h3>";
					// print "</td>";
					print "<td >";
					
						print "<input	type = 'button'
									name = 'add_none'
									id = 'add_none1'
									value = 'Click for List of All Triggers and Interventions'
									onClick=\"seeAllTriggers('$Population','add_none','$end')\"/>\n";
					print "</td>";
				print "</tr>";
				print "<tr align='center'>";
					print "<td >";
						
						print "<input	type = 'button'
									name = 'add_none'
									id = 'add_none2'
									value = 'Click for List of Current Resident Triggers'
									onClick=\"seeAllTriggers('$Population','add_none','$end', '$residentkey')\"/>\n";
					print "</td>";
				print "</tr>";
			print "</table>";
				print"<h4 align='center' > Enter <em>Trigger Names</em> then Choose <em>Interventions</em> listed in order of historical effectivness or select New Intervention and enter single key words and/or short phrases as descriptors in the green \"New Intervention\" drop down textboxes.</h4>\n";
				print"<div id = 'trigger'>\n";

		print "<table align='center' border=1>";
		for ($dataline=0; $dataline<$r;$dataline ++){
			$trigger=NULL;	
			$trigger=$_REQUEST['trigger'.$dataline];
			if($trigger){
				for($i=1;$i<7;$i++){
					if(isset($_REQUEST['intervention_t_'.$i.$dataline])){
						$new_intervention=$_REQUEST['intervention_t_'.$i.$dataline];
					}else{
						$new_intervention='';
					}

					if($new_intervention!='Enter New Intervention'){
						${'intervention_'.$i}=$new_intervention;
					}else{	
						${'intervention_'.$i}=str_replace('_',' ',$_REQUEST['intervention_'.$i.$dataline]);
					}
				}		

			}
		}// end for

				
					print "<tr>\n";

					if($data){ 
						if($end<$r&&(!isset($_REQUEST['add_one'])||!isset($_REQUEST['subtract_one']))){
							$m=$r;
							$end=$r;
						}
						if($end>5){
							$end=5;
						}
							for($t=0;$t<$end;$t++){
								// $end=$m;
								$count=$t+1;
								print "<td align='center'width='50'>";

									$trigger='trigger'.$t;

										if(isset(${'trigger_val'.$t})){
											$trigger_val = ${'trigger_val'.$t};
											print"<input type='text' name ='$trigger' id='$trigger'  class='textBox'  placeholder='Enter Trigger' value='$trigger_val'/>\n";
										}else{
											// Per Baddger prairie request, don't autofill these:
											// if($t<count($raw_triggers)){
											// 	print"<input type = 'text' name ='$trigger' id='$trigger' class='textBox' value='$raw_triggers[$t]'/>\n";
											// }else{
											// 	print"<input type = 'text' name ='$trigger' id='$trigger' class='textBox'/>\n";
											// }
											print"<input type = 'text' name ='$trigger' id='$trigger' class='textBox'/>\n";
										}

										$selected_array = array();
										
											for($k=1;$k<7;$k++){
												if(isset($_REQUEST['selectedArray'])){
													$selected_array = $_REQUEST['selectedArray'];
												}else{
													$selectedArray = array();
												}
												$intervention='intervention_'.$k.$t;
												
												print"<select style='width: 150px' name='$intervention' id='$intervention' onchange='show(this)'><option value='' selected>Intervention$k-$count</option>";
													?>
														<option class='red' style='color:blue; font-weight:bold' value='1' style='color:red'>New Intervention</option>
													<?
													foreach($intervention_sum as $key => $values){
														$key_=str_replace(' ','_',$key);

											

														if($key!='PRN'){
															if(${'intervention_val'.$k.$t}==$key){
																	print "<option selected value=$key_>$key</option>";
																	array_push($selected_array,$key);
																	
															}elseif(${'intervention_val'.$k.$t}=='custom'){
																$key=${'intervention_val_t'.$k.$t};
																$key_=str_replace(' ','_',$key);
																print "<option selected value=$key_>$key</option>";
																
															}elseif(isset($_REQUEST['intervention_'.$k.$t])&& !empty($_REQUEST['intervention_'.$k.$t])){
																$key=$_REQUEST['intervention_'.$k.$t];
																$key_=str_replace(' ','_',$key);
																print "<option selected value=$key_>$key</option>";
															}elseif(!in_array($key, $selected_array)){
																	print"<option value=$key_>$key</option>";
															}

														}
													}

												print"</select>";
												
												$intervention_t='intervention_t_'.$k.$t;
												$intervention_=$intervention.'_';
												print"<input type = 'text' name ='$intervention_t' id='$intervention_' class='textBox' style='display: none; background-color: GreenYellow' value='Enter New Intervention'  autofocus='autofocus' onfocus=\"if
			(this.value==this.defaultValue) this.value='';\"/>";
											}
								$r = $r+1;

							}
							print "<p>\n";
							if($r>1){
								print "<input	type = 'checkbox'
										name = 'subtract_one'
										id = 'subtract_one'
										value = '$end'
										class = 'minus_checkbox'
										onchange=\"reload(this.form, 'subtract_one', '$Population')\"/>\n";
								// print "<p><-- Delete One</p>";

							}
							if($end<5){
								print "<input	type = 'checkbox'
										name = 'add_one'
										id = 'add_one'
										value = '$end'
										class = 'plus_checkbox'
										onchange=\"reload(this.form,'add_one', '$Population')\"/>\n";
							}


					}else{
							for($t=0;$t<$end;$t++){
								$count=$t+1;
								print "<td align='center'width='50'>";

									$trigger='trigger'.$t;
										if(isset(${'trigger_val'.$t})){
											$trigger_val = ${'trigger_val'.$t};
											print"<input type='text' name ='$trigger' id='$trigger'  class='textBox' placeholder='Enter Trigger' value='$trigger_val'/>\n";
										}else{
											print"<input type = 'text' name ='$trigger' id='$trigger' class='textBox' placeholder='Enter Trigger'/>\n";
										}
										$selected_array = array();
											for($k=1;$k<7;$k++){
												if(isset($_REQUEST['selectedArray'])){
													$selected_array = $_REQUEST['selectedArray'];
												}else{
													$selectedArray = array();
												}
												$intervention='intervention_'.$k.$t;
												
												print"<select style='width: 150px' name='$intervention' id='$intervention' onchange='show(this)'><option value='' selected>Intervention$k-$count</option>";
													?><option class='red' style='color:blue; font-weight:bold' value='1' style='color:red'>New Intervention</option><?
													foreach($intervention_sum as $key => $values){
														$key_=str_replace(' ','_',$key);
											

														if($key!='PRN'){
															if(${'intervention_val'.$k.$t}==$key){
																	print "<option selected value=$key_>$key</option>";
																	array_push($selected_array,$key);
															}elseif(${'intervention_val'.$k.$t}=='custom'){
																$key=${'intervention_val_t'.$k.$t};
																$key_=str_replace(' ','_',$key);
																print "<option selected value=$key_>$key</option>";
															}elseif(isset($_REQUEST['intervention_'.$k.$t])&& !empty($_REQUEST['intervention_'.$k.$t])){
																$key=$_REQUEST['intervention_'.$k.$t];
																$key_=str_replace(' ','_',$key);
																print "<option selected value=$key_>$key</option>";
															}else{
																	print"<option value=$key_>$key</option>";
															}

														}
													}

												print"</select>";

												$intervention_t='intervention_t_'.$k.$t;
												$intervention_=$intervention.'_';
												print"<input type = 'text' name ='$intervention_t' id='$intervention_' class='textBox' style='display: none; background-color: GreenYellow' value='Enter New Intervention'  autofocus='autofocus' onfocus=\"if(this.value==this.defaultValue) this.value='';\"/>";
											}
								$r = $r+1;

							}
							print "<p>\n";
							if($r>1){
								print "<input	type = 'checkbox'
										name = 'subtract_one'
										id = 'subtract_one'
										value = '$end'
										class = 'minus_checkbox'
										onchange=\"reload(this.form, 'subtract_one', '$Population')\"/>\n";
								// print "<p><-- Delete One</p>";

							}
							if($r<5){
								print "<input	type = 'checkbox'
										name = 'add_one'
										id = 'add_one'
										value = '$end'
										class = 'plus_checkbox'
										onchange=\"reload(this.form,'add_one', '$Population')\"/>\n";
							}
							
					}
				// $_SESSION['r']=$r;

				print "</table>\n";
				print"</div>\n";
			// }//end else for map log

$_SESSION['Target_Population']=$Population_temp;
?>
<br></br>
<div id='submit'>
			<input 	type = 'submit'
						name = 'submit'
						value = 'Submit Behavior Scale'/>
</div>
</form>		
</fieldset>

	<?build_footer()?>
	</div>
	</body>
</html>