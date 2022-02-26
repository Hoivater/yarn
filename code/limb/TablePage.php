<?
	namespace limb\code\limb;
	use limb\app\base as Base;
	/**
	 * формирование логики вывода страницы
	 * Основные функции:
	 * -проверка подключения к бд(этот статус выводится на всех страницах);
	 * -проверка общих настроек;
	 */
	class TablePage extends TableTable
	{
		use tPage;
		function __construct($name_page)
		{
			parent::__construct();
			$staticPage = new StaticPage();//получение html кода статической части страницы

			$this -> html = $staticPage -> getStaticPage();
			$this -> Page($name_page);
		}


		public function Page($name_page)
		{
			if(!isset($_SESSION))
			{
				session_start();
			}
			if(isset($_SESSION["message"]))  unset($_SESSION['message']);
			

			$main_left = $this -> main_left_table_F();

			


			if(isset($_SESSION["message"])) $message = $_SESSION["message"];
			else $message = "";
			

			if($name_page === 0)
			{
				$main_right = $this -> searchPage(0, "num");
			}
			else
			{
				$main_right = $this -> searchPage($name_page, "name");
			}
			$replace = [$main_left, $message, $main_right];

			$this -> page = Base\control\Necessary::standartReplace($this -> tmplt, $replace, $this -> html);
			
		}

		private function searchPage($name_page, $acc)
		{
			if($acc == "num")
			{
				#берем первую таблицу
				if(isset($this -> res[0][0])){
					$name = $this -> res[0][0];
					return $this -> VisibleTable($name);
				}
				else
					return "Таблица не найдена";

			}
			elseif($acc == "name")
			{
				#берем заданную таблицу
				return $this -> VisibleTable($name_page);
			}
		}


	}
?>