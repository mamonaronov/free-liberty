<?php
//с помощью этого можно выводить ошибки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 


echo 'летс гоу';
$db = mysqli_connect('localhost', 'root', '', 'torrents'); //[имя сервера],[логин],[пароль],[название БД]
// Проверка подключения к БД
if (!$db) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Выбор базы данных
mysqli_select_db($db, 'torrents');

require_once 'biblio/thumbs.php'; // класс для обрезки картинок


// узнаем id публикующего пользователя
$login = $_COOKIE['login'];
$sql = "SELECT * FROM users WHERE login = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); // получаем из БД массив-строку пользователя





// Проверка загрузки файла торрент!!!!!!
if ($_FILES && $_FILES["filename"]["error"] == UPLOAD_ERR_OK) {
    $upload_dir = "res/TFiles/";
	$name_of_file = $_FILES["filename"]["name"]; //
    $name = $upload_dir . basename($_FILES["filename"]["name"]);

    // Получаем информацию о файле
    $file_info = pathinfo($name_of_file);
    $base_name = $file_info['filename']; // Имя файла без расширения
    $extension = $file_info['extension']; // Расширение файла


    // Проверка существования директории и прав на запись
    if (!is_dir($upload_dir)) {
        die("Директория для загрузки файлов не существует.");
    }
    if (!is_writable($upload_dir)) {
        die("Директория для загрузки файлов не доступна для записи.");
    }
	echo ' <br/>';
	echo 'проверяем есть ли файлы с таким же именем';
	$new_name = $base_name . '.' . $extension;
 	for (; file_exists($name);)
	{
		echo ' <br/>';
		echo "Такой файл уже есть...";
		
		$new_name = $base_name . ')' . '.' . $extension;
		$name = $upload_dir . $new_name;
		$base_name = $base_name . ')';
	} 

	echo ' <br/>';
	
    // Перемещение загруженного файла в нужную директорию
    if (move_uploaded_file($_FILES["filename"]["tmp_name"], $name)) {
        echo "Файл успешно загружен."; 
		echo ' <br/>';
		echo 'путь и имя к загруженному торрент файлу: ', $name; // путь и имя к загруженному торрент файлу
    } else {
        echo "Ошибка при загрузке файла.";
    }
	echo '<br/>';
	echo 'только имя загруженного торрент файла: ',$new_name;//только имя загруженного торрент файла
	$Final_TF_name = $new_name;
} 
else {
    switch ($_FILES["filename"]["error"]) {
        case UPLOAD_ERR_INI_SIZE:
            echo "Размер файла превышает допустимый размер, указанный в php.ini.";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo "Размер файла превышает допустимый размер, указанный в HTML-форме.";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "Файл был загружен только частично.";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "Файл не был загружен.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "Отсутствует временная папка.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "Не удалось записать файл на диск.";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "Загрузка файла была остановлена расширением PHP.";
            break;
        default:
            echo "Неизвестная ошибка загрузки файла.";
            break;
    }
}

echo '<br/>';
echo "теперь работаем с картинкой";
	
// Проверка загрузки файла картинки!!!!!
if ($_FILES && $_FILES["picturename"]["error"] == UPLOAD_ERR_OK) {
    $upload_dir = "res/Tpics/";
	$name_of_file = $_FILES["picturename"]["name"]; //
    $name = $upload_dir . basename($_FILES["picturename"]["name"]);

    // Получаем информацию о файле
    $file_info = pathinfo($name_of_file);
    $base_name = $file_info['filename']; // Имя файла без расширения
    $extension = $file_info['extension']; // Расширение файла
	
	
	
	
	// Разрешенные расширения
	$allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

	if (in_array($extension, $allowedfileExtensions)) 
	{

		// Проверка существования директории и прав на запись
		if (!is_dir($upload_dir)) {
			die("Директория для загрузки файлов не существует.");
		}
		if (!is_writable($upload_dir)) {
			die("Директория для загрузки файлов не доступна для записи.");
		}
		
		echo ' <br/>';
		echo 'проверяем есть ли картинки с таким же именем';
		$new_name = $base_name . '.' . $extension;
		for (; file_exists($name);)
		{
				echo '<br/>';
				echo "Такая картинка уже есть...";
					
				$new_name = $base_name . ')' . '.' . $extension;
				$name = $upload_dir . $new_name;
				$base_name = $base_name . ')';
		} 

		// Перемещение загруженного файла в нужную директорию
		if (move_uploaded_file($_FILES["picturename"]["tmp_name"], $name)) {
			
			// Обрезка изображения
				$image = imagecreatefromjpeg($name);
				if (!$image) {
					$image = imagecreatefrompng($name);
				}
				if (!$image) {
					$image = imagecreatefromgif($name);
				}
				if ($image) 
				{
					
				echo "отладка 2";
					
					$TuImg = new Thumbs($name);
					$TuImg->cut(1000, 1000);
					$TuImg->save();
					/* $TuImg->cut(100, 100);
					$TuImg->saveJpg("res/Avatars100/" . $new_name, 90); */
					
					/* $width = imagesx($image);
					$height = imagesy($image);
					// Устанавливаем размеры обрезки
					$new_width = 500;
					$new_height = 500;
					$thumb = imagecreatetruecolor($new_width, $new_height);
					imagecopyresampled($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					
					// Сохраняем обрезанное изображение
					$thumbPath = $upload_dir . 'thumb_' . $new_name;
					imagejpeg($thumb, $thumbPath, 90); 
					*/
					imagedestroy($image);
					//imagedestroy($thumb);
					
					echo "Изображение успешно загружено и обрезано.";
				} 
				else 
				{
					echo "Ошибка при обработке изображения.";
				}
				echo ' <br/>';		
			
			echo ' <br/>';
			echo 'путь и имя к загруженной картинке: ', $name; // путь и имя к загруженному торрент файлу
		} 
		else {
			echo "Ошибка при загрузке файла.";
		}
		
		echo '<br/>';
		echo 'только имя загруженнй картинки: ',$new_name; //только имя загруженной картинки
	}
} 
else {
    switch ($_FILES["picturename"]["error"]) {
        case UPLOAD_ERR_INI_SIZE:
            echo "Размер файла превышает допустимый размер, указанный в php.ini.";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo "Размер файла превышает допустимый размер, указанный в HTML-форме.";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "Файл был загружен только частично.";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "Файл не был загружен.";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "Отсутствует временная папка.";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "Не удалось записать файл на диск.";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "Загрузка файла была остановлена расширением PHP.";
            break;
        default:
            echo "Неизвестная ошибка загрузки файла.";
            break;
    }
}






