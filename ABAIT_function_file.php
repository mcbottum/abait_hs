<?
function ABAIT_bar_graph($values,$graphTitle,$yLabel,$xlabel,$graphNumber){
	//print_r($values);
	$img_width=700;
	$img_height=400; 
	$margins=20;
	$c1 = array("x"=>$margins, "y"=>$margins);
	//getting the size of the fonts
	$fontwidth  = imagefontwidth(4);
	$fontheight = imagefontheight(4);
	$font=5;
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - ($margins*4);
	$img=imagecreate($img_width,$img_height);
	
	if($values){
		$total_bars=count($values);
		$bar_width=$graph_width/($total_bars*(2+1));
	}else{$bar_width=1;
	}
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

	# -------  Define Colors ----------------
	
	$background_color=imagecolorallocate($img,240,240,255);
	$border_color=imagecolorallocate($img,200,200,200);
	$line_color=imagecolorallocate($img,220,220,220);
	$label_color=imagecolorallocate($img, 0,0,0);
 
	# ------ Create the border around the graph ------
	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins*2,$margins*2,$img_width-1-$margins,$img_height-$margins*2,$background_color);

	# ------- Max value is required to adjust the scale	-------
	if($values){
	$max_value=max($values);
		if ($max_value==0){
		$max_value=.1;
	}
	}else{$max_value=1;
	}
	$ratio= $graph_height/$max_value;

	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=10;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins*2 - $horizontal_gap * $i ;
		imageline($img,$margins*2,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,$font-2,$margins+$fontheight/2,$y-5,$v,$label_color);
	}

	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){
		$r=5*$i;
		$g=15*$i;
		$b=10*$i;
		$bar_color=imagecolorallocate($img,$r,$g,$b);
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins*2+$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins*2;
		imagestring($img,$font,$x1+3+5,$y1-$fontheight,$value,$bar_color);
		// x labels
		//imagestring($img,$font,$x1+3,$img_height-15,$key,$bar_color);
		if($xlabel=='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|'){
			if($key>=7&&$key<15){
				$bar_color=imagecolorallocate($img, 0x00, 0x33, 0x99);//blue
			}elseif($key>=15&&$key<23){
				$bar_color=imagecolorallocate($img, 0x33, 0x99, 0x00);//green
			}elseif($key>=23||$key<7){
				$bar_color=imagecolorallocate($img, 0x99, 0x00, 0x00);//red
			}
		imagestring($img,$font,$x1-27,$img_height-40,$key,$bar_color);
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
		}else{$bar_color=imagecolorallocate($img, 0x00, 0x33, 0x99);//blue
			imagestringup($img,$font,$x1-20,$img_height-42,$key,$bar_color);
			imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
		}

	}
	//graph title
	imagestring($img,$font+1, ($img_width/2)-(strlen($graphTitle)*$fontwidth)/2, $c1['y']-($fontheight/2)-($margins/2), $graphTitle, $label_color);
	//x labels
	if($xlabel=='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|'){
	imagestring($img,$font+1, ($margins*2), $img_height-$c1['y']+($fontheight)/1.5-($margins/2), $xlabel, $label_color);
	}else{
		imagestring($img,$font+2, ($img_width/2)-(strlen($xlabel)*$fontwidth)/2, $img_height-$c1['y']+($fontheight)/1.5-($margins/2), $xlabel, $label_color);
	}
	//y labels	
	ImageStringUp($img,$font,$c1['x']/2-$fontheight/2, $img_height/2+(strlen($yLabel)*$fontwidth)/2, $yLabel, $label_color);
	imagepng($img,'behaviorgraph'.$graphNumber.'.png');
		
}
function ABAIT_pie_graph($values,$graphTitle,$yLabel,$graphNumber){
	// create image
	$img_width=700;
	$img_height=400;
	$margin_height=10;
	$margin_width=110;
	$pie_height=$img_height-$margin_height*2;
	$pie_width=$img_width-$margin_width*2.8;
	$z_offset=20; 
	$img = imagecreatetruecolor($img_width, $img_height);
	//getting the size of the fonts
	$fontwidth  = imagefontwidth(4);
	$fontheight = imagefontheight(4);
	$font=5;
	//# of pie pieces
	$total_wedge=count($values);

	// allocate colors
	$white    = imagecolorallocate($img, 0xFF, 0xFF, 0xFF);//white
	$green= imagecolorallocate($img, 51, 255, 0);//green
	$darkgreen= imagecolorallocate($img, 51, 153, 0);//dark green
	$orange= imagecolorallocate($img, 255, 153, 0);//orange
	$darkorange= imagecolorallocate($img, 255, 102, 0);//orange
	$gray     = imagecolorallocate($img, 0xC0, 0xC0, 0xC0);//gray
	$darkgray = imagecolorallocate($img, 0x90, 0x90, 0x90);//darkgray
	$navy     = imagecolorallocate($img, 0x00, 0x00, 0xFF);//navy
	$darknavy = imagecolorallocate($img, 0x00, 0x00, 0x50);//darknavy
	$red      = imagecolorallocate($img, 0xFF, 0x00, 0x00);//red
	$darkred  = imagecolorallocate($img, 0x90, 0x00, 0x00);//darkred
	$color_array=array($green,$darkgreen,$orange,$darkorange,$gray,$darkgray,$navy,$darknavy,$red,$darkred);

	//make arc lengths
	$arc_start_angle=1;
	$h=0;
	if($values && array_sum($values)!=0){
		foreach ($values as $pie_piece){
			$arc_length=($pie_piece/array_sum($values))*360;

		// make the 3D effect
			for ($i = 220; $i > 200; $i--) {
				if($pie_piece!=0){
		  	imagefilledarc($img, $pie_width/2+$margin_height, $i, $pie_width, $pie_height/1.5, $arc_start_angle, ($arc_start_angle+$arc_length), $color_array[$h+1], IMG_ARC_NOFILL);
				}//end if
			}// end 3d for loop
				if($pie_piece!=0){
					$arc_start_angle=$arc_start_angle+$arc_length;
					$h=$h+2;
				}
			}//end foreach
			$arc_start_angle=1;
			$h=0;
			//top slice
		$key_space=0;
		foreach ($values as $pie_piece){
			list($key,$value)=each($values);
			$label_string=$key.' '.round($pie_piece/array_sum($values)*100).'%';
			if($pie_piece!=0){
				$arc_length=($pie_piece/array_sum($values))*360;
				imagefilledarc($img, $pie_width/2+$margin_height, $i, $pie_width, $pie_height/1.5, $arc_start_angle, ($arc_start_angle+$arc_length), $color_array[$h], IMG_ARC_PIE);
				imagestring($img,$font,$pie_width+2*$margin_height,($img_height-$pie_height/1.6)/2+$key_space*$fontheight,$label_string,$color_array[$h]);
				$arc_start_angle=$arc_start_angle+$arc_length;
				$h=$h+2;
			}else{
				imagestring($img,$font,$pie_width+2*$margin_height,($img_height-$pie_height/1.6)/2+$key_space*$fontheight,$label_string,$white);
			}
			$key_space=$key_space+2;
		}// end total wedge foreach loop
	}//end if
	imagestring($img,$font+1, ($img_width/2)-(strlen($graphTitle)*$fontwidth)/2, $img_height-2*($fontheight), $graphTitle, $white);
	// flush image
	imagepng($img,'behaviorgraph'.$graphNumber.'.png');
	// does not work imagepng($img,$_SESSION['behaviorgraph'.$graphNumber.'.png']);
	//imagepng($image);
	imagedestroy($img);
}
if($_POST['action'] == 'call_this') {
  build_canvasjs_graph();
}
function build_canvasjs_graph(){
 
$dataPoints1 = array(
	array("label"=> "2010", "y"=> 36.12),
	array("label"=> "2011", "y"=> 34.87),
	array("label"=> "2012", "y"=> 40.30),
	array("label"=> "2013", "y"=> 35.30),
	array("label"=> "2014", "y"=> 39.50),
	array("label"=> "2015", "y"=> 50.82),
	array("label"=> "2016", "y"=> 74.70)
);
$dataPoints2 = array(
	array("label"=> "2010", "y"=> 64.61),
	array("label"=> "2011", "y"=> 70.55),
	array("label"=> "2012", "y"=> 72.50),
	array("label"=> "2013", "y"=> 81.30),
	array("label"=> "2014", "y"=> 63.60),
	array("label"=> "2015", "y"=> 69.38),
	array("label"=> "2016", "y"=> 98.70)
);
	
?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
src="static/js/jquery-3.5.1.min.js">
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Average Amount Spent on Real and Artificial X-Mas Trees in U.S."
	},
	axisY:{
		includeZero: true
	},
	legend:{
		cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "Real Trees",
		indexLabel: "{y}",
		yValueFormatString: "$#0.##",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
	},{
		type: "column",
		name: "Artificial Trees",
		indexLabel: "{y}",
		yValueFormatString: "$#0.##",
		showInLegend: true,
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="static/js/canvasjs.min.js"></script>
</body>
</html> 
<?
}

