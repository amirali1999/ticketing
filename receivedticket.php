<?php
session_start();
function table($row,$count){
    $userposition = $_SESSION['UserPosition'];
    $turn = 0;
    $rownumber=0;
    ?>
    <table border="3">
        <tr>
            <td>Row</td><td>Title</td><td>Sender User</td><td>Date Of Ticket</td><td>link</td><td>Active/Deactive</td>
        </tr>
        <?php
        while ($turn<$count){
            $rownumber++;
            ?>
            <tr>
                <td>
                    <?php
                    echo $rownumber;
                    ?>
                </td>
                <td>
                    <?php
                    echo $row[$turn]["Title"];
                    ?>
                </td>
                <td>
                    <?php
                    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
                    $queryUsername = "SELECT Username FROM Users WHERE ID = ?";
                    $stmtUsername = $connection->prepare($queryUsername);
                    $stmtUsername->execute(array($row[$turn]["UserID"]));
                    $rowUsername = $stmtUsername->fetchAll(PDO::FETCH_ASSOC);
                    echo $rowUsername[0]["Username"];

                    ?>
                </td>
                <td>
                    <?php
                    echo $row[$turn]["DateOfTicket"];
                    ?>
                </td>
                <td>
                    <?php
                    $ticketID = $row[$turn]["TicketID"];
                    echo "<a href='showingticket.php?ticketid=$ticketID&from=receivedticket'>link</a>"
                    ?>
                </td>
                <td>
                    <?php
                    if($row[$turn]["Status"]==0){
                        echo "1";
                        if($row[$turn]["Receiver"] == $userposition || $userposition == 1) {
                            $changeTicketID = $row[$turn]["TicketID"];
                            echo "<a href='changeactive.php?ticketid=$changeTicketID&changing=2&from=receivedticket'>change</a>";
                        }
                    }
                    if($row[$turn]["Status"]==1){
                        echo "1";
                        if($row[$turn]["Receiver"] == $userposition || $userposition == 1) {
                            $changeTicketID = $row[$turn]["TicketID"];
                            echo "<a href='changeactive.php?ticketid=$changeTicketID&changing=2&from=receivedticket'>change</a>";
                        }
                    }
                    elseif ($row[$turn]["Status"]==2){
                        echo "0";
                        if($row[$turn]["Receiver"] == $userposition || $userposition == 1) {
                            $changeTicketID = $row[$turn]["TicketID"];
                            echo "<a href='changeactive.php?ticketid=$changeTicketID&changing=0&from=receivedticket'>change</a>";
                        }
                    }

                    ?>
                </td>
            </tr>
            <?php
            $turn++;
        }
        ?>
    </table>
    <?php
}
include 'testInput.php';
include 'catchError.php';
try {
    set_error_handler('catcherror');
    catcherror($_SESSION["UserID"]);
?>
<form method="POST">
    <select name="receivedTickets" id="receivedTickets">
        <option value="3" name="OpenMenu">Open Menu</option>
        <option value="0" name="NewTickets">New Tickets</option>
        <option value="1" name="OpenTickets">Open Tickets</option>
        <option value="2" name="CloseTickets">Close Ticket</option>
    </select>
    <input type="submit" name="submit"><br>
</form>
<?php

    if (isset($_POST['submit'])) {
        $choose = test_input($_POST['receivedTickets']);
        $connection = new PDO("mysql:host=localhost;dbname=ticketing", 'root', '');

        $query = "SELECT Title,Receiver,DateOfTicket,Status,TicketID,UserID FROM ticket WHERE Status = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute(array($choose));
        $count = $stmt->rowCount();
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($choose == 3) {
            echo "please choose another field";
        } elseif ($choose == 0) {
            table($row, $count);
        } elseif ($choose == 1) {
            table($row, $count);
        } elseif ($choose == 2) {
            table($row, $count);
        }
    }

    echo "<a href='desktop.php'>back</a><br>";
    echo "<a href='logout.php'>logout</a><br>";
}
    catch (Exception $e){

        echo $e->getMessage();
        restore_error_handler();
    }

