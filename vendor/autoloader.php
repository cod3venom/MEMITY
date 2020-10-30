<?php 

spl_autoload_register("myAutoLoader");
function myAutoLoader($className)
{
	$file = "vendor/".$className.".class.php";
	if(file_exists($file))
	{
		require_once $file;
	}
 }
?>