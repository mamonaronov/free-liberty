<?php
function enter () 
{
	//   echo "привет это функция [enter] <br/>";///////
	
	include ('lib/connect.php'); //подключаемся к БД

	$error = array(); //массив для ошибок   
	if ($_POST['login'] != "" && $_POST['password'] != "") //если поля заполнены    
	{
		$login = $_POST['login']; 
		$password = $_POST['password'];
		//     echo "поля заполнены <br/>";///////
		//     echo "login: ", $login, " <br/>";////////
		//     echo "password: ", $password, " <br/>";////////
		
		/* $sql = "SELECT * FROM users";
		$result = $db->query($sql); */
		
		
		// Используем подготовленные выражения для безопасности
        $sql = "SELECT * FROM users WHERE login = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
		
		
		//      echo "sql-запрос выполнен<br/>";///////
		//      echo phpversion();///////
		//      echo "<br/>";///////
		
		
		
		if ($result->num_rows == 1) //если нашлась одна строка, значит такой юзер существует в базе данных
		{
			//      echo "такой юзер существует в базе данных <br/>";///////
			$row = $result->fetch_assoc();
			print_r($row);/////принтит весь словарь
			//      echo "<br/>";///////
			//if (md5(md5($password).$row['salt']) == $row['password']) 
			if (password_verify($password, $row['password'])) 
				//сравнивается хэшированный пароль из базы данных с хэшированными паролем, введённым пользователем
			{
				//      echo "пароль подошел<br/>";///////

				//пишутся логин и хэшированный пароль в cookie, также создаётся переменная сессии
				setcookie ("login", $row['login'], time() + 50000);                         
				//setcookie ("password", md5($row['login'].$row['password']), time() + 50000);                    
				setcookie ("password", password_hash($row['login'].$row['password'], PASSWORD_DEFAULT), time() + 50000);
				
				//      echo "куки поставлены<br/>";///////
				$_SESSION['id'] = $row['id'];   //записываем в сессию id пользователя
				
				$id = $_SESSION['id'];
				//      echo "id сессии: " . $id . "<br/>";///////
				lastAct($id);
				//      echo "пока функция [enter] <br/>";///////
				return $error;
			}
			
			else //если пароли не совпали
			{
				//   echo "пароль не подошел<br/>";///////
				$error[] = "Неверный пароль";
				//   echo "пока функция [enter] <br/>";///////
				return $error;
			}
		}
		else //если такого пользователя не найдено в базе данных
		{
			//   echo "такого пользователя не найдено в базе данных <br/>";///////
			$error[] = "Неверный логин и пароль(пользователя с таким логином не существует)";
			//   echo "пока функция [enter] <br/>";///////
			return $error;
		}
	}
	else
	{
		$error[] = "Поля не должны быть пустыми!";
		//   echo "пока функция [enter] <br/>";///////
		return $error;
	}
	//   //    "пока функция [enter] <br/>";///////
}




function lastAct($id) { //Пишет время полследней активности пользоваетля в базу данных
	include ('lib/connect.php'); //подключаемся к БД
	
	//$tm = time();
	//mysql_query("UPDATE users SET online='$tm', last_act='$tm' WHERE id='$id'"); 
	$sql_add = "UPDATE users SET last_online = NOW() WHERE id='$id'"; 
	$result_add=mysqli_query($db, $sql_add);
	
	if ($result_add == false) {
    print("Произошла ошибка при выполнении запроса (функция lastAct)");
	}
} 





