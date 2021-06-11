<?php
include 'connection.php'; // start MySQL connection

session_start();

$role = $_SESSION['role']; // user role
	
function sanitize($input)
{
	global $link; // needed to refrence active MySQL connection
	
	return mysqli_real_escape_string($link,strip_tags(trim($input))); // return string with quotes escaped to prevent SQL injection, script tags stripped to prevent XSS attach, and trimmed to remove whitespace
}

function heador()
{	
	global $link; // needed to refrence active MySQL connection
	global $role; // needed to refrence user role
	
	if($role != "Manager")
			{
							echo"
                                <form class=\"text-left\" method=\"POST\">
                    <p class=\"mb-4\">Name: <br>".$_SESSION['name']."<br /><div class=\"mb-4\">Secret: <div class=\"secret\">".$_SESSION['secret']."</div></div><a style=\"color:#4e73df;cursor: pointer;\" id=\"mylink\">Change</a><button style=\"border: none;padding:0;background:0;color:#FF0000;padding-left:5px;\" name=\"deleteapp\" onclick=\"return confirm('Are you sure you want to delete application?')\">Delete</button>";
						($result = mysqli_query($link, "SELECT * FROM `apps` WHERE `secret` = '".$_SESSION['app']."'")) or die(mysqli_error($link));
						$row = mysqli_fetch_array($result);
						if ($row['paused'] == "0"){
						echo"<button style=\"border: none;padding:0;background:0;color:#ffcc00;padding-left:5px;\" name=\"pausekeys\" onclick=\"return confirm('Are you sure you want to pause all keys?')\">Pause</button></p></form>";
						}
						else
						{
						echo"<button style=\"border: none;padding:0;background:0;color:#ffcc00;padding-left:5px;\" name=\"unpausekeys\" onclick=\"return confirm('Are you sure you want to unpause all keys?')\">Unpause</button></p></form>";
						}
			}
	
}
function sidebar($role){	echo'	<li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Application</span></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/licenses/" aria-expanded="false"><i data-feather="key"></i><span class="hide-menu">Licenses</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/users/" aria-expanded="false"><i data-feather="users"></i><span class="hide-menu">Users</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/subscriptions/" aria-expanded="false"><i data-feather="bar-chart"></i><span class="hide-menu">Subscriptions</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/webhooks/" aria-expanded="false"><i data-feather="server"></i><span class="hide-menu">Webhooks</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/files/" aria-expanded="false"><i data-feather="paperclip"></i><span class="hide-menu">Files</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/variables/" aria-expanded="false"><i data-feather="file-text"></i><span class="hide-menu">Variables</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/logs/" aria-expanded="false"><i data-feather="database"></i><span class="hide-menu">Logs</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/blacklists/" aria-expanded="false"><i data-feather="user-x"></i><span class="hide-menu">Blacklists</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../app/settings/" aria-expanded="false"><i data-feather="settings"></i><span class="hide-menu">Settings</span></a></li>                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Account</span></li>';					    if($role == "developer" || $role == "seller")    {		echo '                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../account/manage/" aria-expanded="false"><i data-feather="sliders"></i><span class="hide-menu">Manage</span></a></li>';	}							echo'                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../account/upgrade/" aria-expanded="false"><i data-feather="activity"></i><span class="hide-menu">Upgrade</span></a></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../account/settings/" aria-expanded="false"><i data-feather="settings"></i><span class="hide-menu">Settings</span></a></li>';												if($role == "seller")						{						echo'                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Seller</span></li>                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../../seller/settings/" aria-expanded="false"><i data-feather="settings"></i><span class="hide-menu">Settings</span></a></li>						';						}						}
function error($msg)
{
	echo '<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css"><script type=\'text/javascript\'>
                
                            const notyf = new Notyf();
                            notyf
                              .error({
                                message: \''.$msg.'\',
                                duration: 3500,
                                dismissible: true
                              });               
                
                </script>';
}

function success($msg)
{
	echo '<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css"><script type=\'text/javascript\'>
                
                            const notyf = new Notyf();
                            notyf
                              .success({
                                message: \''.$msg.'\',
                                duration: 3500,
                                dismissible: true
                              });               
                
                </script>';
}

function random_string_upper($length = 10, $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
        $out = '';

        for($i = 0; $i < $length; $i++){
            $rand_index = random_int(0, strlen($keyspace) - 1);

            $out .= $keyspace[$rand_index];
        }

        return $out;
}

function random_string_lower($length = 10, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz'): string {
        $out = '';

        for($i = 0; $i < $length; $i++){
            $rand_index = random_int(0, strlen($keyspace) - 1);

            $out .= $keyspace[$rand_index];
        }

        return $out;
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomNum($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function file_encrypt($text, $key = 'KeyAuth') : string {
    $iv = "salksalasklsakslakaslkasl";
    return base64_encode( openssl_encrypt($text, 'aes-256-cbc', md5($key), true, $iv) . '{keyauth}' . $iv);
}

function file_decrypt($text, $key = 'KeyAuth') : string {
    $data = explode('{keyauth}', base64_decode($text));
    return openssl_decrypt($data[0], 'aes-256-cbc', md5($key), true, $data[1]);
}
?>