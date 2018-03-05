<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    //include database and object files
    include_once './database.php';
    include_once './product.php';
    
    //instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    
    //initialize object
    $product = new Product($db);
    //query products
    $stmt = $product->readAll();
    $num = $stmt->rowCount();    
?>