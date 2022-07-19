<?php
(session_status() === PHP_SESSION_NONE)?session_start():""; //check if session active otherwise activate it

include "../functions.php";
include "../var.php";

 // insert which DB to connect to
 $dbtable="bdd_lists";

 if(isset($_POST['list_name']))
 {
    $my_Db_Connection= db_connect($servername,$db_to_use,$db_username,$db_password);
    
            
    try{
        // verification of value not in DB to allow the writing
        $query_check= $my_Db_Connection->prepare("SELECT list_name
        from bdd_lists
        where
        list_name = :list_name");
        $query_check ->bindParam(":list_name", $_POST["list_name"]);

        $query_check->execute();
        $answers = $query_check->fetch();

        if($answers) 
        {
            //this name already exist
            header('Location: ../../../pages/nouvel_element/nouvel_element.html?result_list=name');
        }
    }
    catch (PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    try{
        // create an array of all the POST variables you want to use
        $fields = array('list_name');

        // prepare SQL statement and bind values    
        $stm_insert = $my_Db_Connection->prepare(query_insert($dbtable,$fields)) ;

        $count=1;
        foreach($fields as $field){
            $stm_insert ->bindParam($count, $_POST[$field]);
            $count++;
        }

        $stm_insert->execute();

        //retrieve the existing list 
        $array_list= list_retrieve($my_Db_Connection);

        $_SESSION["team_members"]=$array_team;
        $_SESSION["list_name"]=$array_list;

        var_dump($array_list);
        include("../var.php"); //used to insert new value with session active

        header('Location: ../../../pages/nouvel_element/nouvel_element.html?result_list=success');
    }
    catch (PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }
 }
 else
 {
    echo "is not set";
    header("../../../pages/nouvel_element/nouvel_element.html?result_list=error");
 }


 ?> 

