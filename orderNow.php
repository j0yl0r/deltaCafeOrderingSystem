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

    <!-- Use for item to be added to order     -->
    <?php
        if(isset($_POST["item_id"]) && isset($_POST["qty"]) && $_POST["qty"]){
            $query = "CALL `relate_item_and_order`(".$user_id.", ".$_POST["item_id"].", ".$_POST["qty"].");";
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while (mysqli_next_result($conn));
            echo "<p style='color:red;'><b>Added item to order</b></p>";
        }
    ?>    

<!-- Menu table -->
<?php
    
        echo "<br>
            <table class ='myTable'>";
        echo "
        <tr>
            <th>&emsp;&emsp;&emsp;&emsp;</th>
            <th>Item Name &emsp;&emsp;&emsp;&emsp;</th>
            <th>Description &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th> Price &emsp;</th>
            <th> Size &emsp;&emsp;</th>
            <th> Qty &emsp;&emsp;</th>
            <th> Actions &emsp;&emsp;&emsp; &nbsp; </th>
            </table>
        ";

        echo "<div style='float:left;'>";
        // images that is on the menu
        $query = "SELECT * FROM tbl_images ORDER BY id ASC";  
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){  
            echo "</tr><table class ='myTable'>";
            echo '<tr><td>
                    <img src="data:image/jpeg;base64,'.base64_encode($row[1] ).'" height="73" width="60" class="img-thumnail" />
                  </td></tr></table>';  
        }
        echo "</div>";
        
        // update inventory when user add item to order        
        if(isset ($_POST['item_id']) && isset ($_POST['qty'])){
            $UpdateQuery =" UPDATE available_items SET stock = stock - '$_POST[qty]'
                        WHERE id ='$_POST[item_id]'";
            mysqli_multi_query($conn, $UpdateQuery) or die(mysqli_error($conn));
                
        }  

        // Menu that shows available drinks.
        $query = "CALL `select_all_items`()";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){
            echo "<table class ='myTable'>";
            echo "<tr>";
            echo "<td style='width: 150px; height: 73px'>".$row[1]."</td>";
            echo "<td style='width: 350px; height: 73px''>".$row[2]."</td>";
            echo "<td style='width: 50px; height: 73px''>$".$row[3]."</td>";
            echo "<td style='width: 75px; height: 73px''>".$row[5]."</td>";
            echo "<form action='' method='post'>
                <td style='width: 50px; height: 73px''>
                    <input type='number' name='qty' min='0' max='99'>
                </td>";
            echo "<td style='width: 120px; height: 73px''>
                    <button type='submit' name='item_id' value=".$row[0].">Add To Order</button>
                </td>
            </form>";
            echo "</tr>";
        }
        mysqli_free_result($result);
        while (mysqli_next_result($conn));
?>

    </table>

    </div>
</div>

<?php
    include("./footer.php");
?>
