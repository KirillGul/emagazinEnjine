<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="/assets/readmore.js"></script>
    <script src="/assets/scripts.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
    <?= $title ?>
    <?= $description ?>
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="logotype"><?php if(!empty($logo)) echo $logo;  //категория и товар?></div>
            <div class="searchForm">
                <form action="/search" method="POST">
                    <input type="text" name="searchText" placeholder="Я хочу найти...">
                    <button type="submit"></button>
                </form>
            </div>
            <ul>
				<li><a href="/">ВСЕ ИНТЕРНЕТ-МАГАЗИНЫ</a></li>
			</ul>
            <div class="clrb"></div>
            <div class="city">
                <a class="citybutton"><?= $city ?></a>
            </div>
        </header>
        <article>
            <?= $h1 ?>
            <?php if(!empty($countProduct)) echo $countProduct; //категория?>
            <?php if(!empty($pag)) echo $pag; //категория?>
            <?= $content ?>
            <?php if(!empty($pag)) echo $pag; //категория?>
            <div class="clrb"></div>
        </article>
        <footer>
            &copy; <?php echo $_SERVER['HTTP_HOST']; ?> - интернет-магазин, 2021          
        </footer>
    </div>
    <div class="citywindow_overlay"></div>
    <div class="citywindow">
        <div class="citywindow_title">Ваш город: <span class="citywindow_closer">X</span></div>
        <div class="citywindow_content">
            <div class="cblok">
                <ul>
                    <li class="letter">А</li><li><a href="javascript:SetCookie(1)">Абакан</a></li><li><a href="javascript:SetCookie(2)">Альметьевск</a></li><li><a href="javascript:SetCookie(3)">Ангарск</a></li><li><a href="javascript:SetCookie(4)">Армавир</a></li><li><a href="javascript:SetCookie(5)">Архангельск</a></li><li><a href="javascript:SetCookie(6)">Астрахань</a></li><li class="letter">Б</li><li><a href="javascript:SetCookie(7)">Балаково</a></li><li><a href="javascript:SetCookie(8)">Балашиха</a></li><li><a href="javascript:SetCookie(9)">Барнаул</a></li><li><a href="javascript:SetCookie(10)">Белгород</a></li><li><a href="javascript:SetCookie(11)">Березники</a></li><li><a href="javascript:SetCookie(12)">Бийск</a></li><li><a href="javascript:SetCookie(13)">Братск</a></li><li><a href="javascript:SetCookie(14)">Брянск</a></li><li class="letter">В</li><li><a href="javascript:SetCookie(15)">Великий Новгород</a></li><li><a href="javascript:SetCookie(16)">Владивосток</a></li><li><a href="javascript:SetCookie(17)">Владикавказ</a></li><li><a href="javascript:SetCookie(18)">Владимир</a></li><li><a href="javascript:SetCookie(19)">Волгоград</a></li><li><a href="javascript:SetCookie(20)">Волгодонск</a></li><li><a href="javascript:SetCookie(21)">Волжский</a></li><li><a href="javascript:SetCookie(22)">Вологда</a></li><li><a href="javascript:SetCookie(23)">Воронеж</a></li><li class="letter">Г</li><li><a href="javascript:SetCookie(24)">Грозный</a></li><li class="letter">Д</li><li><a href="javascript:SetCookie(25)">Дзержинск</a></li><li class="letter">Е</li><li><a href="javascript:SetCookie(26)">Екатеринбург</a></li><li class="letter">З</li><li><a href="javascript:SetCookie(27)">Зеленоград</a></li><li><a href="javascript:SetCookie(28)">Златоуст</a></li><li class="letter">И</li><li><a href="javascript:SetCookie(29)">Иваново</a></li><li><a href="javascript:SetCookie(30)">Ижевск</a></li><li><a href="javascript:SetCookie(31)">Йошкар-Ола</a></li><li><a href="javascript:SetCookie(32)">Иркутск</a></li></ul></div><div class="cblok"><ul><li class="letter">К</li><li><a href="javascript:SetCookie(33)">Казань</a></li><li><a href="javascript:SetCookie(34)">Калининград</a></li><li><a href="javascript:SetCookie(35)">Калуга</a></li><li><a href="javascript:SetCookie(36)">Каменск-Уральский</a></li><li><a href="javascript:SetCookie(37)">Кемерово</a></li><li><a href="javascript:SetCookie(38)">Керчь</a></li><li><a href="javascript:SetCookie(39)">Киров</a></li><li><a href="javascript:SetCookie(40)">Ковров</a></li><li><a href="javascript:SetCookie(41)">Коломна</a></li><li><a href="javascript:SetCookie(42)">Колпино</a></li><li><a href="javascript:SetCookie(43)">Комсомольск-на-Амуре</a></li><li><a href="javascript:SetCookie(44)">Копейск</a></li><li><a href="javascript:SetCookie(45)">Королёв</a></li><li><a href="javascript:SetCookie(46)">Кострома</a></li><li><a href="javascript:SetCookie(47)">Краснодар</a></li><li><a href="javascript:SetCookie(48)">Красноярск</a></li><li><a href="javascript:SetCookie(49)">Курган</a></li><li><a href="javascript:SetCookie(50)">Курск</a></li><li class="letter">Л</li><li><a href="javascript:SetCookie(51)">Липецк</a></li><li><a href="javascript:SetCookie(52)">Люберцы</a></li><li class="letter">М</li><li><a href="javascript:SetCookie(53)">Магнитогорск</a></li><li><a href="javascript:SetCookie(54)">Майкоп</a></li><li><a href="javascript:SetCookie(55)">Махачкала</a></li><li><a href="javascript:SetCookie(56)">Миасс</a></li><li><a href="javascript:SetCookie(57)">Москва</a></li><li><a href="javascript:SetCookie(58)">Мурманск</a></li><li><a href="javascript:SetCookie(59)">Мытищи</a></li><li class="letter">Н</li><li><a href="javascript:SetCookie(60)">Набережные Челны</a></li><li><a href="javascript:SetCookie(61)">Нальчик</a></li><li><a href="javascript:SetCookie(62)">Находка</a></li><li><a href="javascript:SetCookie(63)">Нефтеюганск</a></li><li><a href="javascript:SetCookie(64)">Нижневартовск</a></li><li><a href="javascript:SetCookie(65)">Нижнекамск</a></li><li><a href="javascript:SetCookie(66)">Нижний Новгород</a></li><li><a href="javascript:SetCookie(67)">Нижний Тагил</a></li><li><a href="javascript:SetCookie(68)">Новокузнецк</a></li><li><a href="javascript:SetCookie(69)">Новороссийск</a></li><li><a href="javascript:SetCookie(70)">Новосибирск</a></li><li><a href="javascript:SetCookie(71)">Новочеркасск</a></li><li><a href="javascript:SetCookie(72)">Норильск</a></li></ul></div><div class="cblok"><ul><li class="letter">О</li><li><a href="javascript:SetCookie(73)">Одинцово</a></li><li><a href="javascript:SetCookie(74)">Омск</a></li><li><a href="javascript:SetCookie(75)">Орел</a></li><li><a href="javascript:SetCookie(76)">Оренбург</a></li><li><a href="javascript:SetCookie(77)">Орск</a></li><li class="letter">П</li><li><a href="javascript:SetCookie(78)">Пенза</a></li><li><a href="javascript:SetCookie(79)">Пермь</a></li><li><a href="javascript:SetCookie(80)">Петрозаводск</a></li><li><a href="javascript:SetCookie(81)">Петропавловск-Камчатский</a></li><li><a href="javascript:SetCookie(82)">Подольск</a></li><li><a href="javascript:SetCookie(83)">Прокопьевск</a></li><li><a href="javascript:SetCookie(84)">Псков</a></li><li><a href="javascript:SetCookie(85)">Пятигорск</a></li><li class="letter">Р</li><li><a href="javascript:SetCookie(86)">Ростов-на-Дону</a></li><li><a href="javascript:SetCookie(87)">Рубцовск</a></li><li><a href="javascript:SetCookie(88)">Рыбинск</a></li><li><a href="javascript:SetCookie(89)">Рязань</a></li><li class="letter">С</li><li><a href="javascript:SetCookie(90)">Салават</a></li><li><a href="javascript:SetCookie(91)">Самара</a></li><li><a href="javascript:SetCookie(92)">Санкт-Петербург</a></li><li><a href="javascript:SetCookie(93)">Саранск</a></li><li><a href="javascript:SetCookie(94)">Саратов</a></li><li><a href="javascript:SetCookie(95)">Севастополь</a></li><li><a href="javascript:SetCookie(96)">Северодвинск</a></li><li><a href="javascript:SetCookie(97)">Симферополь</a></li><li><a href="javascript:SetCookie(98)">Смоленск</a></li><li><a href="javascript:SetCookie(99)">Сочи</a></li><li><a href="javascript:SetCookie(100)">Ставрополь</a></li><li><a href="javascript:SetCookie(101)">Старый Оскол</a></li><li><a href="javascript:SetCookie(102)">Стерлитамак</a></li><li><a href="javascript:SetCookie(103)">Сургут</a></li><li><a href="javascript:SetCookie(104)">Сызрань</a></li><li><a href="javascript:SetCookie(105)">Сыктывкар</a></li></ul></div><div class="cblok"><ul><li class="letter">Т</li><li><a href="javascript:SetCookie(106)">Таганрог</a></li><li><a href="javascript:SetCookie(107)">Тамбов</a></li><li><a href="javascript:SetCookie(108)">Тверь</a></li><li><a href="javascript:SetCookie(109)">Тольятти</a></li><li><a href="javascript:SetCookie(110)">Томск</a></li><li><a href="javascript:SetCookie(111)">Тула</a></li><li><a href="javascript:SetCookie(112)">Тюмень</a></li><li class="letter">У</li><li><a href="javascript:SetCookie(113)">Улан-Удэ</a></li><li><a href="javascript:SetCookie(114)">Ульяновск</a></li><li><a href="javascript:SetCookie(115)">Уссурийск</a></li><li><a href="javascript:SetCookie(116)">Уфа</a></li><li class="letter">Х</li><li><a href="javascript:SetCookie(117)">Хабаровск</a></li><li><a href="javascript:SetCookie(118)">Химки</a></li><li class="letter">Ч</li><li><a href="javascript:SetCookie(119)">Чебоксары</a></li><li><a href="javascript:SetCookie(120)">Челябинск</a></li><li><a href="javascript:SetCookie(121)">Череповец</a></li><li><a href="javascript:SetCookie(122)">Чита</a></li><li class="letter">Ш</li><li><a href="javascript:SetCookie(123)">Шахты</a></li><li class="letter">Э</li><li><a href="javascript:SetCookie(124)">Электросталь</a></li><li><a href="javascript:SetCookie(125)">Энгельс</a></li><li class="letter">Ю</li><li><a href="javascript:SetCookie(126)">Южно-Сахалинск</a></li><li class="letter">Я</li><li><a href="javascript:SetCookie(127)">Якутск</a></li><li><a href="javascript:SetCookie(128)">Ярославль</a></li>
                </ul>
            </div>
            <div class="clrb"></div>
        </div>
    </div>
</body>
</html>