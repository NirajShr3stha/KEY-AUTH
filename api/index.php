<?php
ob_start();
ini_set('display_errors', 'Off');
error_reporting(0);
if ($_SERVER["HTTP_USER_AGENT"] != 'KeyAuth') die('Missing required user-agent.');

include '../includes/connection.php';
include '../../includes/functions.php';
session_start();

// Encryption
function Encrypt($string, $enckey)
{
    return bin2hex(openssl_encrypt($string, "aes-256-cbc", substr(hash('sha256', $enckey) , 0, 32) , OPENSSL_RAW_DATA, substr(hash('sha256', $_POST['init_iv']) , 0, 16)));
}
function Decrypt($string, $enckey)
{
    return openssl_decrypt(hex2bin($string) , "aes-256-cbc", substr(hash('sha256', $enckey) , 0, 32) , OPENSSL_RAW_DATA, substr(hash('sha256', $_POST['init_iv']) , 0, 16));
}

$ownerid = hex2bin($_POST['ownerid']);
$ownerid = strip_tags(trim(mysqli_real_escape_string($link, $ownerid)));

$name = hex2bin($_POST['name']);
$name = strip_tags(trim(mysqli_real_escape_string($link, $name)));
$result = mysqli_query($link, "SELECT * FROM `apps` WHERE `ownerid` = '$ownerid' AND `name` = '$name'");
if (mysqli_num_rows($result) < 1)
{
    Die(Encrypt("KeyAuth_Invalid", $secret));
}

while ($row = mysqli_fetch_array($result))
{
    $secret = $row['secret'];
    $hwidenabled = $row['hwidcheck'];
    $hashenabled = $row['hashcheck'];
    $hash = $row['hash'];
    $status = $row['enabled'];
    $webhook = $row['webhook'];
    // $currentver = $row['ver'];
    
}

$type = hex2bin($_POST['type']);
if ($type == "init")
{
    if ($status == "0")
    {
        Die(Encrypt("KeyAuth_Disabled", $secret));
    }

    if ($hashenabled == "1")
    {
        $programhash = $_POST['hash'];
        $programhash = strip_tags(trim(mysqli_real_escape_string($link, $programhash)));
        if ($hash == "")
        {
            mysqli_query($link, "UPDATE `apps` SET `hash` = '$programhash' WHERE `secret` = '$secret'");
        }
        else if ($programhash != $hash)
        {
            Die(Encrypt("KeyAuth_WrongHash", $secret)); // need to add $secret in func
            
        }
    }
    Die(Encrypt("KeyAuth_Initialized", $secret));

}
else if ($type == "login")
{

    $key = $_POST['key'];
    $key = strip_tags(trim(mysqli_real_escape_string($link, $key)));
    $checkkey = Decrypt($key, $secret);

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $checkkey)));

    $hwid = Decrypt($_POST['hwid'], $secret);
    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $hwid)));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        Die(Encrypt("KeyAuth_Invalid", $secret));
    }
    elseif (mysqli_num_rows($result) > 0)
    {
        $currtime = time();
        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime' WHERE `key` = '$checkkey'");

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $hwidd = $row['hwid'];
            $status = $row['status'];
        }

        if ($status == "Not Used")
        {

            mysqli_query($link, "UPDATE `keys` SET `status` = 'Used' WHERE `key` = '$checkkey'");

            $expiry = time() + $expires;

            mysqli_query($link, "UPDATE `keys` SET `expires` = '$expiry' WHERE `key` = '$checkkey'");

            if ($hwidenabled == "1")
            {
                mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
            }

            $url = $webhook;
            $key = $checkkey .= " activated";
            $headers = ['Content-Type: application/json; charset=utf-8'];
            $POST = ['username' => 'KeyAuth', 'avatar_url' => 'https://keyauth.com/assets/img/favicon.png', 'content' => $key];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
            curl_exec($ch);
            Die(Encrypt("KeyAuth_Valid", $secret));
        }
        else
        {
            if ($hwidenabled == "1")
            {
                if ($hwidd == "")
                {
                    mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
                }
                else if ($hwid != $hwidd)
                {
                    Die(Encrypt("KeyAuth_InvalidHWID", $secret));
                }
            }

            $today = time();
            if ($expires < $today)
            {
                Die(Encrypt("KeyAuth_Expired", $secret));
            }

            Die(Encrypt("KeyAuth_Valid", $secret));
        }
        // check if hwid enabled for application
        // check if $hwid match $keyhwid <-- obtained from SQL query above.
        // if all good, return success
        
    }
}
else if ($type == "log")
{

    $currtime = time();

    $message = $_POST['message'];
    $msg = Decrypt($message, $secret);
    $msg = strip_tags(trim(mysqli_real_escape_string($link, $msg)));

    $keyy = $_POST['key'];
    $logkey = Decrypt($keyy, $secret);
    $logkey = strip_tags(trim(mysqli_real_escape_string($link, $logkey)));

    $url = $webhook;
    $key = $logkey .= $msg;
    $headers = ['Content-Type: application/json; charset=utf-8'];
    $POST = ['username' => 'KeyAuth', 'avatar_url' => 'https://keyauth.com/assets/img/favicon.png', 'content' => $key];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    curl_exec($ch);

    $result = mysqli_query($link, "SELECT * FROM `accounts` WHERE `ownerid` = '$ownerid'");

    while ($row = mysqli_fetch_array($result))
    {
        $usorname = $row['username'];
    }

    mysqli_query($link, "INSERT INTO `logs` (`logdate`, `logdata`, `logkey`, `logowner`, `logapp`) VALUES ('$currtime','$msg','$logkey','$usorname','$secret')");
}
else if ($type == "file")
{
    $fileid = $_POST['fileid'];
    $fileid = Decrypt($fileid, $secret);
    $fileid = strip_tags(trim(mysqli_real_escape_string($link, $fileid)));

    $result = mysqli_query($link, "SELECT * FROM `accounts` WHERE `ownerid` = '$ownerid'");

    while ($row = mysqli_fetch_array($result))
    {
        $usorname = $row['username'];
    }

    $result = mysqli_query($link, "SELECT * FROM `accounts` WHERE `ownerid` = '$ownerid'");

    while ($row = mysqli_fetch_array($result))
    {
        $usorname = $row['username'];
    }

    $result = mysqli_query($link, "SELECT * FROM `files` WHERE `uploader` = '$usorname' AND `app` = '$secret' AND `id` = '$fileid'");

    while ($row = mysqli_fetch_array($result))
    {
        $filename = $row['name'];
    }

    $file_destination = '../../api/libs/' . $fileid . '/' . $filename;

    $myFile = $file_destination;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($myFile) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($myFile));
    ob_end_flush();
    die(file_decrypt(file_get_contents($file_destination) , "salksalasklsakslakaslkasl"));
}
else if ($type == "level")
{
    $key = $_POST['key'];
    $checkkey = Decrypt($key, $secret);

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $checkkey)));
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        Die(Encrypt("KeyAuth_Invalid", $secret));
    }
    elseif (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $level = $row['level'];
        }
        Die(Encrypt($level, $secret));
    }
}
else
{
    die("Invalid Type.");
}
?>