<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);





	include ('lib/connect.php'); //подключаемся к БД
	include ('lib/module_global.php'); //подключается файл с глобальными функциями
	if (login()) {
			//  echo "кнопка на тру <br/>";///////
			$account_but = true;
			
			// Используем подготовленные выражения для безопасности
			$login = $_COOKIE['login'];
			$sql = "SELECT * FROM users WHERE login = ?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("s", $login);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$avatar_address_100 = $row['avatar_address_100'];
		}
		else { //если вошел то выводим кнопку выйти
			//   echo "кнопка на фолс <br/>";///////
			$account_but = false;
		}
?>

<link rel="stylesheet" href="template/HeaderStyle.css">
<header>
  <a href="Menu.php"><img id="header_Logo" src="res_for_site/images/logo.svg" alt="Логотип"></a>
  <a id="header_sitename" href="Menu.php"><h1 id="SiteName">FREE LIBERTY</h1></a>
  <div class= "push" id="menu"> <!-- Меню -->
  <nav id="menu">
    <ul>
		<li>
			<a href="Menu.php">Главная</a>
		</li>
		<li>
			<a href="NewFileForm.php">Загрузить</a>  
		</li>
		<li>
			<a href="NewUserForm.php">Регистрация</a>
		</li>
		
		<?php
			if($account_but == true) {
				echo '
				
					<li>
					<div class= "element"><!-- Меню -->
						<img id="header_AccImg" src="res/Avatars100/' . $avatar_address_100 . '" />
						
						<a href="/torrent_tracker/Log_A.php?action=out">Выйти</a>
							
					</div><!-- Меню -->
					</li>
				';///////
			}
			else 
			{
				echo '
					<li>
					<div class= "element"><!-- Меню -->
						

						<a href="/torrent_tracker/Log.php">Войти</a>

					</div><!-- Меню -->
					</li>
				';
			}
		?>
		
    </ul>
	</nav>
  </div>
</header>

 
 
 
<!-- <header>
	  <style>
    #header_sitename {
      color: #7918a0; /* Цвет текста */
      text-decoration: none; /* Убираем подчеркивание */
    }
    #header_sitename h1 {
      color: inherit; /* Наследуем цвет от родительского элемента */
      text-decoration: inherit; /* Наследуем оформление текста от родительского элемента */
      margin: 0; /* Убираем отступы по умолчанию у заголовка */
    }
	  </style>
  <a href="Menu.php"><img src="res_for_site/images/logo.svg" alt="Логотип"></a>
  <a id="header_sitename" href="Menu.php"><h1 >FREE LIBERTY</h1></a>
  <div id="menu"> <!-- Меню -->
<!--    <ul>
      <li><a href="Menu.php">Главная</a></li>
      <li><a href="/about">ссылка</a></li>
      <li><a href="/contacts">еще ссылка</a></li>
    </ul>
  </div>
</header>
-->
