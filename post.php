<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoloader.php';
require 'vendor/requires.php';
$user->_session_start();
if($user->loggedin())
{
    if(isset($_GET["details"]))
    {
        $postid = $db->sqlsafe($_GET["details"]);
        $post->post_details($postid);
    }
    else
    {
        header("Location:index.php");
    }
}
else
{
    header("Location:index.php");
}

?>