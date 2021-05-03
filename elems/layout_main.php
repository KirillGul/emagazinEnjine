<?php
$query = "SELECT * FROM category WHERE url!='404'";
$result = mysqli_query($link, $query) or die( mysqli_error($link) );
//Преобразуем то, что отдала нам база в нормальный массив PHP $data:
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Самые выгодные предложения интернет-магазинов собраны здесь!">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="/assets/readmore.js"></script>
    <script src="/assets/scripts.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
    <title>Каталог популярных интернет-магазинов</title>    
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="logotype"></div>
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
            <h1>Популярные интернет-магазины</h1>
			<div class="mgs">
                <?php
                    foreach ($data as $value) {
                        $idValue = $value['id'];
                        $uriValue = $value['url'];
                        $nameValue = $value['name'];

                        if ($uriValue != '/') {
                            $content = '<div class="mg">';
                            $content .= "<a href=\"/$uriValue/\">";
                            $content .= "<img src=\"/images/logo/$idValue.jpg\" alt=\"$nameValue\" onload=\"goodLoadImg(this);\" onerror=\"errLoadImg(this);\">";
                            $content .= '</a></div>';
                            echo $content;
                        }
                    }
                ?>
			</div>
            <div class="clrb"></div>
        </article>
        <footer>
            &copy; <?php echo $_SERVER['HTTP_HOST']; ?> - интернет-магазин, 2021          
        </footer>
    </div>
    <?php echo $city; ?>
</body>
</html>