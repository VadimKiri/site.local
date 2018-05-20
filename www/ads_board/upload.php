<!DOCTYPE html>
<html>
<head>
  <link href="style.css" rel="stylesheet">
  <meta charset="utf-8">
  <title> Подать объявление </title>
</head>
<body>
	  <header>
      <nav>
        <ul>
          <li><a href="index.php">Посмотреть все объявления</a></li>
        </ul>
      </nav>
  </header>

<form enctype="multipart/form-data" action="upload.php" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Отправить" />
</form>

<?php

//Читаем config.ini переменную $config
//Используем значение из конфиг при подключении к БД
$cfg = parse_ini_file("../../config.ini");

//Подключение к БД
//$conn = new mysqli($host, $user, $password, $database);
$conn = new mysqli($cfg["host"],$cfg["user"],$cfg["password"],$cfg["database"]);

//Установка кодирования
$conn->set_charset("utf8");

if ($conn->connect_error) {
	die("Connect failed: " . $conn->connect_error);
};

if (isset($_FILES['userfile'])) {
	$tmp_name = $_FILES["userfile"]["tmp_name"]; 
	if (($handle = fopen($tmp_name, "r")) !== FALSE) {
		//
		$failed = 0; 
		$uploaded = 0;
  		while (($data = fgetcsv($handle, 1000)) !== FALSE) {
  			//var_dump($data);
  			//Проверяем элементы массива на длинну строки
  			if(strlen($data[0]) > 200 or strlen($data[1]) > 80) {
  				$failed+=1;
  				continue;
  			} 

  			$text=$conn->real_escape_string($data[0]);
  			$contact=$conn->real_escape_string($data[1]);
  			
  			// Отправляем запрос к БД на добавление новой записи
  			$q="INSERT INTO ads (text, contacts) VALUES ('".$text."', '".$contact."');";
			$res = $conn->query($q);
			$uploaded+=1;
  		}

  		echo "Загружено $uploaded | Ошибка: $failed"; 

  	}

	fclose($handle); // Закрываем файл, на который указывает дескриптор handle
}

?>

</body>
</html>