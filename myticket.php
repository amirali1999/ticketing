<?php
session_start();
include 'catchError.php';

try {
    $userID = $_SESSION['UserID'];
    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
    $query = "SELECT Title,Receiver,DateOfTicket,Status,TicketID FROM ticket WHERE UserID = ?";
    $stmt = $connection->prepare($query);
    $stmt->execute(array($userID));
    $count = $stmt->rowCount();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $numRow=1;
    $turn=0;
    set_error_handler('catcherror');
    catcherror($_SESSION["UserID"]);
    ?>

        <table border="3">
            <tr><td>row</td><td>title</td><td>Date Of Ticket</td><td>Receiver</td><td>link</td><td>Active/Deactive</td></tr>
            <?php
            while($turn<$count){
                    /*Do some PHP calculation or something*/?>
                    <tr>
                        <td>
                            <?php
                                echo $numRow;
                                $numRow++;
                            /*Do some PHP calculation or something*/?>
                        </td>

                        <td>
                            <?php
                                echo $row[$turn]["Title"];
                            /*Do some PHP calculation or something*/?>
                        </td>

                        <td>

                            <?php
                                echo $row[$turn]["DateOfTicket"];
                            /*Do some PHP calculation or something*/?>
                        </td>

                        <td>

                            <?php
                                if($row[$turn]["Receiver"] == 1) echo "manager";
                                elseif ($row[$turn]["Receiver"] == 2) echo "supporter";
                                elseif ($row[$turn]["Receiver"] == 3) echo "public relation";

                            /*Do some PHP calculation or something*/?>
                        </td>

                        <td>
                            <?php
                                $ticketID = $row[$turn]["TicketID"];
                                echo "<a href='showingticket.php?ticketid=$ticketID&from=myticket'>link</a>"
        //                        echo $row[$turn]["Receiver"];
                            ?>
                        </td>

                        <td>
                            <?php
                                if($row[$turn]["Status"]==0){
                                    echo "1";
                                    $changeTicketID=$row[$turn]["TicketID"];
                                    echo "<a href='changeactive.php?ticketid=$changeTicketID&changing=1&from=myticket'>change</a>";
                                }
                                else{
                                    echo "0";
                                    $changeTicketID=$row[$turn]["TicketID"];
                                    echo "<a href='changeactive.php?ticketid=$changeTicketID&changing=0&&from=myticket'>change</a>";
                                }
                            ?>
                        </td>

                    </tr>
                    <?php
                $turn++;
            }
            ?>
        </table><br>
        <?php
        echo "<a href='desktop.php'>back</a><br>";
        echo "<a href='logout.php'>logout</a>";
    }
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}
