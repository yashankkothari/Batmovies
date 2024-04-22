

<?php

session_start();

if(!isset($_SESSION['login_status']))
{
    echo"Unauthorised Access";
    die;
}
if($_SESSION['login_status']==false)
{
    echo "Illegal Attempt, Login First";
    die;
}

$username=$_SESSION['username'];

echo"

<div class="sign">$username</div>


?>
