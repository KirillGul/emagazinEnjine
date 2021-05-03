<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">	
    <link rel="stylesheet" href="/assets/style.css">
    <title>Такой страницы нет на сайте</title>
</head>
<body>
    <div id="wrapper">
        Такая страница отсутствует на нашем сайте. Перейти к разделу <a href="/">"Популярные интернет-магазины"</a><br>Перенаправление через <span id="count">12</span> сек.<br>
        <script>
            var counter = 12;
            setInterval (function(){  
                counter--;
                if(counter < 1) window.location = '/';
                else document.getElementById('count').innerHTML = counter;
            }, 800);
        </script>
    </div>
</body>
</html>