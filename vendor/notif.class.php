<?php 

class notif
{
	function send_notification($postid,$target,$t)//b = receiver
    {
        require "requires.php"; $target = $db->sqlsafe($target);  $t = $db->sqlsafe($t);
        if($user->loggedin()&& $t === "Followed"|| $t === "Like"||$t === "Funny"||$t === "Love"||$t === "Angry"||$t === "comment"&&$_SESSION["userid"]!==$target)
        {
            $text = $this->get_description($t);
            if(!empty($postid))
            {
                $stmt= $db->create_stmt($conn,"INSERT INTO MEMITY_NOTIFICATION(Postid,User_id_a,User_id_b,Data,Type) VALUES (?,?,?,?,?) ");
                $stmt->bind_param("sssss",$postid,$_SESSION["userid"],$target,$text,$t); $db->db_store($stmt);
            } 
            else
            {
                $stmt= $db->create_stmt($conn,"INSERT INTO MEMITY_NOTIFICATION(User_id_a,User_id_b,Data,Type) VALUES (?,?,?,?) ");
                $stmt->bind_param("ssss",$_SESSION["userid"],$target,$text,$t); $db->db_store($stmt);
            }
        }
    }
    function get_description($t)
    {
        if($t === 'Followed')
        {
             $t = 'Follows you';
        }
        else if($t ===  "Like"||$t === "Funny"||$t === "Love"||$t === "Angry")
        {
            $t = 'Reacted '.$t. ' on your post';
        }
        else if($t === 'comment')
        {
            $t = 'Commented on your post';
        }
        return $t; 
    }
    function get_notification()
    {
        require "requires.php";
        if($user->loggedin())
        {
            $query = "SELECT * FROM MEMITY_NOTIFICATION WHERE User_id_b = ?  ORDER BY(ID) DESC LIMIT 10";
            $stmt = $db->create_stmt($conn,$query);
            $stmt->bind_param("s",$_SESSION["userid"]);
            $stmt->execute();
            $result = $stmt->get_result();
             foreach($result as $ntf)
            {
                echo $this->load_notification($ntf);    
            }
         }
    }
    function load_notification($ntf)
    {
        require "requires.php";
        $t = $ntf["Type"]; $data = $ntf["Data"]; 
        $box = $html->load_html("notif");

        $box = str_replace("NOTIF_IMG++;", $user->user_selector("Avatar",$ntf["User_id_a"]),$box);
        $box = str_replace("NOTIF_LINK++;", '?profile='.$ntf["User_id_a"],$box);
        $box = str_replace("NOTIF_USER++;", $user->user_selector("Username",$ntf["User_id_a"]),$box);
        if($t === "Like" || $t === "Funny" || $t === "Love" ||$t === "Angry")
        {
            $box = str_replace("NOTIF_TYPE++;", 'Reacted <img src="static/img/'.$t.'.png"> on your <a href="?details='.$ntf["Postid"].'">post</a>',$box);
        }
        if($t === "Followed")
        {
            $box = str_replace("NOTIF_TYPE++;", 'Follows  you',$box);
        }
        if($t === 'comment')
        {
            $box = str_replace("NOTIF_TYPE++;", 'Commented <img src="static/img/'.$t.'.png"> on your <a href="?details='.$ntf["Postid"].'">post</a>',$box);
        }
        return $box;
    }
}

?>