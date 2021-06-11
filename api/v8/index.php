<?php
ob_start();

ini_set('display_errors', 'Off');

error_reporting(0);

include '../../includes/connection.php';
include '../../includes/functions.php';

session_start();

$ownerid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['ownerid'])));

$name = strip_tags(trim(mysqli_real_escape_string($link, $_POST['name'])));

$result = mysqli_query($link, "SELECT * FROM `apps` WHERE `ownerid` = '$ownerid' AND `name` = '$name'");

if (mysqli_num_rows($result) < 1)

{

    Die("KeyAuth_Invalid");

}

while ($row = mysqli_fetch_array($result))
{

    $secret = $row['secret'];

    $hwidenabled = $row['hwidcheck'];

    $anti = $row['antishare'];

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

$type = $_POST['type'];

if ($type == "init")

{

    if ($status == "0")

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$appdisabled"
        )));

    }

    $ver = strip_tags(trim(mysqli_real_escape_string($link, $_POST['ver'])));

    if ($ver != $currentver)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "invalidver",
            "download" => "$download"
        ) , JSON_UNESCAPED_SLASHES));

    }

    die(json_encode(array(
        "success" => true,
        "message" => "Initialized"
    )));

}

else if ($type == "register")

{

    // Read in username
    $username = strip_tags(trim(mysqli_real_escape_string($link, $_POST['username'])));

    // search username
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username' AND `app` = '$secret'");

    // check if username already exists
    if (mysqli_num_rows($result) >= 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$usernametaken"
        )));

    }

    // Read in key
    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

    // search for key
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    // check if key exists
    if (mysqli_num_rows($result) < 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
        )));

    }

    // if key does exist
    elseif (mysqli_num_rows($result) > 0)

    {

        $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

        // get key info
        while ($row = mysqli_fetch_array($result))
        {

            $expires = $row['expires'];

            $status = $row['status'];

            $level = $row['level'];

        }

        // check if used
        if ($status == "Used")

        {

            die(json_encode(array(
                "success" => false,
                "message" => "$keyused"
            )));

        }

        // Read in password
        $password = strip_tags(trim(mysqli_real_escape_string($link, $_POST['pass'])));

        $password = password_hash($password, PASSWORD_BCRYPT);

        // Read in hwid
        $hwid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['hwid'])));

        // set key to used
        mysqli_query($link, "UPDATE `keys` SET `status` = 'Used' WHERE `key` = '$checkkey'");

        // add current time to key time
        $expiry = $expires + time();

        $result = mysqli_query($link, "SELECT * FROM `subscriptions` WHERE `app` = '$secret' AND `level` = '$level'");

        $num = mysqli_num_rows($result);

        if ($num == 0)

        {

            mysqli_close($link);
            die(json_encode(array(
                "success" => false,
                "message" => "$nosublevel"
            )));

        }

        while ($row = mysqli_fetch_array($result))
        {

            $subname = $row['name'];

            mysqli_query($link, "INSERT INTO `subs` (`user`, `subscription`, `expiry`, `app`) VALUES ('$username','$subname', '$expiry', '$secret')");

        }

        // insert that bitch in
        mysqli_query($link, "INSERT INTO `users` (`username`, `password`, `hwid`, `app`) VALUES ('$username','$password', '$hwid', '$secret')");

        // success
        die(json_encode(array(
            "success" => true,
            "message" => "Registered correctly"
        )));

    }

}

else if ($type == "upgrade")

{

    // Read in username
    $username = strip_tags(trim(mysqli_real_escape_string($link, $_POST['username'])));

    // search username
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username' AND `app` = '$secret'");

    // check if username already exists
    if (mysqli_num_rows($result) == 0)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$usernamenotfound"
        )));

    }

    // Read in key
    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

    // search for key
    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    // check if key exists
    if (mysqli_num_rows($result) < 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
        )));

    }

    // if key does exist
    elseif (mysqli_num_rows($result) > 0)

    {

        $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

        // get key info
        while ($row = mysqli_fetch_array($result))
        {

            $expires = $row['expires'];

            $status = $row['status'];

            $level = $row['level'];

        }

        // check if used
        if ($status == "Used")

        {

            die(json_encode(array(
                "success" => false,
                "message" => "$keyused"
            )));

        }

        // set key to used
        mysqli_query($link, "UPDATE `keys` SET `status` = 'Used' WHERE `key` = '$checkkey'");

        // add current time to key time
        $expiry = $expires + time();

        $result = mysqli_query($link, "SELECT * FROM `subscriptions` WHERE `app` = '$secret' AND `level` = '$level'");

        $num = mysqli_num_rows($result);

        if ($num == 0)

        {

            mysqli_close($link);
            die(json_encode(array(
                "success" => false,
                "message" => "$nosublevel"
            )));

        }

        while ($row = mysqli_fetch_array($result))
        {

            $subname = $row['name'];

            mysqli_query($link, "INSERT INTO `subs` (`user`, `subscription`, `expiry`, `app`) VALUES ('$username','$subname', '$expiry', '$secret')");

        }

        // success
        die(json_encode(array(
            "success" => true,
            "message" => "Upgraded successfully"
        )));

    }

}

