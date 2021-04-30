<?php
//include 'elems/init.php';

//отрезаем get параметры
$uri = trim(preg_replace('#(\?.*)?#', '', $_SERVER['REQUEST_URI']), '/');

if (empty($uri)) $uri = 'main';

if ($uri == 'main') {

    query($link);

} else {

    $uriArr = explode ("/", $uri);

    if (count($uriArr) == 2) {
        if (checkCat($uriArr['0']) AND checkProd($uriArr['1'])) {
            query($link, $uriArr['0'], $uriArr['1']);
        } else {
            $page = query($link, '404');
            header("HTTP/1.0 404 Not Found");
        }
    } elseif (count($uriArr) == 1 AND checkCat($uriArr['0'])) {
        query($link, $uriArr['0']);
    } else {
        $page = query($link, '404');
        header("HTTP/1.0 404 Not Found");
    }

}

/*function query ($link, $uri) {
    $query = "SELECT * FROM pagesj WHERE url='$uri'";
    $result = mysqli_query($link, $query) or die( mysqli_error($link) );
    //Преобразуем то, что отдала нам база в нормальный массив PHP $data:
    return mysqli_fetch_assoc($result);
}

$page = query($link, $uri);

if (!$page) {
    $page = query($link, '404');
    header("HTTP/1.0 404 Not Found");
}

$title = $page['text'];
$content = $page['text'];

include 'elems/layout.php';*/

echo "<a href=\"admin/\">Перейти в админку</a>";

/*echo "<pre>";
var_dump($_SERVER['REQUEST_URI']);
//var_dump($url);
echo "</pre>";*/