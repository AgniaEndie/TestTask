<?php
include("core/init/init.php");

use core\database\DatabaseManager;
use core\service\MenuService;

$databaseManager = new DatabaseManager();
$menuService = new MenuService($databaseManager);
$selectedGroup = 0;
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тестовое задание</title>
</head>
<body>
<div>
    <div style="float:left; width: 40%">
        <a href="/">Все товары</a>
        <?php

        if (isset($_GET['group'])) {
            $selectedGroup = $_GET['group'];

            // Вывод списка групп и товаров для выбранной группы
            $menuService->displayGroups(0, $selectedGroup);

            // Вывод товаров для выбранной группы и ее потомков
        } else {
            $menuService->displayGroups();
        }

        ?>
    </div>
    <div style="float: left; width: 60%;">
        <?php
        $menuService->displayProductsInCategory($selectedGroup);
        ?>
    </div>
</div>
</body>
</html>