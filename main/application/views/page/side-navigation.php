<style>
a{cursor: pointer;}
</style>
<!-- Regular Size Menu -->
<ul class="menu-0">


    <!-- Menu Regular Item -->
    <li class="glyphicons display active"><a href="<?php echo base_url(); ?>"><i></i><span>Dashboard</span></a></li>

    <!-- System Admin -->
    <li class="hasSubmenu glyphicons cogwheels">
        <a data-toggle="collapse" href="#mnu_sys"><i></i><span>SysAdmin</span></a>
        <ul class="collapse" id="mnu_sys">
            <li>
                <ul>
                    <li>
                        <a url-mod="main" url-det="menu"><span>Menu Controls</span></a>
                    </li>
                </ul>
            </li>
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_sys_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_sys_trx">
                    <li class=""><a url-mod="mod_admin" url-det="trx01"><span>User Access</span></a></li>
                    <li class=""><a url-mod="mod_admin" url-det="trx01"><span>User Access</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Transaction END -->
            <!-- Reference -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_sys_ref"><span>Reference</span></a>
                <ul class="collapse" id="mnu_sys_ref">
                    <li class=""><a data-url="login" href=""><span>Role </span></a></li>
                    <li class=""><a href=""><span>Module </span></a></li>
                    <li class=""><a href=""><span>Access Button </span></a></li>
                </ul>
                <span class="count">3</span>
            </li>
            <!-- // Reference END -->
        </ul>
        <span class="count">4</span>
    </li>
    <!-- System Admin END -->
    <!-- Sales Estimating -->
    <li class="hasSubmenu glyphicons stats">
        <a data-toggle="collapse" href="#mnu_eis"><i></i><span>Executive Information</span></a>
        <ul class="collapse" id="mnu_eis">
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_logs_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_logs_rpt">
                    <li class=""><a url-mod="mod_empcenter" url-det="emp_stat"><span>Employee Status</span></a></li>
                    <li class=""><a url-mod="mod_empcenter" url-det="emp_sum"><span>Employee Summary</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Reports END -->
        </ul>
        <span class="count">1</span>
    </li>
    <!-- Sales Estimating END -->


    <!-- Employee Center -->
    <li class="hasSubmenu glyphicons group">
        <a data-toggle="collapse" href="#mnu_emc"><i></i><span>Employee Center</span></a>
        <ul class="collapse" id="mnu_emc">
            <!-- Reports -->
            <li class="hasSubmenu">
                <a  data-toggle="collapse" href="#mnu_emc_rpt" ><span>Reports</span></a>  
                <span class="count">1</span>    
            </li>
            <!-- // Reports END -->
            <!-- Master -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_emc_mst"><span>Master</span></a>
                <ul class="collapse" id="mnu_emc_mst">
                    <li class=""><a url-mod="mod_empcenter" url-det="mst03" ><span>Organization Structure</span></a></li>
                    <li class=""><a href=""><span>Personal</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Master END -->
            <!-- Reference -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_emc_ref"><span>Reference</span></a>
                <ul class="collapse" id="mnu_emc_ref">
                    <li class=""><a href=""><span>Organization Structure </span></a></li>
                    <li class=""><a href=""><span>Job Position </span></a></li>
                    <li class=""><a href=""><span>Job Location/Site </span></a></li>
                </ul>
                <span class="count">3</span>
            </li>
            <!-- // Reference END -->
        </ul>
        <span class="count">4</span>
    </li>
    <!-- Employee Center END -->

    <!-- Time Attendance -->
    <li class="hasSubmenu glyphicons clock">
        <a data-toggle="collapse" href="#mnu_tma"><i></i><span>Time Attendance</span></a>
        <ul class="collapse" id="mnu_tma">
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_tma_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_tma_trx">
                    <li class=""><a href=""><span>Overtime </span></a></li>
                    <li class=""><a href=""><span>Present Incomplete </span></a></li>
                    <li class=""><a href=""><span>Leave Permit </span></a></li>
                    <li class=""><a href=""><span>Sickness Leave </span></a></li>
                    <li class=""><a href=""><span>Leave Permit </span></a></li>
                    <li class=""><a href=""><span>Leave </span></a></li>
                </ul>
                <span class="count">6</span>
            </li>
            <!-- // Transaction END -->
            <!-- Process -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_tma_proc"><span>Process</span></a>
                <ul class="collapse" id="mnu_tma_proc">
                    <li class=""><a href=""><span>Cardraw </span></a></li>
                    <li class=""><a href=""><span>Monthly Process</span></a></li>
                    <li class=""><a href=""><span>Moving Date Attendance</span></a></li>
                    <li class=""><a href=""><span>Rawdata Result</span></a></li>
                </ul>
                <span class="count">4</span>
            </li>
            <!-- // Transaction END -->
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_tma_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_tma_rpt">
                    <li class=""><a href=""><span>Detail Absentism </span></a></li>
                    <li class=""><a href=""><span>Detail Overtime </span></a></li>
                    <li class=""><a href=""><span>Summary Presence</span></a></li>
                    <li class=""><a href=""><span>Summary Absentism Staff</span></a></li>
                    <li class=""><a href=""><span>Summary Absentism Field</span></a></li>
                    <li class=""><a href=""><span>Summary Overtime Staff</span></a></li>
                    <li class=""><a href=""><span>Summary Overtime Field</span></a></li>
                </ul>
                <span class="count">7</span>
            </li>
            <!-- // Reports END -->
            <!-- Master -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_tma_mst"><span>Master</span></a>
                <ul class="collapse" id="mnu_tma_mst">
                    <li class=""><a href=""><span>ID Card Mapping</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Master END -->
            <!-- Reference -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_tma_ref"><span>Reference</span></a>
                <ul class="collapse" id="mnu_tma_ref">
                    <li class=""><a href=""><span>Holidays </span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Reference END -->
        </ul>
        <span class="count">19</span>
    </li>
    <!-- Time Attendance END -->

    <!-- Field Payroll -->
    <li class="hasSubmenu glyphicons wallet">
        <a data-toggle="collapse" href="#mnu_field"><i></i><span>Field Payroll</span></a>
        <ul class="collapse" id="mnu_field">
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_field_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_field_trx">
                    <li class=""><a href=""><span>Manual Deduction</span></a></li>
                    <li class=""><a href=""><span>Manual Addition</span></a></li>
                </ul>
                <span class="count">2</span>
            </li>
            <!-- // Transaction END -->
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_field_proc"><span>Process</span></a>
                <ul class="collapse" id="mnu_field_proc">
                    <li class=""><a href=""><span>Monthly Process</span></a></li>
                    <li class=""><a href=""><span>Daily Salary</span></a></li>
                    <li class=""><a href=""><span>Daily Overtime</span></a></li>
                    <li class=""><a href=""><span>Additional</span></a></li>
                    <li class=""><a href=""><span>Deduction</span></a></li>
                </ul>
                <span class="count">5</span>
            </li>
            <!-- // Transaction END -->
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_field_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_field_rpt">
                    <li class=""><a href=""><span>Payslip Salary</span></a></li>
                    <li class=""><a href=""><span>Payslip Khusus </span></a></li>
                </ul>
                <span class="count">2</span>
            </li>
            <!-- // Reports END -->
            <!-- Master -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_field_mst"><span>Master</span></a>
                <ul class="collapse" id="mnu_field_mst">
                    <li class=""><a href=""><span>Personal Payroll</span></a></li>
                    <li class=""><a href=""><span>Personal Loan</span></a></li>
                </ul>
                <span class="count">2</span>
            </li>
            <!-- // Master END -->
        </ul>
        <span class="count">11</span>
    </li>
    <!-- Field Payroll END -->

    <!-- Logistic -->
    <li class="hasSubmenu glyphicons truck">
        <a data-toggle="collapse" href="#mnu_logs"><i></i><span>Logistic</span></a>
        <ul class="collapse" id="mnu_logs">
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_logs_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_logs_trx">
                    <li class=""><a href=""><span>Delivery Order/Note</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Transaction END -->
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_logs_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_logs_rpt">
                    <li class=""><a href=""><span>Delivery Order Production</span></a></li>
                    <li class=""><a href=""><span>Delivery Order Canceled </span></a></li>
                </ul>
                <span class="count">2</span>
            </li>
            <!-- // Reports END -->
            <!-- Master -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_logs_mst"><span>Master</span></a>
                <ul class="collapse" id="mnu_logs_mst">
                    <li class=""><a href=""><span>Product </span></a></li>
                    <li class=""><a href=""><span>Customer </span></a></li>
                    <li class=""><a href=""><span>Subcon </span></a></li>
                    <li class=""><a href=""><span>Vendor </span></a></li>
                </ul>
                <span class="count">4</span>
            </li>
            <!-- // Master END -->
        </ul>
        <span class="count">7</span>
    </li>
    <!-- Logistic END -->

    <!-- Accounting -->
    <li class="hasSubmenu glyphicons coins">
        <a data-toggle="collapse" href="#mnu_acc"><i></i><span>Accounting</span></a>
        <ul class="collapse" id="mnu_acc">
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_acc_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_acc_trx">
                    <li class=""><a href=""><span>Cash Payment</span></a></li>
                    <li class=""><a href=""><span>Cash Receive</span></a></li>
                    <li class=""><a href=""><span>Bank Payment</span></a></li>
                    <li class=""><a href=""><span>Bank Receive</span></a></li>
                    <li class=""><a href=""><span>Adjustment</span></a></li>
                </ul>
                <span class="count">5</span>
            </li>
            <!-- // Transaction END -->
            <!-- Process -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_acc_proc"><span>Process</span></a>
                <ul class="collapse" id="mnu_acc_proc">
                    <li class=""><a href=""><span>Posting </span></a></li>
                    <li class=""><a href=""><span>Monthly Closing </span></a></li>
                    <li class=""><a href=""><span>Yearly Closing </span></a></li>
                </ul>
                <span class="count">3</span>
            </li>
            <!-- // Process END -->
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_acc_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_acc_rpt">
                    <li class=""><a href=""><span>General Ledger</span></a></li>
                    <li class=""><a href=""><span>Income Statement </span></a></li>
                    <li class=""><a href=""><span>Profit & Loss </span></a></li>
                </ul>
                <span class="count">3</span>
            </li>
            <!-- // Reports END -->
            <!-- Master -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_acc_mst"><span>Master</span></a>
                <ul class="collapse" id="mnu_acc_mst">
                    <li class=""><a href=""><span>Chart Of Account </span></a></li>
                    <li class=""><a href=""><span>Begining Balance  </span></a></li>
                </ul>
                <span class="count">2</span>
            </li>
            <!-- // Master END -->
        </ul>
        <span class="count">13</span>
    </li>
    <!-- Accounting END -->

    <!-- Sales Estimating -->
    <li class="hasSubmenu glyphicons calculator">
        <a data-toggle="collapse" href="#mnu_est"><i></i><span>Sales Estimating</span></a>
        <ul class="collapse" id="mnu_est">
            <!-- Transaction -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_est_trx"><span>Transaction</span></a>
                <ul class="collapse" id="mnu_est_trx">
                    <li class=""><a url-mod="mod_estimating" url-det="trx01"><span>Request Estimation</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Transaction END -->
            <!-- Reports -->
            <li class="hasSubmenu">
                <a data-toggle="collapse" href="#mnu_logs_rpt"><span>Reports</span></a>
                <ul class="collapse" id="mnu_logs_rpt">
                    <li class=""><a href=""><span>Journal Estimation</span></a></li>
                </ul>
                <span class="count">1</span>
            </li>
            <!-- // Reports END -->
        </ul>
        <span class="count">2</span>
    </li>
    <!-- Sales Estimating END -->

</ul>
<div class="clearfix"></div>
<!-- // Regular Size Menu END -->

<!-- Item Click -->
<script type="text/javascript">
$(document).ready(function(){
    var menu_items = $('#menu .slim-scroll > ul.menu-0 ul li ul li a');

    menu_items.click(
            function()
            {
                var content = $("#content .innerLR");
                var url = ROOT.base_url + $(this).attr("url-mod") + '/index.php/' + $(this).attr("url-det") + '/home';
//                alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
                return false;
            });
});
</script>
<!-- Item Click END -->
