<?php

	namespace limb\app\form;
	use limb\app\base\control as Control;

	/**
	 *
	 */
	class FormFile extends FormBase
	{

		protected $files;
		protected $names; #содержит ИМЯ из формы => ИМЯ для записи в таблицу, которое получило при сохранениеи
		protected $ini;
		public $ex;
		protected $size;

		public function __construct($files)
		{
			$this -> files = $files;
			$this -> ini = parse_ini_file(__DIR__."/../../setting.ini");
			$this -> Sort();
		}

		protected function Sort()
		{
			$name_for_table = [];
			$count = count($this -> files);
			foreach ($this -> files as $key => $value) {
				$name_for_table[] = $key;
			}
			$result = 2;
			for ($j=0; $j < count($name_for_table); $j++) {
				if($this -> files[$name_for_table[$j]]["name"])
				{
					$result = $this -> securityEx(end(explode(".", $this -> files[$name_for_table[$j]]["name"])), $result);
					$result = $this -> securitySize($this -> files[$name_for_table[$j]]["size"], $result);
				}
			}
			if($result === 2)
			{
				for($i = 0; $i < count($name_for_table); $i++)
				{
					if($this -> files[$name_for_table[$i]]["name"])
					{
						$this -> names[$name_for_table[$i]] = Control\Generate::nameLatinGenerate(10).".".end(explode(".", $this -> files[$name_for_table[$i]]["name"]));
					}

				}
				foreach($this -> names as $key => $value)
				{
					$name = $value;
					copy($this -> files[$key]['tmp_name'], __DIR__.'/../../'.$this -> ini["folder_file"].$name);
				}
			}
			else
			{
				$res = "Изображения не были загружены. Несовпадений: ".($result-2);
				$_SESSION['message'] = $res;

			}
		}
		private function securityEx($ex, $count_control)
		{
			$o = 1;
			$rule_ex = explode(", ", $this -> ini["ex_file"]);
			for ($i=0; $i < count($rule_ex); $i++) {
				if(trim($rule_ex[$i]) == $ex)
				{
					$o = 0;
				}
			}
			return $o + $count_control;
		}
		private function securitySize($size, $count_control)
		{
			$o = 1;
			$max_size_file = $this -> ini["max_size_file"];
			if($size < $max_size_file)
				$o = 0;
			return $o + $count_control;
		}

		public function getNames()
		{
			return $this -> names;
		}
	}
?>