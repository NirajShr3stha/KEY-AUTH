<?php
include '../../includes/connection.php';
$payload = file_get_contents('php://input');

$secret = "NP3EsYPfndYMFtlq"; // replace with your webhook secret
$header_signature = $_SERVER["HTTP_X_SHOPPY_SIGNATURE"]; // get our signature header

$signature = hash_hmac('sha512', $payload, $secret);
if (hash_equals($signature, $header_signature)) {

$json = json_decode($payload);
// in terms of looking at shoppy API, $json = $payload


if ($json->event === 'order:paid') {
	$data = $json->data;
	$order = $data->order;
	$product = $order->product;
	
	if($product->title == "KeyAuth Developer Plan")
	{
	$custom = $order->custom_fields;			$result = mysqli_query($link, "SELECT `role` FROM `accounts` WHERE `username` = '".$custom[0]->value."'");    $row = mysqli_fetch_array($result);	$role = $row['role'];    if($role == "Reseller" || $role == "Manager")	{    die('account is manager or reseller');	}
	mysqli_query($link, "UPDATE `accounts` SET `role` = 'developer' WHERE `username` = '".$custom[0]->value."'");
	die("upgraded to developer plan");
	}
	else if($product->title == "KeyAuth Seller Plan")
	{
	$custom = $order->custom_fields;		$result = mysqli_query($link, "SELECT `role` FROM `accounts` WHERE `username` = '".$custom[0]->value."'");    $row = mysqli_fetch_array($result);	$role = $row['role'];    if($role == "Reseller" || $role == "Manager")	{    die('account is manager or reseller');	}
	mysqli_query($link, "UPDATE `accounts` SET `role` = 'seller' WHERE `username` = '".$custom[0]->value."'");
	die("upgraded to seller plan");
	}
	else
	{
	die("invalid product");	
	}
}
else
{
	die("didn't pay");
}

}

die("You shouldn't be here");

?>