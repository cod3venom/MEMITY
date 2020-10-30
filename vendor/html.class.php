<?php 

class html
{
    public function ascii_logo()
    {
        $html = '<html> <!--
         __       __  ________  __       __  ______  ________  __      __ 
        |  \     /  \|        \|  \     /  \|      \|        \|  \    /  \
        | $$\   /  $$| $$$$$$$$| $$\   /  $$ \$$$$$$ \$$$$$$$$ \$$\  /  $$
        | $$$\ /  $$$| $$__    | $$$\ /  $$$  | $$     | $$     \$$\/  $$ 
        | $$$$\  $$$$| $$  \   | $$$$\  $$$$  | $$     | $$      \$$  $$  
        | $$\$$ $$ $$| $$$$$   | $$\$$ $$ $$  | $$     | $$       \$$$$   
        | $$ \$$$| $$| $$_____ | $$ \$$$| $$ _| $$_    | $$       | $$    
        | $$  \$ | $$| $$     \| $$  \$ | $$|   $$ \   | $$       | $$    
         \$$      \$$ \$$$$$$$$ \$$      \$$ \$$$$$$    \$$        \$$-->
        ';
        return $html;
    }
     
     
    
    public function load_index()
    {
        $html = $this->load_html("index"); 
        $html = str_replace("MY_ID++;",$_SESSION["userid"], $html);
        $html = str_replace("MY_AVATAR++;",$_SESSION["avatar"], $html);
        $html = str_replace("ID++;",$_SESSION["userid"], $html);
        return $html;
    }
     
