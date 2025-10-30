<?php
/* switch($_GET['action']) { //получаем значение переменной action
   case "about" :
      require_once("about.php"); // выводим данные О Нас
   break;
   case "contacts" :
      require_once("contacts.php"); // выводим данные Контакты
   break;
   default : // если значение переменной action не указано, либо её не существует, либо нет искомого значения
      print "Данных нет";
   break;
} */

//$db = mysqli_connect('localhost', 'root', '1234', 'torrents'); //[имя сервера],[логин],[пароль],[название БД]





$sql = "SELECT * FROM files";
if($result = $db->query($sql)){
    $rowsCount = $result->num_rows; // количество полученных строк
    
	
/* 	echo "<p>Получено объектов: $rowsCount</p>";
    echo "
	<table>
	<tr>
	<th>Id</th>
	<th>Имя</th>
	<th>Описание</th>
	<th>ссылка</th>
	</tr>";
    foreach($result as $row){
        echo "<tr>";
			$CurId = $row["id"];
            echo "<td>" . $CurId . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            //echo "<td>" . $row["decription"] . "</td>";
			
			$num = (string)$CurId;
			$Link = '<a href="CurrentTorrent.php?itemId=' . $num . '">ссылка</a>';
			echo "<td>" . $Link . "</td>"; //'<a href="CurrentTorrent.php?itemId=$CurId">ссылка<a/>'
        echo "</tr>";
    }
    echo "</table>";
	echo "отладка 1"; */


	echo '<div class="articles-film-cuted">';
	
	foreach ($result as $row) {
		
	$Tname = $row["name"];
	$main_picture_address = $row["main_picture_address"];
	$CurId = $row["id"];
	$num = (string)$CurId;
	
	echo '
	<div class="article-film">
	<div class="article-film-descr">
		<div class="article-film-descr-bottom"></div>
		
	</div>
	<div class="article-film-image">
		<div class="article-film-badge">
		</div>
		<a href="CurrentTorrent.php?itemId=' . $num . '" title="'.$Tname.'">
			<img class="article-img" id="TorrentImg1" src="res/Tpics/'.$main_picture_address.'" alt="'.$Tname.'" title="'.$Tname.'">
		</a>
	</div>
    <center>
		<div class="article-film-title">
			<a href="CurrentTorrent.php?itemId=' . $num . '">'.$Tname.'</a>
		</div>
	</center>
	</div>
	

	';
	}
	
	
	echo "</div>";
	
	
    $result->free();
} else{
    echo "Ошибка: " . $db->error;
}
$db->close();
?>
<!--
<div class="article-film">
	<div class="article-film-descr">
		<div class="article-film-descr-bottom"></div>
		
	</div>
	<div class="article-film-image">
		<div class="article-film-badge">
		</div>
		<a href="ссылка на current torrent" title="имя торрента">
			<img class="article-img" src="ссылка на картинку" alt="имя торрента" title="имя торрента">
		</a>
	</div>
    <center>
		<div class="article-film-title">
			<a href="ссылка на current torrent">Portal 2 (Портал 2)</a>
		</div>
	</center>
</div>
-->
