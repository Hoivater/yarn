<?php 
	namespace limb\app\modules\language;

	class Language
	{
		private $all_language;
		private $language;#текущий язык

		public function __construct()
		{
			$ini = parse_ini_file(__DIR__."/../../../setting.ini");
			$this -> all_language = $ini["language"];
			$this -> language = $_COOKIE["language"];
		}

		public function SwitchPublic()
		{
			
		}
	}
?>