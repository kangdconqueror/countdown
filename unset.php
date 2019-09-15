<?php
session_start();
 
// Removing session data
if(isset($_SESSION["mulai"])){
    unset($_SESSION["mulai"]);
}
?>