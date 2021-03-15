<?ob_start();
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
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>newadministrator.php</title>
<script type='text/javascript'>

function validate_form()
{
	valid=true;
	var alertstring=new String("");

	if(document.form.first.value=="")
	{
		alertstring=alertstring+"\n-First Name-";
		document.form.first.style.background = "Yellow";
		valid=false;
	}else{
		document.form.first.style.background = "white";
	}//end first name
	
	if(document.form.last.value=="")
	{
		alertstring=alertstring+"\n-Last Name-";
		document.form.last.style.background = "Yellow";
		valid=false;
	}else{
		document.form.last.style.background = "white";
	}//end first name


	var rb=radiobutton(document.form.gender);
	if(rb==false){
		alertstring=alertstring+"\n-Gender-";
		//document.form.gender.style.background = "Yellow";
		valid=false
	}	//end gender check

	if(document.form.month.selectedIndex==""){
		alertstring=alertstring+"\n-Birth Month-";
		document.form.month.style.background = "Yellow";
		valid=false;
	}else{
		document.form.month.style.background = "white";
	}//end birthmonth check
	
	if(document.form.day.selectedIndex==""){
		alertstring=alertstring+"\n-Birth Day-";
		document.form.day.style.background = "Yellow";
		valid=false;
	}else{
		document.form.day.style.background = "white";
	}//end birthday check
	
	if(document.form.year.selectedIndex==""){
		alertstring=alertstring+"\n-Birth Year-";
		document.form.year.style.background = "Yellow";
		valid=false;
	}else{
		document.form.year.style.background = "white";
	}//end birthyear check

	var emailExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	if(document.form.email.value.match(emailExp)){
		document.form.email.style.background = 'white';
		valid=true;
	}else{
		alertstring=alertstring+"\n-Check to see if e-mail address is valid-";
		document.form.email.style.background = 'Yellow';
		valid=false;
	}	
	if((document.form.email.value=="")||(document.form.email.value != document.form.email2.value))
	{
		alertstring=alertstring+"\n-E-mail one must match E-mail two";
		document.form.email.style.background = "Yellow";
		document.form.email2.style.background = "Yellow";
		valid=false;
	}else{
		document.form.email.background = "white";
		document.form.email2.background = "white";
	}//passwordcheck
	
	if(document.form.Target_Population.selectedIndex=="0"){
		alertstring=alertstring+"\n-Target Population-";
		document.form.Target_Population.style.background = "Yellow";
		valid=false;
	}else{
		document.form.Target_Population.background = "white";
	}//end Target Population check	
	
	if((document.form.password1.value=="")||(document.form.password1.value != document.form.password2.value))
	{
		alertstring=alertstring+"\n-Password one must match password two";
		document.form.password1.style.background = "Yellow";
		document.form.password2.style.background = "Yellow";
		valid=false;
	}else{
		document.form.password1.background = "white";
		document.form.password2.background = "white";
	}//passwordcheck

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

	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}//generate the conncanated alert message



	return valid;
}//end form validation function	


</script>
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
build_page($_SESSION[privilege],$cgfirst);

$sql="SELECT * FROM scale_table";
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
mysqli_select_db($_SESSION['database']);
$session=mysqli_query($sql,$conn);
?>
	<div id = "head">
		<h4>Enroll New Administrator</h4> 
	</div>
		<form
				name="form"
				onsubmit="return validate_form()"
				action = "ABAIT_administrator_log.php"
				method = "post">			
			
	
		<fieldset id="newclient">
		<h3 align="center"><label id="formlabel">New Administrator Data Form (*required)</label></h3>
		<tr><td colspan="100%"><div id = "greyline""></div></td></tr>
