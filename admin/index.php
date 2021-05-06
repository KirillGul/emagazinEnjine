<?php
include '../elems/init.php';

function showPageTable ($link) {
    $query = "SELECT id, name, uri FROM category";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    $table = "
        <table>
        <tr>
        <th>ID</th>
        <th>Name</th>
        <th>URL</th>
        <th>Count</th>
        <th>ImportXML</th>
        <th>Очистить</th>
        <th>Удалить</th>
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
            <td>{$value['uri']}</td>
            <td>$countProduct</td>
            <td><a href=\"import.php?id=$idCat\">Import</a></td>
            <td><a href=\"?delProductID=$idCat\">Очистить от товаров</a></td>
            <td><a href=\"?delCategoryID=$idCat\">Удалить категорию</a></td>
            </tr>";
    }
    return $table .= "</table>";
}

function deletePages ($link, $idCat, $nameCat='product', $param='category_id') { //удаление страниц в таблице и таблиц
    $query = "SELECT * FROM $nameCat WHERE $param='$idCat'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    if ($data) {
        foreach ($data as $prod) {
            $query = "DELETE FROM $nameCat WHERE $param='$idCat'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));

            if($result) return true;
            else return false;
        }

    } else return true;
}

function checkTable ($link, $db_name, $nameTable) {
    $query = "SHOW TABLES FROM $db_name LIKE '$nameTable'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    return mysqli_fetch_assoc($result);
}

 function checkURI ($link, $uri, $table='category') { //запрос существования страницы в таблице
    $query = "SELECT COUNT(*) as count FROM $table WHERE uri='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    return mysqli_fetch_assoc($result)['count']; //Преобразуем то, что отдала нам база из объекта в нормальный массив с одним значением и значение count
}

function getTranslit ($str) {
    $arr = ['a'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 
       'д'=>'d', 'е'=>'e', 'ё'=>'e', 'ж'=>'zh', 'з'=>'z', 
       'и'=>'i', 'й'=>'i', 'к'=>'k', 'л'=>'l', 'м'=>'m', 
       'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 
       'т'=>'t', 'у'=>'u', 'ф'=>'f', 'х'=>'kh', 'ц'=>'ts', 
       'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shch', 'ъ'=>'ie', 'ы'=>'y', 
       'ь'=>'', 'э'=>'e', 'ю'=>'iu', 'я'=>'ia'];
 
    foreach (preg_split('/(?<!^)(?!$)/u', $str) as $value) {
        if (!empty($arr[$value])) {
            $arrTranslit[] = $arr[$value];
        } else {
            $arrTranslit[] = $value;
        }
    }
    return $arrTranslit;
 }
///////////////////////////////////////////////////////////////////////////////////////
/*Начало логики*/
if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {

/*ПОДГОТОВКА ПЕРЕМЕННЫХ*/
    $content = "";
    $title = 'admin main page';

    $info = '';
    if (isset($_SESSION['info'])) {
        $info = $_SESSION['info'];
    }



/*ОБРАБОТКА GET ЗАПРОСОВ*/ 
    /*Выход из авторизации*/
    if (!empty($_GET['logout']) && $_GET['logout'] == 1) {
        header('Location: /admin/logout.php'); die();
    }
    
    /*Если нажато удаление*/
    if (!empty($_GET['delProductID'])) {
        if (deletePages($link, $_GET['delProductID'])) {
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
            header('Location: /admin/'); die();
        }
    }

    /*Если нажато удаление категории*/
    if (!empty($_GET['delCategoryID'])) {
        if (deletePages($link, $_GET['delCategoryID'], 'category', 'id')) {
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
            header('Location: /admin/'); die();
        }
    }



/*ОБРАБОТКА POST ЗАПРОСОВ*/
    /*Добавление категорий если были введены названия в форму*/
    if (isset($_POST['text'])) {
        $textValue = $_POST['text'];

        $arrCat = explode ("\n", $textValue);

        foreach ($arrCat as $value) {
            $nameCat =  (str_replace('.xml', '', $value)); //убрать .xml
            $nameCat =  trim($nameCat);

            $uriCat = mb_strtolower(str_replace(' ', '-', $nameCat)); //перевод в нижн. регистр и замена пробелов на тире 
            $uriCat = implode('', getTranslit($uriCat));

            if (checkURI($link, $uriCat , 'category') == false) {
                $query = "INSERT INTO category SET name=' $nameCat', uri='$uriCat'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
            }
        }

        header('Location: index.php'); die();

    } else {
        $textValue = "Введите названия категорий (каждую категорию с новой строки)";
    }



/*ВЫВОД НА ЭКРАН ТАБЛИЦ*/
    /*Проверка инсталяции таблиц в базе*/
    $rezStaticPage = checkTable ($link, $db_name, $nameTable = 'page');
    $rezCategory = checkTable($link, $db_name, $nameTable = 'category');
    $rezProduct = checkTable ($link, $db_name, $nameTable = 'product');    

    if (!isset($rezStaticPage) || !isset($rezCategory) || !isset($rezProduct)) {
        /*Если не созданы таблицы*/
        header('Location: /admin/install.php'); die();        
    } else {
        /*Если все создано вывод на экран*/
        $content .= showPageTable($link);
    }

    /*Добавление категорий*/
    $content .= "<br><br><p>ДОБАВЛЕНИЕ КАТЕГОРИЙ</p>";
    $content .= "<table><tr>";
    $content .= "<td>";
    $content .= "<form method=\"POST\">
            <textarea name=\"text\" style=\"width:500px; height:300px;\">$textValue</textarea><br><br>
            <input type=\"submit\">
        </form>";
    $content .= "</td>";
    $content .= "</tr></table>";


        
/*КОНЕЦ*/    
    include 'elems/layout.php';
} else {
    header('Location: /admin/login.php'); die();
}

