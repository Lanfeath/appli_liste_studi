<?php
include "../functions.php";
include "../var.php";

 // insert which DB to connect to
 $dbtable="bdd_task";

 
 if(isset($_POST['task_name']))
 {
 
    $my_Db_Connection= db_connect($servername,$db_to_use,$db_username,$db_password);
        
    // create an array of all the POST variables you want to use
    $fields = array('task_name', 'list_name','task_create_by','task_description','task_responsible', 'task_status','task_end_date','task_important','task_comment');

    // prepare SQL statement and bind values    
    $stm_insert = $my_Db_Connection->prepare(query_insert($dbtable,$fields)) ;
    $createur="createur";

    $count=1;
    foreach($fields as $field){
        if($field==="task_create_by"){
            $stm_insert ->bindParam($count,$createur);
        }
        else{
            $stm_insert ->bindParam($count, $_POST[$field]);
        }
        $count++;
    }

    $stm_insert->execute();
    
    header('Location: ../../../pages/nouvel_element/nouvel_element.html?result_task=success');

 }
 else
 {
    header('../../../pages/nouvel_element/nouvel_element.html?result_task=error');

 }


 ?> 

