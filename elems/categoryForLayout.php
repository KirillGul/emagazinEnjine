<?php
//в массиве $page уже есть все значения категории найденные по uri
$catID = $page['id'];
$catName = ltrim($page['name']);
$catURI = $page['uri'];

if (isset($_GET['page'])) {
    $list = $_GET['page'];
} else {
    $list = 1;
}

$notesOnPage = 40; //выводить по 40 на странице
$from = ($list-1) * $notesOnPage;

$query = "SELECT * FROM product WHERE category_id='$catID' LIMIT $from,$notesOnPage"; //выбираем товары определенной категории по определенному кол-ву
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row); //Преобразуем то, что отдала нам база в нормальный массив PHP $data

$query = "SELECT COUNT(*) as count FROM product WHERE category_id='$catID'"; //узнаем кол-во товаров в категории
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$count = mysqli_fetch_assoc($result)['count']; //кол-во товара
$pagesCount = ceil($count/$notesOnPage); //кол-во страниц для категории


$title = "<title>Интернет-магазин $catName: каталог с фото и ценами</title>";
$description = "<meta name=\"description\" content=\"Каталог интернет-магазина $catName. Цены, фото, описания товаров и скидки.\">";
$logo = "<a href=\"/$catURI/\"><img src=\"/images/logo/$catName.jpg\"></a>";

$arrCity=array(1=>'Абакан','Альметьевск','Ангарск','Армавир','Архангельск','Астрахань','Балаково','Балашиха','Барнаул','Белгород','Березники','Бийск','Братск','Брянск','Великий Новгород','Владивосток','Владикавказ','Владимир','Волгоград','Волгодонск','Волжский','Вологда','Воронеж','Грозный','Дзержинск','Екатеринбург','Зеленоград','Златоуст','Иваново','Ижевск','Йошкар-Ола','Иркутск','Казань','Калининград','Калуга','Каменск-Уральский','Кемерово','Керчь','Киров','Ковров','Коломна','Колпино','Комсомольск-на-Амуре','Копейск','Королёв','Кострома','Краснодар','Красноярск','Курган','Курск','Липецк','Люберцы','Магнитогорск','Майкоп','Махачкала','Миасс','Москва','Мурманск','Мытищи','Набережные Челны','Нальчик','Находка','Нефтеюганск','Нижневартовск','Нижнекамск','Нижний Новгород','Нижний Тагил','Новокузнецк','Новороссийск','Новосибирск','Новочеркасск','Норильск','Одинцово','Омск','Орел','Оренбург','Орск','Пенза','Пермь','Петрозаводск','Петропавловск-Камчатский','Подольск','Прокопьевск','Псков','Пятигорск','Ростов-на-Дону','Рубцовск','Рыбинск','Рязань','Салават','Самара','Санкт-Петербург','Саранск','Саратов','Севастополь','Северодвинск','Симферополь','Смоленск','Сочи','Ставрополь','Старый Оскол','Стерлитамак','Сургут','Сызрань','Сыктывкар','Таганрог','Тамбов','Тверь','Тольятти','Томск','Тула','Тюмень','Улан-Удэ','Ульяновск','Уссурийск','Уфа','Хабаровск','Химки','Чебоксары','Челябинск','Череповец','Чита','Шахты','Электросталь','Энгельс','Южно-Сахалинск','Якутск','Ярославль');
if (empty($_COOKIE["city"])) {
    $city = 'РОССИЯ';
} else {
    $city = $arrCity[$_COOKIE["city"]];
}

$h1 = "<h1>Каталог $catName</h1>";
$countProduct = "<p class=\"gcnt\">Всего: $count шт.</p>";

$pag = '<div class="pag"><ul>';
    if ($list != 1) {
        $prev = $list - 1;
        $pag .= "<li><a href=\"?page=$prev\"><span><</span></a></li>";
    }

    $pageMax = $list + 5;
    for ($i = $list; $i <= $pagesCount AND $i <= $pageMax; $i++) {
        if ($i == $list) {
           $class = ' class="active"';
        } else $class = '';
        $pag .= "<li><a href=\"?page=$i\"$class><span>$i</span></a></li> ";
     }

     if ($list != $pagesCount) {
        $next = $list + 1;
        $pag .= "<li><a href=\"?page=$next\"$class><span>></span></a></li> ";
     }
$pag .= '</ul></div>';

$content = '';
$catClear = 1;
foreach ($data as $tovar) {
    $content .= '<div class="tovar">';

    //процент скидки
    if (isset($tovar['oldprice']) AND $tovar['oldprice'] == TRUE) {
        $content .= '<div class="discount">';
        $content .= '-';
        $content .=  round((100*($tovar['oldprice'] - $tovar['price']))/$tovar['oldprice'], 0);
        $content .=  '%</div>';
    }

    //картинка
    $content .= '<div class="image">';
    $content .= "<a href=\"{$tovar['uri']}\">";
    $content .= "<img src=\"{$tovar['picture']}\" onload=\"goodLoadImg(this);\" onerror=\"errLoadImg(this);\">";
    $content .= '</a></div>';

    //название товара
    $content .= '<div class="name">';
    $content .= "<a href=\"{$tovar['uri']}\">{$tovar['name']}</a>";
    $content .= '</div>';

    //вывод цены и старой цены
    $content .= '<div>';
    $content .= '<span class="price">';
    $content .= number_format($tovar['price'], 0, "", " ");
    $content .= 'р.</span>';
    if (isset($tovar['oldprice']) AND $tovar['oldprice'] == TRUE) {
        $content .= "<span class=\"oldprice\">{$tovar['oldprice']}р.</span>";
    }
    $content .= '</div>';

    $content .= '</div>';
    //разделить
    if ($catClear % 4 == 0) { $content .= '<div class="clrb"></div>'; } 
    $catClear++;
}