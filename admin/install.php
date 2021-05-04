<?php
include 'elems/password.php';
include '../elems/init.php';

if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {

    function checkTableInDB ($link, $db_name, $nameTable) {
        $query = "SHOW TABLES FROM $db_name LIKE '$nameTable'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        return mysqli_fetch_assoc($result);
    }

    function deleteTable ($link, $nameCat) {
        $query = "DROP TABLE `$nameCat`";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        //$result = mysqli_fetch_assoc($result);

        if($result) return [
            'msg' => "Таблица успешно удалена.",
            'status' => 'success'
        ];
        else return [
            'msg' => "Ошибка удаления таблицы",
            'status' => 'error'
        ];
    }

    $title = 'admin install page';

    //создание таблиц
    if (!empty($_GET['add'])) {

        if ($_GET['add'] == 1) {
            $query = "CREATE TABLE IF NOT EXISTS category (
                `id` INT NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(256) NOT NULL,
                `name` VARCHAR(256) NOT NULL,
                PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";
           
           $result = mysqli_query($link, $query) or die(mysqli_error($link));
             
        } elseif ($_GET['add'] == 2) {
            $query = "CREATE TABLE IF NOT EXISTS product (
                `id` INT NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(256) NOT NULL,
                `available` VARCHAR(256) NOT NULL,
                `category_id` MEDIUMINT(12) NOT NULL,
                `description` TEXT NULL DEFAULT NULL,
                `modified_time` DATE NULL DEFAULT NULL,
                `name` VARCHAR(256) NOT NULL,
                `oldprice` INT NULL DEFAULT NULL,
                `price` INT NOT NULL,
                `param` TEXT NULL DEFAULT NULL,
                `picture` TEXT NULL DEFAULT NULL,
                `type` VARCHAR(256) NULL DEFAULT NULL,                
                `vendor` VARCHAR(256) NOT NULL,
                `vendorcode` VARCHAR(256) NULL DEFAULT NULL,
                `category` VARCHAR(256) NULL DEFAULT NULL,
                `groupid` INT NOT NULL,
                `topseller` VARCHAR(256) NULL DEFAULT NULL,
                PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";

            $result = mysqli_query($link, $query) or die(mysqli_error($link));

        } elseif ($_GET['add'] == 3) {
            $query = "CREATE TABLE IF NOT EXISTS page (
                `id` INT NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(256) NOT NULL,
                `name` VARCHAR(256) NOT NULL,
                `text` TEXT NULL DEFAULT NULL,
                PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci";

            $result = mysqli_query($link, $query) or die(mysqli_error($link));

            $query = "INSERT INTO page (name, url, text) VALUES ('main', '/', 'main'), ('404', '404', '404')";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));

        }
     
        if ($result) {
            $_SESSION['info'] = [
                'msg' => "Таблица успешно созданна.",
                'status' => 'success'
            ];
            header('Location: /admin/install.php'); die();
        } else {
            $info = $_SESSION['info'] = [
                'msg' => "Ошибка создания таблицы",
                'status' => 'error'
            ];
            include 'elems/layout.php';
        }
    }

    //удаление таблиц
    if (!empty($_GET['delTable'])) {

        if ($_GET['delTable'] == 1) {
            $info = deleteTable($link, 'category');
        }

        if ($_GET['delTable'] == 2) {
            $info = deleteTable($link, 'product');
        }

        if ($_GET['delTable'] == 3) {
            $info = deleteTable($link, 'page');
        }
    }

    $content = "
    <table>
    <tr>
    <th>№</th>
    <th>Таблица</th>
    <th>Действие</th>
    <th>Состояние</th>
    </tr>";

    /*Проверка таблицы category в базе*/
    $rezCategory = checkTableInDB($link, $db_name, $nameTable = 'category');
    $content .= "<tr><td>1</td><td>'category'</td>";
    if (!isset($rezCategory)) {
        $content .= "
        <td><a href=\"?add=1\">Создать таблицу 'category'</a></td>
        <td>Нет в базе</td>
        </tr>";        
    } else {
        $content .= "
        <td><a href=\"?delTable=1\">Удалить таблицу 'category'</a></td>
        <td>Созданно</td>
        </tr>";  
    }

    /*Проверка таблицы product в базе*/
    $rezProduct = checkTableInDB ($link, $db_name, $nameTable = 'product');
    $content .= "<tr><td>2</td><td>'product'</td>";
    if (!isset($rezProduct)) {
        $content .= "
        <td><a href=\"?add=2\">Создать таблицу 'product'</a></td>
        <td>Нет в базе</td>
        </tr>";        
    } else {
        $content .= "
        <td><a href=\"?delTable=2\">Удалить таблицу 'product'</a></td>
        <td>Созданно</td>
        </tr>";  
    }

    /*Проверка таблицы page в базе*/
    $rezStaticPage = checkTableInDB ($link, $db_name, $nameTable = 'page');
    $content .= "<tr><td>3</td><td>'page'</td>";
    if (!isset($rezStaticPage)) {
        $content .= "
        <td><a href=\"?add=3\">Создать таблицу 'page'+страницы ('/', '404')</a></td>
        <td>Нет в базе</td>
        </tr>";        
    } else {
        $content .= "
        <td><a href=\"?delTable=3\">Удалить таблицу 'page'+страницы ('/', '404')</a></td>
        <td>Созданно</td>
        </tr>";  
    }


    $content .= "</table>";
    $content .= "<p><a href=\"index.php\">В админку</a></p>";

    include 'elems/layout.php';

} else {
   header('Location: /admin/login.php'); die();
}