else if ($type == "login")

{

    // Read in username
    $username = strip_tags(trim(mysqli_real_escape_string($link, $_POST['username'])));

    // Read in HWID
    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['hwid'])));

    // Find username
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username' AND `app` = '$secret'");

    // if not found
    if (mysqli_num_rows($result) < 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$usernamenotfound"
        )));

    }

    // if found
    elseif (mysqli_num_rows($result) > 0)

    {

        // Read in password
        $password = strip_tags(trim(mysqli_real_escape_string($link, $_POST['pass'])));

        // get all rows from username query
        while ($row = mysqli_fetch_array($result))

        {

            $pass = $row['password'];

            //$expires = $row['expires'];
            $hwidd = $row['hwid'];

        }

        // check if pass matches
        if (!password_verify($password, $pass))

        {

            die(json_encode(array(
                "success" => false,
                "message" => "$passmismatch"
            )));

        }

        // check if expired
        /*
        $today = time();
        
        if ($expires < $today)
        
        {
        
        die(Encrypt(json_encode(array("success" => false, "message" => "User has expired.")), $secret));
        
        }
        */

        // check if hwid enabled for application
        if ($hwidenabled == "1")

        {

            // check if hwid in db contains hwid recieved
            if (strpos($hwidd, $hwid) === false)

            {

                die(json_encode(array(
                    "success" => false,
                    "message" => "$hwidmismatch"
                )));

            }

        }

        $result = mysqli_query($link, "SELECT `subscription`, `expiry` FROM `subs` WHERE `user` = '$username' AND `app` = '$secret' AND `expiry` > " . time() . "");

        $num = mysqli_num_rows($result);

        if ($num == 0)

        {

            mysqli_close($link);
            die(json_encode(array(
                "success" => false,
                "message" => "$noactivesubs"
            )));

        }

        $rows = array();

        while ($r = mysqli_fetch_assoc($result))
        {

            $rows[] = $r;

        }

        mysqli_close($link);

        // die(json_encode($rows, JSON_PRETTY_PRINT));
        

        // success
        die(json_encode(array(
            "success" => true,
            "message" => "Logged in!",
            "subscriptions" => $rows
        )));

        // die(Encrypt(json_encode(array("success" => true, "message" => "Logged in. Will add user data later")), $secret));
        

        
    }

}

else if ($type == "license")

{

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['hwid'])));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    

    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
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

            $note = $row['note'];

            $banned = $row['banned'];

            $ip = $row['ip'];

        }

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

        if ($banned != NULL)

        {

            die(json_encode(array(
                "success" => false,
                "message" => $banned
            )));

        }

        $currtime = time();

        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime', `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "' WHERE `key` = '$checkkey'");

        if ($status == "Paused")

        {

            die(json_encode(array(
                "success" => false,
                "message" => "$keypaused"
            )));

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
            "embeds" => [

            [

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
            "footer" => [

            "text" => $name

            ],

            // Additional Fields array
            "fields" => [

            // Field 1
            [

            "name" => "Key",

            "value" => $checkkey,

            "inline" => true

            ],

            // Field 2
            [

            "name" => "Level",

            "value" => $level,

            "inline" => true

            ]

            // Etc..
            ]

            ]

            ]

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

                    die(json_encode(array(
                        "success" => false,
                        "message" => "$hwidmismatch"
                    )));

                }

            }

            $today = time();

            if ($expires < $today)

            {

                die(json_encode(array(
                    "success" => false,
                    "message" => "$keyexpired"
                )));

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
            "message" => "worked",
            "expiry" => "$expiry"
        )) , $secret));

        // if all good, return success
        
    }

}

else if ($type == "var")

{

    $varid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['varid'])));

    $varquery = mysqli_query($link, "SELECT * FROM `vars` WHERE `varid` = '$varid' AND `app` = '$secret'");

    while ($rowww = mysqli_fetch_array($varquery))
    {

        $msg = $rowww['msg'];

    }

    die(json_encode(array(
        "success" => true,
        "message" => "$msg"
    )));

}

else if ($type == "log")

