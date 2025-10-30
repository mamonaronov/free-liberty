

<!DOCTYPE html>
<html>
<div class="wrap">
<head>
    <meta charset="utf-8">
    <title>Вход</title>
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
		input[type="password"], textarea {
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
    </style>
	<link rel="stylesheet" href="template/StyleCurrent.css">
</head>
<body>
    <article>
		<?php include ("template/header.php");?>
		<h1>Вход</h1>
		<form action="Log_A.php" method="post">
			<label for="login">Логин:</label>
			<input  type="text" id="login" name="login" required/>
			
			<label for="password">Пароль:</label>
			<input type="password" id="password" name="password" required/>
			
			<button type="submit" value="Войти" name="log_in" />Войти</button>
		</form>
		<?php
		$ErrType = $_GET['ErrType'];
		if (isset($ErrType)) {
		echo "<h4>ОШИБКА: " . $ErrType . "</h4>";			
		}
		?>
        <!--    <h1>Форма загрузки торрент-файла</h1>
        
        <form enctype="multipart/form-data" action="NewFileForm_A.php" method="POST">
            <input type="text" name="author_id" placeholder="author_id" class="textenter" required />
            <input type="text" name="publication_time" placeholder="publication_time" class="textenter" required />




            <label for="title">Название:</label>
            <input type="text" id="title" name="name" required>





            <label for="description">Описание:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

			
            <label for="tags">Теги (через запятую):</label>
            <input type="text" id="tags" name="tags" required>

            <label for="file">Выберите торрент-файл:</label>
            <input type="file" id="file" name="filename" accept=".torrent" required>

            <label for="picture">Выберите изображение:</label>
            <input type="file" id="picture" name="picturename" accept=".png, .jpg, .jpeg" required>

            <button type="submit">Загрузить</button>
        </form>-->
    </article>
</body>
</div>
</html>