function set_css(){
	// set CSS file for either admin or caregiver role
	if ($_SESSION['privilege'] == 'caregiver'){
		?>
		<link rel="stylesheet" href="bootstrap-4.4.1-dist/css/fontawesome-free-5.13.0-web/css/all.css">
		<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1-dist/css/bootstrap.min.css">
		<script defer src="bootstrap-4.4.1-dist/css/fontawesome-free-5.13.0-web/js/all.js"></script>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script type='text/javascript' src="bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
		<link 	rel = "stylesheet"
				type = "text/css"
				href = "ABAIT_v2.css">
		<link 	rel="stylesheet" 
		type="text/css" 
		href="datetimepicker-master/jquery.datetimepicker.css"/ >
		<script type='text/javascript' src="datetimepicker-master/jquery.js"></script>
		<script type='text/javascript' src="datetimepicker-master/jquery.datetimepicker.js"></script>
		<script type='text/javascript' src="static/js/custom_js.js"></script>

		<?
	}else{
		?>
<!-- 		// <link href="bootstrap-4.4.1-dist/css/fontawesome-free-5.13.0-web/css/all.css" rel="stylesheet">
		// <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1-dist/css/bootstrap.min.css">
		// <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		// <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		// <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		// <script type='text/javascript' src="bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
		// <script type='text/javascript' src="static/js/custom_js.js"></script>
		// <link 	rel = "stylesheet"
		// 		type = "text/css"
		// 		href = "ABAIT_admin.css"> -->

		<?
		?>
		<link rel="stylesheet" href="bootstrap-4.4.1-dist/css/fontawesome-free-5.13.0-web/css/all.css">
		<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1-dist/css/bootstrap.min.css">
		<script defer src="bootstrap-4.4.1-dist/css/fontawesome-free-5.13.0-web/js/all.js"></script>
		<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script type='text/javascript' src="bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
		<link 	rel = "stylesheet"
				type = "text/css"
				href = "ABAIT_v2.css">
		<link 	rel="stylesheet" 
		type="text/css" 
		href="datetimepicker-master/jquery.datetimepicker.css"/ >
		<script type='text/javascript' src="datetimepicker-master/jquery.js"></script>
		<script type='text/javascript' src="datetimepicker-master/jquery.datetimepicker.js"></script>
		<script type='text/javascript' src="static/js/custom_js.js"></script>

		<?
	}


}

