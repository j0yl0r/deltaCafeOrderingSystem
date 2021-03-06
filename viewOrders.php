<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>


<!-- background on the website-->
<div class="container">
    <div class="left2">

    <?php
        // delete function
        if(isset($_POST["relation_id"])){
            $query = "CALL `delete_order_item_relation`(".$_POST['relation_id'].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<p style='color:red;'><b>Removed item from order</b></p>";
        }
        // checkout funtion
        if(isset($_POST["checkout_order_id"])){
            $query ="CALL `checkout_order`(".$_POST['checkout_order_id'].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            header("Location: ./payment.php");
        }
        // update function
        if(isset($_POST['order_id']) && isset($_POST['qty'])){
            $query =
            "UPDATE order_item_relations SET quantity_ordered= '$_POST[qty]'
            WHERE order_item_relations.id = '$_POST[order_id]';";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while(mysqli_next_result($conn));
            echo "<p style='color:red;'><b>Updated Qty</b></p>";
    
        } 
    ?>

   
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

        // If there are not any order then it will state no orders.
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
                
                echo "<p style='font-size:24px'>Order #: ".$order_id." </p>
                      <p><b>Pick-Up Status:</b> ".$order_status."</p>";
                echo "<table class ='myTable'>  
                <tr>
                <th>Item Name</th>
                <th>Item Quantity</th>
                <th>Item Price</th>
                <th>Item Total Price</th>";
                //it will only show the actions button (update and delete) when the status is processing
                if($order_status == 'processing'){
                    echo "<th style='width: 300px;'>Actions</th>";
                }
                echo "</tr>";

                $query = "CALL `select_order_info`(".$order_id.");";
                mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                $result = mysqli_store_result($conn);
                $total_price = 0.0;
                while($row = mysqli_fetch_row($result)){                
                $formID = "userID" . $row[0];
                // This is the order form
                echo "<tr>
                <form id='".$formID."' action='./viewOrders.php' method='post'>
                <input type='hidden' id='hidden' name='hidden' value='".$row[0]. "' >
                    <td style='width: 200px;'>".$row[1]."</td>
                    <td><input  style='width: 100px;' min='1' max='50' type='number' id='qty' name='qty' value= '".$row[2]. "' ></td>
                    <td style='width: 100px;'>".$row[3]."</td>";
                    $total_item_price = $row[3] * $row[2];
                    echo "<td style='width: 150px;'>$".$total_item_price."</td>";
                        //if the status is processing it will show the update and delete button
                        if($order_status == 'processing'){
                            echo "<td>
                            <form action='' method='post'>
                            <a href='' title='Update Qty'>
                            <button type='submit' name='order_id' value=".$row[0].">Update</button> 
                            </a>
                            <a href='' title='Delete Items'>
                            <button style='color:red;' type='submit' name='relation_id' value=".$row[0].">X</button>
                            </a>
                            </form>
                            </td>";
                    }
                    echo "</tr>";

                    $total_price += $total_item_price; //subtotal
                    $tax = 0.08;
                    $total = $total_price * $tax; // subtotal * tax
                    $total2 = $total_price + $total; // total with tax
       
                }
             
                mysqli_free_result($result);
                while (mysqli_next_result($conn));
                echo "</table>";
                echo "<p><b>Subtotal:</b> $".$total_price."";
                echo "<br><b>Tax:</b>".$tax."";
                echo "<br><b>Total:</b> $".number_format($total2, 2, '.', '')."</p>";

                //if the status is processing it will show the checkout button
                if($order_status == 'processing'){
                    echo "<form action='' method='post'>
                        <button type='submit' name='checkout_order_id' value=".$order_id.">Checkout Order</button>
                        </form>";
                }
                //if the status is order placed or ready for pickup it will show the receipt button
                if(($order_status == 'order_placed') || ($order_status == 'ready_for_pickup')){
                    echo "<form action='' method='post'>
                    <button type='submit'><a href='invoice.php'>View Receipt</a></button>
                    </form><br>";
                }
                echo "<br><hr><br>";
            }
        }
    ?>
    </div>
</div>
          
<?php
    include("./footer.php");
?>
