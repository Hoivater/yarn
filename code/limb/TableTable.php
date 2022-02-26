<?
	namespace limb\code\limb;
	use limb\app\base as Base; 
	
	/**
	 * работа с данными таблицы faqs
	 * либо, как в этом случае, работа с файлами
	 */
	class TableTable
	{
		public $setting;
		private $tableInq;
		// #1
		public $tmplt_main_left_table = ['%name_db%', '%information_db%'];
		public $result_main_left_table;//финиш для шаблона main/main_left_table
		public $main_left_table;
		public $inc_main_left_table;
		// end#1

		// #2 левая часть сайта
		public $tmplt_information_db = ['%name_table%'];
		public $result_information_db;
		public $information_db;
		public $res; //список таблиц
		// end#2


		#3 сборка таблицы
		public $main_right_tables;
		public $tmplt_main_right_tables = ['%name_db%', '%tr1%', '%tr2%', '%count_array%', '%code_dtbs%', '%code_tmplt%', '%code_for_code_tmplt%'];
		public $result_main_right_tables;
		#end 3

		function __construct()
		{
			// #1
			$this -> main_left_table = file_get_contents(__DIR__.'/../../view/limb/main/main_left_table.tm');
			$this -> information_db = file_get_contents(__DIR__.'/../../view/limb/main/main_left_table/information_db.tm');
			// end#1
			$this -> setting = parse_ini_file('setting.ini');
			$this -> tableInq = new Base\TableInq();
			#3
			$this -> main_right_tables = file_get_contents(__DIR__.'/../../view/limb/main/main_right_tables.tm');
			#3
		}

		//финишная сборка названия таблиц слева
		public function main_left_table_F()
		{
			
			$ini = parse_ini_file('app/base/db.ini');
			$name_db = $ini["name_db"];
			
			$res = $this -> tableInq -> showTable();
			$this -> res = $this -> InRenameKey($res, "name_table");//преобразование массива 
			


			$information_db = Base\control\Necessary::ReplaceRepeat($this -> tmplt_information_db, $this -> res, $this -> information_db);//
			


			$this -> result_main_left_table = Base\control\Necessary::standartReplace($this -> tmplt_main_left_table, [$name_db, $information_db], $this -> main_left_table);
			return $this -> result_main_left_table;
		}

		#вывод таблицы
		public function VisibleTable($name)
		{
			$ini = parse_ini_file(__DIR__.'/../../app/base/db.ini');
			$name_db = $ini["name_db"];
			// echo $name;
			$data = $this -> tableInq -> structureTable($name, $name_db);
			// print_r($data);
			// foreach($data as $key)
			// {
			// 	print_r($key);
			// 	echo "<br />!!!</br>";
			// }
			#COLUMN_TYPE - , COLLATION_NAME - кодировка
			$keys = file_get_contents(__DIR__.'/../../view/limb/main/main_right_tables/key.tm');
			$tmpltKeys = ['%key_two%'];
			$count_array = count($data);

			$tr1 = $this -> trBuilding($data, "type");

			$tr2 = $this -> trBuilding($data, "value");

			$code_dtbs = $this -> trBuilding($data, "code_dtbs");


			$code_tmplt = $this -> trBuilding($data, "code_tmplt");


			$code_f_code_tmplt = $this -> trBuilding($data, "code_for_code_tmplt");

			$this -> result_main_right_tables = Base\control\Necessary::standartReplace($this -> tmplt_main_right_tables, [$name, $tr1, $tr2, $count_array, $code_dtbs, $code_tmplt, $code_f_code_tmplt], $this -> main_right_tables);

			return $this -> result_main_right_tables;
		}

		private function trBuilding($data, $type)
		{
			$result = "";
			$key_two = file_get_contents(__DIR__.'/../../view/limb/main/main_right_tables/key_two.tm');
			$tmpltKeyTwo = ['%name%'];

			if($type == 'type')
			{
				foreach($data as $one)
				{
					$name = "<b>".$one["COLUMN_NAME"]."</b>";
					

					$result .= Base\control\Necessary::standartReplace($tmpltKeyTwo, [$name], $key_two);
				}
			}
			elseif($type == 'value')
			{
				foreach($data as $one)
				{
					$name = $one["COLUMN_TYPE"];
					if($name == "tinyint(1)")
						$name = "boolean";
					elseif($one["COLUMN_KEY"] == 'PRI' && $one["EXTRA"] == 'auto_increment')
					{
						$name = "this_id";
					}
					$result .= Base\control\Necessary::standartReplace($tmpltKeyTwo, [$name], $key_two);
				}
			}
			elseif($type == "code_tmplt")
			{	
				$result = "[";

				$symbol = $this -> setting['symbol_code_tmplt'];
				foreach($data as $one)
				{
					$name = $symbol.$one["COLUMN_NAME"].$symbol;

					$result .= $name.", ";
				}	
				$result = substr($result, 0, -2)."]"; 
			}
			elseif($type == "code_for_code_tmplt")
			{
				$symbol = $this -> setting['symbol_code_tmplt'];
				foreach($data as $one)
				{
					$name = '$'.$one["COLUMN_NAME"];

					$result .= $name.", ";
				}
				$result = substr($result, 0, -2);
			}
			elseif($type == "code_dtbs")
			{
				if(isset($data[0]['TABLE_NAME']))
					$t = $data[0]['TABLE_NAME'];
				else
					$t = "Таблицы несуществует";
				$result = "table_name = '".$t."';\n";
				foreach($data as $one)
				{
					$name = $one["COLUMN_TYPE"];
					if($name == "bigint unsigned" || $name == "int unsigned")
					{
						if($one["COLUMN_KEY"] == 'PRI' && $one["EXTRA"] == 'auto_increment')
						{
							$name =  $one["COLUMN_NAME"]." = 'this_id';\n";
						}
						else
						{
							$name =  $one["COLUMN_NAME"]." = '".$one["DATA_TYPE"]."(".$one["CHARACTER_MAXIMUM_LENGTH"].")';\n";
						}
					}
					elseif($one["DATA_TYPE"] == "text")
					{
						$name =  $one["COLUMN_NAME"]." = '".$one["DATA_TYPE"]."';\n";
					}
					elseif($one["DATA_TYPE"] == "tinyint")
					{
						$name =  $one["COLUMN_NAME"]." = 'boolean';\n";
					}
					elseif($one["DATA_TYPE"] == "int")
					{
						$name =  $one["COLUMN_NAME"]." = 'int(11)';\n";
					}
					else
					{
						$name =  $one["COLUMN_NAME"]." = '".$one["DATA_TYPE"]."(".$one["CHARACTER_MAXIMUM_LENGTH"].")';\n";
					}
					$result .= $name;
				}
			}
			return $result;
		}


		private function InRenameKey($array, $new_name)
		{
			$result = [];
			for($i = 0; $i <= count($array)-1; $i++)
			{
				foreach ($array[$i] as $key => $value) {
					$result[] = [$value]; 
				}
			}
			return $result;
		}


	}
?>