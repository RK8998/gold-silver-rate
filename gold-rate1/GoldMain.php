<?php 
	include('conn.php');
	$conn=getCon();
	$temp1 = 0;
	$temp2 = 0;
	$temp3 = 0;
?>

	<?php
	// date table insert current date
		$cdate = date('Y-m-d');

		$select_date = "select count(*)as dt from date where dt='".$cdate."'";
		$select = $conn->query($select_date);
		$select = $select->fetch_assoc();
		
		if($select['dt'] == 0){
			$q = "insert into date (dt) values ('$cdate')";
			$insert = $conn->query($q);	
			if($insert){
				$temp1 = 1;
			}
		}
	?>

	
	<?php
	//city table price update
		$city = "select cname from city";
		$cityData = $conn->query($city);	
		$cnt = 0;

		while($row = $cityData->fetch_assoc()) {
			$cnt++;
			if($row['cname'] != "india"){
				preg_match('#href=\"/f/gold-rate/'.$row['cname'].'/\">₹([0-9,]+)#', 
				file_get_contents('https://cleartax.in/f/gold-rate/india/'), $matches);
				$cityname = $row['cname'];
				$price = $matches[1];
				$q = "update city set price = '₹".$price."' where cname = '".$cityname."'";
				$update = $conn->query($q);
				if($update){
					$temp2 = 1;
				}
			}
		}
	?>
				
								
	<?php

		$city_select = "select * from city";
		$cityData = $conn->query($city_select);	
		
		$cdate = date('Y-m-d');

		$select_date = "select did from date where dt='".$cdate."'";
		$data = $conn->query($select_date);
		$data = $data->fetch_assoc();
		$did = $data['did'];

		$chk = "select count(*) as chkdid from price where did=$did";
		$data_chk = $conn->query($chk);
		$data_chk = $data_chk->fetch_assoc();
		 $cnt=1;
		if($data_chk['chkdid'] == 0){
			while($row = $cityData->fetch_assoc()) {
				$cid = $row['cid'];
				$cname = $row['cname'];

				// // fetch 24 karat price
				preg_match('#<p class="font-1-5-rem m-auto">₹([0-9,]+)</p>#', 
				file_get_contents('https://cleartax.in/f/gold-rate/'.$cname.'/'), $matches);
				$pr_24 = $matches[1];

				preg_match('#<div class="font-16  rise ">₹(.*)</div>#', 
				file_get_contents('https://cleartax.in/f/gold-rate/'.$cname.'/'), $matches1);
				if(empty($matches1)){
					preg_match('#<div class="font-16  decline ">₹(.*)</div>#', 
					file_get_contents('https://cleartax.in/f/gold-rate/'.$cname.'/'), $matches2);
					if(empty($matches2)){
						preg_match('#<div class="font-16  neutral ">₹(.*)</div>#', 
						file_get_contents('https://cleartax.in/f/gold-rate/'.$cname.'/'), $matches3);
						$ch_24 = $matches3[1];
					}else{
						$ch_24 = $matches2[1];
					}
				}else{
					$ch_24 = $matches1[1];
				}
				

				$price_insert = "insert into price (cid,did,24pr,24ch) values ($cid,$did,'$pr_24','$ch_24')";
				// echo $price_insert;
				$result = $conn->query($price_insert);
				if($result){
					$temp3=1;
				}
				$cnt++;
			}
		}
		else{
			echo "Data aready Load";
		}
	?>

	<?php
		if($temp1 == 1 && $temp2 == 1 && $temp3 == 1){
			echo "Data Load Succesfully..";
			// return header("location : gold-rate.php");
		}
	?>
	<?php 
		// 	// 24 karat
		// 		preg_match('#<p class="font-16 m-auto pt-18">24 K - 10gm</p>#', 
		// 			file_get_contents('https://cleartax.in/f/gold-rate/'.$city.'/'), $matches1);

		// 		//print_r($matches1);
		// 		echo "<b>".$matches1[0]."</b>";
		// 	//-------------------------------------------------
		// 		preg_match('#<p class="font-1-5-rem m-auto">₹([0-9,]+)</p>#', 
		// 			file_get_contents('https://cleartax.in/f/gold-rate/'.$city.'/'), $matches1);

		// 		echo "<br><br>";
		// 		// print_r($matches1);
		// 		echo "<b>".$matches1[0]."</b>";

		// 	//-------------------------------------------------
		// preg_match('#<th class="pt-0 pb-2 align-top font-16  rise ">₹([-0-9,]+)#', 
		// 			file_get_contents('https://cleartax.in/f/gold-rate/'.$city.'/'), $matches1);

		// 		echo "<br><br>";
		// 		// print_r($matches1);
		// 		echo "<b>".$matches1[0]."</b>";
	?>


	<?php
		// // 22 karat
		// 	preg_match('#<p class="font-16 m-auto pt-18">22 K - 10gm</p>#', 
		// 		file_get_contents('https://cleartax.in/f/gold-rate/'.$city.'/'), $matches);

		// 	//print_r($matches);
		// 	echo "<b>".$matches[0]."</b>";

		// 	preg_match('#<p class="font-1-5-rem m-auto">₹([0-9,]+)</p>#', 
		// 		file_get_contents('https://cleartax.in/f/gold-rate/'.$city.'/'), $matches);


		// 	echo "<br><br>";
		// 	//print_r(array($matches));
		// 	echo "<b>".$matches[0]."</b>";
	?>
