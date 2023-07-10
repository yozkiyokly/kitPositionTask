<?php
#Created with KDevelop by yozki.com till 2023-07-10
$dbHost="localhost";
$dbName="kit_treeTask";
$dbUser="kit";
$dbPasswd="kit";
$namedArray=array();# Переиспользуемое хранилище данных дерева
$indentCategory=0; 	# <-- NOTE не забыть о важной фиче с регулируемым отступом при выводе дерева

try {  # Откупориваем сокет к базе данных.
  	$DBH = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);  
	}catch(PDOException $e) {  echo $e->getMessage(); }
session_start();
if (isset ($_GET['logout'])){session_destroy();unset($_SESSION['authSuccess']);}

// сразу после сессии выводим розовое фиксированное окно сообщений под вывод javaScript:
printMsgwindow();
if (isset ($_GET['logout'])){printMessage("Сеанс администратора завершён.");}

elseif (isset($_POST['existingLogin'])and isset($_POST['existingPasswd'])) {getAuthDB($DBH);}
if (isset ($_SESSION['authSuccess'])){
	printMessage("Сеанс администратора открыт.");
	taskPanelShow();
	if (isset ($_GET['adduser']))	{adduserFormShow();}
	if (isset ($_POST['addRec'])){	printMessage( "Добавление записи");	insertRecord($DBH);	}
	if (isset ($_GET['del'])){	printMessage( "Удаление записи".$_GET['del'] );	deleteRecord($DBH);	}
	addrecord_form_Show();
	$namedArray=getRecords($DBH);
	printingTree();
	}else{adduserFormShow();
		printMessage("Вы не авторизованы. \nСоздайте учётную запись или войдите в систему.");
		loginFormShow();}

		#Если добавляем пользователя:
if ((isset($_POST['newLogin'])and isset($_POST['newPasswd']))&&($_POST['newLogin']>''and ($_POST['newPasswd']>''))){
	#проверим есть ли логин.
	if (!loginExists($DBH)){
	$data = array($_POST['newLogin'], password_hash($_POST['newPasswd'], PASSWORD_DEFAULT));
	$STH = $DBH->prepare("INSERT INTO users (login, password) values (?, ?)");
		if  ($STH->execute($data)){
		printMessage("Добавлена пара логин\криптпасс в БД:ч\"".$_POST['newLogin']."\"\n<- войдите в систему");
		loginFormShow();
		}
	#значит логина нет в базе:
	}else{	printMessage("Пользователь \"".$_POST['newLogin']."\" уже существует. ");adduserFormShow();
		printMessage("Вы не авторизованы. \nСоздайте учётную запись или войдите в систему.");
		loginFormShow();
}

}



function getAuthDB($DBH){
		printMessage("Ищем пару логин\криптпасс в БД..");
		if ($_POST['existingLogin']>""){
		$data = array($_POST['existingLogin']);
		#NOTE WARNING (!злая шутка!) Малозаметно оставляем бэкдор для инъекции SQL :
		$STH = $DBH->query("SELECT login,password from users where login='$_POST[existingLogin]'");
		$STH->setFetchMode(PDO::FETCH_LAZY);
		$row = $STH->fetch();
			if (! is_bool($row)){ #Если значение не булево, то наш запрос успешен.
				printMessage( "логин $_POST[existingLogin] обнаружен,");
					if (password_verify($_POST['existingPasswd'], $row['password']))
						{	printMessage( 'и пароль совпал.');
							$_SESSION['authSuccess']=true;/**<-- сохраняем сессию и return editMenu()*/	}
						else
						{ printMessage( 'но пароль не совпал.');
							$_SESSION['authSuccess'] =false;/**<-- зануляем сессию и return showMenu();*/}
			} else {printMessage( "логин $_POST[existingLogin] не найден");$_SESSION['authSuccess'] =false;}
			} else {printMessage( "логин пуст");$_SESSION['authSuccess'] =false;}


}


function loginExists($DBH){
		$data = array($_POST['newLogin']);

		$STH = $DBH->prepare("SELECT id from users where login=?");
		//$STH = $DBH->query("SELECT * from users where login=?");
		$STH->setFetchMode(PDO::FETCH_ASSOC);
		 $STH->execute($data);
		$row = $STH->fetchAll();
//		print "<h1>-".$row[1];

		if (isset ($row[0]['id'])){ #запрос успешен.
				return true;}else {return false;}
}

