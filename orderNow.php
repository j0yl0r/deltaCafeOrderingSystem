<?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<!-- background on the website-->
<div class="container"> 
    <div class="left">

 
    <?php
        if(isset($_POST["item_id"]) && isset($_POST["qty"]) && $_POST["qty"]){
            $query = "CALL `relate_item_and_order`(".$user_id.", ".$_POST["item_id"].", ".$_POST["qty"].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<p style='color:red;'><b>Added item to order</b></p>";
        }
    ?>    

<!-- <table class="myTable"><tr><th>Item Name</th><th>Description</th><th>Price</th><th>Order Qty</th><th>Actions</th></tr> -->

<?php
    
        echo "<br>
            <table class ='myTable'>";
        echo "
        <tr>
            <th>&emsp;&emsp;&emsp;&emsp; Item Name &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th>  Description &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th> Price &emsp;&emsp;</th>
            <th> Size &emsp;&emsp;&emsp;</th>
            <th> Qty &emsp;&emsp;</th>
            <th> Actions &emsp;&emsp;&emsp; &nbsp; </th>
            </table>
        ";

        $query = "SELECT * FROM tbl_images ORDER BY id ASC";  
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){  
            echo "</tr><table class ='myTable'>";
            echo '<tr><td>
                    <img src="data:image/jpeg;base64,'.base64_encode($row[1] ).'" height="73" width="60" class="img-thumnail" />
                  </td></tr></table>';  
        }
        
                // update inventory when user add item to order
                if(isset ($_POST['item_id']) && isset ($_POST['qty'])){
                    $UpdateQuery =" UPDATE available_items SET stock = stock - '$_POST[qty]'
                                    WHERE id ='$_POST[item_id]'";
                    mysqli_multi_query($conn, $UpdateQuery) or die(mysqli_error($conn));
                
                }  

        echo "<div class='column'>";
        echo "<br><br><br><br>";


        $query = "CALL `select_all_items`()";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){
            echo "<table class ='myTable'>";
            echo "<tr>";
            echo "<td style='width: 200px; height: 73px'>".$row[1]."</td>";
            echo "<td style='width: 250px;'>".$row[2]."</td>";
            echo "<td style='width: 75px;'>$".$row[3]."</td>";
            echo "<td style='width: 75px;'>".$row[5]."</td>";
            echo "<form action='' method='post'>
                <td style='width: 50px;'>
                    <input type='number' name='qty' min='0' max='99'>
                </td>";
            echo "<td style='width: 120px;'>
                    <button type='submit' name='item_id' value=".$row[0].">Add To Order</button>
                </td>
            </form>";
            echo "</tr>";
        }
        mysqli_free_result($result);
        while (mysqli_next_result($conn));
?>

    </table>
    <br><br><br><br>  <br><br><br><br>
    </div>
    </div>
</div>

<?php
    include("./footer.php");
?>