<?php
session_start();
include 'catchError.php';

try {
    set_error_handler('catcherror');
    catcherror($_SESSION["UserID"]);
    if($_SESSION["UserPosition"] == 0){
        /*Do some PHP calculation or something*/?>
    <?php
        echo "<a href='sendticket.php'>sendticket</a><br>";
        echo "<a href='myticket.php'>myticket</a><br>";
        echo "<a href='logout.php'>logout</a><br>";
    }
    elseif($_SESSION["UserPosition"] == 1 || $_SESSION["UserPosition"] == 2 || $_SESSION["UserPosition"] == 3){

        echo "<a href='sendticket.php'>sendticket</a><br>";
        echo "<a href='myticket.php'>myticket</a><br>";
        echo "<a href='receivedticket.php'>receivedticket</a><br>";
        echo "<a href='logout.php'>logout</a><br>";
    }
}
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}

