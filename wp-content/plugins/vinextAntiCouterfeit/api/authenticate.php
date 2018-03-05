<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include database and object files
include_once './database.php';
include_once './product.php';

$tag = intval($_GET['tag']);
$tag .= 'modified';
//decode tag
header("Location: result.php?productId=" . $tag);
die();
?>