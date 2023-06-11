<?php 
	include('conn.php');
	$conn=getCon();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gold-Rate</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.css" integrity="sha512-1hsteeq9xTM5CX6NsXiJu3Y/g+tj+IIwtZMtTisemEv3hx+S9ngaW4nryrNcPM4xGzINcKbwUJtojslX2KG+DQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Date', 'Price'],
        <?php 
            $past_data = $conn->query("select * from (select * from price where cid=1 ORDER BY pid DESC limit 30) sub ORDER BY pid ASC");
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

<style type="text/css">
	*{
		padding: 0px;
		margin: 0px;
	}
	.left-box{
		border: 0px solid black;
		padding: 30px;
		border-radius: 15px;
		/*box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;*/
		box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
	}
	#left_line:hover{
		cursor: pointer;
		text-decoration: underline;
		color: blue;
	}
	.right_box{
		border: 0px solid black;
		padding-left: 50px;
		padding-right: 50px;
		padding-top: 10px;
		padding-bottom: 5px;
		box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
		border-radius: 10px;
	}
</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8">
				<div class="row text-center">
					<h2>Gold-Rate</h2>
				</div>
				<form method="post">
					<div class="row mt-3">
						<div class="col-md-3">
							<div class="form-group">
								Rate in : 
								<select name="city" id="city" class="form-control">
									<?php 
										$q = "select * from city";
										$data = $conn->query($q);
										while($row = $data->fetch_assoc()){
											if($row['cname'] == "bangalore"){
											echo "<option value=".$row['cid']." selected>".ucfirst($row['cname'])."</option>";
											}else{
											echo "<option value=".$row['cid'].">".ucfirst($row['cname'])."</option>";
											}
											
										}
									?>
								</select>
								<script type="text/javascript">
								  document.getElementById('city').value = "<?php echo $_REQUEST['city'];?>";
								</script>
							</div>		
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<br><input type="date" name="dateinp" class="form-control"
								value="<?php echo (new DateTime())->format('Y-m-d'); ?>">	
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<br><input type="submit" name="submit" class="btn btn-primary"> 
							</div>	
						</div>
					</div>
				</form>

				<div class="right_box mt-4 text-center">
					<?php 
						if(isset($_REQUEST['submit'])){
							$cityid = $_REQUEST['city'];
							$data = $conn->query("select * from city where cid = $cityid");
							$data = $data->fetch_assoc();
							$cname = $data['cname'];
						}else{
							$cname = "bangalore";
						}
					?>
					<h3>Gold Rate in <?php echo ucfirst($cname); ?>- 
						<?php 
							$cdate = date('Y-m-d');
							$data_did = $conn->query("select did from date where dt = '".$cdate."'");
							$data_did = $data_did->fetch_assoc();
							if(empty($data_did)){
								$yesterday = new DateTime('yesterday');
								echo $yesterday->format('d M Y'); 
							}else{
								if(isset($_REQUEST['dateinp'])){
									$dt = $_REQUEST['dateinp'];
									echo date('d M Y', strtotime($dt));
								}else{
									echo date('d M Y');	
								}
							}
						?>
					</h3>
					<br>
					<div><b>24 Karat - Rs/10gm.</b></div>
					<?php 
						$did = 0;
						if(!isset($_REQUEST['dateinp'])){
							$cdate = date('Y-m-d');
							$data_did = $conn->query("select did from date where dt = '".$cdate."'");
							$data_did = $data_did->fetch_assoc();
							if(empty($data_did)){
								$yesterday = new DateTime('yesterday');
								$ydate = $yesterday->format('Y-m-d');
								$data_did = $conn->query("select did from date where dt = '".$ydate."'");
								$data_did = $data_did->fetch_assoc();
								$did = $data_did['did'];
							}else{
								$did = $data_did['did'];
							}
						}
						else{
							$dt = $_REQUEST['dateinp'];
							$data_did = $conn->query("select did from date where dt = '".$dt."'");
							$data_did = $data_did->fetch_assoc();
							$did = $data_did['did'];
						}


						$data_cid = $conn->query("select cid from city where cname = '".$cname."'");
						$data_cid = $data_cid->fetch_assoc();
						$cid = $data_cid['cid'];

						$data_price = $conn->query("select * from price where cid=$cid and did=$did");
						$data_price = $data_price->fetch_assoc();
						$pr_24 = $data_price['24pr'];
						$ch_24 = $data_price['24ch'];
					?>

					<h2 class="mt-3" style="margin-left: 10px;"><?php echo "₹".$pr_24; ?></h2>
					<?php 
						if($ch_24 == 0){
							echo "
								<h2 class='mt-3' style='margin-left: 10px;'>₹".$pr_24."</h2>
							";
							echo "
								<p class='mt-2' style='color:gray;margin-left: 17px;'>No Changes</p>
							";
						}
						if($ch_24 < 0){
							echo "
								<h2 class='mt-3' style='margin-left: 5px;color:red;'>
							<i class='fa-solid fa-angle-down' style='font-size:25px;'></i>&nbsp;&nbsp;₹".$pr_24."</h2>
							";
							echo "
								<p class='mt-2' style='color:red;margin-left: 17px;'>".$ch_24."</p>
							";
						}
						if($ch_24 > 0){
							echo "
								<h2 class='mt-3' style='margin-left: 5px;color:green;'>
							<i class='fa-solid fa-angle-up' style='font-size:25px;'></i>&nbsp;&nbsp;₹".$pr_24."</h2>
							";
							echo "
								<p class='mt-2' style='color:green;margin-left: 17px;'>".$ch_24."</p>
							";
						}
					?>
				</div>	
				
				<div class="chart">
					<div id="barchart_material" style="width: auto; height: 550px;"></div>
				</div>

			</div>	
			<div class="col-md-4">
				<div class="left-box">
					<?php 
						$city_query = "Select * from city";
						$fetch_city = $conn->query($city_query);
						$cnt = 0;
						while($row = $fetch_city->fetch_assoc()){
							if($cnt == 0){
								echo "
									<div class='text-center mb-2' style='font-size:20px;font-weight:bold;'>
										Gold Rates in Metro Cities</div>
					                <div class='text-center' style='font-weight:bold;'>24 Karat - 10gm</div><hr/>
								";
							}
							if($cnt == 6){
								echo "<hr/>
									<div class='text-center mb-2' style='font-size:20px;font-weight:bold;'>
										Gold Rates in Other Capitals</div>
					                <div class='text-center' style='font-weight:bold;'>24 Karat - 10gm</div><hr>
								";
							}
							if($cnt == 12){
								echo "<hr/>
									<div class='text-center mb-2' style='font-size:20px;font-weight:bold;'>
										Gold Rates in Other Major Cities</div>
					                <div class='text-center' style='font-weight:bold;'>24 Karat - 10gm</div><hr>
								";
							}

							echo "
								<div class='row'>
									<form action='".$row['cname'].".php' method='post'>
									<button type='submit' name='citylink' value='".$row['cname']."' class='btn'>
										<p id='left_line'>Gold Rate in ".ucfirst($row['cname'])."
										<span><b>".$row['price']."</b></span></p>
									</button>
									</form>
								</div>
							";

							$cnt++;
						}
					?>
				</div>
			</div>
	</body>
</html>