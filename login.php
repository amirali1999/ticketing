
<?php
    session_start();
    include 'testInput.php';

    if(isset($_POST['submit'])){
        $username = test_input($_POST['username']);
        $password = test_input($_POST['password']);

        $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute(array($username));
    //    print_r($stmt);
        $count = $stmt->rowCount();
        $row   = $stmt->fetch(PDO::FETCH_ASSOC);
        if($count == 1 && !empty($row) && password_verify($password, $row["Password"]) ) {
            $_SESSION['UserUsername'] = $row["Username"];
            $_SESSION['UserID'] = $row["ID"];
            $_SESSION['UserPosition'] = $row["Position"];
            header("Location: ../mysql/desktop.php");
        } else {
            echo "Invalid username and password!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charser="UTF-8">
    <title>TickThing</title>
</head>
<body>
<form action="login.php" method="post">
    <input type="text" placeholder="Username" name="username"><br>
    <input type="password" placeholder="Password" name="password"><br>
    <input type="submit" name="submit">
</form>
</body>
</html>