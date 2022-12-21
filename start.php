<?php
    if(isset($_POST['signup'])){
        header("Location: ../mysql/signup.php");
    }

    if(isset($_POST['login'])){
        header("Location: ../mysql/login.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charser="UTF-8">
    <title>TickThing</title>
</head>
<body>
<h1>TickThing</h1>
<form action="start.php" method="post">
    <input type="submit" value="signup" name="signup"><br>
    <input type="submit" value="login" name="login"><br>
</form>
</body>
</html>
