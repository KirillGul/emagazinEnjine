<?php
include 'elems/password.php';
include '../elems//init.php';

if (isset($_SESSION['auth']) AND $_SESSION['auth'] == TRUE) {
   $info = '';
   $content = '';
   $titleValue = '';
   $urlValue = '';
   $textValue = '';

   $title = 'admin import page';

   function checkPage($link, $urlPOST) {
      $query = "SELECT COUNT(*) as count FROM pagesj WHERE url='$urlPOST'";
      $result = mysqli_query($link, $query) or die(mysqli_error($link));
      return mysqli_fetch_assoc($result)['count'];
   }

   function addPage($link, $titlePOST, $urlPOST, $textPOST) {
      $query = "INSERT INTO pagesj SET title='$titlePOST', url='$urlPOST', text='$textPOST'";
      mysqli_query($link, $query) or die(mysqli_error($link));
      return checkPage($link, $urlPOST);
   }


   if(isset($_POST['title'])) {
      $titlePOST = mysqli_real_escape_string($link, $_POST['title']);
      $titleValue = " value = '$titlePOST'";
   }
   if(isset($_POST['url'])) {
      $urlPOST = mysqli_real_escape_string($link, $_POST['url']);
      $urlValue = " value = '$urlPOST'";
   }
   if(isset($_POST['text'])) {
      $textPOST = $textValue = mysqli_real_escape_string($link, $_POST['text']);
   }

   if (!empty($titlePOST) AND !empty($urlPOST)) {
      if (checkPage($link, $urlPOST) == false) {
         $add = addPage($link, $titlePOST, $urlPOST, $textPOST);

         if ($add) {
            $_SESSION['info'] = [
               'msg' => "Успешно добавленно",
               'status' => 'success'
            ];
            header('Location: /admin/'); die();
         } else {
            $_SESSION['info'] = [
               'msg' => "Ошибка добавления",
               'status' => 'error'
            ];
         }
      } else {
         $_SESSION['info'] = [
            'msg' => "Такой URL существует",
            'status' => 'error'
         ];
      }
   } else {
      $_SESSION['info'] = [
         'msg' => "Введите значения title, url, [text]",
         'status' => 'warning'
      ];
   }

   $content = "<form method=\"POST\">
   title:<br><input name='title'$titleValue><br><br>
   url:<br><input name='url'$urlValue><br><br>
   text:<br><textarea name='text'>$textValue</textarea><br><br>
   <input type='submit'>
   </form>";

   include 'elems/layout.php';

   /*echo "<pre>";
   //var_dump($_SERVER['REQUEST_URI']);
   var_dump($_REQUEST);
   echo "</pre>";*/
} else {
   header('Location: /admin/login.php'); die();
}