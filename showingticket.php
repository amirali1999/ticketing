<form method="post">
<?php
session_start();
include 'catchError.php';
function Answer($row){
    $ticketID = intval($_GET['ticketid']);
    $userID = $_SESSION['UserID'];
    $position = $_SESSION['UserPosition'];
    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
    ?>
    <label for="newTicket">send new ticket:</label><br>
    <textarea id="newTicket" name="newticket" rows="4" cols="50" ></textarea><br>
    <input type="submit" name="submit">
    <?php
    $dateOfTicket = date("j-m-Y H:i:s");
    $receiver = 0;
    if($position == 0){
        $receiver = $row['Receiver'];
    }
    if(isset($_POST['submit'])){
        $text = $_POST['newticket'];
        $sendNewTicketQuery = "INSERT INTO ticketitem(TicketID,SenderID,Text,DateOfTicket) VALUES ('$ticketID','$userID','$text','$dateOfTicket')";
        $connection->exec($sendNewTicketQuery);
        if($position !=0){
            $queryChangeActive = "UPDATE ticket SET Status = ? WHERE TicketID = ?";
            $stmt = $connection->prepare($queryChangeActive);
            $stmt->execute(array(1,$ticketID));
        }
        else{
            $queryChangeActive = "UPDATE ticket SET Status = ? WHERE TicketID = ?";
            $stmt = $connection->prepare($queryChangeActive);
            $stmt->execute(array(0,$ticketID));
        }
        header("Refresh:0");
    }
}
try {
    set_error_handler('catcherror');
    catcherror($_SESSION["UserID"]);
    $from = $_GET['from'];
    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
    $ticketID = intval($_GET['ticketid']);
    $userID = $_SESSION['UserID'];

    $position = $_SESSION['UserPosition'];
    $haveAccess = 0;
    if($position == 1 || $position == 2 || $position == 3){
        $haveAccess = 1;
    }

    $queryGetIdOfUserTicket = "SELECT TicketID FROM ticket WHERE UserID = ?";
    $stmtGetIdOfUserTicket = $connection->prepare($queryGetIdOfUserTicket);
    $stmtGetIdOfUserTicket->execute(array($userID));
    $countGetIdOfUserTicket = $stmtGetIdOfUserTicket->rowCount();
    $rowGetIdOfUserTicket = $stmtGetIdOfUserTicket->fetchAll(PDO::FETCH_ASSOC);


    $query = "SELECT UserID,Receiver,Status FROM ticket WHERE TicketID = ?";
    $stmt = $connection->prepare($query);
    $stmt->execute(array($ticketID));
    $count = $stmt->rowCount();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);



    $userTicket = 0;
    for ($x=0;$x<$countGetIdOfUserTicket;$x++){
        if ($rowGetIdOfUserTicket[$x]['TicketID'] == $ticketID){

            $userTicket = 1;
            break;
        }
    }

    if($userTicket == 1  || $haveAccess == 1) {

        $anotherticketsquery ="SELECT Text,SenderID FROM ticketitem WHERE TicketID = ?";
        $anotherticketsstmt = $connection->prepare($anotherticketsquery);
        $anotherticketsstmt->execute(array($ticketID));
        $anotherticketscount = $anotherticketsstmt->rowCount();
        $anotherticketsrow = $anotherticketsstmt->fetchAll(PDO::FETCH_ASSOC);


        $turn=0;
        while($turn<$anotherticketscount){
            $Username2query ="SELECT Username FROM users WHERE ID = ?";
            $username2stmt = $connection->prepare($Username2query);
            $username2stmt->execute(array($anotherticketsrow[$turn]['SenderID']));
            $username2count = $username2stmt->rowCount();
            $username2row = $username2stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <label for="anotherticket"><?=$username2row['Username']?></label><br>
            <textarea id="anotherticket" name="tickettext" rows="4" cols="50" disabled><?=$anotherticketsrow[$turn]['Text'] ?></textarea><br><br>
            <?php
            $turn++;
        }
        //Answer Text Box
        if($row['UserID']==$userID){
            Answer($row);
        }
        else if($position==3){
            if($row['Receiver'] == 2 && ($row['Status'] == 0 || $row['Status'] == 1)){
                Answer($row);
            }
            else if($row['Receiver'] == 2){
                Answer($row);
            }
        }
        else if($position==1 || $position==2){
            Answer($row);
        }

    }
    else{
        echo "you can't have access to this ticket";
    }

    if(isset($_POST['back'])){
        if($from=='receivedticket'){
            header("Location: ../mysql/receivedticket.php");
        }
        elseif($from='myticket'){
            header("Location: ../mysql/myticket.php");
        }
    }

?>
    <input type="submit" name="back" value="back">
</form>
<?php
    echo "<a href='logout.php'>logout</a><br>";
}
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}



