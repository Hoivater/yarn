<?php
	require "autoload.php";
	
	use limb\app\web as Web;

	$page = new Web\Route($_SERVER["REQUEST_URI"]);//полноценная html страница
	Web\Visible::PrintPage($page -> getHtml(), $page -> getForm(), $page -> getNamePage());//вывод страницы в браузер

?>
