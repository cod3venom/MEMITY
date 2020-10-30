<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoloader.php';
require 'vendor/requires.php';
$user->_session_start();
if(isset($_POST["start"]) && isset($_POST["limit"]) && isset($_POST["type"]))
{	
	$limit = $_POST["limit"];
	$start = $_POST["start"];
	$type = $db->sqlsafe($_POST["type"]);
	$post->Load_post($start,$limit,$type);
}

if(isset($_POST["signup"])&&isset($_POST["username"])&&isset($_POST["password"])&&isset($_POST["repeat"])&&isset($_POST["email"]))
{
	$username = $db->sqlsafe($_POST["username"]); $password = $db->sqlsafe($_POST["password"]); $repeat = $db->sqlsafe($_POST["repeat"]); $email = $db->sqlsafe($_POST["email"]);
	$user->signup($username,$password,$repeat,$email);
}
if(isset($_POST["signin"]) && isset($_POST["email"]) && isset($_POST["password"]))
{
	$email = $db->sqlsafe($_POST["email"]); $password = $db->sqlsafe($_POST["password"]);
	$user->signin($email,$password);
}
if(isset($_POST["top"]))
{
    $post->top_post();
}
if($user->loggedin())
{
    if(isset($_POST["st"])&&isset($_POST["t"]))
    {
      $status = $db->sqlsafe($_POST["st"]); $time = $db->sqlsafe($_POST["t"]);
      $user->init_status($status,$time);
    }
    if(isset($_POST["online"]))
    {
      $time = $db->sqlsafe($_POST["online"]);
      $rel->online_rel($time);
    }
  	if(isset($_POST["post"]) && isset($_POST["type"]))
  	{
  		$postid = $db->sqlsafe($_POST["post"]); $type = $db->sqlsafe($_POST["type"]);
  		if($type === "Like" || $type === "Funny" || $type === "Love" || $type === "Angry")
  		{
  			$post->init_vote($postid,$type);
  		}
  	}
  	if(isset($_POST["total"]) && isset($_POST["type"]))
      {
        $postid = $db->sqlsafe($_POST["total"]);
        $type = $db->sqlsafe($_POST["type"]);
        if($type === "Like" ||$type === "Funny" ||$type === "Love" ||$type === "Angry")
        {
          echo $post->vote_total($postid,$type);
        }
      }
  	 if(isset($_POST["notif"]))
     {
        echo $notif->get_notification();
     }
  	if(isset($_POST["_c"]) && isset($_POST["newcom"]))
  	{
  		$postid = $db->sqlsafe($_POST["_c"]);
  		$comment = $_POST["newcom"];
  		$post->send_comment($postid,$comment,"New","");
  		//echo $post->comment_by_id($postid,"","New");
  	}
    if(isset($_POST["upp"])&&isset($_FILES["postim"])&& isset($_POST["upt"]))
    {
      $file = $_FILES["postim"]; $text = strip_tags($_POST["upt"]);
      $sys->upload_image($file,$text);
      ob_clean();
    }
  	if(isset($_POST["post"]) && isset($_POST["parent"]) && isset($_POST["comment"]))
  	{
  		$postid = $db->sqlsafe($_POST["post"]);
  		$parent = $_POST["parent"];
  		$reply = $_POST["comment"];
  		$post->send_comment($postid,$reply,"Reply",$parent);
  		//echo $post->comment_by_id($postid,$parent,"Reply");
  	}
  	if(isset($_POST["load"]))
  	{
  		$postid = $db->sqlsafe($_POST["load"]);
  		echo '<div class="all_comments">';
  		$post->load_comments($postid);
  		echo '</div>';
  	}
	  if(isset($_POST["follow"]))
  	{
        $b = $db->sqlsafe($_POST["follow"]); $t = "Followed";
        $rel->set($b,$t);
  	}
  	if(isset($_POST["get_relations"]) && isset($_POST["type"]))
  	{
        $userid = $db->sqlsafe($_POST["get_relations"]); $type = $db->sqlsafe($_POST["type"]);
        $rel->get_relations($userid, $type);
  	} 
  	if(isset($_POST["profile_posts"]))
 	  {
        $profile_id = $db->sqlsafe($_POST["profile_posts"]);
        $post->load_user_post($profile_id);
  	}
  	if(isset($_POST["settings"]))
  	{
  		echo $html->settings();
  	}
  	if(isset($_POST["changeu"]) && isset($_POST["newu"]))
  	{
  		$username = $db->sqlsafe($_POST["newu"]);
  		$user->update_user($username);
  	}
  	if(isset($_POST["changem"]) && isset($_POST["newm"]))
  	{
  		$email = $db->sqlsafe($_POST["newm"]);
  		$user->update_email($email);
  	}
  	if(isset($_POST["changep"])&&isset($_POST["npwd"])&& isset($_POST["nrpt"]))
  	{
  		$password = $db->sqlsafe($_POST["npwd"]); $repeat = $db->sqlsafe($_POST["nrpt"]);
  		if($password === $repeat)
  		{
  			$user->update_pwd($password);
  			echo '0';
  		}
  		else
  		{
  			echo '1';
  		}
  	}
}
if(isset($_POST["pymachine"]) && isset($_POST["pyimage"]))
{
	$extension =   $sys->get_ext($_POST["pyimage"]);

    $query = "INSERT INTO MEMITY_POST 
    (Userid,Postuser,Postid,Posturl,Posttype)
     VALUES(?,?,?,?,?)";
     $userid = '$2y$10$eZ9FoKdEM9z0raRawr4DaOfyqvhsT1uZi3JqhhC2y6aVtggy7Ty';
     $postuser = "admin";
     $postid = md5(microtime());
     $posturl = $_POST["pyimage"];


     $stmt = $db->create_stmt($conn,$query);
     $stmt->bind_param("sssss",$userid,$postuser,$postid,$posturl,$extension);
     $stmt->execute(); $stmt->store_result();
     echo "ADDED";
}
?>