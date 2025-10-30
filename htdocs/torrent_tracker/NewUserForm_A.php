<?php
//с помощью этого можно выводить ошибки
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */


//    echo 'летс гоу';
$db = mysqli_connect('localhost', 'root', '', 'torrents'); //[имя сервера],[логин],[пароль],[название БД]
// Проверка подключения к БД
if (!$db) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Выбор базы данных
mysqli_select_db($db, 'torrents');


require_once 'biblio/thumbs.php'; // класс для обрезки картинок

//     echo ' <br/>';
//     echo ' <br/>';
//     echo 'подготовка переменных для отправки в бд';

//Отправляем перменные для внесения информации в БД
if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		
		$login = $_POST['login'];
		$password = $_POST['password'];
		$password2 = $_POST['2password'];
		$email = $_POST['email'];
		$about_myself = $_POST['about_myself'];
		
		// это сделает неопределенные переменные пустыми
		$M_name = isset($_POST['name']) ? $_POST['name'] : '';
		$surname = isset($_POST['tasurnameg_ids']) ? $_POST['tasurnameg_ids'] : '';
		$type_id = isset($_POST['type_id']) ? $_POST['type_id'] : 0;
		$status_id = isset($_POST['status_id']) ? $_POST['status_id'] : 0;
		$likes_ids = isset($_POST['likes_ids']) ? $_POST['likes_ids'] : '';
	}
	
	$sql = "SELECT * FROM users WHERE login = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("s", $login);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows == 0) //если логин уникальный
	{
		if ($password == $password2) 
		{
			$password = password_hash($password2, PASSWORD_DEFAULT);
			// если все данные совпали то теперь можно сохранять картинки и грузить инфу в бд
			
			if ($_FILES && $_FILES["avatar"]["error"] == UPLOAD_ERR_OK) { // Проверка загрузки картинки
				$upload_dir = "res/Avatars/";
				$name_of_file = $_FILES["avatar"]["name"]; //
				$name = $upload_dir . basename($_FILES["avatar"]["name"]);

				// Получаем информацию о файле
				$file_info = pathinfo($name_of_file);
				$base_name = $file_info['filename']; // Имя файла без расширения
				$extension = $file_info['extension']; // Расширение файла
				/* echo ' <br/>';
				echo "base_name: ". $base_name;
				echo ' <br/>';
				echo "extension: ". $extension;
				echo ' <br/>';
				echo "name: ". $name;
				echo ' <br/>';
				echo "basename(: ". basename($_FILES["avatar"]["name"]);/////////////////////////////;
				echo ' <br/>';
				echo "name_of_file: ". $name_of_file;
				echo ' <br/>'; */
				
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
					
					
					
					//       echo ' <br/>';
					//       echo 'проверяем есть ли файлы с таким же именем';
					$new_name = $base_name . '.' . $extension;
					for (; file_exists($name);)
					{
						//   echo ' <br/>';
						//   echo "Такой файл уже есть...";
						
						$new_name = $base_name . ')' . '.' . $extension;
						$name = $upload_dir . $new_name;
						$base_name = $base_name . ')';
					}
					//      echo ' <br/>';
					//      echo "new_name: ". $new_name;////////
					//      echo ' <br/>';

					//      echo ' <br/>';
					
					// Перемещение загруженного файла в нужную директорию
					if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $name)) {
						//    echo "отладка 1";///////
						
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
								
							//    echo "отладка 2";
								
								$TuImg = new Thumbs($upload_dir . $new_name);
								$TuImg->cut(1000, 1000);
								$TuImg->save();
								$TuImg->cut(100, 100);
								$TuImg->saveJpg("res/Avatars100/" . $new_name, 90);
								
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
								
								//     echo "Изображение успешно загружено и обрезано.";
							} 
							else 
							{
								//     echo "Ошибка при обработке изображения.";
							}
							//    echo ' <br/>';			
						
						
						
						//        echo "Файл успешно загружен.";
						//        echo ' <br/>';
						//        echo 'путь и имя к загруженномой картинке: ', $name; // путь и имя к загруженной картинке
						//        echo ' <br/>';
					}
					else
					{
						//    echo "Ошибка при загрузке файла.";
					}
					//      echo '<br/>';
					//      echo 'только имя загруженного торрент файла: ',$new_name;//только имя загруженного торрент файла
					$Final_TF_name = $new_name;
				}
				else 
				{
					//    echo "Загруженный файл не является изображением или имеет недопустимое расширение.";
				}
			}
			else {
				switch ($_FILES["avatar"]["error"]) {
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
			
			
			// продожаем с бд
			echo '<br/>';
			echo 'login[', $login, ']';
			echo '<br/>';
			echo 'password[', $password, ']';
			echo '<br/>';
			echo 'email[', $email, ']';
			echo '<br/>';
			echo 'about_myself[', $about_myself, ']';
			echo '<br/>';
			echo 'avatar_address[', $new_name, ']';
			echo '<br/>';
			echo 'avatar_address_100[', $new_name, ']';
			echo '<br/>';
			/* echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>';
			echo 'name[', $name, ']';
			echo '<br/>'; */
			
			
			/* echo 'description[', $description, ']';
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
			echo 'rating_count[', $rating_count, ']'; */
			
			
			echo '<br/>';
			echo '*Вводим данные в бд*';
			echo '<br/>';

			//Ввод данных в БД (тут все работает, трушные имена на вводе, нужно откорректировать присваивание данных переменным перед отправкой)
			$sql_add = "INSERT INTO users
			(
				id, #комментарий в sql запросе
				login,
				password,
				email,
				name,
				surname,
				type_id,
				status_id,
				comments_ids,
				downloads_ids,
				avatar_address,
				avatar_address_100,
				about_myself,
				likes_ids,
				reg_time,
				last_online
			)
			VALUES (
				NULL,
				'$login',
				'$password',
				'$email',
				'$M_name',
				'$surname',
				'$type_id',
				'$status_id',
				'$comments_ids',
				'$downloads_ids',
				'$new_name',
				'$new_name',
				'$about_myself',
				'$likes_ids',
				NOW(),
				NOW()
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
		}
		else 
		{
			$error[] = "пароли не совпадают!";
			echo "ошибка: " . $error;///////
			
			$Link = 'NewUserForm.php?ErrType=' . $error[0];
			header("Location: " . $Link);
			exit();
		}
	}
	else 
	{
		$error[] = "логин '" . $login . "' уже занят!";
		echo "ошибка: " . $error;///////
		$Link = 'NewUserForm.php?ErrType=' . $error[0];
		header("Location: " . $Link);
		exit();
	}
// Закрытие соединения с БД
mysqli_close($db);
echo '<li><a href="Menu.php">Главная</a></li>';
?> 
