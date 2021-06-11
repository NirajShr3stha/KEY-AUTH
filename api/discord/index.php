<?php

/*
	This code is pretty much all from https://gist.github.com/Jengas/ad128715cb4f73f5cde9c467edf64b00
	It serves as the redirect page for users who 'Link Discord' in account settings
	Using their oauth2 access_token, it will first attempt to add them to the server, then give them a role if they're developer or seller
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', '808227154931875893');
define('OAUTH2_CLIENT_SECRET', 'OktNxrbWKMRI78WZnIKkEP2UmH87iHEH');

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

session_start();

if (!isset($_SESSION['username'])) {
        die("not logged into KeyAuth. please log into KeyAuth so I can know which role to assign you :)");
}

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {

  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => 'https://keyauth.com/api/discord/',
    'code' => get('code')
  ));
  $logout_token = $token->access_token;
  $_SESSION['access_token'] = $token->access_token;


  header('Location: ' . $_SERVER['PHP_SELF']);
}

if(session('access_token')) {
  $user = apiRequest($apiURLBase);
 
  $headers = array(
            'Content-Type: application/json',
            'Authorization: Bot ODA4MjI3MTU0OTMxODc1ODkz.YCDeMQ.sA2ihLvHWHCM8boOUpBPFcU-rCY'
        );
        $data = array("access_token" => session('access_token'));
    $data_string = json_encode($data);
	
                              $url = "https://discord.com/api/guilds/824397012685291520/members/". $user->id;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
                curl_exec($ch);
                curl_close($ch);


	
     if($_SESSION['role'] == "seller")
	 {
		 $role = 824406788581490708;
	 }
	 else if($_SESSION['role'] == "developer")
	 {
		 $role = 824406803827392523;
	 }

    $url = "https://discord.com/api/guilds/824397012685291520/members/". $user->id. "/roles/{$role}";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	curl_exec($ch);
    curl_close($ch);


} else {
  die("not logged into discord, go to keyauth app settings and press link.");
}


if(get('action') == 'logout') {
  // This must to logout you, but it didn't worked(

  $params = array(
    'access_token' => $logout_token
  );

  // Redirect the user to Discord's revoke page
  header('Location: https://discord.com/api/oauth2/token/revoke' . '?' . http_build_query($params));
  die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  $response = curl_exec($ch);


  if($post)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

  $headers[] = 'Accept: application/json';

  if(session('access_token'))
    $headers[] = 'Authorization: Bearer ' . session('access_token'); 

  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  return json_decode($response);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

?>
<html>
  <head>
	<title>KeyAuth Discord Link Complete</title>
	<link rel="icon" type="image/png" href="https://keyauth.com/static/images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
    <body>
      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">&#10004;</i>
      </div>
        <h1>Success</h1> 
        <p>You've been added to the server if not already in it,</br>and you've been assigned a paid user role if applicable</p>
      </div>
    </body>
</html>