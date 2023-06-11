<?php 
	include('conn1.php');
	$conn=getCon();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Script</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>
    <br/>
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type=date name=date class="form-control"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="submit" name="submit" value="Delete Data" 
                        onclick="return confirm('Are you sure you want to delete ?');" class="btn btn-danger"/>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <?php  
        if(isset($_REQUEST['submit'])){
            $date_value = $_REQUEST['date'];
            echo "<div class='container'>";

            if($date_value!=null){
                $date_select = $conn->query("select * from date where dt = '$date_value' ");
                $date_data = $date_select->fetch_assoc();
                $did = $date_data['did'];

                $price_delete = "delete from price where did = $did";
                $result = $conn->query($price_delete);
                if($result){
                    $date_delete = "delete from date where did = $did";
                    $res = $conn->query($date_delete);
                    if($res){
                        echo "<h5 style='color:green;'>".$date_data['dt']." Data Deleted Successcully....</h5>";
                    }else{
                        echo "<p style='color:red;'>Date table data not deleted...</p>";
                    }
                }else{
                    echo "<p style='color:red;'>Price table data not deleted....</p>";
                }    
            }else{
                echo "
                    <br/><h5 style='color:red;'>Please Select Date....</h5>
                ";
            }
            echo "</div>";
        }  
    ?>
</body>
</html>
