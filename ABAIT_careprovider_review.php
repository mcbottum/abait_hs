<?
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
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
    charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<link   rel = "stylesheet"
        type = "text/css"
        href = "ABAIT_admin.css">
<style>
    table.local thead th{
        width:500px;
    }
    table.local tbody{
        max-height: 400px;
    }
    table.local tbody td{
        width:500px;
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

<script type="text/javascript" language="JavaScript">
function validate_form()
    {
        valid=true;
        var alertstring=new String("");



        var rb=radiobutton(document.form1.personaldatakey);
        if(rb==false){
            alertstring=alertstring+"\n-Either a provider or all providers-";
            //document.form1.intensityB[0].style.background = "Yellow";
            valid=false
        }   //end call for intensity Before intervention radio button check

        var rb=radiobutton(document.form1.review_time);
        if(rb==false){
            alertstring=alertstring+"\n-A review period-";
            //document.form1.intensityB.style.background = "Yellow";
            valid=false
        }   //end call for intensity Before intervention radio button check

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

</script>

</head>

<body>
<fieldset>
<div id="body" style="width:978px;margin: 0px auto 0px auto; text-align: left">
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
<div id="head"><h3>
Care Provider Review
</h3></div>
<?
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

if(isset($_REQUEST['Population'])){
    $Population=str_replace('_',' ',$_REQUEST['Population']);
}else{
    $Population=null;
}

if($_SESSION['Target_Population']=='all'&&!$Population){
    $sql1="SELECT * FROM behavior_maps";


    mysqli_select_db($_SESSION['database']);
    $session1=mysqli_query($conn,$sql1);
    //$session3=mysqli_query($sql3,$conn);
    ?>
        <form action="ABAIT_careprovider_review.php" method="post">
    <?
        print"<h3><label>Select ABAIT Scale Target Population</label></h3>";

                print "<select name = 'Population'>";

                    print"<option value =''>Choose</option>";
                            $Target_Pop[]="";
                            while($row1=mysqli_fetch_assoc($session1)){
                                if(!array_search($row1['Target_Population'],$Target_Pop)){
                                    $pop=str_replace(' ','_',$row1['Target_Population']);
                                    $pop_strip=mysqli_real_escape_string($conn,$pop);
                                    print"<option value=$pop>$row1[Target_Population]</option>";
                                    $Target_Pop[]=$row1['Target_Population'];
                                }
                            }
                 print"</select>";
    ?>
            <div id="submit">
                <input  type = "submit"
                        name = "submit"
                        value = "Submit Target Population">
            </div>
        </form>
<?
    }//end global admin if
else{

?>
    <form   name = 'form1'
        onsubmit='return validate_form()'
        action = "ABAIT_careprovider_review_analysis.php"
        method = "post">
<? //print $Population;
    $scale_array[]=null;
    $conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

if($_SESSION['Target_Population']!='all'){
    $Population_strip=mysqli_real_escape_string($conn,$_SESSION['Target_Population']);
    $sql1="SELECT * FROM personaldata WHERE Target_Population='$Population_strip' order by first";
    $sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
    $sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";
    $Population=$_SESSION[Target_Population];
}//end target population if
else{
    $Population_strip=mysqli_real_escape_string($conn,$Population);
    $sql1="SELECT * FROM personaldata WHERE Target_Population='$Population_strip' order by first";
    $sql2="SELECT * FROM behavior_maps WHERE Target_Population='$Population_strip'";
    $sql3="SELECT * FROM scale_table WHERE Target_Population='$Population_strip'";

}//end else
$pop=str_replace(' ','_',$Population);
//print $pop;
$_SESSION['pop']=$pop;
print"<input type='hidden' value='$pop' name='Target_Population'>";
//print $Population;

$scale_array=array();
    $session1=mysqli_query($conn,$sql1);
    $session3=mysqli_query($conn,$sql3);
    //following makes an array of scale names
    $scale_holder='';
        while($row3=mysqli_fetch_assoc($session3)){
            if(!in_array($row3['scale_name'],$scale_array)){
                $scale_array[]=$row3['scale_name'];
            }
            $scale_holder=$row3['scale_name'];
        }
    $_SESSION['scale_array']=$scale_array;

    $counterarray=$_SESSION['scale_array'];

        print "<table class='center'><tr><td>";
                ?>
                    <h3 align='center'>Select Care Provider <input type='submit' align='block' value='Tap for more Info' onClick="alert('Choose either a single individual or all care providers for review.  Note, All Care Provider Summary provides a comparison of intervention effectiveness.');return false"></h3>
                <?
            print "<table class='scroll local hover' border='1' bgcolor='white'>";
                print "<thead>";
                    print"<tr align='center'>\n";
                        print"<th><p><span class='tab'>Click Choice</span><span class='tab'>First Name</span><span class='tab'>Last Name</span></p></th>";
                    print"</tr>\n";
                print "</thead>";
                print "<tbody>";
                        print "<tr align='center'><td><label>";

                        print "<span class='tab'><input type = 'radio'
                                name = 'personaldatakey'
                                value ='all_careproviders'></span>";
                        print "<span class='tab'><strong>All Care Provider</strong></span>";
                        print "<span class='tab'><strong>Summary</strong></span>";
                        print "</label></td></tr>\n";
                    while($row1=mysqli_fetch_assoc($session1)){
                        print"<tr align='center'><td><label>";

                        print "<span class='tab'><input type = 'radio'
                            name = 'personaldatakey'
                            value = $row1[personaldatakey]></span>";


                        print "<span class='tab'>$row1[first]</span>";
                        print "<span class='tab'>$row1[last]</span>";
                        print "</td></label></tr>\n";
                    }
                print "</tbody";
            print "</table>";
        print "</td><th>";

        print "</th></tr></table>";// table for javascript comment

        print "<table width='100%' >";

            print "<tr><td>";
                print "<table style='margin-top:25px' class='scroll local hover' border='1' bgcolor='white'>";


                    print "<thead>";
                        print "<tr>\n";
                            print "<th align='center'><p>Review Duration</p></th>\n";

                        print "</tr>\n";
                    print "</thead>";
                    print "<tbody>";
                        print "<tr><td>\n";
                                // print "<table width='100%' >\n";
                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                    name= 'review_time'
                                    value= 'day'>Current Day</label></td></tr>\n";
                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                    name= 'review_time'
                                    value= 'week'>Current Week</label></td></tr>\n";
                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                    name= 'review_time'
                                    value= '1'>Current Month</label></td></tr>\n";

                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                    name= 'review_time'
                                    value= '3'>3 Month</label></td></tr>\n";

                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                        name='review_time'
                                        value='6'>6 Month</label></td></tr>\n";

                                print "<tr><td>";
                                print "<label>";
                                print "<input type='radio'
                                        name='review_time'
                                        value='all'>All Time</label></td></tr>\n";

                                //print "<tr><td><input type='textbox' size='5'
                                    //name='custom_time'>Select Date</td></tr>\n";
                            // print "</table>\n";
                        print"</td>\n";
                        print"<input type='hidden' value='all' name='all_scales'>";

                        print "</tr>\n";
                    print "</tbody>";
                print "</table>";
        print "</td><th>";

        print "</th></tr></table>";// table for javascript comment
?>
            <div id = "submit">
                <input  type = "submit"
                        name = "submit"
                        value = "Submit Selection for Analysis">
            </div>
            <?
}
?>
            </fieldset>
    </form>
    <?build_footer()?>
</body>
</html>
