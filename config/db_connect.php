<?php

    $conn = mysqli_connect("localhost", "Admin", "Password123","biocare");

    if(!$conn){
        echo "connection error" . mysqli_connect_error();
    }

?>