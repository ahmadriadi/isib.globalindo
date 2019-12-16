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
if ($state == 'confirm' and $step == "pgt"){
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
        You have 1 leave request <?php echo $ttljenis;?> awaiting your confirmation. <br>
        Mr/Mrs/Ms <b><?php echo $sendername;?></b> wants you to be his/her substitute (as a person in charge) while him/her away.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to take an action for this request. <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | pic | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php }
if ($state == 'confirm' and $step == "ats"){
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
        You have 1 leave request <?php echo $ttljenis;?> from Mr/Mrs/Ms <b><?php echo $sendername;?></b>.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>    
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to take an action for this request.  <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | hod | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php }
if ($state == 'confirm' and $step == "hrd"){
?>
<text>
<span style="font-size: 13px;">
    [ <?php echo date("l, F ").$date." ".date("Y H:i:s"); ?> ]
</span>
<br>
<br>
    Dear <b><?php echo "HRD | ".$receivername;?></b>,
    <br><br>
    <p>
        1 leave request <?php echo $ttljenis;?> from Mr/Mrs/Ms <b><?php echo $sendername;?></b> is awaiting your confirmation.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>    
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to take an action for this request.  <br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | hrd | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php }
if ($state == "reject" and $step == "pgt"){
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
        Your submitted leave request <?php echo $ttljenis;?> (requested on <?php echo $tglreq;?>) <b>rejected</b> by your substitute / the person you chose to be in charge while you away. Please, see the reason of this rejection.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>    
    <p>
        Open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to make a revision.<br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | pic | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php } 
if ($state == "reject" and $step == "ats"){
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
        Your submitted leave request <?php echo $ttljenis;?> (requested on <?php echo $tglreq;?>) <b>rejected</b> by head of your department. Please, see the reason of this rejection.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>    
    <p>
        Open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to make a revision.<br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | hod | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php }
if ($state == "reject" and $step == "hrd"){
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
        Your submitted leave request <?php echo $ttljenis;?> (requested on <?php echo $tglreq;?>) <b>rejected</b> by the human resource department. Please, see the reason of this rejection.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
			<td>Leave Reason</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
			<td><?php echo $leave->Alasan;?></td>
		</tr>
    </table>    
    <p>
        Open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address to make a revision.<br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | hrd | #<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php }

if ($state == "accept" and $step == "hrd"){
    
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
        Your submitted leave request <?php echo $ttljenis;?> (requested on <?php echo $tglreq; ?> ) has been <b>accepted</b> by the human resource department.
    </p>
    <table border='1' style='border-collapse: collapse;'>
		<tr style='background: #ddd;'>
			<td>Name</td>
			<td>Request Date</td>
			<td>Leave From</td>
			<td>Leave Until</td>
			<td>Type</td>
		</tr>
		<tr>
			<td><?php echo $sendername;?></td>
			<td><?php echo $leave->TglPengajuan;?></td>
			<td><?php echo $leave->TglCutiDari;?></td>
			<td><?php echo $leave->TglCutiSampai;?></td>
			<td><?php echo $leave->Jenis;?></td>
		</tr>
    </table>    
    <p>
        Please, open your Employee Center at this <a href='http://192.168.0.5/office'><b>local</b></a> or <a href="http://119.110.75.218/office"><b>public</b></a> address.<br>
        Thank you    
    </p>
    Regards,
    <br>
    <br><i style="font-size: 10px;">[this is an automated email sent by TIS System | acc-hrd |#<?php echo $leave->IDLeave?>]</i><br>
</text>
<?php } 

?>



