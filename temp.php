<?php
    // include('api_conn_class.php');
    require_once 'api_conn_class.php';

    $connection = new MySqlConnection();

    $connection->testConnection();
    // $connection->testConnection();

?>