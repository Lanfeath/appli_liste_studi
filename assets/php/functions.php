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

    // function to delete from a DB ($db_name is the connected to) into a specific table using a key_ref
function db_dump($db_name, $db_table,$primary_key_name, $primary_key){
    
    try{

        $delete = 'DELETE FROM '.$db_table .' WHERE '.$primary_key_name.' = :primary_key';
        $stmt = $db_name->prepare($delete);
        $stmt->bindValue(":primary_key",$primary_key);
        $stmt->execute();

        $message="la ligne a été supprimée";
     }
    catch(PDOException $error)
    {
        echo 'Connection error: ' . $error->getMessage() ."<br>";
        die();
    }

    return $message;
}

/*
function get_team_members($username,$surname){
    
    // Function to have the list of the name team members without the name of the person registered 
    $my_Db_Connection= db_connect("localhost","appli_liste","root","");
    $sql = "SELECT user_name, user_surname FROM users";
    foreach ($my_Db_Connection->query($sql) as $user) {
        if ($user["user_name"]!==$username && $user["user_surname"]!==$surname){
            $array_team[]=$user["user_name"];
        };
    }

    return $array_team;
}
*/