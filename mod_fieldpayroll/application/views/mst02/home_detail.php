<table id="tableloan" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable " >
    <thead>                 
        <tr>
            <?php echo "LOAN DETAL : " . $fullname ?>
            <th class="center">No.</th>
            <th class="center">Installment Date</th>
            <th class="center">Installment</th>                    
            <th class="center">Status</th>
            <th class="center">Note</th>	  

        </tr>
    </thead>
    <tbody>        
        <?php
        error_reporting(0);
        foreach ($activity as $row) {
            echo $row['tr'];
        }
        ?>
    </tbody> 
</table>

<script type="text/javascript">
    $(document).ready(function() {

        $(function()
        {
            /* DataTables */
            if ($('#tableloan').size() > 0)
            {
                $('#tableloan').dataTable({
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_"
                    },
                    "sScrollX": "100%",
                    "sScrollXInner": "110%",
                    "bScrollCollapse": true,
                });
            }


        });

    });

</script>