function set_homepage() {
	// set first and last names as well has homepage based on privilege
	if($_SESSION['cgfirst']!=""){
		$cgfirst=$_SESSION['cgfirst'];
		$cglast=$_SESSION['cglast'];
		$home = "caregiverhome_v2.php";
	}else{
		$cgfirst=$_SESSION['adminfirst'];
		$cglast=$_SESSION['adminlast'];
		$home = "adminhome.php";
	}
}

function build_page_pg($display='none'){
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
	    $nextfile='ABAIT_logout_v2.php';
		header("Location:$nextfile");
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp




	if($_SESSION['cgfirst']!=""){
		$first=$_SESSION['cgfirst'];
		$last=$_SESSION['cglast'];
	}else{
		$first=$_SESSION['adminfirst'];
		$last=$_SESSION['adminlast'];
	}
	$home = $_SESSION['home_page'];

	$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
    $date=date('Y-m-d');
    $date_start=date('Y-m-d',(strtotime('- 2 days')));
    if($_SESSION['privilege']=='caregiver'){
        $sql_show="SELECT * from resident_mapping WHERE date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN=1 AND post_PRN_observation IS NULL ORDER BY date";
        $sql_show_map="SELECT * from behavior_map_data WHERE date > '$date_start' AND personaldatakey='$_SESSION[personaldatakey]' AND PRN=1 AND post_PRN_observation IS NULL ORDER BY date";
    	$session1=mysqli_query($conn,$sql_show);
    	$session2=mysqli_query($conn,$sql_show_map);
    	if($session1->num_rows > 0 || $session2->num_rows > 0){
    		$display='block';
        // echo "<script>";
        //     echo "document.getElementById('alert').style.display = 'block';";
        // echo "</script>";
    	}
    }

	?>
<!-- FUNCTION TO REDIRECT TO PCS IF NEED TO EXIT ABAIT -->
			<script type="text/javascript">
				function goHome() {
					window.location.href = document.referrer;
				}
			</script>

			<div class="page-header p-2">  </div>

			<nav class="navbar navbar-expand-md navbar-dark">
			<img src="favicon6.ico" class="img-fluid" alt="Responsive image">
			<a class="navbar-brand p-3 icon-ginko" href= <? print $home ?> >ABAIT Home</a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse order-md-last" id="navbarSupportedContent">
			    <ul class="navbar-nav ml-auto  d-flex align-items-end bd-highlight">
			     	
			     </li>
			      <li class="navbar-text pb-3">
				      <? 
				      	print $first;
				      ?> 
				      Signed In
			      </li>
			      	<? print "<li class='nav-item  mb-4 ml-1 mr-3'><span id='alert' style='font-size: 1em; display:".$display."; color: Red;'>
			      		<a href='ABAIT_prn_effect_v2.php' style='color:Red'>
			      			<i class='fas fa-bell'>
			      			</i>
		      			</a>
		      			</span>";?>
			      	<li class="nav-item">
<?
					if($_SESSION['remote_login']){
						// print"<a class='navbar-brand p-3' href='$_SESSION[returnurl]'>Leave ABAIT</a>";
						print"<a class='navbar-brand p-3' href='ABAIT_logout_v2.php'>Leave ABAIT</a>";
					}else{
?>
			        	<a class="navbar-brand p-3" href="ABAIT_logout_v2.php">Logout</a>
<?
					}
?>
			      </li>
			    </ul>
			  </div>
			</nav>
	<?	
	$names = array($first, $last);
	return $names;

}

