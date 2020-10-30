<?php

class post
{
    function post_exists($postid)
    {
        require "requires.php";
        if($user->loggedin() && !empty($postid))
        {
            $postid = $db->sqlsafe($postid);
            $stmt = $db->create_stmt($conn,"SELECT Postid FROM MEMITY_POST WHERE Postid = ? LIMIT 1");
            $stmt->bind_param("s",$postid);
            $stmt->execute();$stmt->store_result();
            $result = $stmt->num_rows();
            if($result>0)
            {
                 return true;
            }
            else{
                 return false;
            }
        }
    }
    public function Load_post($start , $limit, $type)
    {
        require "requires.php";
        $limit = $db->sqlsafe($limit); $type = $db->sqlsafe($type);
        $query = "";
        if($type === "latest")
            $query = "SELECT * FROM MEMITY_POST ORDER BY(ID) DESC LIMIT ".$start.", ".$limit."";
        if($type === "random")
            $query= "SELECT * FROM MEMITY_POST ORDER BY RAND() DESC LIMIT ".$start.", ".$limit."";
        if($type === "mp4")
            $query = "SELECT * FROM MEMITY_POST WHERE Posttype = ?  ORDER BY(ID) DESC LIMIT ".$start.", ".$limit."";
        if($type === "gif")
            $query = "SELECT * FROM MEMITY_POST WHERE Posttype = ?  ORDER BY(ID) DESC LIMIT ".$start.", ".$limit."";  
        $stmt = $db->create_stmt($conn,$query);
         if($type === "mp4" || $type === "webp")
         {
            $stmt->bind_param("s",$type);
         }
         if($type === "gif")
         {
            $stmt->bind_param("s",$type);
         }

        $stmt->execute(); $data = $stmt->get_result();
        foreach($data as $post)
        {
            $username = $post["Postuser"]; 
            $avatar = $user->user_selector("AVATAR",$post["Userid"]);
            $comment = array("Comment_num" => $this->comment_total($post["Postid"]));
            $like = array("Like" => $this->vote_total($post["Postid"],"Like")); 
            $funny = array("Funny" => $this->vote_total($post["Postid"],"Funny")); 
            $love =  array("Love" => $this->vote_total($post["Postid"],"Love")); 
            $angry = array("Angry" => $this->vote_total($post["Postid"],"Angry")); 
            
            $post = array_merge($comment,$post);
            $post = array_merge($like,$post); 
            $post = array_merge($funny,$post);
            $post = array_merge($love,$post); 
            $post = array_merge($angry,$post);
            
            echo $html->post_html($post);
        }
    }
    function load_user_post($id)
    {
        require "requires.php";
        if($user->loggedin() && $user->user_exists($id))
        {   
            $username = $user->user_selector("Username",$id);
            $stmt = $db->create_stmt($conn,"SELECT Posturl,Posttext, Postid,Postdate FROM MEMITY_POST WHERE Postuser = ? ORDER BY(ID) DESC LIMIT 10"); $stmt->bind_param("s",$username); $stmt->execute(); 
            $result = $stmt->get_result();  
            foreach($result as $data)
            {
                $extension = $sys->get_ext($data["Posturl"]);
                $profile_post = $html->load_html("profile_post");
                if($extension === "mp4" || $extension === "webm")
                {
                    $video = '<video autoplay muted> <source src="'.$data["Posturl"].'" type="video/mp4"></video>';
                     $profile_post = str_replace("POST_IMG++;",$video,$profile_post);
                }
                if($extension === "png" || $extension === "jpg" || $extension === "jpeg" || $extension === "gif")
                {
                    $profile_post = str_replace("POST_IMG++;", '<img src="'.$data["Posturl"].'">', $profile_post);
                }
                $profile_post = str_replace("POSTURL++;","?details=".$data["Postid"],$profile_post);
                $profile_post = str_replace("POST_TEXT++;", $data["Posttext"], $profile_post);
                $profile_post = str_replace("POST_DATE++;", $data["Postdate"], $profile_post);
                echo $profile_post;
            }
        }
    }
    function post_details($postid)
    {
        require "requires.php";
         
        $stmt = $db->create_stmt($conn,"SELECT * FROM MEMITY_POST WHERE Postid = ? LIMIT 1");
        $stmt->bind_param("s",$postid);$stmt->execute();
        $result = $stmt->get_result();
         $output = $html->load_html("index_details");
         foreach($result as $meme)
        {

            $extension = $sys->get_ext($meme["Posturl"]);
            $comment = array("Comment_num" => $this->comment_total($meme["Postid"]));

            $like =  array("Like" => $this->vote_total($meme["Postid"],"Like"));
            $funny = array("Funny" => $this->vote_total($meme["Postid"],"Funny")); 
            $love =  array("Love" => $this->vote_total($meme["Postid"],"Love")); 
            $angry = array("Angry" => $this->vote_total($meme["Postid"],"Angry")); 

            $meme = array_merge($comment,$meme);
            $meme = array_merge($like,$meme); 
            $meme = array_merge($funny,$meme);
            $meme = array_merge($love,$meme); 
            $meme = array_merge($angry,$meme);

            $avatar = $user->user_selector("Avatar",$meme["Userid"]);
            $output = str_replace("MY_ID++;",$_SESSION["userid"], $output);
            $output = str_replace("MY_AVATAR++;",$_SESSION["avatar"], $output);
            $output = str_replace("POST_ID++;",$meme["Postid"],$output);
            $output = str_replace("POST_AVATAR++;",$avatar,$output);
            $output = str_replace("USER_LINK++;",$meme["Userid"],$output);
            $output = str_replace("POST_USER++;",$meme["Postuser"],$output);
            $output = str_replace("POST_DATE++;",$meme["Postdate"],$output);
            $output = str_replace("COMMENTS++;", $meme["Comment_num"],$output);
            
            if($extension === "mp4" || $extension === "webm")
            {
                $video =$video = '<video controls><source src="'.$meme["Posturl"].'" type="video/mp4"></video>';
                $output = str_replace("POST_MEME++;",$video,$output);
            }
            if($extension === "png" || $extension === "jpg" || $extension === "jpeg" || $extension === "gif")
            {
                $output = str_replace("POST_MEME++;",'<img src="'.$meme["Posturl"].'">',$output);
            }
            $output = str_replace("LIKES++;",$meme["Like"],$output);
            $output = str_replace("FUNNYS++;",$meme["Funny"],$output);
            $output = str_replace("LOVES++;",$meme["Love"],$output);
            $output = str_replace("ANGRYS++;",$meme["Angry"],$output);
            $output = str_replace("ID++;",$meme["Postid"],$output);
         }
        echo $output;
        echo '<script>
list();function list(){$.ajax({type:"POST",url:"operator.php",data:"load='.$meme["Postid"].'",cache:false,success:function(_KK){$(".all_comments").html("");$(".all_comments").html(_KK);}});}</script>';
        //$this->load_comments($meme["Postid"]);
    }
    public function load_comments($postid)
    {
        require 'requires.php'; $type = "New";
        $stmt = $db->create_stmt($conn, "SELECT * FROM MEMITY_COMMENT WHERE Postid = ? AND Comment_type = ? ORDER BY(ID) DESC");
        $stmt->bind_param("ss",$postid,$type); $stmt->execute();
        $result = $stmt->get_result();
         foreach($result as $comment)
        {
            echo $html->comments($comment);
            $type = "Reply";
            $stmt = $db->create_stmt($conn,"SELECT * FROM MEMITY_COMMENT WHERE Comment_ID = ? AND Comment_type = ? ORDER BY(ID) DESC");
            $stmt->bind_param("ss",$comment["Comment_ID"], $type);
            $stmt->execute();
            $result = $stmt->get_result();
            foreach($result as $reply_text)
            {
                echo $html->reply($reply_text);
            }
        }
         
     }
    public function comment_by_id($postid,$commentid,$type)
    {
        require "requires.php"; 
        if($user->loggedin())
        {
            $postid = $db->sqlsafe($postid);
            $query = '';
            if($type === "Reply")
                $query = "SELECT * FROM MEMITY_COMMENT WHERE Postid = ? AND Comment_ID = ? AND Comment_type = ? ORDER BY(stamp) DESC LIMIT 1";
            if ($type === "New")
                $query = "SELECT * FROM MEMITY_COMMENT WHERE Postid = ?  AND Comment_type = ? ORDER BY(stamp) DESC LIMIT 1";
            $stmt = $db->create_stmt($conn,$query);
            if($type === "Reply")
                $stmt->bind_param("sss",$postid,$commentid,$type);
            if($type === "New")
                $stmt->bind_param("ss",$postid,$type);
            $stmt->execute(); 
            $result = $stmt->get_result();
            foreach($result as $comment)
            {
                if($type === "New")
                {
                    return $html->comments($comment);
                }
                else
                {
                    return $html->reply($comment);
                }
            }
        }

    }
    
    
    function send_comment($postid,$comment,$type,$parent)
    {
        require "requires.php";
        if($user->loggedin()&& $this->post_exists($postid) && !empty($comment) && !empty($type))
        {
            $postid = $db->sqlsafe($postid); $comment = strip_tags($comment);  $type = $db->sqlsafe($type);  $parent = $db->sqlsafe($parent);
            $query = "INSERT INTO MEMITY_COMMENT(Postid,Userid, Comment_user,Comment_text,Comment_id,Comment_type,Comment_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->create_stmt($conn,$query);
            $comment_id = '';
            if($type === 'New')
            {
                $comment_id = microtime() . '-New';
                $comment_id = str_replace(".","",$comment_id);
                $comment_id = str_replace(" ","",$comment_id);
            }
            if($type === 'Reply')
            {
                $comment_id = $parent;
            }
            $comment_date = date("m.d.y");
            $stmt->bind_param("sssssss",$postid,$_SESSION["userid"],$_SESSION["username"],$comment,$comment_id,$type,$comment_date);
            $db->db_store($stmt); 
            $notif->send_notification($postid,$this->post_author($postid),"comment");
        }
    }
     function init_vote($postid,$type)
     {
        require "requires.php";
         if($user->loggedin())
         {
            if(!empty($type) && !empty($type))
            {
               $postid = $db->sqlsafe($_POST["post"]); $type = $db->sqlsafe($_POST["type"]); 
               if($this->is_voted($postid,$_SESSION["username"]))
               {
                   $this->update_vote($postid,$type,$_SESSION["username"]);
               }
               else
               {    
                   $this-> write_vote($postid,$type,$_SESSION["username"]);

               }
            }
         }
     }
     function update_vote($postid,$type,$username)
     {
        require "requires.php";
        $stmt = $db->create_stmt($conn,"UPDATE MEMITY_VOTE SET Type = ? WHERE Postid =? AND Username = ?");
        $stmt->bind_param("sss",$type,$postid,$username);
        $db->db_store($stmt);
        $this->add_view($postid);
        $notif->send_notification($postid,$this->post_author($postid),$type);
     }
    function write_vote($postid,$type,$username)
    {
        require "requires.php";
        $stmt = $db->create_stmt($conn,"INSERT INTO MEMITY_VOTE(Postid,Username,Type) VALUES (?,?,?)");
        $stmt->bind_param("sss",$postid,$username,$type);
        $db->db_store($stmt); 
        $this->add_view($postid);
        $notif->send_notification($postid,$this->post_author($postid),$type);


     }
    function is_voted($postid,$username)
    {
        require "requires.php";
        $stmt = $db->create_stmt($conn,"SELECT Postid,Username FROM MEMITY_VOTE WHERE Postid = ? AND Username = ? LIMIT 1");
        $stmt->bind_param("ss",$postid,$username);$result = $db->db_count_exec($stmt);
        if($result > 0)
            return true;
        else
            return false;
    }
    function vote_total($postid,$type)
    {
        require "requires.php";
        if($this->post_exists($postid) && !empty($type) && $type === "Like" || $type === "Funny" || $type === "Love" || $type === "Angry")
        {
            $postid = $db->sqlsafe($postid);  $stmt = $db->create_stmt($conn,"SELECT Postid FROM MEMITY_VOTE WHERE Postid = ? AND Type = ?");
            $stmt->bind_param("ss",$postid,$type);  
            return $db->db_count_exec($stmt);         
        }
    }
    function comment_total($postid)
    {
        require "requires.php";
        if($this->post_exists($postid))
        {
            $stmt = $db->create_stmt($conn,"SELECT Postid FROM MEMITY_COMMENT WHERE Postid = ?");
            $stmt->bind_param("s",$postid); 
            return $db->db_count_exec($stmt);
        }
    }
    public function add_post($url,$text)
    {
        require "requires.php";
        if($user->loggedin() && !empty($url) && !empty($text))
        {
            $postid = md5(microtime());  $ext = $sys->get_ext($url); $text = strip_tags($text);
            $stmt = $db->create_stmt($conn,$query = "INSERT INTO MEMITY_POST (Userid,Postuser,Postid,Posturl,Posttext,Posttype) VALUES(?,?,?,?,?,?)");
            $stmt->bind_param("ssssss",$_SESSION["userid"],$_SESSION["username"],$postid,$url,$text,$ext);
            $db->db_store($stmt);
            header("Location:index.php?posted_successfully");
        }
    }
    public function top_post()
    {
        require "requires.php";
        $stmt = $db->create_stmt($conn,"SELECT Posturl,Postid,Posttext,Postview  FROM MEMITY_POST ORDER BY(Postview) DESC LIMIT 9");
        $stmt->execute(); $result = $stmt->get_result();
        foreach($result as $top)
        {
            $html->top_memes($result);
        }

    }
    public function add_view($postid)
    {
        require "requires.php";
        $num = $this->get_view($postid);
        $num= $num + 1;
        $stmt = $db->create_stmt($conn,"UPDATE MEMITY_POST SET Postview = ? WHERE Postid = ? LIMIT 1");
        $stmt->bind_param("is",$num,$postid);
        $db->db_store($stmt);
    }
    public function get_view($postid)
    {
        require "requires.php";
        $stmt = $db->create_stmt($conn,"SELECT Postview FROM MEMITY_POST  WHERE Postid = ? LIMIT 1");
        $stmt->bind_param("s",$postid); $stmt->execute(); 
        $result = $stmt->get_result();
        foreach($result as $view)
        {
            return $view["Postview"];
        }
    }
    public function post_author($postid)
    {
        require "requires.php";
        if($user->loggedin() && $this->post_exists($postid))
        {
            $stmt = $db->create_stmt($conn,"SELECT Userid FROM MEMITY_POST WHERE Postid = ? LIMIT 1");
            $stmt->bind_param("s",$postid); $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $author)
            {
                return $author["Userid"];
            }
        }
    }
    function split_vote($value)
    {
        $_k = explode("_",$value);
        return $_k[1];
    }
}

?>