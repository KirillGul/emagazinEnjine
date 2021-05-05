<?php
include 'elems/init.php'; //Устанавливаем доступы к базе данных и др. настройки

function queryPage ($link, $uri, $table='category', $param = '*', $all=false) { //запрос всех данных страницы из таблицы
    $query = "SELECT $param FROM $table WHERE uri='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    if ($all === true) {
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
        return $data;
    } else {
        return mysqli_fetch_assoc($result); 
    }
}

function checkURI ($link, $uri, $table='category') { //запрос существования страницы в таблице
    $query = "SELECT COUNT(*) as count FROM $table WHERE uri='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    return mysqli_fetch_assoc($result)['count']; //Преобразуем то, что отдала нам база из объекта в нормальный массив с одним значением и значение count
}

///////////////////////////////////////////////////////////////////////////////////

$uri = trim(preg_replace('#(\?.*)?#', '', $_SERVER['REQUEST_URI']), '/'); //отрезаем в URI: всё что после (?) и убираем (/)

if (empty($uri)) //если после отразания ни чего нет, то главная
    $uri = '/';

if ($uri == '/') { //если главная   
    $flag = 'main';
} else { //если не главная
    $uriArr = explode ("/", $uri);
    if (count($uriArr) == 1 AND checkURI($link, $uriArr[0])) { //если первый уровень
        $flag = 'category';
    } elseif (count($uriArr) == 2) { //если второй уровень
        if (checkURI($link, $uriArr[0]) AND checkURI($link, $uriArr[1], 'product')) {
            $flag = 'product';
        } else {
            $flag = '404';
        }
    } else { //если больше второго уровня
        $flag = '404';
    }
}

switch ($flag) {
    case 'main':
        $page = queryPage($link, $uri, 'page');
        include 'elems/mainForLayout.php';
        include 'elems/layout.php';
        break;
    case 'category':
        $page = queryPage($link, $uriArr[0]);
        include 'elems/categoryForLayout.php';
        include 'elems/layout.php';
        break;
    case 'product':
        $page = queryPage($link, $uriArr[1], 'product');
        $catID = queryPage($link, $uriArr[0], 'category', 'id')['id'];
        include 'elems/productForLayout.php';
        include 'elems/layout.php';
        break;
    default:
        $page = queryPage($link, '404', 'page');
        include 'elems/layout_404.php';
        header("HTTP/1.0 404 Not Found");
        break;
}