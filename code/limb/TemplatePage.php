<?
	namespace limb\code\limb;
	use limb\app\base as Base;
	/**
	 * формирование логики вывода страницы
	 * Основные функции:
	 * -проверка подключения к бд(этот статус выводится на всех страницах);
	 * -проверка общих настроек;
	 */
	class TemplatePage extends TemplateTable
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
			if(isset($_SESSION["message"]))  unset($_SESSION['message']);
			$main_left = $this -> copyF_main_left_table_F();
			$main_right = $this -> mainRight();
			


			if(isset($_SESSION["message"])) $message = $_SESSION["message"];
			else $message = "";
			
			$replace = [$main_left, $message, $main_right];
			$this -> page = Base\control\Necessary::standartReplace($this -> tmplt, $replace, $this -> html);
			
		}


	}
?>