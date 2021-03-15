<?
include("ABAIT_function_file.php");
ob_start()?>
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
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<link rel="stylesheet" type="text/css" href="bootstrap-4.4.1-dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script type='text/javascript' src="bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
<?
if ($_SESSION['privilege'] == 'caregiver'){
	?>
	<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT_v2.css">
	</head>
	<?
}else{
	?>
	<link 	rel = "stylesheet"
			type = "text/css"
			href = "ABAIT_admin.css">
	</head>
	<?
	}
?>
<style>
    td {
    	padding-top: 5px;
    	padding-bottom: 5px;
    }

/*	.page-header {
		background-color: #3700B3
	}
	.navbar{
		background-color: #6200EE;
		box-shadow: 5px 5px 10px #888888;
	}
	.navbar-brand {
		font-size: 1.5em;
		border-radius: 2px;
	}
	.navbar-brand:hover {
		background-color: #7F44F7;
		box-shadow: 2px 2px 5px 3px lightgrey;
		border-radius: 2px;
		border-style:solid;
	    border-width:.5px;
	}
	.navbar-text {
		font-size: 1.5em;
	}*/

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

</style>

<body>

	<!-- <div class="container"> -->
		<?
		if($_SESSION['cgfirst']!=""){
			$cgfirst=$_SESSION['cgfirst'];
			$cglast=$_SESSION['cglast'];
			$home = "caregiverhome_v2.php";
			}else{
			$cgfirst=$_SESSION['adminfirst'];
			$cglast=$_SESSION['adminlast'];
			$home = "adminhome_v2.php";
			}
			
			build_page_pg($_SESSION['privilege'],$cgfirst, $home);

		?>

		<!-- <div class="page-header"> -->
			<h2 class="m-3" align='center'>ABAIT Tutorials and Education Resources</h2>
		<!-- </div> -->

			<table class='center'>
				<tr><td align='center'>
					<a  class="btn  btn-lg btn-block p-5" href='ABAIT_Education2.php'>ABAIT Education Module</a>
				</td></tr>

				<tr><td align='center'>
					<a class="btn  btn-lg btn-block p-5" href='ABAIT_triggers_and_interventions.php'>Catalog of Behavior Triggers and Interventions</a>
				</td></tr>
				<tr><td align='center'>
					<a class="btn btn-lg btn-block p-5" href='resident_fact_sheet.php'>Quick Reference to Resident Triggers and Interventions</a>
				</td></tr>
			</table>

		<? build_footer_pg() ?>


	<!-- </div> -->
</body>
</html>