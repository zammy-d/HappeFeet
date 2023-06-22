<?php
// Se realiza la conexion a la base de datos
// Make the connection to the database
$db_name = 'mysql:host=localhost;dbname=shop_db';
$user_name = 'root';
$user_password = '';

$conn = new PDO($db_name, $user_name, $user_password);

?>