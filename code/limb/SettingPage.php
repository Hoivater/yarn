<?
	namespace limb\code\limb;
	use limb\app\base as Base;
	/**
	 * формирование логики вывода страницы
	 * Основные функции:
	 * -проверка подключения к бд(этот статус выводится на всех страницах);
	 * -проверка общих настроек;
	 */
	class SettingPage extends SettingTable
	{
		use tPage;

		function __construct()
		{
			parent::__construct();
			$staticPage = new StaticPage();//получение html кода статической части страницы

			$this -> html = $staticPage -> getStaticPage();
			$this -> Page();
		}

		public function Page()
		{
			if(!isset($_SESSION))
			{
				session_start();
			}
			if(isset($_SESSION["message"])){
				if($_SESSION["message"] == 1)
					$_SESSION["message"] = "Настройки успешно обновлены";
				elseif($_SESSION["message"] == 0)
					$_SESSION["message"] = "Произошла ошибка обновления, попробуйте еще раз или вручную";
			}
			$main_right = "";

			$main_left = $this -> main_left_settingF();

			if(isset($_SESSION["message"])) $message = $_SESSION["message"];
			else $message = "";



			$replace = [$main_left, $message, $main_right];

			$this -> page = Base\control\Necessary::standartReplace($this -> tmplt, $replace, $this -> html);
		}

	}
?>