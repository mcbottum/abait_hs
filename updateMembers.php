<?session_start();
include("ABAIT_function_file.php");
if($_SESSION['passwordcheck']!='pass'){
    header("Location:logout.php");
date_default_timezone_set('America/Chicago');
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
function validate_form()
{
    valid=true;
    var alertstring=new String("");
    
    var rb=radiobutton(document.form1.makemap);
    if(rb==false){
        alertstring=alertstring+"\n-Click on a Behavior Scale to Create-";
        //document.form.hour.style.background = "Yellow";
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

function reload(selTag) {

    if(selTag.name=='update_resident'){
        self.location='residentdata.php?rk='+selTag.value;
    }else if(selTag.name=='update_caregiver'){
        self.location='caregiverdata.php?ck='+selTag.value;  
    }else if(selTag.name=='update_admin'){
        self.location='administratordata.php?ak='+selTag.value;      
    }
}

var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler
</script>
<link   rel = "stylesheet"
        type = "text/css"
        href = "ABAIT_admin.css">
<style>

    table.local thead th{
        width:250px;
        text-align:center;
    }

    table.local tbody td{
        width:250px;
        text-align:center;
    }
    span.tab{
        width:75px !important;
    }

    label {
        /* whatever other styling you have applied */
        width: 100%;
        display: inline-block;
    }
</style>
</head>
<body>
<div id="body" style="width:980px;margin: 0px auto 0px auto; text-align: left">
<fieldset>
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


<?  
if(isset($_REQUEST['Population'])){
    $Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
    $Population='';
}
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

$Population_strip=mysqli_real_escape_string($conn,$Population);
$success = null;
if(isset($_REQUEST['resident_count'])){
    print"<div id='head'><h3>Deleted Program Members</h3></div>";

    if(isset($_REQUEST['update_resident'])){
        $resident_key=$_REQUEST['update_resident'];
        $update_resident = mysqli_query($conn,"SELECT * FROM residentpersonaldata WHERE residentkey=$resident_key");
        // $success = mysqli_query("DELETE FROM residentpersonaldata WHERE residentkey=$delete_resident",$conn);
        echo $update_resident;
    }
    if(isset($_REQUEST['update_caregiver'])){
        $caregiver_key=$_REQUEST['update_caregiver'];
        $update_caregiver=mysqli_query($conn,"SELECT * FROM personaldata WHERE personaldatakey=$caregiver_key");
    }
    if(isset($_REQUEST['update_admin'])){
        $admin_key=$_REQUEST['update_admin'];
        $update_admin=mysqli_query($conn,"SELECT * FROM personaldata WHERE personaldatakey=$admin_key");
    }
    if($success){
        print"<div id='head'><h4>Member(s) Successfully Deleted</h4></div>";
    }else{
        print"<div id='head'><h4>Member(s) Successfully Deleted</h4></div>";
    }
    
}else{

        ?>
    <div id="head"><h3>
        Update Program Members
    </h3></div>
        <form   name='form'
                onsubmit='return validate_form()'
                action="updateMembers.php" 
                method="post">
        <?

        if($_SESSION['Target_Population']=='all'&&!$Population){
            $residentpersonaldata=mysqli_query($conn,"SELECT * FROM residentpersonaldata order by first");
            $caregiverdata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='caregiver' order by first");
            $admindata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='admin' order by first");
        }else if($_SESSION['privilege']=='admin'){
            $residentpersonaldata=mysqli_query($conn,"SELECT * FROM residentpersonaldata order by first");
            $caregiverdata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='caregiver' order by first");
            $admindata=mysqli_query($conn,"SELECT * FROM personaldata WHERE accesslevel='admin' order by first");            
        }
        # table scroll reference: http://jsfiddle.net/CrSpu/13513/
        print "<table align='center' cellpadding='10'>";
            print"<tr align='center'>";
                print"<th style='background-color:transparent'><label>Enrolled Residents</label></th>";
                print"<th style='background-color:transparent'><label>Enrolled Care Providers</label></th>";
                print"<th style='background-color:transparent'><label>Enrolled Administrators</label></th>";
            print"</tr>";

/////////
            print "<tr valign='top'>";
                print"<td>";
                    print"<table class=' table scroll hover local'  bgcolor='white' cellpadding='5'>";
                        print "<thead>";
                            print"<tr>";
                                print"<th>";
                                    print "<span class='tab'>First Name</span>";
                                    print"<span class='tab'>Last Name</span>";
                                    print"<span class='tab'>Update</span>";
                                print"</th>";
                            print"</tr>";
                        print "</thead>";
                        print "<tbody>";
                            $resident_count=0;
                            while($resident=mysqli_fetch_assoc($residentpersonaldata)){
                                
                                print"<tr>";
                                    print"<td>";
                                        print "<label>";
                                            print"<span class='tab'>$resident[first]</span>";
                                            print"<span class='tab'>$resident[last]</span>";
                                            print "<span class='tab'>";
                                    
                                                print "<input    
                                                    type = 'radio'
                                                    onchange='reload(this)'
                                                    name = 'update_resident'
                                                    id = 'update_resident'
                                                    value = '$resident[residentkey]'
                                                    class = 'update_checkbox'/>";
                                            print"</span>";
                                        print "</label>";
                                    print "</td>";
                                print"</tr>\n";
                                $resident_count++;
                            }
                        print "</tbody>";

                    print"</table>";
                print"</td>";

                print"<td>";
                    print"<table class='scroll hover local'  bgcolor='white' cellpadding='5'>";
                        print "<thead>";
                            print"<tr>";
                                print"<th>";
                                    print "<span class='tab'>First Name</span>";
                                    print"<span class='tab'>Last Name</span>";
                                    print"<span class='tab'>Update</span>";
                                print"</th>";
                            print"</tr>";
                        print "</thead>";
                        print "<tbody>";
                            $caregiver_count=0;
                            while($caregiver=mysqli_fetch_assoc($caregiverdata)){
                               
                                print"<tr>";
                                    print"<td>";
                                        print"<label>";
                                            print"<span class='tab'> $caregiver[first]</span>";
                                            print"<span class='tab'>$caregiver[last]</span>";
                                            print "<span class='tab'><input    type = 'radio'
                                                onchange='reload(this)'
                                                name = 'update_caregiver'
                                                id = 'update_caregiver'
                                                value = '$caregiver[personaldatakey]'
                                                class = 'update_checkbox'/></span>";
                                        print"</label>";
                                    print"</td>";
                                print"</tr>";
                                $caregiver_count++;
                            }
                        print "</tbody>";
                    print"</table>";
                print"</td>";

                print"<td>";
                    print"<table class='scroll hover local'  bgcolor='white' cellpadding='5'>";
                        print "<thead>";
                            print"<tr>";
                                print"<th>";
                                    print "<span class='tab'>First Name</span>";
                                    print"<span class='tab'>Last Name</span>";
                                    print"<span class='tab'>Update</span>";
                                print"</th>";
                            print"</tr>";
                        print "</thead>";
                        print "<tbody>";
                            $admin_count=0;
                            while($admin=mysqli_fetch_assoc($admindata)){
                                
                                print"<tr>";
                                    print"<td>";
                                        print"<label>";
                                            print"<span class='tab'>$admin[first]</span>";
                                            print"<span class='tab'>$admin[last]</span>";
                                            print "<span class='tab'><input    type = 'radio'
                                                onchange='reload(this)'
                                                name = 'update_admin'
                                                id = 'update_admin'
                                                value = '$admin[personaldatakey]'
                                                class = 'update_checkbox'/></span>";
                                            print"</label>";
                                        print"</td>";
                                print"</tr>";
                                $admin_count++;
                            }
                        print "</tbody>";
                    print"</table>";
                print"</td>";

            print "</tr>";
        print"</table>";
        print"<input type='hidden' name='resident_count' value='$resident_count'>";
        print"<input type='hidden' name='caregiver_count' value='$caregiver_count'>";
        print"<input type='hidden' name='admin_count' value='$admin_count'>";
        ?>
<!--                     <div id="submit">   
                        <input  type = "submit"
                                name = "submit"
                                value = "Submit">
                    </div> -->
                </form>

    <?
}//END ELSE FOR ISSET SUBMITTED FORM




    ?>
    </fieldset>
    <?build_footer()?>
    </body>
</html>