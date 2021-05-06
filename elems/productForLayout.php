<?php
//в массиве $page уже есть все значения товара найденные по uri
$prodName = $page['name'];
$vendorCode = $page['vendorcode'];
$vendor = $page['vendor'];
$price = $page['price'];
$oldprice = $page['oldprice'];
if (isset($oldprice) AND $oldprice == TRUE) $oldpriceSuf = 'со скидкой';
else $oldpriceSuf = '';
$picture = $page['picture'];
$dateProd = date('Y-m-d', $page['modified_time']);


$catID = $cat['id'];
$catName = $cat['name'];
$catURI = $cat['uri'];

$prefhostHTTP = 'http://';
$hostHTTP = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];

$title = "<title>$prodName $vendorCode, цена $price р., фото и отзывы</title>";
$description = "
    <meta name=\"description\" content=\"$prodName $vendorCode $oldpriceSuf за $price р. в интернет-магазине $catName. Купите недорого с доставкой. Смотрите фото, описание и характеристики.\">
    <link rel='canonical' href='$prefhostHTTP$hostHTTP$request_uri'>
    <meta name=\"twitter:card\" content=\"summary_large_image\">
    <meta name=\"twitter:site\" content=\"@$hostHTTP\">
	<meta name=\"twitter:creator\" content=\"@$hostHTTP\">
	<meta name=\"twitter:title\" content=\"$prodName $vendorCode | $hostHTTP\">
	<meta name=\"twitter:description\" content=\"$prodName $vendorCode  $oldpriceSuf за $price р. в интернет-магазине $catName. Купите недорого с доставкой. Смотрите фото, описание и характеристики.\">
	<meta name=\"twitter:image\" content=\"$picture\">
	<meta property=\"og:title\" content=\"$prodName $vendorCode | $hostHTTP\">
	<meta property=\"og:description\" content=\"$prodName $vendorCode  $oldpriceSuf за $price р. в интернет-магазине $catName. Купите недорого с доставкой. Смотрите фото, описание и характеристики.\">
	<meta property=\"og:image\" content=\"$picture\">
	<meta property=\"og:type\" content=\"product\">
	<meta property=\"og:url\" content=\"$prefhostHTTP$hostHTTP$request_uri\">
";

$itemscope = "
    <span itemscope itemtype=\"http://schema.org/Product\">
        <meta itemprop=\"name\" content=\"$prodName $vendorCode\">
        <meta itemprop=\"description\" content=\"$prodName $vendorCode  $oldpriceSuf за $price р. в интернет-магазине $catName. Купите недорого с доставкой. Смотрите фото, описание и характеристики.\">
        <meta itemprop=\"image\" content=\"$picture\">
        <meta itemprop=\"brand\" content=\"$vendor\">
        <meta itemprop=\"sku\" content=\"$vendorCode\">
        <meta itemprop=\"productID\" content=\"sku:$vendorCode\">
        <span itemprop=\"offers\" itemscope itemtype=\"http://schema.org/Offer\">
            <link itemprop=\"availability\" href=\"http://schema.org/InStock\">
            <meta itemprop=\"price\" content=\"$price\">
            <meta itemprop=\"priceCurrency\" content=\"RUB\">
            <meta itemprop=\"priceValidUntil\" content=\"$dateProd\">
        </span>
    </span>
";
$logo = "<a href=\"/$catURI/\"><img src=\"/images/logo/$catID.jpg\"></a>";

$arrCity=array(1=>'Абакан','Альметьевск','Ангарск','Армавир','Архангельск','Астрахань','Балаково','Балашиха','Барнаул','Белгород','Березники','Бийск','Братск','Брянск','Великий Новгород','Владивосток','Владикавказ','Владимир','Волгоград','Волгодонск','Волжский','Вологда','Воронеж','Грозный','Дзержинск','Екатеринбург','Зеленоград','Златоуст','Иваново','Ижевск','Йошкар-Ола','Иркутск','Казань','Калининград','Калуга','Каменск-Уральский','Кемерово','Керчь','Киров','Ковров','Коломна','Колпино','Комсомольск-на-Амуре','Копейск','Королёв','Кострома','Краснодар','Красноярск','Курган','Курск','Липецк','Люберцы','Магнитогорск','Майкоп','Махачкала','Миасс','Москва','Мурманск','Мытищи','Набережные Челны','Нальчик','Находка','Нефтеюганск','Нижневартовск','Нижнекамск','Нижний Новгород','Нижний Тагил','Новокузнецк','Новороссийск','Новосибирск','Новочеркасск','Норильск','Одинцово','Омск','Орел','Оренбург','Орск','Пенза','Пермь','Петрозаводск','Петропавловск-Камчатский','Подольск','Прокопьевск','Псков','Пятигорск','Ростов-на-Дону','Рубцовск','Рыбинск','Рязань','Салават','Самара','Санкт-Петербург','Саранск','Саратов','Севастополь','Северодвинск','Симферополь','Смоленск','Сочи','Ставрополь','Старый Оскол','Стерлитамак','Сургут','Сызрань','Сыктывкар','Таганрог','Тамбов','Тверь','Тольятти','Томск','Тула','Тюмень','Улан-Удэ','Ульяновск','Уссурийск','Уфа','Хабаровск','Химки','Чебоксары','Челябинск','Череповец','Чита','Шахты','Электросталь','Энгельс','Южно-Сахалинск','Якутск','Ярославль');
if (empty($_COOKIE["city"])) {
    $city = 'РОССИЯ';
} else {
    $city = $arrCity[$_COOKIE["city"]];
}

$h1 = "<h1>$prodName $vendorCode</h1>";

$content = '';

$socSeti='<script src="/yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script><script src="/yastatic.net/share2/share.js"></script><div class="ya-share2" data-services="collections,vkontakte,facebook,odnoklassniki,moimir,twitter,viber,whatsapp,telegram" data-counter=""></div>';
$dostavka='<h2>Доставка</h2><p>Доставка товара осуществляется транспортными компаниями до пунктов выдачи в вашем городе, а также по указанному при заказе адресу курьером. Итоговая стоимость доставки отобразится при оформлении заказа после заполния формы с адресом.</p>';
$readMore='<script>$(".description").readmore({maxHeight: 240, moreLink: "<a href=\"#\" class=\"moredescription\">+ Смотреть</a>", lessLink: "<a href=\"#\" class=\"moredescription\">- Скрыть</a>"});</script>';

echo "<pre>";
print_r($page);
echo "</pre>";