<?php 
include_once "./assets/php/var.php";
include_once "./assets/php/functions.php";

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

/*
$count_member=0;
while ($count_member <count(get_team_members("Jenny","Smith"))) {
    echo $count_member;
    $count_member++;
}

*/