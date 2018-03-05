<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include database and object files
include_once './database.php';
include_once './product.php';
include_once './securityKey.php';
include_once './kreyviumCipher.php';

//include_once './wc_product.php';

// test encrypted productID = 0a00000948a4b819b538bd

$encryptedRQ = $_GET['microTagID'];
//decode product id
echo "Request = $encryptedRQ \n";
echo "\n";
$monthInHex = substr($encryptedRQ, 0, 2);
$month = hexdec($monthInHex);
echo "Month = $month \n";
echo "\n";

$lastIndexOffsetInHex = substr($encryptedRQ, 2, 6);

$encryptedUID = substr($encryptedRQ, 8);
echo "Encrypted UID = $encryptedUID \n";
echo "\n";

$lastIndexOffset = hexdec($lastIndexOffsetInHex);
echo "Last index offset = $lastIndexOffset \n";
echo "\n";

$sk = new SecurityKey($month); 
$cipher = new KreyviumCipher(
									$sk->getKREYVIUM_SECRET_KEY(), 
									$lastIndexOffset);

$msg="4d5e1011134f2c";
$newEnc = $cipher->encryptMsgInString($msg);
echo $newEnc;
echo "\n";
									
$product_uid = $cipher->decryptMsgInString($encryptedUID);
echo "\n";

echo "Product ID = $product_uid";
// Get product description and product name from id
//instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// Get productid and redirect to the product description page

//$wcProd = new WC_product($db);
//echo $prodictId;
//$prods = $wcProd->getProductionDescriptionFromId($productId);

//echo $productId;

//header("Location: https://localhost/wordpress/?product=" . $productId);

die();
?>