<?php 

class db
{
    public function sqlsafe($value)
    {
        $value = str_replace(' ', '-', $value);
        // $value = preg_replace('/[^A-Za-z0-9\-]/', '', $value);
         $value = str_replace("'",'',$value);
         $value = str_replace('"', '',$value);
         $value = str_replace('`','',$value);
         $value = str_replace('\\','',$value);
         $value = str_replace('/','',$value);
         $value = str_replace('<','',$value);
         $value = str_replace('<script>','',$value);
         $value = strip_tags($value);
         return $value;
    }
    function create_stmt($conn,$query)
    {
        if($conn&&!empty($query))
       {
           $stmt = mysqli_stmt_init($conn);
           if(!$stmt->prepare($query))
           {
               echo mysqli_stmt_error($stmt);
               exit();
           }
           $stmt->prepare($query);
           return $stmt;
       }
   }
   function db_count_exec($stmt)
   {
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows();
   }
   function db_store($stmt)
   {
       $stmt->execute();
       $stmt->store_result();
   }
}

?>