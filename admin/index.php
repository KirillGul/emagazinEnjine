<?php
include 'elems/password.php';
include '../elems/init.php';

///////////////////////////////////////////////////////////////////////////////////////
function showPageTable ($link) {
    $query = "SELECT id, name, url FROM category";
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
            <td>{$value['url']}</td>
            <td>$countProduct</td>
            <td><a href=\"import.php?id=$idCat\">Import</a></td>
            <td><a href=\"?del=$idCat\">Очистить от товаров</a></td>
            <td><a href=\"?delCat=$idCat\">Удалить категорию</a></td>
            </tr>";
    }
    return $table .= "</table>";
}

function deletePages ($link, $idCat, $nameCat='product') {

    $param = 'category_id';
    if ($nameCat == 'category') {
        $param = 'id';
    }

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

function checkTableInDB ($link, $db_name, $nameTable) {
    $query = "SHOW TABLES FROM $db_name LIKE '$nameTable'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    return mysqli_fetch_assoc($result);
}

function checkPage($link, $table, $urlPOST) {
    $query = "SELECT COUNT(*) as count FROM $table WHERE url='$urlPOST'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    return mysqli_fetch_assoc($result)['count'];
 }
///////////////////////////////////////////////////////////////////////////////////////
/*Начало логики*/
if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {

    /*Выход из авторизации*/
    if (!empty($_GET['logout']) && $_GET['logout'] == 1) {
        header('Location: /admin/logout.php'); die();
    }

    /*Подготовка переменных*/
    $content = "<p><a href=\"?logout=1\">Выход</a></p>";
    $title = 'admin main page';

    $info = '';
    if (isset($_SESSION['info'])) {
        $info = $_SESSION['info'];
    }
///////////////////////
    /*Если нажато удаление*/
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
///////////////////////
    /*Добавление категорий если были введены названия в форму*/
    if (isset($_POST['text'])) {
        $textValue = $_POST['text'];

        $arrCat = explode (";", $textValue);

        foreach ($arrCat as $value) {
            $nameCat =  $value;
            $urlCat =  strtolower(str_replace(' ', '-', $value));

            if (checkPage($link, 'category', $urlCat) == false) {
                $query = "INSERT INTO category SET name=' $nameCat', url='$urlCat'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
            }
        }

        header('Location: index.php'); die();

    } else {
        $textValue = "Введите через '\;' названия категорий";
    }
///////////////////////
    /*Если нажато удаление категории*/
    if (!empty($_GET['delCat'])) {
        $isDelete = deletePages($link, $_GET['delCat'], 'category');

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

///////////////////////
    /*Проверка инсталяции таблиц в базе*/
    $rezStaticPage = checkTableInDB ($link, $db_name, $nameTable = 'page');
    $rezCategory = checkTableInDB($link, $db_name, $nameTable = 'category');
    $rezProduct = checkTableInDB ($link, $db_name, $nameTable = 'product');    

    if (!isset($rezStaticPage) || !isset($rezCategory) || !isset($rezProduct)) {
    /*Если не созданы таблицы*/
        header('Location: /admin/install.php'); die();        
    } else {
    /*Если все создано вывод на экран*/
        $content .= showPageTable($link);
    }
///////////////////////

$content .= "<p><a href=\"install.php\">В install</a></p>";
$content .= "<p><a href=\"/\">На FrontEnd</a></p>";

/*Добавление категорий*/
$content .= "<br><p>Добавить категорию</p>";
$content .= "
<form method=\"POST\">
Name:<textarea name='text'>$textValue</textarea><br><br>
<input type=\"submit\">
</form>";

    include 'elems/layout.php';
} else {
    header('Location: /admin/login.php'); die();
}