function build_footer_pg(){
	?>
	<div class="m-2 p-4 footer_div align-middle text-center">  
		<a class="footer" href='https://centerfordementiabehaviors.com/'><span>Center for Agitated Behaviors</a></br>ABAIT Proprietary</span>
	</div>
	<?
}


function build_page($privilege,$first){
	// Logout time: 
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {

	    $nextfile='ABAIT_logout_v2.php';
	    header_remove("Location:$nextfile");
		header("Location:$nextfile");
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

	if($privilege=='globaladmin'||$privilege=='admin'){


	$adminfirst=$_SESSION['adminfirst'];
		print"<table style='margin-top:2px; margin-bottom:4px' width='100%'>\n";
			print"<tr>\n";
				if($_SESSION['remote_login']){
				    print"<td valign='bottom' align='right'>$first logged in | <a href='$_SESSION[returnurl]'>Leave ABAIT</a></td>\n";
				    $logout_location = $_SESSION['returnurl'];
				}else{
				    print"<td valign='bottom' align='right'>$first logged in | <a href='ABAIT_logout_v2.php'>logout</a></td>\n";
				    $logout_location = "ABAIT_logout_v2.php";
				}
			print"</tr>\n";
		print"</table>\n";
		?>
		<div id="globalheader">
			<ul id="globalnav">
				<li id="gn-home"><a href="adminhome.php"></a></li>
				<? print"<li id='gn-logout'><a href='$logout_location'></a></li>"; ?>
			</ul>
		</div>
		<?
	}elseif($privilege=='caregiver'){
		$cgfirst=$_SESSION['cgfirst'];
			print"<table width='98%'>\n";
			print"<tr>\n";

			print"<td valign='bottom' align='right'><span id='alert' style='font-size: 2em; color: Red;'><i class='fas fa-bell'></i></span>HI</td>\n";
				print"<td valign='bottom' align='right'>$first logged in | <a href='ABAIT_logout_v2.php'>logout</a></td>\n";
			print"</tr>\n";
		print"</table>\n";
		?>
		<div id="globalheader">
			<ul id="globalnav">
				<li id="gn-home"><a href="caregiverhome.php"></a></li>
				<li id="gn-logout"><a href="ABAIT_logout_v2.php"></a></li>
				<li><span id='alert' style='font-size: 2em; color: Red;'><i class='fas fa-bell'></i></span></li>
			</ul>
		</div>
		<?
	}

}
function build_footer(){
ini_set('session.bug_compat_42',0);
ini_set('session.bug_compat_warn',0);
	?>
	<!-- <div id= "footer"><p>&nbsp;Copyright &copy; 2012 ABAIT<br>ABAIT LLC</br></p></div> -->
	<div id= "footer"><a href='https://centerfordementiabehaviors.com/'><span><br>Center for Agitated Behaviors</a></br>ABAIT Proprietary</span></div>
	
	<?}
	
?>
