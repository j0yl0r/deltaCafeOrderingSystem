
  <?php
    include("./force_login.php");
    global $user_id;
    include_once("./sqlInit.php");
    global $conn;
    include("./header.php");
?>

<div class="container">
    <div class="left">

    <?php
    // Items
    if(isset($_POST['update']) && isset($_POST['item_id']) && 
    isset($_POST['name']) && isset($_POST['desc']) && 
    isset($_POST['price']) && isset($_POST['stock']) && 
    isset($_POST['size'])){
        // If this page was loaded to update an item
        $query = 
        "CALL `update_menu`('".$_POST['item_id']."', '".$_POST["name"]."', '".
            $_POST["desc"]."', '".$_POST['price']."', '".$_POST['stock']."', '".$_POST['size']."');";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while(mysqli_next_result($conn));
        echo "<h4>Updated Item: ".$_POST['name']."</h4>";

    } else if(isset ($_POST['delete']) && isset($_POST['item_id'])){
        // If this page was loaded to delete an item
        $query = "CALL `delete_available_item`(".$_POST['item_id'].");";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while(mysqli_next_result($conn));
        echo "<h4>Deleted Item: ".$_POST['name']."</h4>";

    } else if(isset ($_POST['add']) && isset($_POST['name']) &&
    isset($_POST['desc']) && isset($_POST['price']) && 
    isset($_POST['stock']) && isset($_POST['size']) ){
        // If this page was loaded to add an item
        $query = "CALL `insert_new_item`('".$_POST["name"]."', '".$_POST["desc"]."', '".$_POST['price']."', 
        '".$_POST['stock']."', '".$_POST['size']."');";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while(mysqli_next_result($conn));
        echo "<h4>Added Item: ".$_POST['name']."</h4>";
    } 
?>
    
    <h3>Edit Menu</h3>
    <table class= 'myTable'>
        <!-- Table header for the menu -->
        <tr>
            <th>&emsp;&emsp;&emsp;&emsp;</th>
            <th>Item Name &emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th>Description &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
            <th>Price &emsp;</th>
            <th>Qty &emsp;&emsp;</th>
            <th>Size &emsp;&emsp;</th>
            <th>&emsp;&emsp;&emsp;</th>
            <th>&emsp;&emsp;&emsp;</th>
        </tr>
    </table>

    <?php

            // The pictures that are on the menu
            echo "<div style='float:left;'>";
            $query = "SELECT * FROM tbl_images ORDER BY id asc";  
            mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
            $result = mysqli_store_result($conn);
            while($row = mysqli_fetch_row($result)){  
                echo "</tr><table class ='myTable'>";
                echo '<tr><td>
                        <img src="data:image/jpeg;base64,'.base64_encode($row[1] ).'" height="73" width="60" class="img-thumnail" />
                      </td></tr></table>';  
            }
            echo"</div>";

        // table for the available items on the menu
        $query = "CALL `select_all_items`()";
        mysqli_multi_query($conn, $query) or die(mysqli_error($conn));
        $result = mysqli_store_result($conn);
        while($row = mysqli_fetch_row($result)){
            $formID = "inventoryID" . $row[0];
            echo "</tr><table class ='myTable'>";
            echo "<form id='".$formID."' action='./manageMenu.php' method='post'>
            <input type='hidden' id='item_id' name='item_id' value='".$row[0]."'>
            <tr>
                <td>
                    <input style='width: 158px; height: 67px'' type='text' id='name' name='name' value='".$row[1]."' required>
                </td>
                <td>
                    <textarea style='width: 200px;' id='desc' name='desc' form='".$formID."' required>".$row[2]."</textarea>
                </td>
                <td>
                    <input style='width: 50px;' type='text' id='price' name='price' value='".$row[3]."' required>
                </td>
                <td>
                    <input style='width: 50px;'  min='0' max='999' type='number' name='stock' value='".$row[4]."' required>
                </td>
                <td>
                    <input style='width: 50px;' type='text' id='size' name='size' value='".$row[5]."' required>
                </td>
                <td>
                    <button type='submit' name='update' value=''>Update</button>
                </td>
                <td>
                    <button type='submit' name='delete' value=''>Delete</button>
                </td>
            </tr></form>";
        }
        mysqli_free_result($result);
        while (mysqli_next_result($conn));
    ?>
  
    </table><br><br>


    <div style="overflow: auto;">
        <div style="float:left;">
            <!-- Add new items to the menu -->
            <h3>Add New Item to Menu</h3>
            <form id='add_item_form_id' action='manageMenu.php' method='post'> <tr>
                <table class ='myTable'>
                    <tr>
                        <td>Item Name</td>
                        <td><input type='text' name='name' required></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea id='desc' name='desc' form='add_item_form_id' required></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type='text' name='price' required></td>
                    </tr>
                    <tr>
                        <td>Qty</td>
                        <td><input type='number' name='stock'  min='0' max='999' required></td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <td><input type='text' name='size' required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><button type='submit' name='add' value=''>Add Item</button></td>

                    </tr>
                </table>
            </form>
        </div>



        <div style="float:left; padding-left: 15%">
            <!-- Add button that takes the user to a different page to upload image(s) that will be added to the menu automatically -->
            <h3>Coffee Images</h3>
            <button type="submit"><a href="uploadimages.php">Add/Delete Image</a></button>
        </div>
    </div>

    </div>
</div>    
    
<?php
    include("./footer.php");
?>

