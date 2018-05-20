<!DOCTYPE html>
<html>
<head>
  <link href="style.css" rel="stylesheet">
  <meta charset="utf-8">
  <title> Доска объявлений </title>
</head>
<body>
  <header>
      <nav>
        <ul>
          <li><a href="upload.php">Загрузить новое объявление</a></li>
        </ul>
      </nav>
  </header>

<?php 

$result_per_page=50;

//Читаем config.ini переменную $config
//Используем значение из конфиг при подключении к БД
$cfg = parse_ini_file("../../config.ini");

//Подключение к БД
//$conn = new mysqli($host, $user, $password, $database);
$conn = new mysqli($cfg["host"],$cfg["user"],$cfg["password"],$cfg["database"]);

//Установка кодирования
$conn->set_charset("utf8");

//Проверяем соединение
if ($conn->connect_error) {
	die("Connect failed: " . $conn->connect_error);//?
}

if (isset($_GET["page"]))  {
		$page = $_GET["page"]; 
} else {
		$page=1;
};

// Лмитит результатов на страницу
$start_from = ($page-1)*$result_per_page;
$sql = "SELECT * FROM  ads ORDER BY created_at DESC limit $result_per_page OFFSET $start_from ";

$rs_result = $conn->query($sql);


?>
<!--Вывод результатов в таблице-->
<section>
<table class="main_table">
	<tr>
		<td>Дата</td>
		<td>Текст объявления</td>
		<td>Контакты</td>
	</tr>
  
  <?php

    if($rs_result) {
       while ($row = $rs_result->fetch_row()) 
       {
        printf ("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $row[1], $row[2], $row[3]);
       }
    }

  ?>
</table>
</section>
</br>

<?php 
//Подсчет общего колличества строк в БД
$q="SELECT count(*) FROM ads";
$res = $conn->query($q);
$row = $res->fetch_row();
$total_rows=$row[0];

//Возврат целого значения для страницы
$num_pages=intval(ceil($total_rows/$result_per_page));
if($num_pages == 0) {
  $num_pages = 1;
}
// var_dump($num_pages);

//Пагинация
	for($i=1; $i<=$num_pages; $i++) {
		if ($i == $page) {
			echo $i. " ";
		} else {
			echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i." </a>";
		}
	}

 ?>

</body>
</html>