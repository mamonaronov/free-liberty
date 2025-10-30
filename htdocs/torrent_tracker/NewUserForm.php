<!DOCTYPE html>
<html>
<div class="wrap">
<head>
    <meta charset="utf-8">
    <title>Добавление пользователя бро</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f2e0fa;
        }
        input[type="file"] {
            margin-top: 10px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
		h1 {
			
			max-width: 600px;
            margin: 0 auto;
		}
    </style>
	<link rel="stylesheet" href="template/StyleCurrent.css">
</head>
<body>
    <article>
		<?php include ("template/header.php");?>
        
            <h1>Форма регистрации</h1>
        
        <form enctype="multipart/form-data" action="NewUserForm_A.php" method="POST">
		
		
		
		
		
		
            <!--
			<input type="text" name="author_id" placeholder="author_id" class="textenter" required />
            <input type="text" name="publication_time" placeholder="publication_time" class="textenter" required />
			-->
			
			<label for="avatar">Загрузите аватарку:</label>
            <input type="file" id="avatar" name="avatar" accept=".png, .jpg, .jpeg" >
			
            <label for="login">Логин:</label>
            <input type="text" id="login" name="login" required>  <!-- сделать проверку на существование такого же имени -->

            <label for="password">Пароль:</label>
            <input type="text" id="password" name="password" required></textarea>
			
			<label for="2password">Повторите пароль:</label>
            <input type="text" id="2password" name="2password" required></textarea>

			<label for="email">Ваш E-Mail:</label>
            <input type="text" id="email" name="email" ></textarea>
			
			<label for="about_myself">О себе:</label>
            <textarea id="about_myself" name="about_myself" rows="4" ></textarea>
			
			
			
			
			
			
			
			<!--
            <label for="tags">Теги (через запятую):</label>
            <input type="text" id="tags" name="tags" required>

            <label for="file">Выберите торрент-файл:</label>
            <input type="file" id="file" name="filename" accept=".torrent" required>

            <label for="picture">Выберите изображение:</label>
            <input type="file" id="picture" name="picturename" accept=".png, .jpg, .jpeg" required>




			<label for="2password">повторите пароль:</label>
            <textarea id="2password" name="password" required></textarea>
			-->


            <button type="submit">Отправить</button>
			
        </form>
		<?php
		$ErrType = $_GET['ErrType'];
		if (isset($ErrType)) {
		echo "<h4>ОШИБКА: " . $ErrType . "</h4>";			
		}
		?>
    </article>
</body>
</div>
</html>