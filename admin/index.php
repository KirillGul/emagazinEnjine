<?php
include 'elems/password.php';
include '../elems/init.php';

if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {
    $content = '';
    $title = 'admin main page';

    $info = '';
    if (isset($_SESSION['info'])) {
        $info = $_SESSION['info'];
    }

    function showPageTable ($link) {
        $query = "SELECT id, name, url FROM category WHERE url!='404'";
        $result = mysqli_query($link, $query) or die( mysqli_error($link) );
        //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

        $table = "
            <table>
            <tr>
            <th>ID</th>
            <th>Title</th>
            <th>URL</th>
            <th>Count</th>
            <th>ImportXML</th>
            <th>Очистить</th>
            </tr>";
        foreach ($data as $value) {
            $idCat = $value['id'];

            $query = "SELECT COUNT(*) as count FROM product WHERE category_id='$idCat'";
            $result = mysqli_query($link, $query) or die( mysqli_error($link) );
            //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
            $countProduct = mysqli_fetch_assoc($result)['count'];

            $table .= "<tr>
                <td>{$value['id']}</td>
                <td>{$value['name']}</td>
                <td>{$value['url']}</td>
                <td>$countProduct</td>
                <td><a href=\"import.php?id=$idCat\">Import</a></td>
                <td><a href=\"?del=$idCat\">Очистить от товаров</a></td>
                </tr>";
        }
        return $table .= "</table>";
    }

    function deletePages ($link, $idCat) {
        $query = "SELECT * FROM product WHERE category_id='$idCat'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

        if ($data) {
            foreach ($data as $prod) {
                $query = "DELETE FROM product WHERE category_id='$idCat'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));

                if($result) return true;
                else return false;
            }

        } else return true;
    }

    function checkTableInDB ($link, $db_name, $nameTable) {
        $query = "SHOW TABLES FROM $db_name LIKE '$nameTable'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        return mysqli_fetch_assoc($result);
    }


    if (!empty($_GET['del'])) {
        $isDelete = deletePages($link, $_GET['del']);

        if ($isDelete) {
            $_SESSION['info'] = [
                'msg' => "Успешно удаленно",
                'status' => 'success'
            ];
            header('Location: /admin/'); die();
        } else {
            $_SESSION['info'] = [
                'msg' => "Ошибка удаления",
                'status' => 'error'
            ];
        }
    }

    $content = "<p><a href=\"logout.php\">Выход</a> ";

    //создание таблиц
    if(isset($_GET['add'])) {
        if ($_GET['add'] == 1) {
            $query = "CREATE TABLE IF NOT EXISTS category (
                `index` MEDIUMINT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` TINYTEXT NOT NULL,
                `url` TINYTEXT NOT NULL,
                `text` TINYTEXT NULL,
                UNIQUE INDEX `Индекс 1` (`index`),
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB";
        } elseif ($_GET['add'] == 2) {
            $query = "CREATE TABLE IF NOT EXISTS product (
                `index` MEDIUMINT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
                `available` TINYTEXT NULL DEFAULT NULL,
                `category_id` MEDIUMINT(12) NOT NULL,
                `description` TINYTEXT NULL DEFAULT NULL,
                `modified_time` DATE NULL DEFAULT NULL,
                `name` TINYTEXT NOT NULL,
                `oldprice` MEDIUMINT(12) NULL DEFAULT NULL,
                `price` MEDIUMINT(12) NOT NULL,
                `param` TINYTEXT NULL DEFAULT NULL,
                `picture` TINYTEXT NULL DEFAULT NULL,
                `type` TINYTEXT NULL DEFAULT NULL,
                `url` TINYTEXT NULL DEFAULT NULL,
                `vendor` TINYTEXT NULL DEFAULT NULL,
                `vendorcode` TINYTEXT NULL DEFAULT NULL,
                `category` TINYTEXT NULL DEFAULT NULL,
                `groupid` MEDIUMINT(12) NOT NULL,
                `topseller` TINYTEXT NULL DEFAULT NULL,
                UNIQUE INDEX `Индекс 1` (`index`)
            ) COLLATE='utf8mb4_unicode_ci' ENGINE=InnoDB";
        }
        
        mysqli_query($link, $query) or die(mysqli_error($link));
    }

    $rez = checkTableInDB($link, $db_name, $nameTable = 'category');
    if (!isset($rez)) {
       $content .= "<a href=\"?add=1\">Создать таблицу 'category'</a></p>";
    } else {
        $rez = checkTableInDB ($link, $db_name, $nameTable = 'product');
        if (!isset($rez)) {
            $content .= "<a href=\"?add=2\">Создать таблицу 'product'</a></p>";
        } else {
            $content .= showPageTable($link);
        }
    }
    //создание таблиц конец

    include 'elems/layout.php';

   /* echo "<pre>";
    var_dump($rez);
    echo "</pre>";*/
} else {
    header('Location: /admin/login.php'); die();
}

