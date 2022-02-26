<?php
namespace limb\app\form;
use limb\app\base as Base;
use limb\app\modules\commentary as Comm;
use limb\code\site as Site;
	/**
	 * 
	 */
	// class ArticleTable
	// {
		
	// 	function __construct()
	// 	{
	// 		echo "Клссс";
	// 	}
	// 	public static function insertFieldCom($ef)
	// 	{
	// 		echo "fr";
	// 	}
	// }

	class FormBase
	{	
		public $ini_new;//перезаписанный массив
		public $data;//массив данных полученный через форму
		public $ex = ["connect", "importBD", "newFields", "newTable", "redTable"];#исключения для htmlspecialchar
		protected $controlHtml;

		function __construct($data)
		{
			$this -> data = [];
			$this -> controlHtml = 2;
			for($i = 0; $i < count($this -> ex); $i++)
			{
				if($data["nameForm"] == $this -> ex[$i]) $this -> controlHtml += 1;
			}
			if($this -> controlHtml == 2)
			{
				foreach ($data as $key => $value) {
					if($key != "code")
					{
						$this -> data[$key] = htmlspecialchars($value, ENT_QUOTES);

					}
					else
					{
						$this -> data[$key] = $value;
					}
				}
			}
			else
			{
				$this -> data = $data;
			}

		}

		public function tab_newIni()
		{
			// print_r($this -> data);
			$this -> ini_new = Base\control\Necessary::ConvertInIni($this -> data);
			// print_r($this -> ini_new);
			file_put_contents('../base/db.ini', $this -> ini_new);
			return true;
		}
		public function newRandomFields()
		{
			$ini = parse_ini_file('../base/db.ini');
			$table_name = $this -> data["name_db"];
			$class_name = 'limb\\code\\site\\'.ucfirst(str_replace($ini["fornameDB"], "", $table_name))."Table";
			$obj = $class_name::insertFieldLimb($this -> data['count_fields']);
		}
		
		public function ImportBD()
		{
			$code = $this -> data['file_sql'];
			$tableInq = new Base\TableInq();
			//$code_array = Necessary::ToCodeSql($code);
			$result = $tableInq -> ImportBDU($code);
			if($result == true)
			{
				$result = "База данных успешно импортирована";
			}
			else
			{
				$result = "При импорте произошла ошибка";
			}
			return $result;
		}

	}
?>