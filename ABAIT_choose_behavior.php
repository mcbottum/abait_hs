<?session_cache_limiter('nocache');
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
<script type='text/javascript'>
function validate_form()
{
    valid=true;
    var alertstring=new String("");
    
    var rb=radiobutton(document.form1.scale_name);
    if(rb==false){
        alertstring=alertstring+"\nSelect a Behavior Scale.";
        valid=false
    }   //end call for PRN radio button check
    
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

function reload() {
    
        val = form1.scale_name;

        self.location='resident_scale.php?behavior='+val.value;

}
</script>
<link   rel = "stylesheet"
        type = "text/css"
        href = "ABAIT.css">
</head>
<body>
<fieldset id="submit">
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

<div id = "head">
<?
print $cgfirst."  ". $cglast. "'s Caregiver Home Page";
?>
</div>

<form   name = 'form1'
        onsubmit='return validate_form()'
        action = "resident_scale.php"
        method = "post">
<?
$_SESSION['residentkey']='';
$_SESSION['scale_name']='';
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword']) or die(mysqli_error());
mysqli_select_db($_SESSION['database']); 
if($_SESSION['Target_Population']=='all'){
    $sql2="SELECT * FROM scale_table";
}else{
    $Population_strip=mysqli_real_escape_string($_SESSION['Target_Population'],$conn);
    $sql2="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
    $sql_rm="SELECT * FROM resident_mapping WHERE personaldatakey='$_SESSION[personaldatakey]' AND PRN='1'";
    $sql_bmd="SELECT * FROM behavior_map_data WHERE personaldatakey='$_SESSION[personaldatakey]' AND PRN='1' ";
    $session_rm=mysqli_query($sql_rm,$conn);
    $session_bmd=mysqli_query($sql_bmd,$conn);
}//end sql2 if else

$session2=mysqli_query($sql2,$conn);        

        print   "<h4 align='center'>Choose Behavior Class</h4>";

            print"<table class='center'>";
                // // do this in non- windows
                // while($row=mysqli_fetch_assoc($session2)){
                //     $scale_name=str_replace(' ','_',$row['scale_name']);
                //     print"<tr><td>";
                //     print "<div class='tooltip'>";
                //         print "<label>";
                //             print"<input type = 'radio'
                //                 name = 'scale_name'
                //                 id='scale_name'
                //                 onchange='reload(this)'
                //                 value = $scale_name>$row[scale_name]</dt>";
                //             print "<span class='tooltiptext'>$row[scale_name_description]</span>";
                //         print "</label>";
                //     print "</div>";
                //     print "</td></tr>";
                //                 }
                // print "</table>\n";

                while($row=mysqli_fetch_assoc($session2)){
                    $scale_name=str_replace(' ','_',$row['scale_name']);
                    print"<tr align='left'><td>";
                    print "<div class='tooltip'>";
                        print "<label>";
                            print"<input type = 'radio'
                                name = 'scale_name'
                                id='scale_name'
                                value = $scale_name>$row[scale_name]</dt>";
                            print "<span class='tooltiptext'>$row[scale_name_description]</span>";
                        print "</label>";
                    print "</div>";
                    print "</td></tr>";
                }
            print "</table>\n";

?>

                <input  type = "submit"
                        name = "submit"
                        value = "Submit Behavior Choice">
    </form>


</fieldset>
<?build_footer()?>

</body>
</html>