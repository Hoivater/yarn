<?
	namespace limb\code\limb;
	use limb\app\base as Base; 
	/**
	 * работа с данными таблицы faqs
	 * либо, как в этом случае, работа с файлами
	 */
	class MainTable
	{
		public $ini_off_db;
		public $main_right_commandline;
		public $result_main_right_commandline;
		public $tmplt_main_right_commandline = ['%standart%']; 

		function __construct()
		{

			// echo "Экземпляр класса MainTable создан<br />";
		}

		public function MainRight($name_page)
		{
			$ad = __DIR__."/../../datastore/template/".$name_page.".ini";
			$this -> main_right_commandline = file_get_contents(__DIR__.'/../../view/limb/main/main_right_commandline.tm');
			if(file_exists($ad))
			{
				$files = file_get_contents($ad);
			}
			else
			{
				$files = "Шаблона ".$name_page." не существует.";
			}
			$this -> result_main_right_commandline = Base\control\Necessary::ReplaceRepeat($this -> tmplt_main_right_commandline, [$files], $this -> main_right_commandline);

			return $this -> result_main_right_commandline;
		}

	}
?>