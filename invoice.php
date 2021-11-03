<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<?php
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
                echo "<h3>No Invoice/h3>";
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
                if(($order_status == 'order_placed')){
                    echo "<hr><p style = 'font-size: 1.6em;'> <i>Thank You for your order!</p>";
                    echo "<hr>";
                    echo "<p>Ready for pickup in about <b>10-15 minutes</b></p><hr>";
                }
                if(($order_status == 'order_placed') || ($order_status == 'ready_for_pickup')){
                    echo "
                    <p style = 'font-size: 1.4em;'><b> ".$order_status."</b>
                    <br><p>When your order is ready for pick up, please enter your <b>order #</b> at the kiosk to get your drinks.</i></p>
                    ";
                    echo "
                    Order #: ".$order_id." <br>
                    Order Date: ".$row[1]."<br>

                    ";

    
    
                    $query = "CALL `select_order_info`(".$order_id.");";
                    mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                    $result = mysqli_store_result($conn);
                    $total_price = 0.0;
                    while($row = mysqli_fetch_row($result)){                
                    $formID = "userID" . $row[0];


                    echo "
                    <tr>
                    <form  id = '$formID' action='' method='post'>
                    <input type='hidden' id='hidden' name='hidden' value='".$row[0]. "' >
                        <tr>
                            <th>Items: </th>
                            <td> ".$row[1]." - x".$row[2]."</td>
                        </tr>";
                        echo "</form></tr>";
  
                        $total_item_price = $row[3] * $row[2];                    
                        $total_price += $total_item_price; //subtotal
                        $tax = 0.08;
                        $total = $total_price * $tax; // subtotal * tax
                        $total2 = $total_price + $total; // total with tax
                    }
  
                    echo "<tr>
                            <th>Subtotal: </th>
                            <td> $ ".$total_price."</th>
                        </tr>";

                    echo "<br><tr>
                            <th>Tax: </th>
                            <td> $ ".$tax."</th>
                        </tr>";
                    echo "<br><tr>
                        <th>Total: </th>
                        <td> $ ".number_format($total2, 2, '.', '')."</th>
                    </tr>";
                    echo "</table>";

                    echo "<br><hr><br>";
    
                    }

                    while (mysqli_next_result($conn));

                }
            }

        ?>

<button type="submit"><a href="viewOrders.php">Go back to View Orders</a></button>

        </div>

</div>
          
<?php
    include("./footer.php");
?>
