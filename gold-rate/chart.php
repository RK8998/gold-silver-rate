<?php 
	include('conn.php');
	$conn=getCon();
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Date', 'Price'],
        <?php 
            $past_data = $conn->query("select * from (select * from price where cid=42 ORDER BY pid DESC limit 30) sub ORDER BY pid ASC");
            while($row = $past_data->fetch_assoc()){
                $date = $conn->query("select dt from date where did = ".$row['did']);
                $date = $date->fetch_assoc();
                $dd = date("M d, Y",strtotime($date['dt']));
                $pr = $row['24pr'];
                $pr2 = str_replace(',', '', $pr);
        ?>
            [<?php echo "'$dd'"; ?>, <?php echo $pr2; ?>], 
        <?php
             }              
        ?>
        ]);

        var options = {
          chart: {
            title: 'Gold Price graph',
            subtitle: '30 Days',
          },
          bars: 'vertical', // Required for Material Bar Charts.
          colors: ['gold'],
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

<!-- Line chart -->
  <script>

      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date');
      data.addColumn('number', 'Price');

      data.addRows([
        <?php 
            $past_data = $conn->query("select * from (select * from price where cid=42 ORDER BY pid DESC limit 30) sub ORDER BY pid ASC");
            while($row = $past_data->fetch_assoc()){
                $date = $conn->query("select dt from date where did = ".$row['did']);
                $date = $date->fetch_assoc();
                $dd = date("M d, Y",strtotime($date['dt']));
                $pr = $row['24pr'];
                $pr2 = str_replace(',', '', $pr);
        ?>
            [<?php echo "'$dd'"; ?>, <?php echo $pr2; ?>], 
        <?php
             }              
        ?>
      ]);

      var options = {
        chart: {
          title: 'Gold Price Graph',
          subtitle: '30 Days'
        },
        width: 900,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }

    </script>

  </head>
  <body>
    <h4> Gold Rate History</h4>
	<table class='table' border=1 style="text-align:center;">
        <tbody>
    <?php 
        $past_data = $conn->query("select * from price where cid=42 ORDER BY pid DESC limit 15");
        
        while($row = $past_data->fetch_assoc()){
            
            echo "<tr>";
            $date = $conn->query("select dt from date where did = ".$row['did']);
            $date = $date->fetch_assoc();
            echo "<td>".date("M d, Y",strtotime($date['dt']))." </td> ";
            echo "<td> ₹ ".$row['24pr']." </td> ";
            if ($row['24ch']>0)
            { 
                echo "<td class='mt-3' style='margin-left: 5px;color:green;'>
                        <i class='fa-solid fa-caret-up' style='font-size:10px;'></i>&nbsp;&nbsp;
                        <b>₹".$row['24ch']."</b></td>";
            }
            else
            {
                echo "<td class='mt-3' style='margin-left: 5px;color:red;'>
                        <i class='fa-solid fa-caret-down' style='font-size:10px;'></i>&nbsp;&nbsp;
                            <b>₹".$row['24ch']."</b></td>";
            }
            echo "</tr>";
        }
    ?>
        </tbody>
	</table>
    <br/><br/>
    <div id="barchart_material" style="width: 900px; height: 500px;"></div>

    <div id="linechart_material" style="width: 900px; height: 500px"></div>
  </body>
</html>
