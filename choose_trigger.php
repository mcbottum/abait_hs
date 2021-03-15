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
</title>
<script type='text/javascript'>

function validate_form()
{
	valid=true;
	var alertstring=new String("");
	var rb = radiobutton(document.getElementsByName('trig'));

	if(rb==false){
		alertstring=alertstring+"\nSelect a Trigger.";
		valid=false
	}	//end call for PRN radio button check
	
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message
	
	function radiobutton(rb)
	{
		for(var i=0; i < rb.length;i++){
			if(rb[i].checked){
				return true;
			}
		}
			return false;
	}//end radiobutton

	return valid;
}//end form validation function	

// function reload(selTag) {
// 	if (selTag.value == 'new_intervention') {
// 		val2 = form1.residentkey;
// 	for (var index = 0; index < val2.length; index++) {
// 	}
// 			self.location='resident_map.php?k='+val2[0].value;
// 		}
// }

function reload(trig,scale,id) {
	//var val = form1.scale_name; //if use this, don't need attribute  does not work in IE
	//self.location='resident_scale.php?behavior='+var.value;
	if(trig=='new'){
		self.location='resident_map.php?k='+id+'&&scale_name='+scale;
	}else{
		self.location='ABAIT_scale.php?trig='+trig;
	}
}

</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
<style>
    table.local thead th{
        width:300px;
        padding: 0px !important;
    }
    table.local tbody{
        max-height: 400px;
    }

    table.local tbody td{
    	width:300px;
    	padding: 0px !important;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
</style>
</head>
<fieldset>
<body>
<!-- <div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left"> -->
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
<form 	name='form1' 
		onsubmit='return validate_form()' 
		action = "ABAIT_scale.php" 
		method = "post">
<?	
	$scale_name=$_SESSION['scale_name'];
	$resident_choice=$_REQUEST['resident_choice'];

	print"<input type='hidden' value='$resident_choice' name='residentkey'>";
	print"<input type='hidden' value='$scale_name' name='scale_name'>";


	$_SESSION['residentkey']=$resident_choice;
	$sql="SELECT * FROM residentpersonaldata WHERE residentkey='$resident_choice'";
	$sql1="SELECT * FROM behavior_maps WHERE residentkey='$resident_choice' AND behavior LIKE('$scale_name')";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
	$resident=mysqli_real_escape_string($conn,$sql);
	$resident=mysqli_query($conn,$sql);
	$scale=mysqli_query($conn,$sql1);
	$row=mysqli_fetch_assoc($resident);
	$_SESSION['first']=$row['first'];
	$_SESSION['last']=$row['last'];	
$counter=0;
print"<div id='head'>\n";
print $scale_name.' Scale Triggers for '.$_SESSION['first'] .'  '.$_SESSION['last'];

// go to log unscalled behavior if no scale present
// if(!$row=mysqli_fetch_assoc($scale)){
//     header('Location: resident_map.php?k='.$resident_choice.'&&scale_name='.$scale_name);
//     exit();
// }
mysqli_data_seek($scale, 0);

print"</div>\n";
	print "<table class='center scroll local hover' border='1' bgcolor='white'>";
		print "<p></p>";
		print"<thead>";
			print"<tr align='center'><th><b>Select Trigger</b></th></tr>\n";
		print"</thead>";
		print"<tbody>";
			while($row=mysqli_fetch_assoc($scale)){
				$counter=$counter+1;
				print"<tr>";
					print"<td>";
						print"<label><span class='tab'><input type = 'radio'
							name = 'trig'
							id = 'trig'
							onchange='reload(value)'
							value=$row[mapkey]></span>";	
						print "<span class='tab'>$row[trig]</span></label>";
					print"</td>";
				print"</tr>";
			}//end while
			print"<tr>";
				print"<td>";
					print"<label><span class='tab'><input type = 'radio' name = 'trig' id = 'trig' onchange='reload(value,\"".$scale_name."\",\"".$resident_choice."\")'
						value = 'new'></span>";
					print"<span class='tab'>New Trigger</span></label>";
				print"</td>";
			print"</tr>";
		print"</tbody>";
	print"</table>\n";
print"</fieldset>\n";
build_footer()?>
	</body>
