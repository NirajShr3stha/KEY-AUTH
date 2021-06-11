<?php
session_start();
$lmao = $_SESSION['username'];
if($lmao !== "mak")
{
	header("Location: ../index.php");
	die();
}

include '../includes/connection.php';

if(isset($_POST['devupgrade']))
{
	$username = strip_tags(mysqli_real_escape_string($link, $_POST['username']));
	mysqli_query($link, "UPDATE `accounts` SET `role` = 'developer' WHERE `username` = '$username'");
	die("upgraded to developer");
}

if(isset($_POST['sellerupgrade']))
{
	$username = strip_tags(mysqli_real_escape_string($link, $_POST['username']));
	mysqli_query($link, "UPDATE `accounts` SET `role` = 'seller' WHERE `username` = '$username'");
	die("upgraded to seller");
}

if(isset($_POST['deleteuser']))
{
	$username = strip_tags(mysqli_real_escape_string($link, $_POST['username']));
	mysqli_query($link, "DELETE FROM `accounts` WHERE `username` = '$username'");
	die("deleted");	
}

if(isset($_POST['loginuser']))
{
$username = strip_tags(mysqli_real_escape_string($link, $_POST['username']));
	           $login_status = "invalid";

            ($result = mysqli_query($link, "SELECT * FROM `accounts` WHERE `username` = '$username'")) or die(mysqli_error($link));

            if (mysqli_num_rows($result) < 1)
            {
                $login_status = "invalid";
                
                             
                echo "invalid login details";
                

                  return;
            }
            else if (mysqli_num_rows($result) > 0)
            {
                while ($row = mysqli_fetch_array($result))
                {
                    $user = $row['username'];
                    $id = $row['ownerid'];
                    $email = $row['email'];
                    $role = $row['role'];
                    $app = $row['app'];
                    $isbanned = $row['isbanned'];
                    $img = $row['img'];
                    $pp = $row['pp'];
                    
                    $owner = $row['owner'];
                    $dayrate = $row['dayrate'];
                    $weekrate = $row['weekrate'];
                    $monthrate = $row['monthrate'];
                    $threemonthrate = $row['threemonthrate'];
                    $sixmonthrate = $row['sixmonthrate'];
                    $liferate = $row['liferate'];
                }

                if ($isbanned == "1")
                {
                    $login_status = "banned";
                    echo "ur account has been banned";
                }
                

                if ($login_status !== "banned" || $login_status !== "invalid")
                {
                        $login_status = "success";
                    

                    $resp['login_status'] = $login_status;

                    if ($login_status == "success")
                    {
                        
                            $_SESSION['username'] = $username;
                            $_SESSION['email'] = $email;
                            $_SESSION['ownerid'] = $id;
                            $_SESSION['owner'] = $owner;
                            $_SESSION['role'] = $role;
                            
                            if($role == "Reseller" || $role == "Manager")
                            {
                                $_SESSION['app'] = $app;
                                
                                $_SESSION['dayrate'] = $dayrate;
                                $_SESSION['weekrate'] = $weekrate;
                                $_SESSION['monthrate'] = $monthrate;
                                $_SESSION['threemonthrate'] = $threemonthrate;
                                $_SESSION['sixmonthrate'] = $sixmonthrate;
                                $_SESSION['liferate'] = $liferate;
                            }
                            
                            $_SESSION['img'] = $img;
                            $_SESSION['pp'] = $pp;
                            echo "logged in";                        
                            
                      
                            
             
                            echo "<meta http-equiv='Refresh' Content='2; url=../dashboard/'>";                             
                        


                    }
                    else
                    {
                            die("incorect login details");
}}}
}

?>

<form method="post">
<input name="username" placeholder="username"></input><br><br><button name="devupgrade">Upgrade To Developer</button><br><br><button name="sellerupgrade">Upgrade To Seller</button><br><br><button name="deleteuser">Delete Account</button><br><br><br><button name="loginuser">Login To User</button>
</form>