{

    $currtime = time();

    $msg = strip_tags(trim(mysqli_real_escape_string($link, $_POST['message'])));

    $msg = "📜 Log: " . $msg;

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
    "embeds" => [

    [

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
    "footer" => [

    "text" => $name

    ],

    // Additional Fields array
    "fields" => [["name" => "🔐 Credential:", "value" => "```" . $logkey . "```"], ["name" => "💻 PC Name:", "value" => "```Same```", "inline" => true], ["name" => "🌎 Client IP:", "value" => "```" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "```", "inline" => true], ["name" => "📈 Level:", "value" => "```1```", "inline" => true]]

    ]

    ]

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

    $checkkey = strip_tags(trim(mysqli_real_escape_string($link, $_POST['key'])));

    $hwid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['hwid'])));

    // sql statement to check if key exist. If key do exist, return info like HWID..
    

    $result = mysqli_query($link, "SELECT * FROM `keys` WHERE `key` = '$checkkey' AND `app` = '$secret'");

    if (mysqli_num_rows($result) < 1)

    {

        die(json_encode(array(
            "success" => false,
            "message" => "$keynotfound"
        )));

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

            die(json_encode(array(
                "success" => false,
                "message" => $banned
            )));

        }

        $currtime = time();

        mysqli_query($link, "UPDATE `keys` SET `lastlogin` = '$currtime', `ip` = '" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "' WHERE `key` = '$checkkey'");

        if ($status == "Paused")

        {

            die(json_encode(array(
                "success" => false,
                "message" => "$keypaused"
            )));

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
            "embeds" => [

            [

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
            "footer" => [

            "text" => $name

            ],

            // Additional Fields array
            "fields" => [

            // Field 1
            [

            "name" => "Key",

            "value" => $checkkey,

            "inline" => true

            ],

            // Field 2
            [

            "name" => "Level",

            "value" => $level,

            "inline" => true

            ]

            // Etc..
            ]

            ]

            ]

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

            $webid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['webid'])));

            $webquery = mysqli_query($link, "SELECT * FROM `webhooks` WHERE `webid` = '$webid' AND `app` = '$secret'");

            if (mysqli_num_rows($webquery) < 1)

            {

                die(json_encode(array(
                    "success" => false,
                    "message" => "webhook Not Found."
                )));

            }

            elseif (mysqli_num_rows($webquery) > 0)

            {

                while ($rowww = mysqli_fetch_array($webquery))
                {

                    $baselink = $rowww['baselink'];

                    $useragent = $rowww['useragent'];

                }

                $params = strip_tags(trim(mysqli_real_escape_string($link, $_POST['params'])));

                $url = $baselink .= $params;

                $ch = curl_init($url);

                // https://keyauth.com/api/seller/?sellerkey=sellerkeyhere&type=add&expiry=0.00694444444
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);

                // curl_close($ch);
                die(json_encode(array(
                    "success" => true,
                    "message" => "webhook request successful"
                )));

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

                    die(json_encode(array(
                        "success" => false,
                        "message" => "$hwidmismatch"
                    )));

                }

            }

            $today = time();

            if ($expires < $today)

            {

                die(json_encode(array(
                    "success" => false,
                    "message" => "$keyexpired"
                )));

                mysqli_query($link, "UPDATE `keys` SET `status` = 'Expired' WHERE `key` = '$checkkey'");

            }

            $webid = strip_tags(trim(mysqli_real_escape_string($link, $_POST['webid'])));

            $webquery = mysqli_query($link, "SELECT * FROM `webhooks` WHERE `webid` = '$webid' AND `app` = '$secret'");

            if (mysqli_num_rows($webquery) < 1)

            {

                die(json_encode(array(
                    "success" => false,
                    "message" => "webhook Not Found."
                )));

            }

            elseif (mysqli_num_rows($webquery) > 0)

            {

                while ($rowww = mysqli_fetch_array($webquery))
                {

                    $baselink = $rowww['baselink'];

                    $useragent = $rowww['useragent'];

                }

                $params = strip_tags(trim(mysqli_real_escape_string($link, $_POST['params'])));

                $url = $baselink .= $params;

                $ch = curl_init($url);

                // https://keyauth.com/api/seller/?sellerkey=sellerkeyhere&type=add&expiry=0.00694444444
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $response = curl_exec($ch);

                // curl_close($ch);
                die(json_encode(array(
                    "success" => true,
                    "message" => "webhook request successful"
                )));

            }

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

    die(file_decrypt(file_get_contents($file_destination) , "salksalasklsakslakaslkasl"));

}

else

{

    die("Invalid Type.");

}

?>