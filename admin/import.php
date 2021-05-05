<?php
//время выполнения скрипта в сек. (0-бесконечно)
set_time_limit(0);

include 'elems/password.php';
include '../elems//init.php';

/*установка cookie из формы*/
if (isset($_POST['check_image_one'])) $checkImage = $_POST['check_image_one'].'';
else $checkImage = 'No';

if (isset($_POST['check_param_product'])) $checkParam = $_POST['check_param_product'].'';
else $checkParam = 'No';

if (isset($_POST['check_avaliable'])) $checkAvaliable = $_POST['check_avaliable'].'';
else $checkAvaliable = 'No';

setcookie("check_image_one", $checkImage);
setcookie("check_param_product", $checkParam);
setcookie("check_avaliable", $checkAvaliable);

/*логика*/
if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {
   $title = 'admin import page';

   $info = '';
    if (isset($_SESSION['info'])) {
        $info = $_SESSION['info'];
    }

   if (isset($_GET['id']) AND isset($_FILES['userfile'])) {

      $content = "
      <br>
      <form method=\"POST\" enctype=\"multipart/form-data\">
         Выберите файл XML с продуктами:<br>
         <input type=\"file\" name=\"userfile\"><br>
         <input type=\"submit\" value=\"Загрузить файл для обработки\">
      </form><br><br>
      <form method=\"POST\">
         <span>Загружать все картинки? - <input type=\"checkbox\" value=\"Yes\" name=\"check_image_one\"></span><br>
         <span>Не загружать параметры товара? - <input type=\"checkbox\" value=\"Yes\" name=\"check_param_product\"></span><br>
         <span>Загружать товары которые в наличии(true) (использовать только для первичной загрузки) - <input type=\"checkbox\" value=\"Yes\" name=\"check_avaliable\"></span><br><br>
         <input type=\"submit\" value=\"Подтвердить\">
      </form><br><br>";

      /*передача через форму*/
      $uploaddir = 'files/';
      $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

      
      if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
         echo "Файл корректен и был успешно загружен.\n";
      } else {
         echo "Возможная атака с помощью файловой загрузки!\n";
      }

      /*запись файла в базу*/
	   if (file_exists($uploadfile)) {
         $xml = $uploadfile;
         $reader = new XMLReader(); //логика считывания: (элемент начало + атрубуты->значение->элемент конец) и всё через $reader->read();

         //ДЛЯ КАТЕГОРИЙ
         $reader->open($xml);

         //category
         $cat = [];
         while ($reader->read()) { //работает если категории находяться перед товарами
            if ($reader->name === "category" && $reader->nodeType == XMLReader::ELEMENT) {
               $temp = $reader->getAttribute('id');
               $reader->read();				
               $cat["$temp"] = $reader->value;
            } elseif (($reader->name == "categories" && $reader->nodeType == XMLReader::END_ELEMENT) || ($reader->name == "offer" && $reader->nodeType == XMLReader::ELEMENT)) break;
         }
         //$reader->close();
         //var_dump($cat);
         //Конец парсинга категорий

         //ДЛЯ ТОВАРОВ
         $countProd = 0;
         $countProdUniq = 0;
         $countProdAvaliable = 0;
         $prodArr = [];
         $prodUrlArr = []; //массив для проверки дублей товаров
         $categoryId = $_GET['id'];

         //Начало парсинга товаров
         //$reader->open($xml);

         while ($reader->read()) {
            $prodAvailable = $prodCategoryId = $prodCurrencyId = $prodDescription = $prodId = $prodModified_time = $prodName = $prodOldPrice = $prodParam = $prodPicture = $prodPrice = $prodType =$prodUrl = $prodVendor = $prodVendorCode = $prodGroupId = $prodTopSeller = $prod = ''; //обнуление значений
            
            if ($reader->name == "offer" && $reader->nodeType == XMLReader::ELEMENT) {
               $countProd++; //счетчик товаров
               
               $prodAvailable = $reader->getAttribute('available')."";
               
               if ($_COOKIE['check_avaliable'] == 'Yes') { //пропуск итерации если нет в наличии (при выставленной галочке)
                  if ($prodAvailable === 'false' or $prodAvailable === 'False'  or $prodAvailable === 'FASLE' or $prodAvailable === '0' or $prodAvailable === 0) continue;
               }
               
               $prodId = $reader->getAttribute('id')."";
               $prodGroupId = $reader->getAttribute('group_id')."";
               
               $countProdAvaliable++; //счетчик товаров
               $prodPicture = '';
               $prodParam = '';

               while ($reader->read()) {
                  if ($reader->nodeType == XMLReader::ELEMENT) { //проверка что это открывающий тег например <item>, а не </item>
                     switch ($reader->name) {
                        case "categoryId":
                           $reader->read();
                           $prodSubCategoryId = $reader->value;
                           continue 2;
                        case "currencyId":
                           $reader->read();
                           $prodCurrencyId = $reader->value;
                           continue 2;
                        case "description":
                           $reader->read();
                           $prodDescription = $reader->value;
                           $prodDescription = trim($prodDescription); //убираем переносы строк по краям
                           $prodDescription = str_replace(array("\r\n", "\r", "\n"), ' ', $prodDescription); //убираем переносы строк ввнутри
                           continue 2;
                        case "modified_time":
                           $reader->read();
                           $prodModified_time = $reader->value;
                           continue 2;
                        case "name":
                           $reader->read();
                           $prodName = $reader->value;
                           continue 2;
                        case "oldprice":
                           $reader->read();
                           $prodOldPrice = $reader->value;
                           continue 2;
                        case "price":
                           $reader->read();
                           $prodPrice = $reader->value;
                           continue 2;
                        case "type":
                           $reader->read();
                           $prodType = $reader->value;
                           continue 2;
                        case "url":
                           $reader->read();
                           $prodUrl = $reader->value;
                           array_push($prodUrlArr, $prodUrl); //для проверки дублей
                           continue 2;
                        case "vendor":
                           $reader->read();
                           $prodVendor = $reader->value;
                           continue 2;
                        case "vendorCode":
                           $reader->read();
                           $prodVendorCode = $reader->value;
                           continue 2;
                        case "topseller":
                           $reader->read();
                           $prodTopSeller = $reader->value;
                           continue 2;
                        case "param":
                           if ($_COOKIE['check_param_product'] == 'Yes') {
                              $prodParam = '';
                           } else {
                              $atrib = $reader->getAttribute('name');
                              if ($atrib == 'origin_url') continue 2; //пропуск определенной параметра
                              $reader->read();
                              $prodParam .= $atrib.':'.$reader->value."&-&-&";
                           }
                           continue 2;
                        case "picture":
                           if ($_COOKIE['check_image_one'] == 'Yes') {									
                              $reader->read();
                              $picture = $reader->value;	
                              $prodPicture .= $picture."&-&-&";										
                           } else {
                              $reader->read();
                              $prodPicture = $reader->value;
                           }
                           continue 2;
                     }
                  }
                  if ($reader->name == "offer" && $reader->nodeType == XMLReader::END_ELEMENT) break;					
               }
               
               $prodSubCategoryName = $cat["$prodSubCategoryId"].""; //добавляем имя категории

               $prod = [
                  'available'=>$prodAvailable,
                  'subcategoryid'=>$prodSubCategoryId,
                  'subcategoryname'=>$prodSubCategoryName,
                  'CurrencyId'=>$prodCurrencyId,
                  'description'=>$prodDescription,
                  'Id'=>$prodId,
                  'modifiedtime'=>$prodModified_time,
                  'name'=>$prodName,
                  'oldprice'=>$prodOldPrice,
                  'param'=>$prodParam,
                  'picture'=>$prodPicture,
                  'price'=>$prodPrice,
                  'type'=>$prodType,
                  'produrl'=>$prodUrl,
                  'vendor'=>$prodVendor,
                  'vendorcode'=>$prodVendorCode,                  
                  'groupid'=>$prodGroupId,
                  'topseller'=>$prodTopSeller
               ];
               //echo "$prodId<br>";
               
               array_push($prodArr, $prod); //записываем данные в массив				
            }
         }

         $reader->close();
		
         //Запись данных в файл
         //$f = fopen("../_temp/".trim($value, ".xml").".txt", 'w');
         
         //Подготовка массива с дублями
         $duplicates = array_diff_assoc($prodUrlArr, array_unique($prodUrlArr)); //массив дублей с ключями
         //var_dump($duplicates);
         
         foreach ($prodArr as $key => $value1) {
            ///УДАЛЯЕМ ДУБЛИ			
            if (!array_key_exists($key, $duplicates)) {
               //fwrite($f, $value1);
               $countProdUniq++;

               $available = $value1['available'].'';
               $subcategoryid = $value1['subcategoryid'].'';
               $subcategoryname = $value1['subcategoryname'].'';
               $description = $value1['description'].'';
               $modifiedtime = $value1['modifiedtime'].'';
               $name = $value1['name'].'';
               $oldprice = $value1['oldprice'].'';
               $price = $value1['price'].'';
               $param = $value1['param'].'';
               $picture = $value1['picture'].'';
               $type = $value1['type'].'';
               $produrl = $value1['produrl'].'';
               $vendor = $value1['vendor'].'';
               $vendorcode = $value1['vendorcode'].'';
               $groupid = $value1['groupid'].'';
               $topseller = $value1['topseller'].'';

               $query = "INSERT INTO product SET 
               url='$countProdUniq', 
               available='$available', 
               category_id='$categoryId',
               category_sub_id='$subcategoryid',
               category_sub_name='$subcategoryname',
               description='$description',
               modified_time='$modifiedtime',
               name='$name',
               oldprice='$oldprice',
               price='$price',
               param='$param',
               picture='$picture',
               type='$type',
               produrl='$produrl',
               vendor='$vendor',
               vendorcode='$vendorcode',
               groupid='$groupid',
               topseller='$topseller'
               ";
               $result = mysqli_query($link, $query) or die(mysqli_error($link));
               /*echo '<pre>';
               print_r($value1);
               print "</pre>";*/
            }
            ///--------------------------------------			
         }
         //fclose($f);

         //return "<p><img src=\"img/ok.png\"><span>Всего ".$countProd." в наличии(true) - ".$countProdAvaliable." (в т.ч. уник. - ".$countProdUniq.", дубли - ".($countProd-$countProdUniq).") - ".$value." обработан.</span></p>";

         $_SESSION['info'] = [
            'msg' => "Файл импорта загружен",
            'status' => 'success'
         ];

      } else {
         $_SESSION['info'] = [
            'msg' => "Файл не найден",
            'status' => 'error'
         ];
      }

      /*удаление файла*/
      

   } else {
      $_SESSION['info'] = [
         'msg' => "Выберите файл для импорта",
         'status' => 'warning'
      ];
      $content = "
      <br>
      <form method=\"POST\" enctype=\"multipart/form-data\">
         Выберите файл XML с продуктами:<br>
         <input type=\"file\" name=\"userfile\"><br>
         <input type=\"submit\" value=\"Загрузить файл для обработки\">
      </form><br><br>
      <form method=\"POST\">
         <span>Загружать все картинки? - <input type=\"checkbox\" value=\"Yes\" name=\"check_image_one\"></span><br>
         <span>Не загружать параметры товара? - <input type=\"checkbox\" value=\"Yes\" name=\"check_param_product\"></span><br>
         <span>Загружать товары которые в наличии(true) (использовать только для первичной загрузки) - <input type=\"checkbox\" value=\"Yes\" name=\"check_avaliable\"></span><br><br>
         <input type=\"submit\" value=\"Подтвердить\">
      </form><br><br>";
   }

   $content .= "<p><a href=\"index.php\">В админку</a></p>";

   include 'elems/layout.php';

   /*echo '<pre>';
   print_r($cat);
   print "</pre>";*/

} else {
   header('Location: /admin/login.php'); die();
}