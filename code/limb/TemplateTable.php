<?

	namespace limb\code\limb;
	use limb\app\base as Base;
	/**
	 * работа с данными таблицы faqs
	 * либо, как в этом случае, работа с файлами
	 */
	class TemplateTable
	{
		#1
		public $main_right_table;//html
		public $result_main_right_table;
		public $tmplt_main_right_table = ['%table%'];
		#1

		#2
		public $table;
		public $result_table;
		public $tmplt_table = ['%table_name%', '%key1%', '%key2%'];
		#2

		function __construct()
		{
			$this -> main_right_table = file_get_contents(__DIR__.'/../../view/limb/main/main_right_table.tm');
			$this -> table = file_get_contents(__DIR__.'/../../view/limb/main/main_right_table/table.tm');
		}

		public function copyF_main_left_table_F()
		{
			$_table = new TableTable();
			return $_table -> main_left_table_F();
		}

		public function mainRight(){
			#2
			//$template - название файла с шаблоном в off_db/template/...
			$templates = Base\control\Necessary::ScanDirF(__DIR__.'/../../datastore/template');
			$code_ini_array = [];
			foreach ($templates as $key) {
				$code_ini_array[] = parse_ini_file(__DIR__."/../../datastore/template/".$key);
			}

			$template = $this ->trBuilding($code_ini_array);
			$table = Base\control\Necessary::ReplaceRepeat($this -> tmplt_table, $template, $this -> table);
			#2

			#1
			$this -> result_main_left_table = Base\control\Necessary::ReplaceRepeat($this -> tmplt_main_right_table, [$table], $this -> main_right_table);
			#1
			return $this -> result_main_left_table;
		}

		private function trBuilding($data)
		{
			$result_array = [];
			for($i = 0; $i <= count($data) - 1; $i++)
			{
				$result_key = "";
				$result_value = "";
				foreach ($data[$i] as $key => $value) {
					if($key == "table_name")
					{
						$tn = $value;
					}
					else
					{
						$result_key .= "<td>".$key."</td>";
						$result_value .= "<td>".$value."</td>";
					}
				}
				$result_array[$i]["table_name"] = $tn;
				$result_array[$i]["key1"] = $result_key;
				$result_array[$i]["key2"] = $result_value;
				$result_key = ""; 
				$result_value = "";
				$tn = "";
			}
			// print_r($result_array);
			return $result_array;
		}

	}
?>