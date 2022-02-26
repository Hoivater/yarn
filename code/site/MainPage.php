<?
	namespace limb\code\site;
	use limb\app\base as Base;#для работы с валидатором и бд
	/**
	 * формирование логики вывода страницы
	 * Основные функции:
	 * -проверка подключения к бд(этот статус выводится на всех страницах);
	 * -проверка общих настроек;
	 */
	class MainPage extends MainTable
	{
		use tPage;

		public function __construct()
		{
			parent::__construct();
			$staticPage = new StaticPage();//получение html кода статической части страницы

			$this -> html = $staticPage -> getStaticPage();
			$this -> Page();
		}
		#метод для сборки страницы
		#вся работа с базой данных идет в родительском классе
		public function Page()
		{

			$this -> page = $this -> html;



		}

	}
?>