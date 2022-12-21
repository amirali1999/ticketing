<?php
session_start();
include 'catchError.php';
try {
    $ticketid = $_GET['ticketid'];
    $changing = $_GET['changing'];
    $from = $_GET['from'];

    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
    $queryChangeActive = "UPDATE ticket SET Status = ? WHERE TicketID = ?";
    $stmt = $connection->prepare($queryChangeActive);
    $stmt->execute(array($changing,$ticketid));
//    $connection->exec($stmt);
    if($from=='receivedticket'){
        header("Location: ../mysql/receivedticket.php");
    }
    elseif($from='myticket'){
        header("Location: ../mysql/myticket.php");
    }
}
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}
