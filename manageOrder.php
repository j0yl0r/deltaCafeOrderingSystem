
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
    
<h2>Manage Orders</h2>


    <?php
        //If the Status is order completed it will show this function
        if(isset($_POST["complete_order_id"])){
            
            $query = "CALL `complete_order`(".$_POST['complete_order_id'].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<h4>Marked order #".$_POST["complete_order_id"]." as Completed</h4>";
        }
         //If the Status is ready for pickup it will show this function
        if(isset($_POST["placed_order_id"])){
            
            $query = "CALL `placed_order`(".$_POST['placed_order_id'].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<h4>Marked order #".$_POST["placed_order_id"]." as Ready For Pickup</h4>";
        }
    ?>




    <?php        
    // update the customer status
    if(isset($_POST['update_order_id']) && isset($_POST['status'])){
         $UpdateQuery =" UPDATE customer_orders SET status ='$_POST[status]'
                         WHERE id='$_POST[update_order_id]'"; 

         mysqli_multi_query($conn, $UpdateQuery ) or die(mysqli_error($conn));
         $result = mysqli_store_result($conn);
         while(mysqli_next_result($conn));
         echo "<h4 style='color:red;'>Updated Status for Order # ".$_POST['update_order_id']."</h4>";
        };
    ?>

    <?php 

        $query = "SELECT `customer_orders`.`id` FROM `customer_orders` ORDER BY `customer_orders`.`id` DESC";
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
                $query = "SELECT `customer_orders`.`status`, `customer_orders`.`customer_id` FROM `customer_orders` WHERE `customer_orders`.`id` = $order_id";
                mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                $result = mysqli_store_result($conn);
                $row = mysqli_fetch_row($result);
                $order_status = $row[0];
                $customer_id = $row[1];
                mysqli_free_result($result);
                while (mysqli_next_result($conn));
                
                echo "<hr>";
                echo "<h4>Order ID: ".$order_id. "<br>";
                // if status is ready for pickup it will show the buttom call order completed
                if($order_status == 'ready_for_pickup'){
                    echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
                    echo "<form action='' method='post'>
                            <button type='submit' name='complete_order_id' value=".$order_id.">
                                Mark Order As Completed
                            </button>
                        </form>";
                }
                // if status is ordered place it will show the buttom call oready for pickup
                if($order_status == 'order_placed'){
                    echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
                    echo "<form action='' method='post'>
                            <button type='submit' name='placed_order_id' value=".$order_id.">
                                Mark Order As Ready for pickup
                            </button>
                        </form>";
                }
                echo "</h4>";
                // Order Form
                echo "<table class='myTable'><form action='' method='post'><tr>
                <th>Customer ID</th>
                <th>Item Name</th>
                <th>Item Quantity</th>
                <th>Item Price</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>";
                echo "</tr>";

                $query = "SELECT  a.name, ROUND(a.price, 2) AS price, o.quantity_ordered
                FROM customer_orders c JOIN available_items a JOIN order_item_relations o 
                WHERE a.id = o.item_id AND  o.order_id = c.id AND c.id= $order_id
                ORDER BY c.id DESC"; 
                mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
                $result = mysqli_store_result($conn);
                while($row = mysqli_fetch_row($result)){
                    $total_item= $row[1] * $row[2];   
                                     
                    echo "<tr>
                    <td style='width: 130px;'>".$customer_id."</td>
                    <td style='width: 150px;'>".$row[0]."</td>
                    <td style='width: 150px;'>".$row[2]."</td>
                    <td style='width: 120px;'>".$row[1]."</td>
                    <td style='width: 80px;'>".$total_item."</td>";
                

                    if(($order_status == 'processing') || ($order_status == 'order_placed') || ($order_status == 'ready_for_pickup')){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
                        echo "<form action='' method='post'>
                            <td><input style='width: 120px;' type='text' id='status' name='status' value= '".$order_status. "' required></td>
                            <td><button type='submit' name='update_order_id' value=".$order_id.">Update</button></td>
                            </tr></form>";
                }

                    if(($order_status == 'order_completed')){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
                        echo "<form action='' method='post'>
                        <td style='width: 80px;'>".$order_status."</td>
                            </tr></form>";
                }

            } 
                mysqli_free_result($result);
                while (mysqli_next_result($conn));

                echo "</table><br>";

            }
            
        }
        
    ?>
    <hr><br><br>
</div>
          
<?php
    include("./footer.php");
?>

