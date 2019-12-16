<table id="tablecomment" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable " >
    <thead>                 
        <tr>
            <th class="center">Tested By</th>
            <th class="center">Note</th>                   
        </tr>
    </thead>
    <tbody>        
        <?php
        error_reporting(0);
        foreach ($resultdata as $row) {
            echo $row['tr'];
        }
        ?>
    </tbody> 
</table>

<script type="text/javascript">
    $(document).ready(function() {

        $(function()
        {
            
            $.fn.dataTableExt.sErrMode = 'throw';
            /* DataTables */
            if ($('#tablecomment').size() > 0)
            {
                $('#tablecomment').dataTable({
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_"
                    },
                    "sScrollX": "100%",
                    "sScrollXInner": "110%",
                    "bScrollCollapse": true,
                    "bDestroy": true,
                });
            }


        });

    });

</script>