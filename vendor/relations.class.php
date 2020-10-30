<?php 

class relations
{
	function set($b,$t)
	{
		require "requires.php";
		if($user->loggedin() && $t === 'Followed' && $user->user_exists($b))
		{
			$a = $db->sqlsafe($_SESSION["userid"]); $b = $db->sqlsafe($b);
			if($this->is_followed($a, $b,"Followed"))
			{
				$stmt = $db->create_stmt($conn,"DELETE FROM MEMITY_RELATIONS WHERE User_id_a = ? AND User_id_b = ? AND Type = ?");
                $stmt->bind_param("sss",$a,$b,$t);
                $db->db_store($stmt); exit();
			}
			else
			{
                $stmt = $db->create_stmt($conn,"INSERT INTO MEMITY_RELATIONS(User_id_a,User_id_b,Type) VALUES (?,?,?)");
                $stmt->bind_param("sss",$a,$b,$t);
                $db->db_store($stmt);
                $notif->send_notification("",$b,$t);
 			}

		}
	}
	function is_followed($a,$b,$t)
    {
        require "requires.php"; $a = $db->sqlsafe($a); $b = $db->sqlsafe($b);
        if($user->loggedin() && $t === "Followed" && $user->user_exists($a) && $user->user_exists($b))
        {
            $stmt = $db->create_stmt($conn,"SELECT User_id_a ,User_id_b,Type FROM MEMITY_RELATIONS WHERE User_id_a = ? AND User_id_b = ? AND Type = ?"); $stmt->bind_param("sss",$a,$b,$t); $result = $db->db_count_exec($stmt);
            if($result > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    function count_relations($userid)
    {
        require "requires.php"; $b = $db->sqlsafe($userid);
        $stmt = $db->create_stmt($conn,"SELECT * FROM MEMITY_RELATIONS WHERE User_id_b = ?");
        $stmt->bind_param("s",$b); 
        echo $userid;
        return $db->db_count_exec($stmt);
    }
    function get_relations($userid, $type)
    {
        require "requires.php";
     	$user_id = $db->sqlsafe($userid);
     	if($user->loggedin() && $user->user_exists($userid) && $type === 'followers' || $type === 'followings')
        {
            $select = '';
            if($type === "followers")
            {
                $type = "SELECT User_id_a FROM MEMITY_RELATIONS WHERE User_id_b = ?"; $select = "followers";
            }
            if($type === "followings")
            {
                $type = "SELECT User_id_b FROM MEMITY_RELATIONS WHERE User_id_a = ?"; $select = "followings";
            }
            $stmt = $db->create_stmt($conn,$type);
            $stmt->bind_param("s",$userid); $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $people)
            {
                if($select === "followers")
                    $html->show_followers($result);
                if ($select === "followings")
                    $html->show_followings($result);
            }
              
        }

    }
    function online_rel($now)
    {
        require "requires.php";
        if($user->loggedin())
        { 
            $stmt = $db->create_stmt($conn,"SELECT User_id_b FROM MEMITY_RELATIONS WHERE User_id_a = ?");
            $stmt->bind_param("s",$_SESSION["userid"]); $stmt->execute(); $all = $stmt->get_result();
            foreach($all as $followings)
            {
                $status = "Online";
                $stmt = $db->create_stmt($conn,"SELECT Userid FROM MEMITY_STATUS WHERE Userid = ? AND Status = ?");
                $stmt->bind_param("ss",$followings["User_id_b"],$status); $stmt->execute(); $result = $stmt->get_result();
                foreach($result as $online)
                {
                    $followings = array_merge($online,$followings);
                    echo $html->online_friends($followings);
                }

            }
        }
    } 
}

?>