echo ' <br/>';
echo ' <br/>';
echo 'подготовка переменных для отправки в бд';

//Отправляем перменные для внесения информации в БД
if($_SERVER['REQUEST_METHOD'] == 'POST')
	{	




		$user_id = $row['id']; // из массива-строки извлекаем id пользователя
		

		echo ' <br/>';
		echo ' <br/>';
		echo ' <br/>';
		echo 'user_id = ' . $user_id;
		echo ' <br/>';
		echo ' <br/>';
		
		
		$name = $_POST['name'];
		$description = $_POST['description'];
		$author_id = $user_id;////////////////////////////////////////////////////////////
			//$author_id = $_POST['author_id'];
			// $publication_time = '2001-09-11 16:46:00';
			//$publication_time = date("m.d.y H:i:s ");
			// $publication_time =date('Y-m-d H:i:s');
			// $timeBro strtotime($publication_time)
			// $publication_time = $timeBro
			// $query = "INSERT INTO timeTable(time) VALUES ('$dates')";
			//$publication_time = $_POST['publication_time'];
		$tag_ids = '';
		//$tags = $_POST['tags'];
		$comments_ids = '';
		$downloads_count = 0;
		$file_addres = $Final_TF_name; //потом поменять на норм имена
		$main_picture_address = $new_name;
		$small_picture_addres = $new_name;
		$downloaders_ids = '';
		$rating_numerator = 0;
		$rating_denominator = 0;
		$rating_ids = '';
		$rating_count = 0;
		
	}
	
	
	
	

	echo '<br/>';
	echo 'name[', $name, ']';
	echo '<br/>';
	echo 'description[', $description, ']';
	echo '<br/>';
	echo 'author_id[', $author_id, ']';
	// echo '<br/>';
	// echo 'publication_time[', $publication_time, ']';
	echo '<br/>';
	echo 'tag_ids[', $tag_ids, ']';
	echo '<br/>';
	echo 'comments_ids[', $comments_ids, ']';
	echo '<br/>';
	echo 'downloads_count[', $downloads_count, ']';
	echo '<br/>';
	echo 'file_addres[', $file_addres, ']';//file_address в таблице
	echo '<br/>';
	echo 'main_picture_addres[', $main_picture_addres, ']';
	echo '<br/>';
	echo 'small_picture_addres[', $small_picture_addres, ']';
	echo '<br/>';
	echo 'downloaders_ids[', $downloaders_ids, ']';
	echo '<br/>';
	echo 'rating_numerator[', $rating_numerator, ']';
	echo '<br/>';
	echo 'rating_denominator[', $rating_denominator, ']';
	echo '<br/>';
	echo 'rating_ids[', $rating_ids, ']';
	echo '<br/>';
	echo 'rating_count[', $rating_count, ']';
	
	
	echo '<br/>';
	echo '*Вводим данные в бд*';
	echo '<br/>';

	//Ввод данных в БД (тут все работает, трушные имена на вводе, нужно откорректировать присваивание данных переменным перед отправкой)
	$sql_add = "INSERT INTO files
    (
        id,
        name,
        decription,
        author_id,
        publication_time,
        tag_ids,
        comments_ids,
        downloads_count,
        file_address,
        main_picture_address,
        small_picture_address,
        downloaders_ids,
        rating_numerator,
        rating_denominator,
        rating_ids,
        rating_count
    )
    VALUES (
        NULL,
        '$name',
        '$description',
        '$author_id',
        NOW(),
        '$tag_ids',
        '$comments_ids',
        '$downloads_count',
        '$file_addres',
        '$main_picture_address',
        '$small_picture_address',
        '$downloaders_ids',
        '$rating_numerator',
        '$rating_denominator',
        '$rating_ids',
        '$rating_count'
    )";
	
	
	echo '<br/>';
	echo '*второй этап*';
	echo '<br/>';
	
	$result_add=mysqli_query($db, $sql_add);
	if ($result_add == false) {
    print("Произошла ошибка при выполнении запроса");
	}

	echo '<br/>';
	echo '*Загрузка прошла успешно*';
	echo '<br/>';
	
	echo '<li><a href="Menu.php">Главная</a></li>';
// Закрытие соединения с БД
mysqli_close($db);
?> 
