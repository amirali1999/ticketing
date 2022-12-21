<?php
session_start();
include 'catchError.php';
try {
    session_destroy();
    header("Location: ../mysql/login.php");
}
catch (Exception $e){

    echo $e->getMessage();
    restore_error_handler();
}