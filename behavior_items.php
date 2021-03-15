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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<script type='text/javascript'>

function formValidator(){
	// Make quick references to our fields
	var date = document.getElementsByName('date');
	var month = document.getElementById('month');
	var day = document.getElementById('day');
	var year = document.getElementById('year');
	var hour = document.getElementById('hour');
	var minute = document.getElementById('minute');
	var ampm = document.getElementById('ampm');
	var durat = document.getElementById('durat');
	var trig = document.getElementById('trig');
	var interv = document.getElementById('interv');
	var behavior = document.getElementsByName('behavior');
	var intensity = document.getElementsByName('intensity');
	var PRN = document.getElementsByName('PRN');
	
	// Check each input in the order that it appears in the form!
	if(checkRadio(date, "Please choose a month or today's date")||madeSelection(month, "Please choose a month or today's date")){
		if((checkRadio(date, ""))||madeSelection(day, "Please choose a day")){
			if((checkRadio(date, ""))||madeSelection(year, "Please choose a year")){
				if(madeSelection(hour, "Please choose a hour")){
					if(madeSelection(minute, "Please choose a minute")){
						if(madeSelection(ampm, "Please choose AM or PM")){
							if(isNumeric(durat, "Please enter an episode duration")){
								if(checkRadio(behavior, "Please choose a Behavior")){
									if(checkRadio(intensity, "Please choose a behavior intensity")){
										if(notEmpty(trig, "Please enter a Trigger Description")){
											if(notEmpty(interv, "Please enter an Intervention Description")){
												if(checkRadio(PRN, "Was a PRN required?")){
													return true;
												}
											}		
										}
									}
								}			
							}
						}
					}
				}
			}
		}
	}

	return false;
}
function madeSelection(elem, helperMsg){
	if(elem.value == ""){
		elem.style.background = 'Yellow';
		alert(helperMsg);
		elem.focus();
		return false;
	}else{
		elem.style.background = 'white';
		return true;
	}
}
function isNumeric(elem, helperMsg){
	var numericExpression = /^[0-9]+$/;
	if(elem.value.match(numericExpression)){
		elem.style.background = 'white';
		return true;
	}else{
		elem.style.background = 'Yellow';
		alert(helperMsg);
		elem.focus();
		return false;
	}
}
function notEmpty(elem, helperMsg){
	if(elem.value.length == 0){
		elem.style.background = 'Yellow';
		alert(helperMsg);
		elem.focus(); // set the focus to this input
		return false;
	}
	elem.style.background = 'white';
	return true;
}
function checkRadio(elem, helperMsg) {  
 for (var i=0; i <elem.length; i++) { 
  if (elem[i].checked) { 
   return true; 
  } 
 }
	alert(helperMsg);
 	return false; 
}
</script>
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
</head>
<body>
<div id="body" style="margin: 0px auto 0px auto; text-align: left">
<?
if($_SESSION['cgfirst']&&$_SESSION['cglast']){
	$cgfirst=$_SESSION['cgfirst'];
	$cglast=$_SESSION['cglast'];
}else{
	$cgfirst=$_SESSION['adminfirst'];
	$cglast=$_SESSION['adminlast'];
}
	print"<table width='100%'>\n";
		print"<tr>\n";
			print"<td valign='bottom' align='right'>$cgfirst Logged in</td>\n";
		print"</tr>\n";
	print"</table>\n";
?>
<div id="globalheader">
	<ul id="globalnav">
		<li id="gn-home"><a href='ABAIThome.html'></a></li>
		<li id="gn-maps"><a href="caregiverhome.php">Maps</a></li>
		<li id="gn-contact"><a href="mailto:bott1@centurytel.net?subject=Feedback on ABAIT">Contact ABAIT</a></li>
		<li id="gn-logout"><a href="logout.php">Logout</a></li>
	</ul>
</div><!--/globalheader-->
<form	name= 'form1'
		onsubmit='return formValidator()'
		action = "ABAIT_log.php"
		method = "post">
<?
	$resident=$_REQUEST["resident_choice"];
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
	$resident=mysqli_real_escape_string($resident,$conn);
	$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$resident'";
	mysqli_select_db($_SESSION['database']);
	$resident=mysqli_query($sql1,$conn);
	$row1=mysqli_fetch_assoc($resident);
	$_SESSION['residentkey']=$row1['residentkey'];
	$_SESSION['first']=$row1['first'];
	$_SESSION['last']=$row1['last'];