#########################################
function printingTree($parent_id=0) {
    global $namedArray,$indentCategory; //Забираем массив внутрь пространства ф-ции из глобалскопа
					foreach ($namedArray as $value){
						if ($value['parent_id']== $parent_id ){ //знач. нашли подкатегорию
							$indentCategory++; 
							print "<div class='crumbs' style='margin-left:" .
							($indentCategory * 13)
							. "px;'><a href='?del=".$value['id']."'><img src='del.png' alt='delete'></a> "	.$value['id'] 	." parent="	.$value['parent_id'] 	."  <a href='?edit="	.$value['id']."'>".$value['name'] ."</a> <small>"	.$value['description']		."</small></div>\n";
							printingTree($value['id']); //Рекурсируем перебор массива
							$indentCategory--; 
						}
					}
}

#########################################
function getRecords($DBH) {
		$STH = $DBH->query("SELECT * from treeTable");
		//проверить влияние предупорядоченности на кол-во итераций перебора
		//в последующей сортировке массива при формировании древовидки:
		//order by parent_id");
		$STH->setFetchMode(PDO::FETCH_ASSOC);   #сразу берём ассоциативный
return  $STH->fetchall();
}

##########################
function insertRecord($DBH) {
if ($_POST['parent_id']==''){$_POST['parent_id']=0;}
$inputArray= array_slice($_POST, 0, 3);
$STH = $DBH->prepare("INSERT INTO treeTable (parent_id,name,description) 
						VALUES (:parent_id,:name,:description)");  	
try {$STH->execute($inputArray);}catch (Exception $e){throw $e;}
}


function deleteRecord($DBH) {
$data= array($_GET['del']);
$STH = $DBH->prepare("DELETE FROM treeTable WHERE (id=?)");
try {$STH->execute($data);}catch (Exception $e){throw $e;}
}

function adduserFormShow(){	?>
	<table><tr>
	<td class="forms" ><form method=post action="?">
		<div align='right'>Создать учётную запись<span style='margin-left:38;'>&nbsp;</span><a href="?"><b>[Х]</b></a></div><br>
		<input type="text" name="newLogin"><br>
		<input type="password" name="newPasswd"><br>
		<input type="submit" name="adduser" value="Добавить пользователя">
		</form>
	</td>
	</tr></table><br>
<?php
}

function loginFormShow(){	?>
	<table><tr>
	<td class="forms">
		<form method="post" action="?">
		<input type="text" name="existingLogin"><br>
		<input type="password" name="existingPasswd"><br>
		<input type="submit" name="login" value="Войти в систему">
		</form>
	</td>
	</tr></table><div height=22>&nbsp;</div><?php }


function taskPanelShow(){ 	?>
	<table class="forms" style="margin-top:22px;margin-bottom:12px;">
	<tr>
		<td><a href="?adduser=1">Добавить админ.пользователя</a></td>
		<td width=150>&nbsp;</td>
		<td><a href="?logout=1">Выйти</a></td>
	</tr>
</table>
<?php
}

function addrecord_form_Show(){ 	?>
<form method="post" name="recordsEditor" action="?addrec">
	<table class="forms" style="margin-top:22px;margin-bottom:12px;">
	<tr>
		<td>	№ родительской категории:</td>
		<td><input type="text" name="parent_id" size="2"></td>
	</tr>
		<tr>
		<td>	Имя категории:</td><td><input type="text" name="name"></td>
	</tr>
	<tr>
		<td colspan="2">
		Описание:<br><textarea name="description" size="18" width="100"></textarea><br>

		</td>
	</tr>
	<tr><td colspan="2"  align=right valign=bottom>
	<input type="submit" name="addRec" value="добавить новый пункт">
	</td>
	</tr>
	</table>
</form>
<?php
}

function printMsgwindow() {
	print '<head>
	<link rel="stylesheet" href="kit_theme_head">
	</head>
	<body>
	<div class="mesg" id="mesg"><font color="white">system messages</font></div>';

}


function printMessage($message){
print "<script>node = document.createElement('div');node.innerText = `$message`; document.querySelector('#mesg').appendChild(node);</script> ";
}
###################
$DBH = null; #Закрыли подключение. Пока-пока, МарьяДэБэ!
?>
<div height="20%">
<br><hr>
</p>
<p align="center"><small><a href="http://yozki.com">yozki.com 2023</a></small><br>. . .<br>.
</p>
