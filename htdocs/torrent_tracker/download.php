<?php
/* // Подключение к базе данных
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 */

include ('lib/connect.php'); //подключаемся к БД





// Получение адреса файла из параметра запроса
$file_address = $_GET['file'];

// Проверка, существует ли запись для этого файла в базе данных
$sql = "SELECT * FROM files WHERE file_address = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $file_address);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Если запись существует, увеличиваем счетчик скачиваний
    $sql = "UPDATE files SET downloads_count = downloads_count + 1 WHERE file_address = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $file_address);
    $stmt->execute();
} else {
    // Если записи нет
	echo 'файла с таким адресом нету';
}

// Отправка файла пользователю
$file_path = 'res/TFiles/' . $file_address;
if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
} else {
    echo "File not found.";
}

$db->close();
?>
