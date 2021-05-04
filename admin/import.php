<?php
include 'elems/password.php';
include '../elems//init.php';

if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {
   $content = '';
   $title = 'admin import page';

   if(isset($_GET['id'])) {
      $uploaddir = 'files/';
      $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

      
      if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
         echo "Файл корректен и был успешно загружен.\n";
      } else {
         echo "Возможная атака с помощью файловой загрузки!\n";
      }

      $content .= "
      <br><form method=\"POST\" enctype=\"multipart/form-data\" action=\"\">
         Выберите файл XML с продуктами:<br>
         <input type=\"file\" name=\"userfile\"><br>
			<input type=\"submit\" value=\"Загрузить файл для обработки\">
      </form><br>";

   } else {
      $_SESSION['info'] = [
         'msg' => "Ошибка при входе в импорт",
         'status' => 'error'
      ];
      header('Location: index.php'); die();
   }

   include 'elems/layout.php';

   echo "<pre>";
   var_dump($_GET['id']);
   echo "</pre>";

   /*echo '<pre>';
   print_r($_FILES);
   print "</pre>";*/
} else {
   header('Location: /admin/login.php'); die();
}