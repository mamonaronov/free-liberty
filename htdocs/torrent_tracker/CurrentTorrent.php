<?php
// Подключение к базе данных
//$servername = "localhost";
//$username = "username";
//$password = "password";
//$dbname = "torrent_tracker";

//$conn = new mysqli($localhost, $username, $password, $dbname);

$conn = new mysqli("localhost", "root", "", "torrents");
// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$torrent_id = $_GET["itemId"];

// Получение ID раздачи из GET-запроса
//$torrent_id = $_GET['id'];

// Запрос к базе данных для получения информации о раздаче
$sql = "SELECT * FROM files WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $torrent_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $torrent = $result->fetch_assoc();
} else {
    echo "Раздача не найдена.";
    exit();
}

$author_id = $torrent["author_id"];

// Запрос к базе данных для получения информации о пользователе создавшем раздачу
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $Tuser = $result->fetch_assoc();
} else {
    echo "пользователь не найден.";
    exit();
}

$Tuser_login = $Tuser["login"];
$Tuser_avatar_address_100 = $Tuser["avatar_address_100"];

// Закрытие соединения
$stmt->close();
$conn->close();
?>

	<!DOCTYPE html>
	<html lang="ru">

	<link rel="stylesheet" href="template/StyleCurrent.css">

	<div class="wrap">

		<head>
			<meta charset="UTF-8">
			<title>Информация о раздаче</title>
			<!-- <link rel="stylesheet" href="template/TempStyle.css"> теперь стиль для шапки будет в самой шапке-->
		</head>

		<body>
			<!-- начало хедера (header.php) -->
			<?php include "template/header.php"; ?>
				<!-- конец хедера -->
				<div class="container">
					<h1><?php echo htmlspecialchars($torrent["name"]); ?></h1>
					<?php echo '
			<img id="TorrentImg" src="res/Tpics/' .
       $torrent["main_picture_address"] .
       '" />
			'; ?>

						<p><strong>Описание:</strong></p>
						<p>
							<?php echo nl2br($torrent["decription"]); ?>
						</p>

						<p>
							<strong>Загрузил польльзователь:</strong>
							<?php echo $Tuser_login; ?>
								<img id="header_AccImg" src="res/Avatars100/<?php echo $Tuser_avatar_address_100; ?>" />
						</p>

						<p><strong>Дата добавления:</strong>
							<?php echo htmlspecialchars(
       mb_substr($torrent["publication_time"], 0, 10)
   ); ?>
						</p>
						<p><strong>скачали</strong>
							<?php echo htmlspecialchars(
       $torrent["downloads_count"]
   ); ?> раз</p>

						<?php $file_address =
       $torrent[
           "file_address"
       ]; ?>
							<!-- для оптимизации запишем имя файла в переменную, так как его нужно будет вызывать дважды -->
							<p>
								<strong>Скачать торрент:</strong>
								<a href="download.php?file=<?php echo htmlspecialchars(
        $file_address
    ); ?>" download="<?php echo htmlspecialchars($file_address); ?>">Скачать</a>
							</p>
				</div>
		</body>
	</div>

	</html>