function login () 
{
	//   echo "привет функция [login] <br/>";/////////
	include ('lib/connect.php'); //подключаемся к БД
	//   echo "подключились к бд [login] <br/>";/////////
	ini_set ("session.use_trans_sid", true);
	session_start();

	if (isset($_SESSION['id']))//если сесcия есть
	{
		//     echo "сессия есть <br/>";///////
		if(isset($_COOKIE['login']) && isset($_COOKIE['password'])) //если cookie есть, обновляется время их жизни и возвращается true
		{
			//     echo "куки есть <br/>";///////
			SetCookie("login", "", time() - 1, '/');
			
			SetCookie("password","", time() - 1, '/');

			setcookie ("login", $_COOKIE['login'], time() + 50000, '/');

			setcookie ("password", $_COOKIE['password'], time() + 50000, '/');
			//     echo "обновили куки <br/>";///////
			
			$id = $_SESSION['id'];
			lastAct($id);
			return true;
		}
		else //иначе добавляются cookie с логином и паролем, чтобы после перезапуска браузера сессия не слетала
		{
			//     echo "куки нету! <br/>";///////
			//$result = mysql_query("SELECT * FROM users WHERE id='{$_SESSION['id']}'"); //запрашивается строка с искомым id
			
			$id = $_SESSION['id'];
			// Используем подготовленные выражения для безопасности
			$sql = "SELECT * FROM users WHERE id = ?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			
			
			
			if (mysql_num_rows($result) == 1) //если получена одна строка          
			{
				//$row = mysql_fetch_assoc($result); //она записывается в ассоциативный массив               
				$row = $result->fetch_assoc();
				setcookie ("login", $row['login'], time()+50000, '/');              

				setcookie ("password", password_hash($row['login'].$row['password'], PASSWORD_DEFAULT), time() + 50000, '/'); 
				//  echo "добавили куки <br/>";///////

				//$id = $_SESSION['id']; //вроде как id уже заданно
				lastAct($id); 
				return true;
			}
			else return false;      
		}   
	}   

	else //если сессии нет, проверяется существование cookie. Если они существуют, проверяется их валидность по базе данных     
	{
		//     echo "сессии нету! <br/>";///////
		if(isset($_COOKIE['login']) && isset($_COOKIE['password'])) //если куки существуют      
		{
			//     echo "куки есть <br/>";///////
			//$result = mysql_query("SELECT * FROM users WHERE login='{$_COOKIE['login']}'"); //запрашивается строка с искомым логином и паролем
			
			$login = $_COOKIE['login'];
			//     echo "отладка 1 <br/>";///////
			// Используем подготовленные выражения для безопасности
			$sql = "SELECT * FROM users WHERE login = ?";
			$stmt = $db->prepare($sql);
			$stmt->bind_param("s", $login);
			$stmt->execute();
			$result = $stmt->get_result();
			//     //     "отладка 2 <br/>";///////


			
			//@$row = mysql_fetch_assoc($result);            
			$row = $result->fetch_assoc();

			//if($result->num_rows == 1 && md5($row['login'].$row['password']) == $_COOKIE['password']) //если логин и пароль нашлись в базе данных
			if($result->num_rows == 1 && password_verify($_COOKIE['password'], $row['login'].$row['password'])) //если логин и пароль нашлись в базе данных
			{
				$_SESSION['id'] = $row['id']; //записываем в сесиию id              
				$id = $_SESSION['id'];

				lastAct($id);
				return true;
			}
			else //если данные из cookie не подошли, эти куки удаляются
			{
				SetCookie("login", "", time() - 360000, '/');

				SetCookie("password", "", time() - 360000, '/');
				return false;           
			}
		}
		
		else //если куки не существуют
		{
			return false;
		}
	}
}








//не доделал
function is_admin($id) {    
@$rez = mysql_query("SELECT prava FROM users WHERE id='$id'");

if (mysql_num_rows($rez) == 1)  
{       
$prava = mysql_result($rez, 0);         

if ($prava == 1) return true;       
else return false; 

}   
else return false;   
}





function out () {
//    echo "привет функция [out] <br/>";///////
include ('lib/connect.php'); //подключаемся к БД

session_start();
$id = $_SESSION['id'];

//    echo "ща будет sql запрос <br/>";///////
$sql_add = "UPDATE users SET last_online = NOW() WHERE id='$id'";
$result_add=mysqli_query($db, $sql_add);
if ($result_add == false) {
print("Произошла ошибка при выполнении запроса (функция out)");
}
//    echo "sql запрос сделан <br/>";///////
//mysql_query("UPDATE users SET online=0 WHERE id='$id'"); //обнуляется поле online, говорящее, что пользователь вышел с сайта (пригодится в будущем)

unset($_SESSION['id']); //удалятся переменная сессии
SetCookie("login", ""); //удаляются cookie с логином
SetCookie("password", ""); //удаляются cookie с паролем
//    echo "удалили сессию и куки <br/>";///////


//header('Location: http://'.$_SERVER['HTTP_HOST'].'/'); //перенаправление на главную страницу сайта
$Link = 'Menu.php';
//    echo "пока функция [out] <br/>";///////
header("Location: " . $Link);
exit();
}
?>