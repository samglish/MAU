<?php
$host="localhost"; $user="root"; $pass=""; $db="mau_services";
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error){die("DB Error");}
session_start();
?>