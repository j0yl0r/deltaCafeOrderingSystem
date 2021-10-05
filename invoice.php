<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<div class="container">
    <div class="left4">
    
        <!--   Select customer order to create invoice    -->
        <?php
            $query = "CALL `select_customer_orders`(".$user_id.")";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            $order_count = mysqli_num_rows($result);
            $order_ids = [];
            while($row = mysqli_fetch_row($result)){
                $order_ids[] = $row[0];
            }
            mysqli_free_result($result);
            while (mysqli_next_result($conn));
    
            if ($order_count == 0) {
                echo "<h3>No Orders in the system</h3>";
            } else {
                foreach($order_ids as $order_id){
                    
                    $query ="CALL `select_order_status`(".$order_id.");";
                    mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                    $result = mysqli_store_result($conn);
                    $row = mysqli_fetch_row($result);
                    $order_status = $row[0];
                    mysqli_free_result($result);
                    while (mysqli_next_result($conn));

                // invoice table                
                if($order_status == 'being_made'){
                    echo "<hr><p><i>Thank You for you order. <br>When your order is ready for pick up, please enter your <b>order id #</b> at the kiosk to get your drinks.</i></p>";
                    echo "<table class ='myTable'>               
                    <tr>
                        <th>Order ID: </th> 
                        <td> #".$order_id."</td>
                    </tr>
                    <tr>
                        <th>Order Status: </th>
                        <td> ".$order_status."</td>
                        </tr>
                    <tr>
                        <th>Order Date: </th>
                        <td> ".$row[1]."</td>
                    </tr>";
    
    
                    $query = "CALL `select_order_info`(".$order_id.");";
                    mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                    $result = mysqli_store_result($conn);
                    $total_price = 0.0;
                    while($row = mysqli_fetch_row($result)){                
                    $formID = "userID" . $row[0];


                    echo "
                    <tr>
                    <form id='".$formID."' action='' method='post'>
                    <input type='hidden' id='hidden' name='hidden' value='".$row[0]. "' >
                        <tr>
                            <th>Items: </th>
                            <td> ".$row[1]." - x".$row[2]."</td>
                        </tr>";
                        echo "</form></tr>";
  
                        $total_item_price = $row[3] * $row[2];                    
                        $total_price += $total_item_price;
                    }
  
                    echo "<tr>
                            <th>Order Total Cost: </th>
                            <td> $ ".$total_price."</th>
                        </tr>";
                    echo "</table>";

                    echo "<br><hr><br>";
    
                    }
   
                    // mysqli_free_result($result);
                    while (mysqli_next_result($conn));

                }
            }

        ?>


        </div>

</div>
          
<?php
    include("./footer.php");
?>
