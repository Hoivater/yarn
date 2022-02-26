<?php
	namespace limb\app\worker;
	use limb\app\base\control as Control;
	use limb\app\modules as Modules;#для авторизации

	/**
	 *
	 */
	class Limb
	{
		private $language;
		function __construct()
		{
			if(isset($_COOKIE['language'])) $this -> language = $_COOKIE['language'];
			else 
			{
				$this -> language = "ru_";
			}
		}

		public function TemplateMaster($template, $data, $auth, $html)#основная функция для сборки страницы
		
		{
			#сразу меняем repeat
			#дальше формируем internal
			#далее избавляемся от правил видимости admin, user
			#дальше norepeat
			#потом роли AUTH

			#REPEAT
			if(isset($template["repeat"][0]))
			{
				for($i = 0; $i < count($template["repeat"]); $i++)
				{
					$html = $this -> ReplaceStandart($template["repeat"][$i], $data["repeat"][$i], $html);
				}
			}
			
			#INTERNAL
			if(isset($template["internal"][0]))
			{
				if(!isset($template["repeat_tm"])){
					for($i = 0; $i < count($template["internal"]); $i++)
					{
						$name = $template["internal"][$i]["name"];
						#так называется файл .tm
						#так называется шаблон в layouts %name%
						$folder = $template["internal"][$i]["folder"];
						#так называется папка, которая хранит .tm
						$modules = $this -> ReplaceInternal($template["internal"][$i], $data["internal"][$i]);
						$html = Control\Necessary::standartReplace("%".$template["internal"][$i]["name"]."%", $modules, $html);
					}
				}
				else
				{
						#значит repeat_tm везде постоянный
						for($i = 0; $i < count($template["internal"]); $i++)
						{
							$name = $template["internal"][$i]["name"];
							#так называется файл .tm
							#так называется шаблон в layouts %name%
							$folder = $template["internal"][$i]["folder"];
							#так называется папка, которая хранит .tm
							if(!isset($data["internal"][$i])){
								$data_internal_i = [""];
							}
							else
								$data_internal_i = $data["internal"][$i];

								
								$modules = $this -> ReplaceInternalWithTM($template["internal"][$i], $data_internal_i, $template["repeat_tm"], $data["repeat_tm"]);
								$html = Control\Necessary::standartReplace("%".$template["internal"][$i]["name"]."%", $modules, $html);
						}

				}
			}
			#NOREPEAT
			if(isset($template["norepeat"]) && is_array($template["norepeat"]))
			{
				$html = Control\Necessary::asortReplace($template["norepeat"], $data["norepeat"], $html);
			}

			if($auth !== "noauth")
			{
				$au = new Modules\auth\AuthAccess($html, $auth);
				$html = $au -> getResult();
			}
			return $html;
		}


		private function NoReplaceInternalWithTM($template, $data, $tm, $data_tm)
		{	

			$name = $template["name"];
			$folder = $template["folder"];
			#ищем tm файл для замены в указанной папке
			if(file_exists(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm"))
			{
				$file_tm = file_get_contents(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm");
				$html_module = $file_tm;
				for($i = 0; $i < count($tm); $i++){
					if(str_contains($file_tm, "^start_repeat_".$tm[$i]."^"))
					{
						$html_module = $this -> ReplaceStandart($tm[$i], $data_tm[$i], $html_module);
					}
				}
				#проверяем на возможность повтора
				if(str_contains($file_tm, "^start_repeat_".$name."^"))
				{
					$html_module = $this -> ReplaceStandart($name, $data, $html_module);
				}
				else
				{
					
					$file_tm_arr = explode("\n", $html_module);
					$tmplt = explode(" ", $file_tm_arr[1]);
					unset($file_tm_arr[0]);
					unset($file_tm_arr[1]);
					$file_tm = implode("\n", $file_tm_arr);
					$html_module = $this -> NoReplaceStandart($name, $data, $file_tm, $tmplt);
				}

			}


			return $html_module;

		}
		private function ReplaceInternalWithTM($template, $data, $tm, $data_tm)
		{	

			$name = $template["name"];
			$folder = $template["folder"];
			#ищем tm файл для замены в указанной папке
			if(file_exists(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm"))
			{
				$file_tm = file_get_contents(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm");
				$html_module = $file_tm;
				for($i = 0; $i < count($tm); $i++){
					if(str_contains($file_tm, "^start_repeat_".$tm[$i]."^"))
					{
						$html_module = $this -> ReplaceStandart($tm[$i], $data_tm[$i], $html_module);
					}
				}
				#проверяем на возможность повтора
				if(str_contains($file_tm, "^start_repeat_".$name."^"))
				{
					$html_module = $this -> ReplaceStandart($name, $data, $html_module);
				}
				else
				{
					
					$file_tm_arr = explode("\n", $html_module);
					$tmplt = explode(" ", $file_tm_arr[1]);

					unset($file_tm_arr[0]);
					unset($file_tm_arr[1]);
					$file_tm = implode("\n", $file_tm_arr);

					$html_module = $this -> NoReplaceStandart($name, $data, $file_tm, $tmplt);
				}

			}


			return $html_module;

		}
		private function ReplaceStandartInternal($name, $replace, $html, $folder){
			$start = "^start_repeat_".$name."^";
			$end = "^end_repeat_".$name."^";

			if(file_exists(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm"))
			{
				$file_tm = file_get_contents(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm");

				$ar = explode("\n", $file_tm);
				if(str_contains($file_tm, "^start_repeat_".$name."^")){

					$html_for_repeat = $this -> textInternal([$start, $end], $file_tm);#получаем html для повтора

					$file_tm = $this -> tmpReplace("&&LIMB&&", $file_tm, [$start, $end]);#временная замена повторяющегося участка

					$result_f = Control\Necessary::asortReplace2($html_for_repeat[0], $replace, $html_for_repeat[1]);
					$result_f2 = Control\Necessary::standartReplace("&&LIMB&&", $result_f, $file_tm);
					$html_finish = Control\Necessary::standartReplace("%".$name."%", $result_f2, $html);
				}
			}

			return $html_finish;
		}
		private function get_lvl(array $array) {
		    $max_lvl = 1;

		    foreach ($array as $value) {
		        if (is_array($value)) {
		            $lvl = $this -> get_lvl($value) + 1;

		            if ($lvl> $max_lvl) {
		                $max_lvl = $lvl;
		            }
		        }
		    }

		    return $max_lvl;
		}
		private function ReplaceInternal($template, $data)
		{
			$name = $template["name"];
			$folder = $template["folder"];
			#ищем tm файл для замены в указанной папке
			if(file_exists(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm"))
			{
				$file_tm = file_get_contents(__DIR__."/../../view/".$this -> language."public/".$folder."/".$name.".tm");
				#проверяем на возможность повтора
				$file_tm_arr = explode("\n", $file_tm);
				if(str_contains($file_tm, "^start_repeat_".$name."^"))
				{
					$html_module = $this -> ReplaceStandart($name, $data, $file_tm);
				}
				else
				{
					$tmplt = explode(" ", $file_tm_arr[1]);
					unset($file_tm_arr[0]);
					unset($file_tm_arr[1]);
					$file_tm = implode("\n", $file_tm_arr);
					$html_module = $this -> NoReplaceStandart($name, $data, $file_tm, $tmplt);
				}

			}
			return $html_module;

		}
		private function NoReplaceStandart($name, $data, $file_tm, $tmplt)
		{
			$result_f = Control\Necessary::asortReplace2($tmplt, $data, $file_tm);
			return $result_f;
		}
		private function ReplaceStandart($str_name, $replace, $html)
		{
			$start = "^start_repeat_".$str_name."^";
			$end = "^end_repeat_".$str_name."^";

			$html_for_repeat = $this -> textInternal([$start, $end], $html);#получаем html для повтора

			$html = $this -> tmpReplace("&&LIMB&&", $html, [$start, $end]);#временная замена повторяющегося участка

			$result_f = Control\Necessary::asortReplace2($html_for_repeat[0], $replace, $html_for_repeat[1]);
			$html_finish = Control\Necessary::standartReplace("&&LIMB&&", $result_f, $html);

			return $html_finish;
		}


		#заменяем повторяющийся текст на значок шаблонизатора &&LIMB&&
		private function tmpReplace($limb, $html, $arr)
		{
			$num_start = stripos($html, $arr[0]);
			$num_end = stripos($html, $arr[1]) + strlen($arr[0]);
			$s = substr($html, 0, $num_start).$limb.substr($html, $num_end);
			return $s;
		}
		#возвращает текст внутри двух элементов массива
		private function textInternal($arr, $html)
		{
			$num_start = stripos($html, $arr[0]);
			$num_end = stripos($html, $arr[1]) + strlen($arr[0]);
			// echo $num_start." ".$num_end;
			$res = substr($html, $num_start, $num_end-$num_start);
			$res_arr = explode("\n", $res);
			$template = $res_arr[1];
			$template_arr = explode(" ", $template);
			unset($res_arr[1]);
			$html_res = str_replace($arr, ["", ""], implode("\n", $res_arr));
			$res_temp = [];
			for($i = 0; $i < count($template_arr); $i++)
			{
				$res_temp[] = trim($template_arr[$i]);
			}
			return [$res_temp, $html_res];
		}
	}
?>