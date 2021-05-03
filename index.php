<?php
include 'elems/init.php';

function queryPage ($link, $uri, $table='category') {
    $query = "SELECT * FROM $table WHERE url='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
    return mysqli_fetch_assoc($result);
}

function checkURL ($link, $uri, $table='category') {
    $query = "SELECT COUNT(*) as count FROM $table WHERE url='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
    return mysqli_fetch_assoc($result)['count'];
}

$uri = trim(preg_replace('#(\?.*)?#', '', $_SERVER['REQUEST_URI']), '/');

if (empty($uri)) {
    $uri = '/';
}

if ($uri == '/') {
    $page = queryPage($link, $uri);
    $flag = 'main';
} else {
    $uriArr = explode ("/", $uri);

    if (count($uriArr) == 2) {
        if (checkURL($link, $uriArr[0]) AND checkURL($link, $uriArr[1], 'product')) {
            $page = queryPage($link, $uriArr[1], 'product');
            $flag = 'product';
        } else {
            $page = queryPage($link, '404');
            $flag = '404';
            header("HTTP/1.0 404 Not Found");
        }
    } elseif (count($uriArr) == 1 AND checkURL($link, $uriArr[0])) {
        $page = queryPage($link, $uriArr[0]);
        $flag = 'category';
    } else {
        $page = queryPage($link, '404');
        $flag = '404';
        header("HTTP/1.0 404 Not Found");
    }
}

//$title = '';
//$content = $page['description'];

switch ($flag) {
    case 'main':
        include 'elems/template_incl.php';
        include 'elems/layout_main.php';
        break;
    case 'category':
        $catID = $page['id'];
        include 'elems/template_incl.php';
        include 'elems/layout_category.php';
        break;
    case 'product':
        include 'elems/template_incl.php';
        include 'elems/layout_product.php';
        break;
    default:
        include 'elems/layout_404.php';
        break;
}

//echo "<a href=\"admin/\">Перейти в админку</a>";

/*echo "<pre>";
var_dump($_SERVER);
echo "</pre>";*/