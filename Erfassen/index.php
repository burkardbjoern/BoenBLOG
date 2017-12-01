<?php
$error = [
];
/*
echo 'POST: <br />';
var_dump($_POST);
echo '<hr />';
echo 'GET: <br />';
var_dump($_REQUEST);
*/
$benutzer='';
$blog='';


if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $benutzer = $_POST["benutzer"];
    $passwd = $_POST["passwd"];
    $blog = trim($_POST["eintrag"]);
    $groesse = strlen($blog);

    $dbconnection = new PDO('mysql:host=localhost;dbname=blog','root','');
    $stmt = $dbconnection->query("SELECT user FROM user WHERE user ='$benutzer'");
    $user_array = $stmt -> fetchAll();
    error_reporting(0);
    if($user_array[0][0] != $benutzer)
    {
      $error[] = '-Der Benutzername exsistiert nicht';
    }
    $stmt = $dbconnection->query("SELECT oldpasswd FROM user WHERE user ='$benutzer'");
    $user_array = $stmt -> fetchAll();
    error_reporting(0);
    if($user_array[0][0] != $passwd)
    {
      $error[] = '-Das Passwort ist falsch';
    }
    if($groesse > 400)
    {
      $error[] = '-Der Blogeintrag ist zu lange';
      $error[] = "-Die max. Länge beträgt 400 Zeichen, dein Blogeintrag beträgt $groesse Zeichen";
    }

    if($benutzer === '')
    {
     $error[] = '-Geben sie einen Benutzernamen an';
    }
    if($blog === '')
    {
     $error[] = '-Ihr aktueller Post ist noch nicht mit Zeichen gefüllt';
    }
    if($blog !== '')
    {
      if(strpos($blog, ' ') === false)
      {
        $error[] = '-Ihr Text enthält keine Leerzeichen';
      }
    }

}
if(empty($error) && !empty($benutzer) && !empty($blog))
{
$blog = nl2br($blog);
$blog = strip_tags($blog, '<img><a><br>');
$benutzer = htmlspecialchars($benutzer);
$stmt = '';
$dbconnection = new PDO('mysql:host=localhost;dbname=blog','root','');
$stmt = $dbconnection->query("INSERT INTO posts(user_name,blog_post,post_time) VALUES ('$benutzer','$blog',curtime())");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
  <title>BLJ-Blog</title>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
  <?php include 'views/erfassen.view.php' ?>
</body>
</html>
