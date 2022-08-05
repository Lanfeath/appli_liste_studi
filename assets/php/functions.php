<?php
include_once "var.php";

// function for connection to Database
function db_connect($servername,$database1,$username,$password)
{
    $sql = "mysql:host=$servername;dbname=$database1;";
    
    $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    try { 
        $my_Db_Connection = new PDO($sql, $username, $password, $dsn_Options);
       // echo "Connected successfully <br>";
    } catch (PDOException $error) {
       // echo 'Connection error: ' . $error->getMessage();
        die('could not connect to database');
    }

    return $my_Db_Connection;
}

// function for placeholders creation for query prepare
function placeholders($text, $count=0, $separator=",")
{
    $result = array();
    if($count > 0){
        for($x=0; $x<$count; $x++){
            $result[] = $text ;
        }
    }

    return implode($separator, $result);
}


function query_insert(string $table, array $datafields)
{

    $question_marks[] = '('  . placeholders('?', sizeof($datafields)) . ')';

    $query = "INSERT INTO $table (" . implode(",", $datafields ) . ") VALUES " .
    implode(',', $question_marks);

    return $query;
}

function query_select_where(string $table, array $datafields)
{

    $question_marks[] = placeholders('?', sizeof($datafields));

    $query = "SELECT " . implode(",", $datafields ) . " FROM $table WHERE " .
    implode(',', $question_marks);

    return $query;
}


function db_dump_list($db_name,$list_name)
{
    // function to delete the list from DB
    try{
        $delete = 'DELETE FROM bdd_lists WHERE list_name = :list_name';
        $stmt = $db_name->prepare($delete);
        $stmt->bindValue(":list_name",$list_name);
        $stmt->execute();

        $message="la liste ".$list_name." a été supprimée";
     }
    catch(PDOException $error)
    {
        echo 'Connection error: ' . $error->getMessage() ."<br>";
        die();
    }

    return $message;
}

function db_dump_task($db_name,$task_name)
{
    // function to delete the task from DB
    try{
        $delete = 'DELETE FROM bdd_task WHERE task_name = :task_name';
        $stmt = $db_name->prepare($delete);
        $stmt->bindValue(":task_name",$task_name);
        $stmt->execute();

        $message="la tâche ".$task_name." a été supprimée";
     }
    catch(PDOException $error)
    {
        echo 'Connection error: ' . $error->getMessage() ."<br>";
        die();
    }

    return $message;
}

function list_retrieve($my_Db_Connection){
// récupérer les noms des liste existantes dans une variable
    $array_list=["Aucune"];

    $sql_list = "SELECT list_name FROM bdd_lists";
    foreach ($my_Db_Connection->query($sql_list) as $list) {
            $array_list[]=$list["list_name"];
    }
    return $array_list;
}

function task_list_retrieve($my_Db_Connection, $list_name){
    // récupérer les noms des tâches par liste existante dans une variable
        $array_task_list=[];

        if ($list_name===""){
            $sql_task = 'SELECT task_name FROM bdd_task';
        }
        else{
            $sql_task = 'SELECT task_name FROM bdd_task WHERE list_name="'.$list_name.'"';
        }
        
        foreach ($my_Db_Connection->query($sql_task) as $task) {
                $array_task_list[]=$task["task_name"];
        }
        return $array_task_list;
    }

function list_info_retrieve($my_Db_Connection, $list_name){
    // récupérer les noms des tâches par liste existante dans une variable
        $array_list=[];

        if ($list_name===""){
            $sql_list = 'SELECT list_name, list_description FROM bdd_lists';
        }
        else{
        $sql_list = 'SELECT list_name, list_description FROM bdd_lists WHERE list_name="'.$list_name.'"';
        }

        foreach ($my_Db_Connection->query($sql_list) as $list) {
                $array_list=array("list_description" => $list["list_description"]);
        }
        
        return $array_list;
    }

