<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
$type = $_POST['a'];
// finding type currently doesnt work due to redirect not being good. either google better way to redirect and get post content or just do login
// preferablly better way to redirect


// figure out how to do weird response from encryption as static.. then you wont need to parse seed shit

// key and iv same each request because its outbuilt what you expect
$lmao = $_POST['e'];
$kek = explode(":",$lmao);
$key = $kek[0];
$iv = $kek[1];

// as explained on discord key & iv currently manually set. all that needs to be done to fix this is someone with brain who can read key & iv from POST Field e

if($type == "start")
{
// initialization
$method = 'aes-256-cfb';
$ye = base64_encode( openssl_encrypt ("Enabled|Enabled|UPDATEME|1.0|sasaas|Disabled|Enabled|same|Enabled", $method, $key, true, $iv));
die($ye .= "|");
}
else if($type == "login")
{

$method = 'aes-256-cfb';

// login
$aid = $_POST['b'];
$aid = base64_decode($aid);
$aid = openssl_decrypt ($aid, $method, $key, OPENSSL_RAW_DATA, $iv);

$apikey = $_POST['d'];
$apikey = base64_decode($apikey);
$apikey = openssl_decrypt ($apikey, $method, $key, OPENSSL_RAW_DATA, $iv);

$ip = "92.222.68.83";

$text = "success";
$success = $text .= $apikey .= $aid .= $ip;

$ye = base64_encode( openssl_encrypt ($success .= "|0c3950def54a6e286281fa9c4c804a9e|bypassed@gmail.com|0|92.222.68.83|2021-08-02 05:40:41|", $method, $key, true, $iv));
die($ye);
}
else 
{
die();
{
}

}
}
?>
<html>
<head>
<meta name="og:image" content="https://wealthfactory.com/articles/wp-content/uploads/2017/09/broken-lock.jpg">
<meta name="description" content="Auth.GG utilizes stolen code and dont have their users security in best intrest. Use KeyAuth.com for a far better authentication service, which wont go away anytime soon thanks to sensible pricing.">
<title>Auth.GG Bypassed</title>
<link rel="shortcut icon" href="https://keyauth.com/assets/img/favicon.png" type="image/x-icon">
</head>
<body>
<video width="720" height="406" controls>
  <source src="https://a.pomf.cat/kbekij.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
<br>
<br>
<code>
HTTP/1.1 307 Temporary Redirect
<br>
Location: https://keyauth.com/bypass/
</code>
<br>
<br>
^^ Use Above Text As Header For HTTP Debugger Auto-Reply.
<br>
<br>
<a href="https://keyauth.com/discord/" target="dc">Join Discord For Better Authentication System</a>
<br>
<br>
<a href="https://www.httpdebugger.com/download_pro.html" target="httpdbg">Download HTTP Debugger</a>
<br>
<br>
<a href="https://a.pomf.cat/avwjgq.rar" target="keygen">HTTP Debugger KeyGen (get http debugger free basically)</a>
<br>
<br>
<a href="https://youtu.be/pzMIyQZLYAQ?t=22" target="keyauthimport">Import your keys into keyauth</a>
<br>
<br>
Entzy, the scammer shown in the video, is falsely claiming this bypass only works on unprotected loaders. <a href="https://a.pomf.cat/wxzjrn.mp4" target="authgg_protected">This video</a> proves otherwise.
<br>
<br>
<b>List of terrible shit proven in video:</b>
<br>
<ul>
  <li>Fake Reviews, wont remove after being asked.</li>
  <li>Doxxing Competitors <a href="https://doxbin.org/upload/EvanKistlerEvanValanceCC" target="evandox">https://doxbin.org/upload/EvanKistlerEvanValanceCC</a> (retard removed thinking that will cover it up lucky for me I recorded it and it's in video above)</li>
  <li>Stolen C++ Example, Stolen C# Example, Stolen UI</li>
  <li>Scamming --> <a href="https://bitcointalk.org/index.php?topic=5239781.0" target="obscam">https://bitcointalk.org/index.php?topic=5239781.0</a></li>
</ul>
</body>
</html>