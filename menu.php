<?php 
    include("config/db_connect.php");
    if(isset($_GET["id"])){

        $id = $_GET["id"];
        $sql = "DELETE FROM rooms WHERE ID='$id'";

        mysqli_query($conn, $sql);

    }
    if(isset($_GET["facility"])){
        $_POST["facility"] = $_GET["facility"];
    }

?>

<!DOCTYPE html>
<html>
    <?php include("Templates/header.php"); 
    
    
    if(isset($_POST["facility"])){

        

        $facility = $_POST["facility"];

        $sql = "SELECT * From rooms WHERE Facility ='$facility'";

        $result = mysqli_query($conn, $sql);

        $rooms = mysqli_fetch_all($result);

        mysqli_free_result($result);

    }
    mysqli_close($conn);

    ?>

    <h4 class="center grey-text">Rooms</h4>
    <div class="container">
        <form action="menu.php" method="POST">
            <label>Facility </label><br>
            <input type="text" name="facility" value="<?php  if(isset($_POST["facility"])){ echo $_POST["facility"];} ?>"><br>
            <input type="submit" name="submit" value="Find" class="btn brand z-depth-0">

        </form>
    </div>
    <br>
    <div class="container"> 
        <div class="row">
            <?php 
            if(isset($_POST["facility"])){

                foreach($rooms as $i => $room){ ?>
                    
                    <div class="col s6 md3">
                        <div class="card z-depth-0">
                            <div style="padding: 6px;">
                                <form action="menu.php<?php echo "?id=" . $room[0]. "&facility=". $room[1];?>" method="post">
                                    <input type="submit" name="Delete" value="Delete" class="btn brand z-depth-0"/>
                                </form>
                            </div>
                            <div class="card-content center">
                                <h5><?php echo htmlspecialchars($room[2]);?></h5>
                                <div>
                                    <table>
                                        <tr>
                                            <th>Height</th>
                                            <th><?php echo $room[3]; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Width</th>
                                            <th><?php echo $room[4]; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Length</th>
                                            <th><?php echo $room[5]; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Desks</th>
                                            <th><?php echo $room[6]; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <th><?php echo $room[7]; ?></th>
                                        </tr>
                                        
                                    </table>
                                </div>
                            </div>
                            <div class="card-action right-align">
                                <a class="" href="room.php?id=<?php echo $room[0] ?>">Veiw</a>
                            </div>
                        </div>
                        
                    </div>

                <?php }
                ?>
                <div class="col s6 md3">
                    <div class="card z-depth-0">
                        <div class="card-content center">
                            <div class="center">
                                <a class="" href="room.php">Add</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include("Templates/footer.php"); ?>
</html>


