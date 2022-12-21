
<?php
    include 'testInput.php';
    $connection = new PDO("mysql:host=localhost;dbname=ticketing",'root','');
    try{
        if(isset($_POST['submit'])){
            $nationalCode = test_input($_POST['nationalCode']);
            $username = test_input($_POST['username']);
            $email = test_input($_POST['email']);
            $password = test_input($_POST['password']);
            $repeatpassword = test_input($_POST['repeatpassword']);
            if($password == $repeatpassword && !empty($nationalCode) && !empty($username) && !empty($email) && !empty($password) && !empty($repeatpassword)){
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users(email,nationalCode,password,username)";
                $query .= "VALUES ('$email','$nationalCode','$hashed_password','$username')";
                $connection->exec($query);
                header("Location: ../mysql/login.php");
            }
            elseif($password != $repeatpassword) {
                echo "password and repeat password are not equal";
            }
            elseif(empty($nationalCode) || empty($username) || empty($email) || empty($password) || empty($repeatpassword)){
                echo "some of the fields are empty!";
            }
            else{
                echo "wrong command!!!";
            }

        }
    }
    catch (PDOException){
        echo "One of the fields are ex in the database!please enter your specs again";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charser="UTF-8">
        <title>TickThing</title>
    </head>
    <body>
        <form action="signup.php" method="post">
            <input type="number" placeholder="nationalCode" name="nationalCode"><br>
            <input type="text" placeholder="Username" name="username"><br>
            <input type="email" placeholder="Email" name="email"><br>
            <input type="password" placeholder="Password" name="password"><br>
            <input type="password" placeholder="RepeatPassword" name="repeatpassword"><br>
            <input type="submit" name="submit">
        </form>
    </body>
</html>