<?php

$rel = new relations();
$notif = new notif();
$sys = new system();
$html = new html();
$user = new user();
$post = new post();
$db = new db();
if(!defined("mysqlhost"))
{
    define("mysqlhost","localhost");
}
if(!defined("mysqluser"))
{
    define("mysqluser","root");
}
if(!defined("mysqlpwd"))
{
    define("mysqlpwd","");
}
if(!defined("mysqldb"))
{
    define("mysqldb","MEMITY");
}
if(!defined("homepage"))
{
    define("homepage","http://localhost/meme/");
}
$conn = mysqli_connect(mysqlhost,mysqluser,mysqlpwd,mysqldb);
if(!$conn)
{
   echo "cant connect";
   exit();
}
$avatar = "https://avatarfiles.alphacoders.com/171/thumb-171412.png";
$country = "PL";
$max_text = 400;
?>