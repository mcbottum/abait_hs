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
<link 	rel = "stylesheet"
		type = "text/css"
		href = "ABAIT.css">
<style type="text/css">
 
</style>
</head>
<body>
<?
	$resident=$_REQUEST["resident_choice"];
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());;
	$resident=mysqli_real_escape_string($resident,$conn);
	$sql1="SELECT * FROM residentpersonaldata WHERE residentkey='$resident'";
	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());;
	mysqli_select_db($_SESSION['database']);
	$resident=mysqli_query($sql1,$conn);
	$row1=mysqli_fetch_assoc($resident);
	$_SESSION['residentkey']=$row1['residentkey'];
Print "Mapping Session for  ".$row1['first'] ."  ".  $row1['last'];
?>
		<form 	action = "ABAIT_log.php"
				method = "post">
	<fieldset>	
			<div id = "date">
				<h3><label>Date</label></h3>
			<p>
					<input	type = "radio"
							name = "date"
							value = "1"/> Click if episode took place today
				</p>
				<p>
					<input	type = "text"
							name = "otherday"/>
				</p>
			</div>
			<div id = "time">
				<h3><label>Time of day during which episode took place</label></h3>
					Hour
						<select name = "hour">
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
						<select name = "minute">
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
						<select name = "ampm">
							<option value = "AM">AM</option>
							<option value = "PM">PM</option>
						</select>
			</div>
			<div id = "duration">
				<h3><label>Duration of Episode</label></h3>
				<input	type = "text"
						name = "duration"/>Enter number of minutes
			</div>
			<div id = "trigger">
				<h3><label>Trigger Description</label></h3>
				<input	type = "text"
						name = "trigger"/>
			</div>
			<div id = "behavior">	
					<h3><label>Behavior Classification</label></h3>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "motor"
								value = "motor"/>Motor Agitation Anxiety
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "resisistance"
								value = "resistance"/>Resistance to Care
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "vocalizations"
								value = "vocalizations"/>Vocalizations
					</p>
					<p>
						<input type = "radio"
								name = "behavior"
								id = "agressiveness"
								value = "agressiveness"/>Agressiveness
					</p>
			</div>
			<div id = "intervention">
				<h3><label>Intervention Description</label></h3>
				<input	type = "text"
						name = "intervention"/>
			</div>
			<div id = "intensity">	
					<h3><label>Behavior Intensity Level</label></h3>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity1"
								value = "1"/>Not Present
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity2"
								value = "2"/>Pacing or moving at a specfic rate, looking for someone.
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity3"
								value = "3"/>Increased rate of movement mildly intrusive and easily redirectable
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity4"
								value = "4"/>Rapid movement, moderately intrusive, or disruptive and difficult to redirect.
					</p>
					<p>
						<input type = "radio"
								name = "intensity"
								id = "intensity5"
								value = "4"/>Intense movement and extremely disruptive, not redirectable.
					</p>
			</div>
			<div id = "PRN">
				<h3><label>PRN</label></h3>
					<p>
					<input	type = "radio"
							name = "PRN"
							value = "yes"/> YES
					</p>
					<p>
					<input	type = "radio"
							name = "PRN"
							value = "no"/> NO
					</p>
			</div>
		<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit Resident Mapping Data"/>
		</div>
	</fieldset>
<div id="footer"><p>&nbsp;Copyright &copy; 2010 ABAIT</p></div>	
</body>
