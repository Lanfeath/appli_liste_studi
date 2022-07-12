<?php
session_start();
include "../functions.php";
include "../var.php";

 // insert which DB to connect to

if(isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password']))
{
    $my_Db_Connection= db_connect($servername,$db_to_use,$db_username,$db_password);
    
    // insert data sets
    $email = $_POST['email'];
    $password = $_POST['password'];


    // **************** A revoir **********************************
    // prepare SQL statement     
    $stm_login = $my_Db_Connection->prepare("SELECT user_name, user_surname, user_email, user_pwd 
        FROM users 
        WHERE user_email = :email ") ;
    // bind parameter
    $stm_login ->bindParam(':email', $email_db);

    $email_db = $_POST['email'];
    

    $stm_login->execute();
    $arr_login = $stm_login->fetchAll();  //recup of the value given by $statement (array with username, surname, email and password)

    // if the email is not inserted in the database the $arr_login is empty
    if (empty($arr_login)){
        header('Location: ../../../index.html?error=2');
    }
    
        // check that the password and the email match the DB entries
    foreach ($arr_login as $row) {
        if ($row['user_email']===$email && $row['user_pwd']===$password)
        {
            $_SESSION["username"]=$row["user_name"];
            $_SESSION["surname"]=$row["user_surname"];
        }else
        {
            header('Location: ../../../index.html?error=3');
        }
    }  

    // on mets les noms des membres de l'équipe dans le menu
    $sql = "SELECT user_name, user_surname FROM users";
    foreach ($my_Db_Connection->query($sql) as $user) {
        if ($user["user_name"]!=="John" && $user["user_surname"]!=="Doe"){
            $array_team[]=$user["user_name"];
        };
    }

    $_SESSION["team_members"]=$array_team;
    var_dump($_SESSION["team_members"]);
    include("../var.php"); //used to insert new value with session active
    header('Location: ../../../pages/mes_taches/list_view.html');    // go to the main page     

    
}
else
{
   header('Location: ../../../index?error=1');
}

?> 

