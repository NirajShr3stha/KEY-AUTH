<?php
ob_start();
ini_set('display_errors', 'Off');
error_reporting(0);

include '../../includes/connection.php';
session_start();

$ownerid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['ownerid'])));

$name = strip_tags(trim(mysqli_real_escape_string($link, $_POST['name'])));
$result = mysqli_query($link, "SELECT * FROM `apps` WHERE `ownerid` = '$ownerid' AND `name` = '$name'");
if (mysqli_num_rows($result) < 1)
{
    die(json_encode(array(
        "success" => false,
        "message" => "Application Not Found"
    )));
}

while ($row = mysqli_fetch_array($result))
{
    $status = $row['enabled'];
    $webhook = $row['webhook'];
    $secret = $row['secret'];

    $appdisabled = $row['appdisabled'];
    $hashcheckfail = $row['hashcheckfail'];
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

$type = $_POST['type'];
if ($type == "init")
{
    if ($status == "0")
    {
        die(json_encode(array(
            "success" => false,
            "message" => "This application is disabled"
        )));
    }

    die(json_encode(array(
        "success" => true,
        "message" => "Initialized"
    )));

}
else if ($type == "login")
{
    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));
    $same = $checkkey;

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        die(json_encode(array(
            "success" => false,
            "message" => "Key Not Found."
        )));
    }
    elseif (mysqli_num_rows($result) > 0)
    {
        $currtime = time();
        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime' WHERE `key` = '$checkkey'");

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $status = $row['status'];
            $level = $row['level'];
        }

        if ($status == "Paused")
        {
            die(json_encode(array(
                "success" => false,
                "message" => "Your Key is paused and cannot be used at the moment."
            )));
        }

        if ($status == "Not Used")
        {

            mysqli_query($link, "UPDATE `keys` SET `status` = 'Used' WHERE `key` = '$checkkey'");

            $expiry = time() + $expires;

            mysqli_query($link, "UPDATE `keys` SET `expires` = '$expiry' WHERE `key` = '$checkkey'");

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
            die(json_encode(array(
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
            )));
        }
        else
        {

            $today = time();
            if ($expires < $today)
            {
                die(json_encode(array(
                    "success" => false,
                    "message" => "Key has expired."
                )));
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");
            }

            $intlevel = (int)$level;
            die(json_encode(array(
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
            )));
        }
        // check if hwid enabled for application
        // check if $hwid match $keyhwid <-- obtained from SQL query above.
        die(json_encode(array(
            "success" => true,
            "message" => "worked",
            "expiry" => "$expiry"
        )));
        // if all good, return success
        
    }
}
else if ($type == "log")
{
    $currtime = time();

    $msg = strip_tags(trim(mysqli_real_escape_string($link, $_POST['message'])));

    $logkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

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
else if ($type == "var")
{
    $varid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['varid'])));

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)
    {
        die(json_encode(array(
            "success" => false,
            "message" => "Key Not Found."
        )));
    }
    elseif (mysqli_num_rows($result) > 0)
    {

        while ($row = mysqli_fetch_array($result))
        {
            $expires = $row['expires'];
            $gendate = $row['gendate'];
            $hwidd = $row['hwid'];
            $status = $row['status'];
            $level = $row['level'];
            $banned = $row['banned'];
            $ip = $row['ip'];
        }

        if ($banned != NULL)
        {
            die(json_encode(array(
                "success" => false,
                "message" => $banned
            )));
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
                        die(json_encode(array(
                            "success" => false,
                            "message" => "Key Has Been automatically banned for triggering an anti-keyshare protection. Please contact the owner if this was a mistake"
                        )));
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

            die((json_encode(array(
                "success" => true,
                "message" => "$msg"
            ))));
        }
        else
        {

            $today = time();
            if ($expires < $today)
            {
                die(json_encode(array(
                    "success" => false,
                    "message" => "Key has expired."
                )));
                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");
            }

            $varquery = mysqli_query($link, "SELECT * FROM `vars` WHERE `varid` = '$varid' AND `app` = '$secret'");
            while ($rowww = mysqli_fetch_array($varquery))
            {
                $msg = $rowww['msg'];
            }

            die((json_encode(array(
                "success" => true,
                "message" => "$msg"
            ))));

        }

    }
}
else if ($type == "file")
{
    $fileid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['fileid'])));

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
    header('Content-Disposition: attachment; filename="' . basename($myFile) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($myFile));
    ob_end_flush();
    readfile($myFile);
}
else
{
    die(json_encode(array(
        "success" => false,
        "message" => "Invalid Type"
    )));
}
?>