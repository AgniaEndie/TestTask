<?php

namespace core\service;

use core\database\DatabaseManager;

class MenuService
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    function countProductsInGroup($groupId): int
    {
        $totalProducts = 0;

        $sqlCount = "SELECT COUNT(*) as total FROM `products` WHERE id_group = '$groupId'";
        $resultCount = $this->databaseManager->connection->query($sqlCount);
        $countRow = $resultCount->fetch_assoc();
        $totalProducts += $countRow['total'];

        $sqlSubGroups = "SELECT id FROM `groups` WHERE id_parent = '$groupId'";
        $resultSubGroups = $this->databaseManager->connection->query($sqlSubGroups);

        if ($resultSubGroups->num_rows > 0) {
            while ($subGroup = $resultSubGroups->fetch_assoc()) {
                $subGroupId = $subGroup['id'];
                $totalProducts += $this->countProductsInGroup($subGroupId);
            }
        }

        return $totalProducts;
    }

    function displayGroups($parentId = 0, $selectedGroupId = null, $level = 0): void
    {
        $sql = "SELECT id, name FROM `groups` WHERE id_parent = $parentId";
        $result = $this->databaseManager->connection->query($sql);

        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $groupId = $row['id'];
            $groupName = $row['name'];

            $totalProducts = $this->countProductsInGroup($groupId);

            echo "<li class='group-$groupId'>";
            if ($groupId == $selectedGroupId) {
                echo "<a style='color: forestgreen' href='?group=$groupId'>$groupName ($totalProducts)</a>";
            } else {
                echo "<a href='?group=$groupId'>$groupName ($totalProducts)</a>";
            }

            if ($selectedGroupId > $parentId) {
                $this->displayGroups($groupId, $selectedGroupId, $level + 1);
            }


            echo "</li>";
        }
        echo "</ul>";
    }

    function displayProductsInCategory($categoryId): void
    {
        $sql = "SELECT * FROM `products` WHERE id_group = $categoryId";
        $result = $this->databaseManager->connection->query($sql);

        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['name'] . "</li>";
            }
            echo "</ul>";
        }

        $sqlSubCategories = "SELECT id FROM `groups` WHERE id_parent = $categoryId";
        $resultSubCategories = $this->databaseManager->connection->query($sqlSubCategories);

        if ($resultSubCategories->num_rows > 0) {
            while ($subCategory = $resultSubCategories->fetch_assoc()) {
                $subCategoryId = $subCategory['id'];
                $this->displayProductsInCategory($subCategoryId);
            }
        }
    }
}