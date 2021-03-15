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

</style>
</head>
<body class="container">

	<?	
		$names = build_page_pg();
	?>
	<h2 class="m-3" align='center'>
		Carer Home Page
	</h2>

	<h2 class="m-2 p-2 footer_div" align='center'>
		Roles
	</h2>

	<table class='center'>
		<tr><td class="p-3" align='center'>
			<a  class="btn  btn-lg btn-block p-5" href='ABAIT_quick_scales_v2.php'>Record Behavior Episode</a>
		</td></tr>
		<tr><td class="p-3" align='center'>
			<a  class="btn  btn-lg btn-block p-5" href='ABAIT_prn_effect_v2.php'>Record Medication Effect</a>
		</td></tr>
		<tr><td  class="p-3" align='center'>
			<a class="btn btn-lg btn-block p-5" href='ABAIT_tutorials_v2.php'>Tutorials</a>
		</td></tr>
	</table>

	<? build_footer_pg() ?>


</body>
</html>