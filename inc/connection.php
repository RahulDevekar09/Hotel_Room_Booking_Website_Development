
<?php

    $con = mysqli_connect("localhost","root","","hotel minerva paradise");

    if(mysqli_connect_errno()){
        echo "<script>alert('cannot connect to the database');</script>";
        exit();
    }

?>