function task_info_retrieve($my_Db_Connection, $list_name){
    // récupérer les noms des tâches par liste existante dans une variable
        $array_task=[];

        if ($list_name===""){
            $sql_task = 'SELECT list_name, task_name, task_create_date, task_create_by, task_description, task_responsible, task_end_date, task_important, task_status,task_comment FROM bdd_task';
        }
        else{
        $sql_task = 'SELECT list_name, task_name, task_create_date, task_create_by, task_description, task_responsible, task_end_date, task_important, task_status,task_comment FROM bdd_task WHERE list_name="'.$list_name.'"';
        }

        foreach ($my_Db_Connection->query($sql_task) as $task) {
                $array_task[$task["task_name"]]=array("list_name" => $task["list_name"],
                "task_create_date" => $task["task_create_date"],
                "task_create_by" => $task["task_create_by"],
                "task_description" => $task["task_description"],
                "task_responsible" => $task["task_responsible"],
                "task_end_date" => $task["task_end_date"],
                "task_important" => $task["task_important"],
                "task_status" => $task["task_status"],
                "task_comment" => $task["task_comment"]);
        }
        return $array_task;
    }

function db_modify_list($db_name,$list_name,$new_list_name, $list_description)
{
    // function to modify the list from DB
    try{
        $modify = 'UPDATE bdd_lists SET list_name = :new_list_name, list_description = :list_description WHERE list_name = :list_name';
        $stmt = $db_name->prepare($modify);
        $stmt->bindValue(":list_name",$list_name);
        $stmt->bindValue(":new_list_name",$new_list_name);
        $stmt->bindValue(":list_description",$list_description);
        $stmt->execute();

        $message="la liste '".$list_name."' a été modifiée: <br> nouveau nom:'" . $new_list_name."' <br> description: ".$list_description;
     }
    catch(PDOException $error)
    {
        echo 'Connection error: ' . $error->getMessage() ."<br>";
        die();
    }

    // modification du nom de liste dans la table tâche
    try{
        $modify_task = 'UPDATE bdd_task SET list_name = :new_list_name WHERE list_name = :list_name';
        $stmt_task = $db_name->prepare($modify_task);
        $stmt_task->bindValue(":list_name",$list_name);
        $stmt_task->bindValue(":new_list_name",$new_list_name);
        $stmt_task->execute();
     }
    catch(PDOException $error2)
    {
        echo 'Connection error: ' . $error2->getMessage() ."<br>";
        die();
    }
    return $message;
}

function db_modify_task($db_name,$task_name,$new_task_name, $list_name,$task_modify_date,$task_modify_by,$task_description,$task_responsible,$task_end_date,$task_important,$task_status,$task_comment)
{
    // function to modify the task from DB
    try{
        $modify = 'UPDATE bdd_task SET list_name= :list_name,
                    task_name= :new_task_name,
                    task_modify_date= :task_modify_date,
                    task_modify_by= :task_modify_date,
                    task_description= :task_description, 
                    task_responsible= :task_responsible,
                    task_end_date= :task_end_date, 
                    task_important= :task_important, 
                    task_status= :task_status,
                    task_comment= :task_comment 
                    WHERE task_name = :task_name';
        $stmt = $db_name->prepare($modify);
        $stmt->bindValue(":list_name",$list_name);
        $stmt->bindValue(":task_name",$task_name);
        $stmt->bindValue(":new_task_name",$new_task_name);
        $stmt->bindValue(":task_modify_date",$task_modify_date);      
        $stmt->bindValue(":task_modify_by",$task_modify_by);
        $stmt->bindValue(":task_description",$task_description);
        $stmt->bindValue(":task_responsible",$task_responsible);
        $stmt->bindValue(":task_end_date",$task_end_date);
        $stmt->bindValue(":task_important",$task_important);
        $stmt->bindValue(":task_status",$task_status);
        $stmt->bindValue(":task_comment",$task_comment);
        $stmt->execute();

        $message="la tâche '".$task_name."' a été modifiée: <br> nouveau nom:'" . $new_task_name."' <br> description: ".$task_description;
     }
    catch(PDOException $error)
    {
        echo 'Connection error: ' . $error->getMessage() ."<br>";
        die();
    }

    return $message;
}