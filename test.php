<?php 
include_once "./assets/php/var.php";
include_once "./assets/php/functions.php";
/*
$count_member=get_team_members("Jenny","Smith");
echo "test";

for($i = 0; $i < count($count_member); ++$i) {
    echo '<li class="nav-item">',
        '<a href="../../pages/taches_equipe/view_marc.html" class="nav-link">',
            '<i class="bi bi-house-door-fill"></i>',
            '<strong class="menu-item">'.$count_member[$i].'</strong>',                        
        '</a>',
    '</li>';
}
echo $count_member;


$count_member=0;
while ($count_member <count(get_team_members("Jenny","Smith"))) {
    echo $count_member;
    $count_member++;
}

*/

$my_Db_Connection= db_connect($servername,$db_to_use,$db_username,$db_password);
$task_list= task_list_retrieve($my_Db_Connection,"liste_1");
$array_task= task_info_retrieve($my_Db_Connection,"liste_1");


/*
var_dump($array_task);
foreach ($task_list as $task){
    echo "<br> Task info: " .$task ."<br>";
    echo "Date de création: " .$array_task[$task]["task_create_date"] ."<br> <br>";
}
*/

print(db_modify_list($my_Db_Connection,"Liste_2","List_2", "test"));