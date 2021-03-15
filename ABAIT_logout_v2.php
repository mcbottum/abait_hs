<?ob_start()?>
<?session_start();
$nextfile=$_SESSION['HOME'];
header("Location:$nextfile");
session_unset();
session_destroy();
$_SESSION = array();

?>