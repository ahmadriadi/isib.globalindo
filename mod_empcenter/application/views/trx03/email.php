<style>
    text{
        font-family: arial;
    }
</style>
<?php
$date = date("d");
if ($date == "01"){
    $date = "1st";
}
elseif ($date == "02"){
    $date = "2nd";
}
elseif ($date == "03"){
    $date = "3rd";
}
elseif ($date == "21"){
    $date = "21st";
}
elseif ($date == "31"){
    $date = "31st";
}
else{
    $date = $date."th";
}
if ($state == 'confirm'){
?>
<text>
<span style="font-size: 13px;">
    [ <?php echo date("l, F ").$date." ".date("Y H:i:s"); ?> ]
</span>
<br>
<br>
    Dear <b><?php echo $receivername;?></b>,
    <br><br>
    <p>
        You have 1 memo awaiting your confirmation from Mr./Mrs./Ms. <b><?php echo $sendername;?></b>.
    </p>
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address! <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System]</i><br>
</text>
<?php }
if ($state == 'incoming' and $confirm == "1"){
?>
<text>
<span style="font-size: 13px;">
    [ <?php echo date("l, F ").$date." ".date("Y H:i:s"); ?> ]
</span>
<br>
<br>
    Dear <b><?php echo $receivername;?></b>,
    <br><br>
    <p>
        You received 1 new memo from Mr./Mrs./Ms. <b><?php echo $sendername;?></b>.
    </p>
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address! <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System]</i><br>
</text>
<?php }
if ($state == "incoming" and $confirm == "2"){
?>
<text>
<span style="font-size: 13px;">
    [ <?php echo date("l, F ").$date." ".date("Y H:i:s"); ?> ]
</span>
<br>
<br>
    Dear <b><?php echo $receivername;?></b>,
    <br><br>
    <p>
        Your submitted memo <b>rejected</b> by head of your department. Please, see the reason of this rejection.
    </p>
    <p>
        Open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to make a revision. <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System]</i><br>
</text>
<?php } ?>