    public function load_login_page()
    {
        $html = $this->load_html("login");
        return $html;
    }
    public function post_html($meme)
    {
        require "requires.php";
        $extension = $sys->get_ext($meme["Posturl"]);
        $avatar = $user->user_selector("Avatar",$meme["Userid"]);

        $html = $this->load_html("post");
        $html = str_replace("POST_ID++;",$meme["Postid"],$html);
        $html = str_replace("POST_AVATAR++;",$avatar,$html);
        $html = str_replace("USER_LINK++;",$meme["Userid"],$html);
        $html = str_replace("POST_USER++;",$meme["Postuser"],$html);
        $html = str_replace("POST_DATE++;",$meme["Postdate"],$html);
        $html = str_replace("POST_TEXT++;",$meme["Posttext"],$html);
        
        $html = str_replace("COMMENTS++;", $meme["Comment_num"],$html);
        $html = str_replace("LIKES++;",$meme["Like"],$html);
        $html = str_replace("FUNNYS++;",$meme["Funny"],$html);
        $html = str_replace("LOVES++;",$meme["Love"],$html);
        $html = str_replace("ANGRYS++;",$meme["Angry"],$html);
        if($extension === "mp4" || $extension === "webm")
        {
            $video =$video = '<video controls><source src="'.$meme["Posturl"].'" type="video/mp4"></video>';
            $html = str_replace("POST_MEME++;",$video,$html);
        }
        if($extension === "png" || $extension === "jpg" || $extension === "jpeg" || $extension === "gif")
        {
            $html = str_replace("POST_MEME++;",'<img src="'.$meme["Posturl"].'">',$html);
        }
        
        return $html.$this->vote_js(array("Postid" => $meme["Postid"]));
    }
    public function load_profile($id)
    {
        require "requires.php";
        $avatar = $user->user_selector("Avatar",$id);
        $username = $user->user_selector("Username",$id);
        
        $html = $this->load_html("index_profile");
        $html = str_replace("MY_AVATAR++;", $_SESSION["avatar"],$html);
        $html = str_replace("MY_ID++;", $_SESSION["userid"],$html);  
        $html = str_replace("PROFILE_AVATAR++;", $avatar, $html);
         if($_SESSION["userid"] === $id)
        {
            $html = str_replace("FOLLOW_BTN++;", "Settings", $html);
            $html = str_replace("BTNTYPE++;", "Settings", $html);
        }
        else if($rel->is_followed($_SESSION["userid"],$id,"Followed"))
        {
            $html = str_replace("FOLLOW_BTN++;", "Unfollow", $html);
            $html = str_replace("BTNTYPE++;", "Relation", $html);
        }
        else if($rel->is_followed($_SESSION["userid"],$id,"Followed") === false)
        {
            $html = str_replace("FOLLOW_BTN++;", "Follow", $html);
            $html = str_replace("BTNTYPE++;", "Relation", $html);
        }
        $html = str_replace("PROFILE_AVATAR++;",$avatar, $html);
        $html = str_replace("PROFILE_USER++;",$username, $html);
        $html = str_replace("ID++;",$id, $html);
        

        echo $html;
     }
    public function comments($comment)
    {
        require "requires.php";
        $output = '
        <div class="comment_box '.$comment["Comment_ID"].'">
           <div class="comment_user flex">
               <div class="comment_avatar">
                   <a href="?profile=USERID++;"><img src="'.$user->user_selector("Avatar",$comment["Userid"]).'"></a>
                   <a href="?profile='.$comment["Userid"].'">'.$comment["Comment_user"].'</a>
               </div>
           </div>
           <div class="comment_text flex">
               <span>'.$comment["Comment_text"].'<button id="replybtn_'.$comment["Comment_ID"].'" class="reply_btn transp background"></button></span>
               <span class="comment_date">'.$comment["Comment_date"].'</span>
           </div>
           <form  id="'.$comment["Comment_ID"].'"  class="reply_form" class="flex">
               <input type="hidden" value="'.$comment["Postid"].'" name="post">
               <input type="hidden" value="'.$comment["Comment_ID"].'" name="parent">
                        
               <textarea  id="comment_input_'.$comment["Comment_ID"].'"  wrap="soft" form="'.$comment["Comment_ID"].'" name="comment" placeholder="Write comment"></textarea>
               <div id="input_switch">
                    <button id="submitrepcom" type="submit" class="background transp"><img src="static/img/send.png"></button>
               </div>
           </form>
        </div>
        ';
        $js = '
        <script>
            $("#replybtn_'.$comment["Comment_ID"].'").click(function(){
                $("#'.$comment["Comment_ID"].'").slideDown("300");
            });
            $("#'.$comment["Comment_ID"].'").submit(function(e){
               e.preventDefault();
               $.ajax({
                   type:"POST",url:"operator.php",data:$(this).serialize(),cache:false,success:function(__k)
                   {
                       list();
                       $("#comment_input_'.$comment["Comment_ID"].'").text("");
                   }
               })
           });
           $("#comment_input_'.$comment["Comment_ID"].'").click(function(){
            var inp  = document.getElementById("comment_input_'.$comment["Comment_ID"].'");
            inp.oninput = function(){
                inp.style.height = "";
                inp.style.height = Math.min(inp.scrollHeight) +30 + "px";
            };
           });
             
        </script>
       ';
        return $output.$js;
    }
    public function reply($comment)
    {
          require "requires.php";
        $output = '
        <div class="comment_box reply" style="padding-left:2rem;">
            <div class="comment_user flex">
                <div class="comment_avatar">
                    <a href="?profile='.$comment["Userid"].'"><img src="'.$user->user_selector("Avatar",$comment["Userid"]).'"></a>
                    <a href="?profile='.$comment["Userid"].'">'.$comment["Comment_user"].'</a>
                </div>
            </div>
            <div class="comment_text flex">
                <span>'.$comment["Comment_text"].'</span>
                <span class="comment_date">'.$comment["Comment_date"].'</span>
            </div>
        </div>
        ';
        //$output = '<p style="padding-left:1rem;"><img src="static/img/star.png" style="height:35px; border-radius-50%;">'.$comment["Comment_user"].' <span>'.$comment["Comment_text"].'</span></p>';
        return $output;
    }
    public function vote_js($meme)
    {
        $js =  $js = '<script>
        $(document).ready(function(){
            $("#'.$meme["Postid"].'_like_emotion_up").click(function(){
                __ks("post='.$meme["Postid"].'&type=Like", "Like");
                __ks("total='.$meme["Postid"].'&type=Angry","Angry");
                __ks("total='.$meme["Postid"].'&type=Love","Love");
                __ks("total='.$meme["Postid"].'&type=Funny","Funny");  
                __ks("total='.$meme["Postid"].'&type=Like","Like");
                 
            });
            $("#'.$meme["Postid"].'_funny_emotion_up").click(function(){
                __ks("post='.$meme["Postid"].'&type=Funny","Funny");

                __ks("total='.$meme["Postid"].'&type=Funny","Funny");
                __ks("total='.$meme["Postid"].'&type=Like","Like");
                __ks("total='.$meme["Postid"].'&type=Angry","Angry");
                __ks("total='.$meme["Postid"].'&type=Love","Love"); 
                 
                 
            });
            $("#'.$meme["Postid"].'_love_emotion_up").click(function(){
                __ks("post='.$meme["Postid"].'&type=Love","Love");
                
                __ks("total='.$meme["Postid"].'&type=Love","Love");
                __ks("total='.$meme["Postid"].'&type=Funny","Funny");
                __ks("total='.$meme["Postid"].'&type=Like","Like"); 
                __ks("total='.$meme["Postid"].'&type=Angry","Angry");
                 
            });
            $("#'.$meme["Postid"].'_angry_emotion_up").click(function(){
                __ks("post='.$meme["Postid"].'&type=Angry","Angry");

                __ks("total='.$meme["Postid"].'&type=Funny","Funny");  
                __ks("total='.$meme["Postid"].'&type=Angry","Angry");
                __ks("total='.$meme["Postid"].'&type=Like","Like");
                __ks("total='.$meme["Postid"].'&type=Love","Love");
                 
            });
            function __ks(__Sx,__0x1)
            {
                $.ajax({
                    type:"POST",url:"operator.php",data:__Sx,cache:false,async:true,success:function(_z3r0p)
                    {
                        if(__0x1 === "Like")
                        {   
                            if(_z3r0p ==="")
                                $("#liketotal_'.$meme["Postid"].'").text("0");
                            else
                                $("#liketotal_'.$meme["Postid"].'").text(_z3r0p);
                        }
                        if(__0x1 === "Funny")
                        {
                            if(_z3r0p =="")
                                $("#funnytotal_'.$meme["Postid"].'").text("0");
                            else
                                $("#funnytotal_'.$meme["Postid"].'").text(_z3r0p);
                        }
                        if(__0x1 === "Love")
                        {
                            if(_z3r0p ==="")
                                 $("#lovetotal_'.$meme["Postid"].'").text("0");
                            else
                                $("#lovetotal_'.$meme["Postid"].'").text(_z3r0p);
                        }
                        if(__0x1 === "Angry")
                        {
                            if(_z3r0p ==="")
                                $("#angrytotal_'.$meme["Postid"].'").text("0");
                            else
                                $("#angrytotal_'.$meme["Postid"].'").text(_z3r0p);
                        }
                        console.log(_z3r0p);

                    }
                });
            }
        });
      </script>
     ';
     return $js;
    }
    public function top_memes($data)
    {
        require "requires.php";
        foreach($data as $top)
        {
            $html = $this->load_html("top");
            $extension = $sys->get_ext($top["Posturl"]);
            if($extension === "mp4" || $extension === "webm")
            {
                $video = '<video autoplay muted><source src="'.$top["Posturl"].'" type="video/mp4" </video>';
                $html = str_replace("TOP_IMG++;",$video,$html);
            }
            if($extension === "png" || $extension === "jpg" || $extension === "jpeg" || $extension === "gif")
            {
                $html = str_replace("TOP_IMG++;",'<img src="'.$top["Posturl"].'">',$html);
            }
            $html = str_replace("TOP_TITLE++;",$top["Posttext"],$html);
            $html = str_replace("TOP_URL++;","?details=".$top["Postid"],$html);
            $html = str_replace("ID++;",$_SESSION["userid"],$html);
            echo $html;
        }
    }
    public function show_followers($data)
    {
        require "requires.php";
        foreach($data as $people)
        {
            //$followed = $this->follow_unfollow($_SESSION["userid"], $people["User_id_b"])
            $list = $html->load_html("followers");
            $list = str_replace("PROFILE_AVATAR++;", $user->user_selector("avatar",$people["User_id_a"]), $list);
            $list = str_replace("PROFILE_ID++;", $people["User_id_a"], $list);
            $list = str_replace("PROFILE_NAME++;", $user->user_selector("Username",$people["User_id_a"]), $list);
            $list = $this->follow_unfollow($list,$people["User_id_a"],$_SESSION["userid"]);
            echo $list;
        }
    }
    public function show_followings($data)
    {
        require "requires.php";
        foreach($data as $people)
        {
            //$followed = $this->follow_unfollow($_SESSION["userid"], $people["User_id_b"])
            $list = $html->load_html("followers");
            $list = str_replace("PROFILE_AVATAR++;", $user->user_selector("avatar",$people["User_id_b"]), $list);
            $list = str_replace("PROFILE_ID++;", $people["User_id_b"], $list);
            $list = str_replace("PROFILE_NAME++;", $user->user_selector("Username",$people["User_id_b"]), $list);
            $list = $this->follow_unfollow($list,$_SESSION["userid"],$people["User_id_b"]);
            echo $list;
        }
    }
    public function follow_unfollow($list,$user1,$user2)
    {   
        require "requires.php";
       if($rel->is_followed($user1,$user2,"Followed") === true)
        {
          $list = str_replace("FOLLOW_BTN++;", "Unfollow", $list);
          $list = str_replace("BTNTYPE++;", "Relation", $list);
        }
        else if($rel->is_followed($user1,$user2,"Followed") === false)
        {
          $list = str_replace("FOLLOW_BTN++;", "Follow", $list);
          $list = str_replace("BTNTYPE++;", "Relation", $list);
        }
        else
        {
            $list = str_replace("FOLLOW_BTN++;", "Follow", $list);
            $list = str_replace("BTNTYPE++;", "Relation", $list);
        }
        return $list;
    }
    public function online_friends($friend)
    {
        require "requires.php";
        $html = $this->load_html("followers");
        $html = str_replace("PROFILE_AVATAR++;", $user->user_selector("avatar",$friend["User_id_b"]), $html);
        $html = str_replace("PROFILE_ID++;", $friend["User_id_b"], $html);
        $html = str_replace("PROFILE_NAME++;", $user->user_selector("Username",$friend["User_id_b"]), $html);
        $html = str_replace("FOLLOW_BTN++;", "Unfollow", $html);
        $html = str_replace("BTNTYPE++;", "Relation", $html);
        return $html;
    }
    public function settings()
    {
        require "requires.php";
        if($user->loggedin())
        {
            $html = $this->load_html("settings");
            $html = str_replace("PROFILE_AVATAR++;", $_SESSION["avatar"], $html);
            return $html;
        }
    }
    public function load_html($name)
    {
        $path = "static/html/".$name.".html";
        if(file_exists($path))
        {
            return file_get_contents($path);
        }
    }
}


?>