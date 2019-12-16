<html>
  <head>
    <?php $base_url = $this->session->userdata('sess_base_url'); ?>
    <script type='text/javascript' src='<?php echo $base_url;?>/public/theme/scripts/plugins/google_jsapi.js'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(gambar);
      function gambar() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        data.addRows([
            <?php 
            foreach($all as $a){
    echo "[{v:'$a->IDStructure',f:'$a->DescStructure'},'$a->IDParent','$a->DescStructure'],
          ";
            }
            
            ?>
          ['', '', '']
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
    </script>
  </head>

  <body>
    <div id='chart_div'></div>
  </body>
</html>
