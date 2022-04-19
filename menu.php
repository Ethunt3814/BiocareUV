<!DOCTYPE html>
<html>
    <?php include("Templates/header.php"); ?>

    <h4 class="center grey-text">Rooms</h4>

    <div class="container"> 
        <div class="row">
            <?php foreach($_SESSION["rooms"] as $room)?>
        </div>
    </div>

    <?php include("Templates/footer.php"); ?>
</html>


