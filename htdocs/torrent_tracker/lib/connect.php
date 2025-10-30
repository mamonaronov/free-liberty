<?php
//$db = mysqli_connect('localhost', 'root', '1234', 'torrents'); //[имя сервера],[логин],[пароль],[название БД]
//     echo "это файл конект <br/>";


// Подключение к базе данных
$db = new mysqli('localhost', 'root', '', 'torrents');
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}



// Проверка подключения к БД
/* if (!$db) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
} */


// это с отладки "Процедурный стиль"
/* проверка соединения */
/* if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
} */

//   echo "подключились к БД <br/>";
// Выбор базы данных
//mysqli_select_db($db, 'torrents');
?>