<div id = "dataform">		
			<table class="form">
			
			<div id = "name">	
			
			<tr><td>
			<h3><label>Name</label></h3>
			</td></tr>
			<tr><td align="right">
				<label>First*</label>
			</td><td>
				<input type = "text"
						name = "first"/>
			</td></tr>
			<tr><td align="right">
				<label>Last*</label>
			</td><td>
					<input	type = "text"
							name = "last"/>
			</td></tr>
			</div>

			<tr><td>
				<h3><label>Gender*</label></h3>
				<input type = "radio"
						name = "gender"
						value = "female"/>Female
				<input	type = "radio"
						name = "gender"
						id = "male"
						value = "male"/>Male
			</td>
			
			<td>
			
				<h3><label>Birthdate*</label></h3>
				
					
						<select name = "month">
							<option value = "">Month</option>
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
				
						<select name = "day">
							<option value = "">Day</option>
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

						<select name = "year">
							<option value = "">Year</option>
							<option value = "2010">2010</option>
							<option value = "2009">2009</option>
							<option value = "2008">2008</option>
							<option value = "2007">2007</option>						
							<option value = "2006">2006</option>
							<option value = "2005">2005</option>
							<option value = "2004">2004</option>
							<option value = "2003">2003</option>
							<option value = "2002">2002</option>
							<option value = "2001">2001</option>
							<option value = "2000">2000</option>
							<option value = "1999">1999</option>
							<option value = "1998">1998</option>
							<option value = "1997">1997</option>
							<option value = "1996">1996</option>
							<option value = "1995">1995</option>
							<option value = "1994">1994</option>
							<option value = "1993">1993</option>
							<option value = "1992">1992</option>
							<option value = "1991">1991</option>
							<option value = "1990">1990</option>
							<option value = "1989">1989</option>
							<option value = "1988">1988</option>
							<option value = "1987">1987</option>
							<option value = "1986">1986</option>
							<option value = "1985">1985</option>
							<option value = "1984">1984</option>
							<option value = "1983">1983</option>
							<option value = "1982">1982</option>
							<option value = "1981">1981</option>
							<option value = "1980">1980</option>
							<option value = "1979">1979</option>
							<option value = "1978">1978</option>
							<option value = "1977">1977</option>
							<option value = "1976">1976</option>
							<option value = "1975">1975</option>
							<option value = "1974">1974</option>
							<option value = "1973">1973</option>
							<option value = "1972">1972</option>
							<option value = "1971">1971</option>
							<option value = "1970">1970</option>
							<option value = "1969">1969</option>
							<option value = "1968">1968</option>
							<option value = "1967">1967</option>
							<option value = "1966">1966</option>
							<option value = "1965">1965</option>
							<option value = "1964">1964</option>
							<option value = "1963">1963</option>
							<option value = "1962">1962</option>
							<option value = "1961">1961</option>
							<option value = "1960">1960</option>
							<option value = "1959">1959</option>
							<option value = "1958">1958</option>
							<option value = "1957">1957</option>
							<option value = "1956">1956</option>
							<option value = "1955">1955</option>
							<option value = "1954">1954</option>
							<option value = "1953">1953</option>
							<option value = "1952">1952</option>
							<option value = "1951">1951</option>
							<option value = "1950">1950</option>
							<option value = "1949">1949</option>
							<option value = "1948">1948</option>
							<option value = "1947">1947</option>
							<option value = "1946">1946</option>
							<option value = "1945">1945</option>
							<option value = "1944">1944</option>
							<option value = "1943">1943</option>
							<option value = "1942">1942</option>
							<option value = "1941">1941</option>
							<option value = "1940">1940</option>
							<option value = "1939">1939</option>
							<option value = "1938">1938</option>
							<option value = "1937">1937</option>
							<option value = "1936">1936</option>
							<option value = "1935">1935</option>
							<option value = "1934">1934</option>
							<option value = "1933">1933</option>
							<option value = "1932">1932</option>
							<option value = "1931">1931</option>
							<option value = "1930">1930</option>
							<option value = "1929">1929</option>
							<option value = "1928">1928</option>
							<option value = "1927">1927</option>
							<option value = "1926">1926</option>
							<option value = "1925">1925</option>
							<option value = "1924">1924</option>
							<option value = "1923">1923</option>
							<option value = "1922">1922</option>
							<option value = "1921">1921</option>
							<option value = "1920">1920</option>
							
						</select>
						
					
					</td></tr>
	<tr><td colspan="100%"><div id = "greyline""></div></td></tr>	

			<tr><td>
			<h3>Address</h3>
			</td></tr>
			
			<tr><td align="right">
					<label>Street</label>
					</td><td>
					<input	type = "text"name = "street_address">
			
			</td></tr>
			<tr><td align="right">
					<label>City</label>
			</td><td>
					<input	type = "text" name = "city">
			</td>
			<td>
					<label>State</label>
					<select name = "state">
							<option value="">Choose</option>	
							<option value="AK">AK</option>
							<option value="AL">AL</option>
							<option value="AR">AR</option>
							<option value="AZ">AZ</option>
							<option value="CA">CA</option>
							<option value="CO">CO</option>
							<option value="CT">CT</option>
							<option value="DC">DC</option>
							<option value="DE">DE</option>
							<option value="FL">FL</option>
							<option value="GA">GA</option>
							<option value="HI">HI</option>
							<option value="IA">IA</option>
							<option value="ID">ID</option>
							<option value="IL">IL</option>
							<option value="IN">IN</option>
							<option value="KS">KS</option>
							<option value="KY">KY</option>
							<option value="LA">LA</option>
							<option value="MA">MA</option>
							<option value="MD">MD</option>
							<option value="ME">ME</option>
							<option value="MI">MI</option>
							<option value="MN">MN</option>
							<option value="MO">MO</option>
							<option value="MS">MS</option>
							<option value="MT">MT</option>
							<option value="NC">NC</option>
							<option value="ND">ND</option>
							<option value="NE">NE</option>
							<option value="NH">NH</option>
							<option value="NJ">NJ</option>
							<option value="NM">NM</option>
							<option value="NV">NV</option>
							<option value="NY">NY</option>
							<option value="OH">OH</option>
							<option value="OK">OK</option>
							<option value="OR">OR</option>
							<option value="PA">PA</option>
							<option value="RI">RI</option>
							<option value="SC">SC</option>
							<option value="SD">SD</option>
							<option value="TN">TN</option>
							<option value="TX">TX</option>
							<option value="VA">VA</option>
							<option value="VT">VT</option>
							<option value="WA">WA</option>
							<option value="WI">WI</option>
							<option value="WV">WV</option>
							<option value="WY">WY</option>
						</select>
				</td></tr>	
				<tr><td align="right">		
					<label>Zip Code</label>
				</td>
				
				<td>	
					<input	type = "text" name = "zipcode">
				</td></tr>
			
			

				<tr><td align="right">
				<label>Phone Number</label>
				</td>
				<td>
			
					<input	type = "text"
							name = "phone"/>
						
				</td></tr>
			

			
				<tr><td align="right">
				<label>E-mail*</label>
				</td>
				<td>
					
					<input	type = "text"
							name = "email"/>
				</td>
				<td align="right">
				<label>Re-enter E-mail*</label>
				</td>		
				<td>
					
					<input	type = "text"
							name = "email2"/>
				</td></tr>
			<div id = "phone">
			<tr><td></td colspan="100%"></tr>
			</div>
	<tr><td colspan="100%"><div id = "greyline""></div></td></tr>
			<div id = "target_population">
			<tr><td colspan=2>
				<h3><label>Select ABAIT Scale Target Population*</label></h3>
				
			
			
						<select name = "Target_Population">
							<option value = "">Choose</option>
