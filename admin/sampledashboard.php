<?php
$con  = mysqli_connect("localhost","root","","carrental");
 if (!$con) {
     # code...
    echo "Problem in database connection! Contact administrator!" . mysqli_error();
 }else{
         $sql ="SELECT COUNT(*) id FROM tblvehicles";
         $result = mysqli_query($con,$sql);
         $chart_data="";
         while ($row = mysqli_fetch_array($result)) { 
            $userid[] = $row['id'];	
        }
 
 
 }
?>
						
				<canvas id="chartjs_bar" style="width:100%;max-width:700px"></canvas>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"> </script>
<script>
	var xValues = ["Registered Users", "asd"]
      var ctx = document.getElementById("chartjs_bar").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels:xValues,
                        datasets: [{
                            backgroundColor: [
                               "#5969ff",
                               
                            ],
                            data:<?php echo json_encode($userid); ?>,
                        }]
                    },

                    options: {
                           legend: {
                        display: true,
                        position: 'bottom',
 
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 14,
                        }
                    },
 
 
                }
                });

    </script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Services', 'Total Numbers'],

          <?php
            $query="SELECT services, count(*) as number FROM members GROUP BY services";
            $res=mysqli_query($con,$query);
            while($data=mysqli_fetch_array($res)){
              $services=$data['services'];
              $number=$data['number'];
           ?>
           ['<?php echo $services;?>',<?php echo $number;?>],   
           <?php   
            }
           ?> 

          
        ]);

        var options = {
          // title: 'Chess opening moves',
          width: 1050,
          legend: { position: 'none' },
          // chart: { title: 'Chess opening moves',
          //          subtitle: 'popularity by percentage' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Total'} // Top x-axis.
            }
          },
          bar: { groupWidth: "100%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };


      
    </script>