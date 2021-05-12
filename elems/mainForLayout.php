<?php
$query = "SELECT * FROM category"; //выбрать все категории
$result = mysqli_query($link, $query) or die( mysqli_error($link) );
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row); //Преобразуем то, что отдала нам база в нормальный массив PHP $data

$title = '<title>Каталог популярных интернет-магазинов</title>';
$description = '<meta name="description" content="Самые выгодные предложения интернет-магазинов собраны здесь!">';

$arrCity=array(1=>'Абакан','Альметьевск','Ангарск','Армавир','Архангельск','Астрахань','Балаково','Балашиха','Барнаул','Белгород','Березники','Бийск','Братск','Брянск','Великий Новгород','Владивосток','Владикавказ','Владимир','Волгоград','Волгодонск','Волжский','Вологда','Воронеж','Грозный','Дзержинск','Екатеринбург','Зеленоград','Златоуст','Иваново','Ижевск','Йошкар-Ола','Иркутск','Казань','Калининград','Калуга','Каменск-Уральский','Кемерово','Керчь','Киров','Ковров','Коломна','Колпино','Комсомольск-на-Амуре','Копейск','Королёв','Кострома','Краснодар','Красноярск','Курган','Курск','Липецк','Люберцы','Магнитогорск','Майкоп','Махачкала','Миасс','Москва','Мурманск','Мытищи','Набережные Челны','Нальчик','Находка','Нефтеюганск','Нижневартовск','Нижнекамск','Нижний Новгород','Нижний Тагил','Новокузнецк','Новороссийск','Новосибирск','Новочеркасск','Норильск','Одинцово','Омск','Орел','Оренбург','Орск','Пенза','Пермь','Петрозаводск','Петропавловск-Камчатский','Подольск','Прокопьевск','Псков','Пятигорск','Ростов-на-Дону','Рубцовск','Рыбинск','Рязань','Салават','Самара','Санкт-Петербург','Саранск','Саратов','Севастополь','Северодвинск','Симферополь','Смоленск','Сочи','Ставрополь','Старый Оскол','Стерлитамак','Сургут','Сызрань','Сыктывкар','Таганрог','Тамбов','Тверь','Тольятти','Томск','Тула','Тюмень','Улан-Удэ','Ульяновск','Уссурийск','Уфа','Хабаровск','Химки','Чебоксары','Челябинск','Череповец','Чита','Шахты','Электросталь','Энгельс','Южно-Сахалинск','Якутск','Ярославль');
if (empty($_COOKIE["city"])) {
    $city = 'РОССИЯ';
} else {
    $city = $arrCity[$_COOKIE["city"]];
}

$h1 = '<h1>Популярные интернет-магазины</h1>';

$content = '<div class="mgs">';
foreach ($data as $columns) { //вывод категорий через $content
    $id = $columns['id'];
    $uri = $columns['uri'];
    $name = $columns['name'];

    $content .= '<div class="mg">';
    $content .= "<a href=\"/$uri/\">";
    $content .= "<img src=\"/images/logo/$uri.jpg\" alt=\"$name\" onload=\"goodLoadImg(this);\" onerror=\"errLoadImg(this);\">";
    $content .= '</a></div>'; 
}
$content .= '</div>';