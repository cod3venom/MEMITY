 <?php 
 
 class system
 {
    public function get_ext($file)
    {
        $last_dot = substr_count($file,".");
        $ext = explode(".", $file);
        return $ext[$last_dot];
    }
    public function folder($value)
    {
        return dirname(__DIR__) .$value;
    }
    public function whitelist($file)
    {
        $data=false;
        if(!empty($file))
        {
            
            if (strpos($file, 'php') !== false) {
                $data = false;
            }
            $last_dot = substr_count($file,".");
            $ext = explode(".", $file);
            if($ext[$last_dot] === 'jpeg')
                $data= true;
            if($ext[$last_dot] === 'jpg')
                $data= true;
            if($ext[$last_dot] === 'png')
                $data = true;
            if($ext[$last_dot] === 'gif')
                $data= true;
            if($ext[$last_dot] === 'mp4')
                $data= true;
        }
        return $data;
    }
     
    public function memes_dir()
    {
        $dir = "memes/";
        if(!is_dir($dir))
        {
            mkdir($dir);
        }
        return $dir;
    }
    public function file_name($file)
    {
        $file_name = basename(microtime().".".$this->get_ext($file["name"]));
        return $this->memes_dir(). $file_name;
    }
    public function upload_image($file,$text)
    {
        require "requires.php";
        if($user->loggedin() && $this->whitelist($file["name"]))
        {
            $newname = $this->file_name($file); $newname = str_replace(" ", "", $newname);
            if(move_uploaded_file($file["tmp_name"],$newname))
            {
                require "requires.php";
                $post->add_post($newname,$text);
                header("Location:".homepage);
            }
        }
        else
        {
            header("Location:".homepage."?extension_notallowed");
        }
    }
 }
 
 ?>