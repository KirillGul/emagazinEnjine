<?php
$query = "SELECT * FROM category WHERE url!='404' AND id='$catID'";
$result = mysqli_query($link, $query) or die( mysqli_error($link) );
//Преобразуем то, что отдала нам база в нормальный массив PHP $data:
$catResult = mysqli_fetch_assoc($result);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$notesOnPage = 40;
$from = ($page-1) * $notesOnPage;

$query = "SELECT * FROM product WHERE category_id='$catID' LIMIT $from,$notesOnPage";
$result = mysqli_query($link, $query) or die( mysqli_error($link) );
//Преобразуем то, что отдала нам база в нормальный массив PHP $data:
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

/*echo "<pre>";
var_dump($data);
echo "</pre>";*/

$query = "SELECT COUNT(*) as count FROM product WHERE category_id='$catID'";
$result = mysqli_query($link, $query) or die( mysqli_error($link) );
$count = mysqli_fetch_assoc($result)['count'];
$pagesCount = ceil($count/$notesOnPage);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Каталог интернет-магазина <?= $catResult['name']; ?>. Цены, фото, описания товаров и скидки.">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="/assets/readmore.js"></script>
    <script src="/assets/scripts.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
    <title>Интернет-магазин <?= $catResult['name']; ?>: каталог с фото и ценами</title>    
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="logotype">
                <a href="<?= $_SERVER['REQUEST_URI'] ?>"><img src="/images/logo/<?= $catID ?>.jpg"></a>
            </div>
            <?php echo $searchForm; ?>
            <ul>
				<li><a href="/">ВСЕ ИНТЕРНЕТ-МАГАЗИНЫ</a></li>
			</ul>
            <?php 
			echo $arrCityHead;
			if (empty($_COOKIE["city"])) {echo 'РОССИЯ';} else {echo $arrCity[$_COOKIE["city"]];}
			echo $arrCityFooter;
			?>
        </header>
        <article>
            <h1>Каталог <?= $catResult['name']; ?></h1>
			<p class="gcnt">Всего: <?= $count; ?> шт.</p>
			<div class="pag">
				<ul>                    
                    <?php
                    if ($page != 1) {
                        $prev = $page - 1;
                        echo "<li><a href=\"?page=$prev\"><span><</span></a></li> ";
                    }

                    for ($i = 1; $i <= $pagesCount; $i++) {
                        if ($i == $page) {
                           $class = ' class="active"';
                        } else $class = '';
                        echo "<li><a href=\"?page=$i\"$class><span>$i</span></a></li> ";
                     }

                     if ($page != $pagesCount) {
                        $next = $page + 1;
                        echo "<li><a href=\"?page=$next\"$class><span>></span></a></li> ";
                     }
                    ?>
				</ul>
			</div>

            <?php
            $catClear = 1;

            foreach ($data as $tovar) {
                echo '<div class="tovar">';

                    //процент скидки
                    if (isset($tovar['oldprice']) AND $tovar['oldprice'] == TRUE) {
                        echo '<div class="discount">';
                        echo '-';
                        echo round((100*($tovar['oldprice'] - $tovar['price']))/$tovar['oldprice'], 0);
                        echo '%</div>';
                    }

                    //картинка
                    echo '<div class="image">';
                    echo "<a href=\"{$tovar['name']}\">";
                    echo "<img src=\"{$tovar['imgurl']}\" onload=\"goodLoadImg(this);\" onerror=\"errLoadImg(this);\">";
                    echo '</a></div>';

                    //название товара
                    echo '<div class="name">';
                    echo "<a href=\"{$tovar['url']}\">{$tovar['name']}</a>";
                    echo '</div>';

                    //вывод цены и старой цены
                    echo '<div>';
                    echo '<span class="price">';
                    echo number_format($tovar['price'], 0, "", " ");
                    echo 'р.</span>';
                    if (isset($tovar['oldprice']) AND $tovar['oldprice'] == TRUE) {
                        echo "<span class=\"oldprice\">{$tovar['oldprice']}р.</span>";
                    }
                    echo '</div>';

                echo '</div>';
                //разделить
                if ($catClear % 4 == 0) { echo '<div class="clrb"></div>'; } 
                $catClear++;
            }
            ?>

			<div class="pag">
				<ul>                    
                    <?php
                    if ($page != 1) {
                        $prev = $page - 1;
                        echo "<li><a href=\"?page=$prev\"><span><</span></a></li> ";
                    }

                    for ($i = 1; $i <= $pagesCount; $i++) {
                        if ($i == $page) {
                           $class = ' class="active"';
                        } else $class = '';
                        echo "<li><a href=\"?page=$i\"$class><span>$i</span></a></li> ";
                     }

                     if ($page != $pagesCount) {
                        $next = $page + 1;
                        echo "<li><a href=\"?page=$next\"$class><span>></span></a></li> ";
                     }
                    ?>
				</ul>
			</div>
        </article>
        <footer>
            &copy; <?php echo $_SERVER['HTTP_HOST']; ?> - интернет-магазин, 2021          
        </footer>
    </div>
    <?php echo $city; ?>
</body>
</html>