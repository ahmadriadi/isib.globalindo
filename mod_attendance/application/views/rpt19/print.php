<style>
    table{
        border-collapse: collapse;
    }
    td{
        height: 23px;
    }
</style>

<table width='100%' >
    <tr>
        <td colspan="3" align="center">
            <h3>ANNUAL LEAVE REPORT</h3>
            <br>
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td width='150px;'>Print Range / Leave Period</td>
        <td width='5px;'>:</td>
        <td align="left"><b><?php echo $range;?></b></td>
    </tr>
    <tr>
        <td>Employee ID</td>
        <td>:</td>
        <td align="left" ><?php echo $data->thisemp->IDEmployee;?></td>
    </tr>
    <tr>
        <td>Employee Name</td>
        <td>:</td>
        <td align="left" ><?php echo $data->thisemp->FullName;?></td>
    </tr>
    <tr>
        <td>Hire Date</td>
        <td>:</td>
        <td align="left" ><?php echo $data->thisemp->HireDate;?></td>
    </tr>
    <tr>
        <td>Untaken Leave</td>
        <td>:</td>
        <td align="left"><b><?php echo $data->utk?></b>*</td>
    </tr>
    <tr>
        <td colspan="3">
            <i style="font-size: 12px;">* calculation depends on range</i>
        </td>
    </tr>
    <tr>
        <td colspan="3" >
            <br>
            <br>
            Addition of <b><?php echo $data->thisemp->FullName;?></b>'s Leave Entitlements :
            <br>
            <br>
            <table width='1000px' border='1'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Addition Date</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i=1;
                    foreach ($data->master as $l){
                        echo "<tr>";
                            echo "<td align='center'>$i</td>";
                            echo "<td align='center'>".date("d-m-Y",strtotime($l->Tanggal))."</td>";
                            echo "<td align='center'>".$l->Jml."</td>";
                            echo "<td>".$l->Alasan."</td>";
                        echo "<tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
     
        </td>
    </tr>
    <tr>
        <td colspan="3" >
            <b><?php echo $data->thisemp->FullName;?></b>'s Annual Leave:
            <br>   
            <br>   
            <table width='1000px' border='1'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Reason</th>
                        <th>PiC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i=1;
                    foreach ($data->leaves as $l){
                        echo "<tr>";
                            echo "<td align='center'>$i</td>";
                            echo "<td align='center'>".date("d-m-Y",strtotime($l->Tanggal))."</td>";
                            echo "<td align='center'>".$l->Jml*(-1)."</td>";
                            echo "<td>".$l->Alasan."</td>";
                            echo "<td align='center'>".$l->Pengganti."</td>";
                        echo "<tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </td>
    </tr>
</table>
