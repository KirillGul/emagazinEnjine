<?php
$uriRequest = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segmentsRequ = explode('/', trim($uriRequest, '/'));
		
$uriReferer = $_SERVER['HTTP_REFERER'];
$segmentsRef = explode('http://', $uriReferer);

$uri = explode('?', $_COOKIE["link"]);

$arrCity=array(1=>'Абакан','Альметьевск','Ангарск','Армавир','Архангельск','Астрахань','Балаково','Балашиха','Барнаул','Белгород','Березники','Бийск','Братск','Брянск','Великий Новгород','Владивосток','Владикавказ','Владимир','Волгоград','Волгодонск','Волжский','Вологда','Воронеж','Грозный','Дзержинск','Екатеринбург','Зеленоград','Златоуст','Иваново','Ижевск','Йошкар-Ола','Иркутск','Казань','Калининград','Калуга','Каменск-Уральский','Кемерово','Керчь','Киров','Ковров','Коломна','Колпино','Комсомольск-на-Амуре','Копейск','Королёв','Кострома','Краснодар','Красноярск','Курган','Курск','Липецк','Люберцы','Магнитогорск','Майкоп','Махачкала','Миасс','Москва','Мурманск','Мытищи','Набережные Челны','Нальчик','Находка','Нефтеюганск','Нижневартовск','Нижнекамск','Нижний Новгород','Нижний Тагил','Новокузнецк','Новороссийск','Новосибирск','Новочеркасск','Норильск','Одинцово','Омск','Орел','Оренбург','Орск','Пенза','Пермь','Петрозаводск','Петропавловск-Камчатский','Подольск','Прокопьевск','Псков','Пятигорск','Ростов-на-Дону','Рубцовск','Рыбинск','Рязань','Салават','Самара','Санкт-Петербург','Саранск','Саратов','Севастополь','Северодвинск','Симферополь','Смоленск','Сочи','Ставрополь','Старый Оскол','Стерлитамак','Сургут','Сызрань','Сыктывкар','Таганрог','Тамбов','Тверь','Тольятти','Томск','Тула','Тюмень','Улан-Удэ','Ульяновск','Уссурийск','Уфа','Хабаровск','Химки','Чебоксары','Челябинск','Череповец','Чита','Шахты','Электросталь','Энгельс','Южно-Сахалинск','Якутск','Ярославль');
if (empty($_COOKIE["city"])) {
    $city = 'РОССИЯ';
} else {
    $city = $arrCity[$_COOKIE["city"]];
}

$metaCartRefrash = '<meta name="robots" content="noindex, nofollow">';
$metaCartRefrash .= "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL={$uri[0]}?subid={$segmentsRef[1]}&subid1={$segmentsRequ[3]}&{$uri[1]}\">";

$title = '<title>Корзина заказов</title>';
$h1 = "<h1>Корзина заказов</h1>";

$content = '';

$content .= "<a href=\"{$uri[0]}?subid={$segmentsRef[1]}&subid1={$segmentsRequ[3]}&{$uri[1]}\">";
    $content .= "<img src=\"".$_COOKIE["productImage"]."\" class=\"redirimage\">";
$content .= "</a>";

$content .= '<div class="redirdiv">';
    $content .= "<p>Сейчас происходит переход на сайт интернет-магазина '".$_COOKIE["cat"]."', где Вы сможете более подробнее ознакомиться с '".$_COOKIE["productName"]."'.</p>";
    $content .= '<p>Если вы не хотите ждать, то ';
        $content .= "<b><a href=\"{$uri[0]}?subid={$segmentsRef[1]}&subid1={$segmentsRequ[3]}&{$uri[1]}\">скорее жмите сюда</a></b>.";
    $content .= '</p>';
    $content .= '<p>';
        $content .= '<img src="/assets/reding2.gif">';
    $content .= '</p>';
$content .= '</div>';
$content .= '<div style="widht:100%;height:300px;"></div>';

/*echo "<pre>";
print_r($page);
echo "</pre>";*/