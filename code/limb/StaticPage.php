<?php
	namespace limb\code\limb;
	use limb\app\base as Base;
	/**
	 * Сборка неизменяемой части страницы
	 */
	class StaticPage extends StaticTable
	{
		private $control;
		private $html_static_page;// собранный итого код неизменяемой части страницы
		private $setting;

		function __construct()
		{
			if(!isset($_SESSION))
			{
				session_start();
			}
			if(isset($_SESSION['connect'])) unset($_SESSION['connect']);
			$html = file_get_contents(__DIR__."/../../view/limb/layouts/main.tm");
			$this -> control = new Base\control\Control();
			$this -> html_static_page = $html;
			$this -> controlConnectDB();
			$this -> setting = parse_ini_file('setting.ini');
			$menu_left = file_get_contents(__DIR__.'/../../view/limb/menu/menu_left.tm');
			$menu_right = file_get_contents(__DIR__.'/../../view/limb/menu/menu_right.tm');

			$connect = $_SESSION['connect'];

			$tmplt = ['%version%', '%link%', '%menu_left%', '%menu_right%', '%connect%'];
			$replace = [$this -> setting['version'], $this -> setting['link'], $menu_left, $menu_right, $connect];


			$this -> html_static_page = Base\control\Necessary::standartReplace($tmplt, $replace, $html);

		}


		public function controlConnectDB()
		{
			$error_connection = $this -> control -> ConnectDB();//проверка возможности подключения к бд, при незаполненном возвращается текст описания ошибки, при положительном  = true

			if(!isset($_SESSION))
			{
				session_start();
			}
			if($error_connection === true)
			{
				$_SESSION['connect'] = "<h5 class='m-3' style='color:green;'>Соединение с бд установлено</h5>";
			}
			else{
				$_SESSION['connect'] = "<h5 class='m-3' >Невозможно подключиться к базе данных:<small style='color:red;'>".$error_connection."<br />Проверьте соответствие пользователя</small></h5>";
			}

			}


		public function getStaticPage()
		{
			return $this -> html_static_page;
		}
	}


?>