Print "<h2>Behavior Characterization Session for $_SESSION[first] $_SESSION[last] </h2>\n";
?>
	<fieldset>	
			<table border="1">
			<caption><h3>Time and Date Information</h3></caption>
			<tr>
				<td width="400">
					<h4><div id = "date">
						<input	type = "radio"
								name = "date"
								value = "1"/>Click if episode took place today
					</div></h4>
				</td>
				<td width="525">
	
						<h4><label>Or select date</label></h4>
						Month
						<select id ="month" name = "month" >
							<option value = "">Choose</option>
							<option value = "01">January</option>
							<option value = "02">February</option>
							<option value = "03">March</option>
							<option value = "04">April</option>
							<option value = "05">May</option>
							<option value = "06">June</option>
							<option value = "07">July</option>
							<option value = "08">August</option>
							<option value = "09">September</option>
							<option value = "10">October</option>
							<option value = "11">November</option>
							<option value = "12">December</option>
						</select>
						Day
						<select name = "day" id ="day">
							<option value = "">Choose</option>
							<option value = "01">1</option>
							<option value = "02">2</option>
							<option value = "03">3</option>
							<option value = "04">4</option>
							<option value = "05">5</option>
							<option value = "06">6</option>
							<option value = "07">7</option>
							<option value = "08">8</option>
							<option value = "09">9</option>
							<option value = "10">10</option>
							<option value = "11">11</option>
							<option value = "12">12</option>
							<option value = "13">13</option>
							<option value = "14">14</option>
							<option value = "15">15</option>
							<option value = "16">16</option>
							<option value = "17">17</option>
							<option value = "18">18</option>
							<option value = "19">19</option>
							<option value = "20">20</option>
							<option value = "21">21</option>
							<option value = "22">22</option>
							<option value = "23">23</option>
							<option value = "24">24</option>
							<option value = "25">25</option>
							<option value = "26">26</option>
							<option value = "27">27</option>
							<option value = "28">28</option>
							<option value = "29">29</option>
							<option value = "30">30</option>
							<option value = "31">31</option>
						</select>
						Year
						<select name = "year" id ="year">
							<option value = "">Choose</option>
							<option value = "2010">2010</option>
							<option value = "2009">2009</option>
						</select>
				</td>
			</tr>
			<tr>
				<td>
					<div id = "time">
					<h4><label>Time of day during which episode took place</label></h4>
					Hour
						<select name = "hour" id ="hour">
							<option value = "">Choose</option>
							<option value = "01">1</option>
							<option value = "02">2</option>
							<option value = "03">3</option>
							<option value = "04">4</option>
							<option value = "05">5</option>
							<option value = "06">6</option>
							<option value = "07">7</option>
							<option value = "08">8</option>
							<option value = "09">9</option>
							<option value = "10">10</option>
							<option value = "11">11</option>
							<option value = "12">12</option>
						</select>
					Minute
						<select name = "minute" id ="minute">
							<option value = "">Choose</option>
							<option value = "00">00</option>
							<option value = "05">05</option>
							<option value = "10">10</option>
							<option value = "15">15</option>
							<option value = "20">20</option>
							<option value = "25">25</option>
							<option value = "30">30</option>
							<option value = "35">35</option>
							<option value = "40">40</option>
							<option value = "45">45</option>
							<option value = "50">50</option>
							<option value = "55">55</option>
						</select>
					AM/PM
						<select name = "ampm" id ="ampm">
							<option value = "">Choose</option>
							<option value = "AM">AM</option>
							<option value = "PM">PM</option>
						</select>
					</div>
				</td>
				<td>
					<div id = "duration">
					<h4><label>Duration of Episode</label></h4>
					<input	type = "text" id ="durat" name = "duration" />Enter number of minutes
					</div>
				</td>
			</tr>
			</table>
			<table border="1">
			<caption><h3>Behavior and Intervention Information</h3></caption>
			<tr>
				<td width="400">
				<div id = "behave">	
					<h4><label>Behavior Classification</label></h4>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "behavior"
								value = "motor"/>Motor Agitation Anxiety
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "behavior"
								value = "resistance"/>Resistance to Care
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "behavior"
								value = "vocalizations"/>Vocalizations
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "behavior"
								value = "agressiveness"/>Agressiveness
					</p>

					</div>
				</td>
				<td width="525" colspan="2">
				<div id = "intensity">	
					<h4><label>Behavior Intensity Level</label></h4>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity"
								value = "1"/>Not Present
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity"
								value = "2"/>Pacing or moving at a specfic rate, looking for someone.
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity"
								value = "3"/>Increased rate of movement mildly intrusive and easily redirectable
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity"
								value = "4"/>Rapid movement, moderately intrusive, or disruptive and difficult to redirect.
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity"
								value = "4"/>Intense movement and extremely disruptive, not redirectable.
					</p>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div id = "trigger">
						<h4><label>Trigger Description</label></h4>
						<input	type = "text" size="40"
								id ="trig"
								name = "trigger"/>
						</div>
					</td>
					<td>
						<div id = "intervention">
						<h4><label>Intervention Description</label></h4>
						<input	type = "text" size="40"
								id ="interv"
								name = "intervention"/>
						</div>
					</td>
					<td>
						<div id = "PRN">
						<h4><label>PRN</label></h4>

						<input	type = "radio"
							name = "PRN"
							id = "PRN"
							value = "yes"/> YES
	
						<input	type = "radio"
							name = "PRN"
							id = "PRN"
							value = "no"/> NO
						</div>
					</td>
				</tr>
			</table>
			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Behavior Characterization Data"/>
			</div>
	</fieldset>
	</form>
<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>
</body>
</html>
