<?php 

class user
{
    function signup($username,$password,$repeat, $email)
    {
        require "requires.php";
        if(!$this->loggedin())
        {
            if(!empty($email) && !empty($username) && !empty($password) && !empty($repeat && $password === $repeat))
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9]*$/", $username))
                {
                    $username = $db->sqlsafe($username);$password = $db->sqlsafe($password); $repeat = $db->Sqlsafe($repeat); $email = $db->sqlsafe($email);
                    $query = "SELECT Email FROM MEMITY_ACCOUNT WHERE Email = ?";
                    $stmt = $db->create_stmt($conn,$query);
                    $stmt->bind_param("s",$email);
                    if($db->db_count_exec($stmt) > 0)
                    {
                        echo "2";
                        exit();
                    }
                    else
                    {
                        $this->write_user($username,$password,$repeat, $email);
                    }
                }
                else
                {
                   echo "3"; 
                }
            }
            else
            {
                echo "4";
            }
        }
    }
    function signin($email,$password)
    {
        require "requires.php";
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            echo "5"; exit();
        }
        if(!$this->loggedin() && !empty($email) && !empty($password) )
        {
            $email = $db->sqlsafe($email); $password = $db->sqlsafe($password);
            $stmt = $db->create_stmt($conn,"SELECT * FROM MEMITY_ACCOUNT WHERE Email = ?");
            $stmt->bind_param("s",$email); $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $account)
            {
                if($email !== $account["Email"])
                {
                    echo "5"; exit();
                }
                if(password_verify($password,$account["Password"]))
                {
                    $this->default_session($email,$account["Username"],$account["AVATAR"],$account["Country"],$account["Userid"]);
                }
                else
                {
                    echo "5"; exit();
                }
            }
        }
        else
        {
            echo "4"; exit();
        }
    }
    function write_user($username,$password,$repeat, $email)
    {
        require "requires.php";
        if(!empty($email) && !empty($username) && !empty($password) && !empty($repeat && $password === $repeat))
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9]*$/", $username))
            {
                $stmt = $db->create_stmt($conn,"INSERT INTO MEMITY_ACCOUNT(Email,Userid,Username,Password,Country,Avatar) VALUES (?,?,?,?,?,?)");
                $userid = $db->sqlsafe(password_hash($email.microtime(), PASSWORD_BCRYPT));
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bind_param("ssssss",$email,$userid,$username,$password,$country,$avatar);
                $db->db_store($stmt);
                $this->default_session($email,$username,$avatar,$country,$userid);
            }
        }
    }
    function default_session($email,$username,$avatar,$country,$userid)
    {
        require "requires.php";
        if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($username) && !empty($avatar) && !empty($country) && !empty($userid))
        {
            $this->new_session("email",$email);
            $this->new_session("username",$username);
            $this->new_session("avatar",$avatar);
            $this->new_session("country",$country);
            $this->new_session("userid", $userid);
            echo "1";
        }
    }
    function user_selector($type,$userid)
    {
        require "requires.php";
        if($this->loggedin() && $type !== "Password" || $type !== "password")
        {
            
            $query = "SELECT $type FROM MEMITY_ACCOUNT WHERE Userid = ? LIMIT 1";
            $stmt = $db->create_stmt($conn,$query);
            $stmt->bind_param("s",$userid);
            $stmt->execute();
            $data = $stmt->get_result();
            if($profile = mysqli_fetch_assoc($data))
            {
                return $profile[$type];
            }
            
        }
    }
    function user_by_mail($email)
    {
        require "requires.php";
        $email = $db->sqlsafe($email);
        $stmt = $db->create_stmt($conn,"SELECT Email FROM MEMITY_ACCOUNT WHERE Email = ? LIMIT 1");
        $stmt->bind_param("s",$email);
        if($db->db_count_exec($stmt) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function user_exists($id)
    {
        require "requires.php";
        $id = $db->sqlsafe($id);
        $stmt = $db->create_stmt($conn,"SELECT Userid FROM MEMITY_ACCOUNT WHERE Userid = ? LIMIT 1");
        $stmt->bind_param("s",$id);
        if($db->db_count_exec($stmt) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function update_avatar($avatar)
    {
        require "requires.php";
        if($this->loggedin() && !empty($avatar))
        {
            $stmt = $db->create_stmt($conn,"UPDATE MEMITY_ACCOUNT SET Avatar = ? WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("ss",$avatar, $_SESSION["userid"]); $db->db_store($stmt);
        }
    }
    function update_user($username)
    {
        require "requires.php";
        if($this->loggedin() && !empty($username))
        {
            $username = $db->sqlsafe($username);
            $stmt = $db->create_stmt($conn,"UPDATE MEMITY_ACCOUNT SET Username = ? WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("ss",$username, $_SESSION["userid"]); $db->db_store($stmt);
        }
    }
    function update_email($email)
    {
        require "requires.php";
        if($this->loggedin() && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email = $db->sqlsafe($email);
            $stmt = $db->create_stmt($conn,"UPDATE MEMITY_ACCOUNT SET Email = ? WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("ss",$email, $_SESSION["userid"]); $db->db_store($stmt);
        }
    }
    function update_pwd($pwd)
    {
        require "requires.php";
        if($this->loggedin() && !empty($pwd))
        {
            $pwd = $db->sqlsafe($pwd); $pwd = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt = $db->create_stmt($conn,"UPDATE MEMITY_ACCOUNT SET Password = ? WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("ss",$pwd, $_SESSION["userid"]); $db->db_store($stmt);
        }
    }
    function init_status($status,$time)
    {
        require "requires.php";
        if($user->loggedin()&& $status === 'Online' || $status === 'Offline')
        {
            $stmt = $db->create_stmt($conn,"SELECT Userid FROM MEMITY_STATUS WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("s",$_SESSION["userid"]);
            $result = $db->db_count_exec($stmt);
            if($result>0)
            {
                $this->update_status($status,$time);
            }
            else
            {
                $this->write_status($status,$time);
            }
        }
    }
    function update_status($status,$time)
    {
        require "requires.php";
        if($user->loggedin()&& $status === 'Online' || $status === 'Offline')
        {
            $stmt = $db->create_stmt($conn,"UPDATE MEMITY_STATUS SET Status = ? ,  Date = ?  WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("sss",$status,$time,$_SESSION["userid"]); 
            $db->db_store($stmt);
            echo $this->get_status();
        }
    }
    function write_status($status,$time)
    {
        require "requires.php";
        if($user->loggedin()&& $status === 'Online' || $status === 'Offline')
        {
            $stmt = $db->create_stmt($conn,"INSERT INTO MEMITY_STATUS(Userid,Status,Date) VALUES (?,?,?)");
            $stmt->bind_param("sss",$_SESSION["userid"],$status,$time); $db->db_store($stmt);
            echo $this->get_status();
        }
    }
    function get_status()
    {
        require "requires.php";
        if($user->loggedin())
        {
            $stmt = $db->create_stmt($conn,"SELECT Date FROM MEMITY_STATUS WHERE Userid = ? LIMIT 1");
            $stmt->bind_param("s",$_SESSION["userid"]); $stmt->execute(); $result = $stmt->get_result();
            foreach($result as $interval)
            {
                return $interval["Date"];
            }
        }
    }
    function no_php($value)
    {
        $value = str_replace("<php","",$value);
        $value = str_replace("?>","",$value);
        $value = str_replace("<","",$value);
        $value = str_replace("system(","",$value);
        $value = str_replace("(","",$value);
        $value = str_replace("cmd","",$value);
        return $value;
    }
    function _session_start()
    {
        if(session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }
    function new_session($value1,$value2)
    {
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            $_SESSION[$value1] = $this->no_php($value2);
        }
    }
    function loggedin()
    {
        if(isset($_SESSION["username"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function logout()
    {
        if($this->loggedin())
        {
            require "requires.php";
            session_destroy();
            session_unset();
            $this->init_status("Offline",date("H:m:s"));
         }
         Header("Location:".homepage);
    }
}

?>