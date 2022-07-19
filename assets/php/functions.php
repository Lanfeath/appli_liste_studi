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
    
        $sql_task = 'SELECT task_name FROM bdd_task WHERE list_name="'.$list_name.'"';
        foreach ($my_Db_Connection->query($sql_task) as $task) {
                $array_task_list[]=$task["task_name"];
        }
        return $array_task_list;
    }

function task_info_retrieve($my_Db_Connection, $list_name){
    // récupérer les noms des tâches par liste existante dans une variable
        $array_task=[];
    
        $sql_task = 'SELECT list_name, task_name, task_create_date, task_create_by, task_description, task_responsible, task_end_date, task_important, task_status,task_comment FROM bdd_task WHERE list_name="'.$list_name.'"';
        foreach ($my_Db_Connection->query($sql_task) as $task) {
                $array_task[$task["task_name"]]=array("task_create_date" => $task["task_create_date"],
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
