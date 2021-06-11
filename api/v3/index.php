<?php
ob_start();
ini_set('display_errors', 'Off');
error_reporting(0);

include '../../includes/connection.php';
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
    Die("KeyAuth_Invalid");
}

while ($row = mysqli_fetch_array($result))
{
    $secret = $row['secret'];
    $hwidenabled = $row['hwidcheck'];
    $status = $row['enabled'];
    $currentver = $row['ver'];
    $download = $row['download'];
    $webhook = $row['webhook'];

    $appdisabled = $row['appdisabled'];
    $usernametaken = $row['usernametaken'];
    $keynotfound = $row['keynotfound'];
    $keyused = $row['keyused'];
    $nosublevel = $row['nosublevel'];
    $usernamenotfound = $row['usernamenotfound'];
    $passmismatch = $row['passmismatch'];
    $hwidmismatch = $row['hwidmismatch'];
    $noactivesubs = $row['noactivesubs'];
    $hwidblacked = $row['hwidblacked'];
    $keypaused = $row['keypaused'];
    $keyexpired = $row['keyexpired'];
}

$type = hex2bin($_POST['type']);
if ($type == "init")
{
    if ($status == "0")
    {
        die(Encrypt(json_encode(array(
            "success" => false,
            "message" => "$appdisabled"
        )) , $secret));
    }

    $ver = Decrypt($_POST['ver'], $secret);
    $ver = strip_tags(trim(mysqli_real_escape_string($link, $ver)));
    if ($ver != $currentver)
    {
        die(Encrypt(json_encode(array(
            "success" => false,
            "message" => "invalidver",
            "download" => "$download"
        ) , JSON_UNESCAPED_SLASHES) , $secret));
    }

    die(Encrypt(json_encode(array(
        "success" => true,
        "message" => "Initialized"
    )) , $secret));

}
else if ($type == "login")
{

    $key = $_POST['key'];
    $checkkey = Decrypt($key, $secret);

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $checkkey)));

    $hwid = Decrypt($_POST['hwid'], $secret);
    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $hwid)));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        die(Encrypt(json_encode(array(
            "success" => false,
            "state" => "no_exist",
            "message" => "$keynotfound"
        )) , $secret));
    }
    elseif (mysqli_num_rows($result) > 0)
    {

        $hwidcheck = mysqli_query($link, "SELECT * FROM `bans` WHERE (`hwid` = '$hwid' OR `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "') AND `app` = '$secret'");
        if (mysqli_num_rows($hwidcheck) > 0)

        {

            if ($status == "Not Used")
            {
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Banned',`banned` = 'This key has been banned as the client was blacklisted.' WHERE `key` = '" . $checkkey . "' AND `app` = '" . $secret . "'");
                if ($banned == NULL)
                {
                    $banned = "This key has been banned as the client was blacklisted.";
                }

            }

            $hwidblacked = str_replace("{reason}", $banned, $hwidblacked);
            die(Encrypt(json_encode(array(
                "success" => false,
                "message" => "$hwidblacked"
            )) , $secret));

        }

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $gendate = $row['gendate'];
            $hwidd = $row['hwid'];
            $status = $row['status'];
            $level = $row['level'];
            $note = $row['note'];
            $banned = $row['banned'];
            $ip = $row['ip'];
        }

        if ($banned != NULL)
        {
            die(Encrypt(json_encode(array(
                "success" => false,
                "state" => "banned",
                "message" => $banned
            )) , $secret));
        }

        if ($hwidd == NULL)
        {
            if ($anti == "1")
            {
                if ($ip != '')
                {
                    if ($ip != $_SERVER["HTTP_CF_CONNECTING_IP"])
                    {

                        mysqli_query($link, "UPDATE `keys` SET `status` = 'Banned', `banned` = 'Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake' WHERE `key` = '$checkkey'");
                        die(Encrypt(json_encode(array(
                            "success" => false,
                            "state" => "keyshare_trigger",
                            "message" => "Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake"
                        )) , $secret));
                    }
                }
            }
        }

        $currtime = time();
        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime', `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "' WHERE `key` = '$checkkey'");

        if ($status == "Paused")
        {
            die(Encrypt(json_encode(array(
                "success" => false,
                "state" => "paused",
                "message" => "$keypaused"
            )) , $secret));
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

            $timestamp = date("c", strtotime("now"));

            $json_data = json_encode([
            // Message
            //"content" => "Hello World! This is message line ;) And here is the mention, use userID <@12341234123412341>",
            // Username
            "username" => "KeyAuth",

            // Avatar URL.
            // Uncoment to replace image set in webhook
            "avatar_url" => "https://keyauth.com/assets/img/favicon.png",

            // Text-to-speech
            "tts" => false,

            // File upload
            // "file" => "",
            // Embeds Array
            "embeds" => [[
            // Embed Title
            "title" => "Key Activated",

            // Embed Type
            "type" => "rich",

            // Embed Description
            //"description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",
            // URL of title link
            // "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",
            // Timestamp of embed must be formatted as ISO8601
            "timestamp" => $timestamp,

            // Embed left border color in HEX
            "color" => hexdec("00ffe1") ,

            // Footer
            "footer" => ["text" => $name],

            // Additional Fields array
            "fields" => [
            // Field 1
            ["name" => "Key", "value" => $checkkey, "inline" => true],
            // Field 2
            ["name" => "Level", "value" => $level, "inline" => true]
            // Etc..
            ]]]

            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json'
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
            // echo $response;
            curl_close($ch);

            $intlevel = (int)$level;
            die(Encrypt(json_encode(array(
                "success" => true,
                "message" => "Logged in!",
                "info" => array(
                    "key" => "$checkkey",
                    "expiry" => "$expiry",
                    "hwid" => "$hwid",
                    "ip" => $_SERVER["HTTP_CF_CONNECTING_IP"],
                    "gendate" => "$gendate",
                    "level" => $intlevel
                )
            )) , $secret));
        }
        else
        {
            if ($hwidenabled == "1")
            {
                if ($hwidd == "")
                {
                    mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
                }
                else if (strpos($hwidd, $hwid) === false)
                {
                    die(Encrypt(json_encode(array(
                        "success" => false,
                        "state" => "hwid_mismatch",
                        "message" => "$hwidmismatch"
                    )) , $secret));
                }
            }

            $today = time();
            if ($expires < $today)
            {
                die(Encrypt(json_encode(array(
                    "success" => false,
                    "state" => "expired",
                    "message" => "$keyexpired"
                )) , $secret));
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");
            }

            $intlevel = (int)$level;
            die(Encrypt(json_encode(array(
                "success" => true,
                "message" => "Logged in!",
                "info" => array(
                    "key" => "$checkkey",
                    "expiry" => "$expires",
                    "hwid" => "$hwid",
                    "ip" => $_SERVER["HTTP_CF_CONNECTING_IP"],
                    "gendate" => "$gendate",
                    "level" => $intlevel
                )
            )) , $secret));
        }
        // check if hwid enabled for application
        // check if $hwid match $keyhwid <-- obtained from SQL query above.
        die(Encypt(json_encode(array(
            "success" => true,
            "state" => "logged_in",
            "message" => "worked",
            "expiry" => "$expiry"
        )) , $secret));
        // if all good, return success
        
    }
}
else if ($type == "var")
{
    $var = Decrypt($_POST['varid'], $secret);
    $varid = strip_tags(trim(mysqli_real_escape_string($link, $var)));

    $key = $_POST['key'];
    $checkkey = Decrypt($key, $secret);

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $checkkey)));

    $hwid = Decrypt($_POST['hwid'], $secret);
    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $hwid)));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        die(Encrypt(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
        )) , $secret));
    }
    elseif (mysqli_num_rows($result) > 0)
    {

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $hwidd = $row['hwid'];
            $status = $row['status'];
            $level = $row['level'];
            $banned = $row['banned'];
            $ip = $row['ip'];
        }

        if ($banned != NULL)
        {
            die(Encrypt(json_encode(array(
                "success" => false,
                "message" => $banned
            )) , $secret));
        }

        if ($hwidd == NULL)
        {
            if ($anti == "1")
            {
                if ($ip != '')
                {
                    if ($ip != $_SERVER["HTTP_CF_CONNECTING_IP"])
                    {

                        mysqli_query($link, "UPDATE `keys` SET `status` = 'Banned', `banned` = 'Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake' WHERE `key` = '$checkkey'");
                        die(Encrypt(json_encode(array(
                            "success" => false,
                            "message" => "Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake"
                        )) , $secret));
                    }
                }
            }
        }

        $currtime = time();
        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime', `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "' WHERE `key` = '$checkkey'");

        if ($status == "Not Used")
        {

            mysqli_query($link, "UPDATE `keys` SET `status` = 'Used' WHERE `key` = '$checkkey'");

            $expiry = time() + $expires;

            mysqli_query($link, "UPDATE `keys` SET `expires` = '$expiry' WHERE `key` = '$checkkey'");

            if ($hwidenabled == "1")
            {
                mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
            }

            $varquery = mysqli_query($link, "SELECT * FROM `vars` WHERE `varid` = '$varid' AND `app` = '$secret'");
            while ($rowww = mysqli_fetch_array($varquery))
            {
                $msg = $rowww['msg'];
            }

            die(Encrypt(json_encode(array(
                "success" => true,
                "message" => "$msg"
            )) , $secret));
        }
        else
        {
            if ($hwidenabled == "1")
            {
                if ($hwidd == "")
                {
                    mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
                }
                else if (strpos($hwidd, $hwid) === false)
                {
                    die(Encrypt(json_encode(array(
                        "success" => false,
                        "message" => "$hwidmismatch"
                    )) , $secret));
                }
            }

            $today = time();
            if ($expires < $today)
            {
                die(Encrypt(json_encode(array(
                    "success" => false,
                    "message" => "$keyexpired"
                )) , $secret));
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");
            }

            $varquery = mysqli_query($link, "SELECT * FROM `vars` WHERE `varid` = '$varid' AND `app` = '$secret'");
            while ($rowww = mysqli_fetch_array($varquery))
            {
                $msg = $rowww['msg'];
            }

            die(Encrypt(json_encode(array(
                "success" => true,
                "message" => "$msg"
            )) , $secret));

        }

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

    $timestamp = date("c", strtotime("now"));

    $json_data = json_encode([
    // Message
    //"content" => "Hello World! This is message line ;) And here is the mention, use userID <@12341234123412341>",
    // Username
    "username" => "KeyAuth",

    // Avatar URL.
    // Uncoment to replace image set in webhook
    "avatar_url" => "https://keyauth.com/assets/img/favicon.png",

    // Text-to-speech
    "tts" => false,

    // File upload
    // "file" => "",
    // Embeds Array
    "embeds" => [[
    // Embed Title
    "title" => $msg,

    // Embed Type
    "type" => "rich",

    // Embed Description
    //"description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",
    // URL of title link
    // "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",
    // Timestamp of embed must be formatted as ISO8601
    "timestamp" => $timestamp,

    // Embed left border color in HEX
    "color" => hexdec("00ffe1") ,

    // Footer
    "footer" => ["text" => $name],

    // Additional Fields array
    "fields" => [
    // Field 1
    ["name" => "Key", "value" => $logkey, "inline" => true]
    // Etc..
    ]]]

    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
    // echo $response;
    curl_close($ch);

    $result = mysqli_query($link, "SELECT * FROM `accounts` WHERE `ownerid` = '$ownerid'");

    while ($row = mysqli_fetch_array($result))
    {
        $usorname = $row['username'];
    }

    mysqli_query($link, "INSERT INTO `logs` (`logdate`, `logdata`, `logkey`, `logowner`, `logapp`) VALUES ('$currtime','$msg','$logkey','$usorname','$secret')");
}
else if ($type == "webhook")
{

    $key = $_POST['key'];
    $checkkey = Decrypt($key, $secret);

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $checkkey)));

    $hwid = Decrypt($_POST['hwid'], $secret);
    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $hwid)));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        die(Encrypt(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
        )) , $secret));
    }
    elseif (mysqli_num_rows($result) > 0)
    {

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $hwidd = $row['hwid'];
            $status = $row['status'];
            $level = $row['level'];
            $banned = $row['banned'];
            $ip = $row['ip'];
        }

        if ($banned != NULL)
        {
            die(Encrypt(json_encode(array(
                "success" => false,
                "message" => $banned
            )) , $secret));
        }

        if ($hwidd == NULL)
        {
            if ($anti == "1")
            {
                if ($ip != '')
                {
                    if ($ip != $_SERVER["HTTP_CF_CONNECTING_IP"])
                    {

                        mysqli_query($link, "UPDATE `keys` SET `status` = 'Banned', `banned` = 'Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake' WHERE `key` = '$checkkey'");
                        die(Encrypt(json_encode(array(
                            "success" => false,
                            "message" => "Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake"
                        )) , $secret));
                    }
                }
            }
        }

        $currtime = time();
        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime', `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "' WHERE `key` = '$checkkey'");

        if ($status == "Paused")
        {
            die(Encrypt(json_encode(array(
                "success" => false,
                "message" => "$keypaused"
            )) , $secret));
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

            $timestamp = date("c", strtotime("now"));

            $json_data = json_encode([
            // Message
            //"content" => "Hello World! This is message line ;) And here is the mention, use userID <@12341234123412341>",
            // Username
            "username" => "KeyAuth",

            // Avatar URL.
            // Uncoment to replace image set in webhook
            "avatar_url" => "https://keyauth.com/assets/img/favicon.png",

            // Text-to-speech
            "tts" => false,

            // File upload
            // "file" => "",
            // Embeds Array
            "embeds" => [[
            // Embed Title
            "title" => "Key Activated",

            // Embed Type
            "type" => "rich",

            // Embed Description
            //"description" => "Description will be here, someday, you can mention users here also by calling userID <@12341234123412341>",
            // URL of title link
            // "url" => "https://gist.github.com/Mo45/cb0813cb8a6ebcd6524f6a36d4f8862c",
            // Timestamp of embed must be formatted as ISO8601
            "timestamp" => $timestamp,

            // Embed left border color in HEX
            "color" => hexdec("00ffe1") ,

            // Footer
            "footer" => ["text" => $name],

            // Additional Fields array
            "fields" => [
            // Field 1
            ["name" => "Key", "value" => $checkkey, "inline" => true],
            // Field 2
            ["name" => "Level", "value" => $level, "inline" => true]
            // Etc..
            ]]]

            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json'
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
            // echo $response;
            curl_close($ch);

            $webid = $_POST['webid'];
            $webid = Decrypt($webid, $secret);
            $webid = strip_tags(trim(mysqli_real_escape_string($link, $webid)));

            $webquery = mysqli_query($link, "SELECT * FROM `webhooks` WHERE `webid` = '$webid' AND `app` = '$secret'");
            if (mysqli_num_rows($webquery) < 1)
            {
                die(Encrypt(json_encode(array(
                    "success" => false,
                    "message" => "webhook Not Found."
                )) , $secret));
            }
            elseif (mysqli_num_rows($webquery) > 0)
            {
                while ($rowww = mysqli_fetch_array($webquery))
                {
                    $baselink = $rowww['baselink'];
                    $useragent = $rowww['useragent'];
                }

                $params = $_POST['params'];
                $params = Decrypt($params, $secret);
                $params = strip_tags(trim(mysqli_real_escape_string($link, $params)));

                $url = $baselink .= $params;

                $ch = curl_init($url);
                // https://keyauth.com/api/seller/?sellerkey=sellerkeyhere&type=add&expiry=0.00694444444
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);
                // curl_close($ch);
                die(Encrypt(json_encode(array(
                    "success" => true,
                    "message" => "webhook request successful"
                )) , $secret));
            }
        }
        else
        {
            if ($hwidenabled == "1")
            {
                if ($hwidd == "")
                {
                    mysqli_query($link, "UPDATE `keys` SET `hwid` = '$hwid' WHERE `key` = '$checkkey'");
                }
                else if (strpos($hwidd, $hwid) === false)
                {
                    die(Encrypt(json_encode(array(
                        "success" => false,
                        "message" => "$hwidmismatch"
                    )) , $secret));
                }
            }

            $today = time();
            if ($expires < $today)
            {
                die(Encrypt(json_encode(array(
                    "success" => false,
                    "message" => "$keyexpired"
                )) , $secret));
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");
            }

            $webid = $_POST['webid'];
            $webid = Decrypt($webid, $secret);
            $webid = strip_tags(trim(mysqli_real_escape_string($link, $webid)));

            $webquery = mysqli_query($link, "SELECT * FROM `webhooks` WHERE `webid` = '$webid' AND `app` = '$secret'");
            if (mysqli_num_rows($webquery) < 1)
            {
                die(Encrypt(json_encode(array(
                    "success" => false,
                    "message" => "webhook Not Found."
                )) , $secret));
            }
            elseif (mysqli_num_rows($webquery) > 0)
            {
                while ($rowww = mysqli_fetch_array($webquery))
                {
                    $baselink = $rowww['baselink'];
                    $useragent = $rowww['useragent'];
                }

                $params = $_POST['params'];
                $params = Decrypt($params, $secret);
                $params = strip_tags(trim(mysqli_real_escape_string($link, $params)));

                $url = $baselink .= $params;

                $ch = curl_init($url);
                // https://keyauth.com/api/seller/?sellerkey=sellerkeyhere&type=add&expiry=0.00694444444
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                // curl_close($ch);
                die(Encrypt(json_encode(array(
                    "success" => true,
                    "message" => "webhook request successful"
                )) , $secret));
            }
        }
        // check if hwid enabled for application
        // check if $hwid match $keyhwid <-- obtained from SQL query above.
        die(Encrypt(json_encode(array(
            "success" => true,
            "message" => "worked",
            "expiry" => "$expiry"
        )) , $secret));
        // if all good, return success
        
    }
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

    $result = mysqli_query($link, "SELECT * FROM `files` WHERE `app` = '$secret' AND `id` = '$fileid'");

    while ($row = mysqli_fetch_array($result))
    {
        $filename = $row['name'];
    }

    $file_destination = '../../api/libs/' . $fileid . '/' . $filename;

    $myFile = $file_destination;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    ob_end_flush();
    die(file_decrypt(file_get_contents($file_destination) , "salksalasklsakslakaslkasl"));
}
else if ($type == "level")
{
    $key = $_POST['key'];
    $checkkey = Decrypt($key, $secret);

    if (strlen($checkkey) != 41)
    {
        Die(Encrypt("KeyAuth_Invalid", $secret));
    }

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