<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoloader.php';
require 'vendor/requires.php';
$user->_session_start();
ob_start();
if($user->loggedin())
{
    if(!isset($_GET["profile"]) || !isset($_GET["details"]))
    {
        echo $html->load_index();
    }
    if(isset($_GET["profile"]))
  	{
        $site = $db->sqlsafe($_GET["profile"]); ob_clean();
        $html->load_profile($site);   
  	}
  	if(isset($_GET["details"]))
    {
        $postid = $db->sqlsafe($_GET["details"]); ob_clean();
        $post->post_details($postid);
    }
     
    if(isset($_GET["logout"]))
  	{
  		$user->logout();
  	}
}
if(!$user->loggedin())
{
     echo $html->load_login_page();
    
}

?>