<?
						if($_SESSION['privilege']=='globaladmin'){
							$Target_Population="";
							while($row=mysqli_fetch_assoc($session)){
								if($row[Target_Population]!=$Target_Population){
									$Target_Population=str_replace(' ','_',$row[Target_Population]);
									$Population_strip=mysqli_real_escape_string($Target_Population,$conn);
									print"<option value=$Population_strip>$row[Target_Population]</option>";
									$Target_Population=$row[Target_Population];
								}
							}
						}elseif($_SESSION['privilege']=='admin'){
							$Target_Population=str_replace(' ','_',$_SESSION['Target_Population']);
							//$Target_Population=$_SESSION['Target_Population'];
							$Population_strip=mysqli_real_escape_string($Target_Population,$conn);
							$Population=$_SESSION['Target_Population'];
							print"<option value=$Population_strip>$Population</option>";
						}
?>
						</select>
					</td></tr>	
			</div>
			


		<div id ="clientpassword">
		<tr><td colspan=2>
			<h3><label>Create Administrator Password</label></h3>
		</td></tr>
		<tr><td>
				<label>Enter Password*</label>
		</td>
		<td>
				<input	type = "password"
						name = "password1"/>
		</td>
		<td>
				<label>Re-enter Password*</label>
		</td>
		<td>
				<input type = "password"
						name="password2"/>
		
		</td></tr>
		</div>
	</table>	
</div>
<p><div id = "greyline""></div></p>							
		<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit New Administrator Personal Data"/>
		</div>
	</fieldset>
	</form>
<?build_footer()?>
</body>
</html>