<?php
session_start();
$connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
include 'catchError.php';

include 'testInput.php';
try {
    set_error_handler('catcherror');
    catcherror($_SESSION["UserID"]);
    if (isset($_POST['submit'])) {
        $title = test_input($_POST['title']);
        $text = test_input($_POST['text']);
        $userID = $_SESSION["UserID"];
        $receiver = test_input($_POST['receiver']);

        if (!empty($text) && !empty($title) && $receiver != 0) {
            if ($receiver == "manager") $receiver = 1;
            elseif ($receiver == "supporter") $receiver = 2;
            elseif ($receiver == "publicrelation") $receiver = 3;
            else {
                echo "not valid!!!!!";
            }
            $dateOfTicket = date("j-m-Y H:i:s");
            $queryOfTicket = "INSERT INTO ticket(UserID,Title,Receiver,DateOfTicket) VALUES ('$userID','$title','$receiver','$dateOfTicket')";
            $connection->exec($queryOfTicket);

            $queryGetTicketID = "SELECT TicketID FROM ticket WHERE Title = ?";
            $stmt = $connection->prepare($queryGetTicketID);
            $stmt->execute(array($title));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $ticketID = $row['TicketID'];

            $queryOfTicketItem = "INSERT INTO ticketitem(TicketID,SenderID,Text,DateOfTicket) VALUES ('$ticketID','$userID','$text','$dateOfTicket')";
            $connection->exec($queryOfTicketItem);
        } elseif (empty($title)) {
            echo "tickets title is empty !";
        } elseif (empty($text)) {
            echo "ticket text is empty! ";
        } elseif ($receiver == 0) {
            echo "please choose a reciever!";
        } else {
            echo "not valid!!!!";
        }
    }


?>
<form action="sendticket.php" method="post">
    <label for="title">ticket's title:</label><br>
    <input type="text" name="title" size="108"><br>
    <label for="text">ticket's text:</label><br>
    <textarea name="text" rows="16" cols="100"></textarea><br>

    <input type="radio" id="manager" name="receiver" value="manager">
    <label for="manager">Manager</label><br>

    <input type="radio" id="publicrelation" name="receiver" value="publicrelation">
    <label for="publicrelation">public relation</label><br>

    <input type="radio" id="supporter" name="receiver" value="supporter">
    <label for="supporter">supporter</label><br><br>

    <input type="submit" name="submit"><br>
</form>
<?php
    echo "<a href='desktop.php'>back</a><br>";
    echo "<a href='logout.php'>logout</a><br>";
}
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}
