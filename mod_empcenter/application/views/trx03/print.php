<?php 
$base_url = $this->session->userdata('sess_base_url'); 
$d = $data->row();
?>
<style>
    body{
        font-family: Arial;
    }
    table{
        border-collapse: collapse;
    }
    .subtitle{
        font-size: 10px;
    }
    .title{
        font-size: 20px;
    }
    .isi{
        padding: 15px;
        /*font-size: 11px;*/
    }
    .isi2{
        padding-top: 25px;
        /*font-size: 11px;*/
    }
    .tisi{
        font-size: 12px;
    }
    .atas_border{
        height: 20px;
        border-top: #000 solid thin;
    }
    .bawah_border{
        height: 20px;
        border-bottom: #000 solid thin;
    }
    .kiri_border{
        height: 20px;
        border-left: #000 solid thin;
    }
    .kanan_border{
        height: 20px;
        border-right: #000 solid thin;
    }
</style>
<table border="0" width="100%">
    <tr>
        <td align="center" width="20%">
            <img height="100px" width="100px" src="<?php echo $base_url;?>public/avatar/logoTIS.png">
        </td>
        <td align="center" valign="bottom" width="60%">
            <b class="title">PT TRIAS INDRA SAPUTRA</b><br>
            <div class="subtitle">
                Sentra Industri Terpadu Pantai Indah Kapuk<br>
                Jalan Dokter Kamal Muara VII Blok A No. 6, Jakarta 14470, Indonesia<br>
                Telp : +6221 555 2989, Fax : +6221 619 8571, 619 5812<br>
                Email : info@triasindrasaputra.com, Website : www.triasindrasaputra.com
            </div>
        </td>
        <td width="20%" align="center">
            
        </td>
    </tr>
    <tr>
        <td colspan="3"  class="isi atas_border">
            <table class="tisi" width="1000px" border="0">
                <tr >
                    <td colspan="3" align="right">
                        Jakarta, <?php echo $d->MemoDate;?>
                    </td>
                </tr>
                <tr>
                    <td width="75px">
                        To
                    </td>
                    <td width="10px">:</td>
                    <td>
                        <?php echo $d->ToDiv." : ".$d->ToName;?>
                    </td>
                </tr>
                <tr>
                    <td width="75px">
                        Subject
                    </td>
                    <td width="10px">:</td>
                    <td>
                        <?php echo $d->MemoSubject;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="isi2">
                        <?php echo $d->MemoText;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right">
                        <table>
                            <tr>
                                <td align="center"><?php echo $d->FromDiv;?>,</td>
                            </tr>
                            <tr>
                                <td align="center"><?php echo $d->FromName;?></td>
                            </tr>
                        </table>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>