<?php
	namespace limb\app\worker;
	use limb\app\base\control as Control;
	use limb\app\base as Base;

	/**
	 * Класс для создания классов согласно шаблонов
	 */
	class MasterClass
	{
		public $replace;
		public $tmplt;
		public $table_name;
		public $for_table_key;
		public $type_fields;

		public function __construct($table_name, $tmplt, $replace, $for_table_key, $type_fields)
		{
			$this -> table_name = $table_name;
			$this -> tmplt = $tmplt;
			$this -> replace = $replace;
			$this -> for_table_key = $for_table_key;
			$this -> type_fields = $type_fields;

		}
		
		public function addTablePageCl()
		{
			$result = "";
			$php_file_Page = file_get_contents(__DIR__."/../../datastore/classes/namepage.tm");
			$php_file_Table = file_get_contents(__DIR__."/../../datastore/classes/nametable.tm");

			$ini = parse_ini_file(__DIR__."/../../app/base/db.ini");

			$table_name = str_replace($ini['fornameDB'], "", $this -> table_name);
			$table_name =  ucfirst(mb_strtolower($table_name));
			$rep = explode(", ", $this -> replace);
			$newfieldscode_arr = [];
			for($i = 0;$i <= count($this -> type_fields)-1; $i++)
			{
				preg_match_all('/\([0-9]*\)/', $this -> type_fields[$i], $num_arr);
				if(isset($num_arr[0][0]))
					$num = $num_arr[0][0];
				else
					$num = "()";
				$newfieldscode_arr[] = "\t\t\t".$rep[$i]." = Control\Generate::".preg_replace('/\([0-9]*\)/', '', $this -> type_fields[$i])."generate".$num.";";
			}

			$rep_string = "\$value = \"'\".".implode(".\"', '\".", $rep).".\"'\";";

			$newfieldscode = implode("\n", $newfieldscode_arr)."\n\t\t\t".$rep_string;

			$php_file_Page_result = Control\Necessary::standartReplace(["_NAME_"], [$table_name], $php_file_Page);
			$php_file_Table_result = Control\Necessary::standartReplace(["_NAME_", "_TMPLT_", "_REPLACE_", "_TABLENAME_", "_FORTABLEKEY_", "_NEWFIELDSCODE_"], [$table_name, $this -> tmplt, $this -> replace, $this -> table_name, $this -> for_table_key, $newfieldscode], $php_file_Table);

			$filenamePage = __DIR__."/../../code/site/".$table_name."Page.php";
			$filenameTable = __DIR__."/../../code/site/".$table_name."Table.php";




			$res = file_put_contents($filenamePage, $php_file_Page_result);
			if($res === true)
			{
				$result = "<br /> code/site/".$table_name."Page.php успешно создан.";
			}
			$res = file_put_contents($filenameTable, $php_file_Table_result);

			if($res === true)
			{
				$result .= "<br /> code/site/".$table_name."Table.php успешно создан.";
			}
			return $result;
		}

	}

?>