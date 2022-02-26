<?php
namespace limb\app\web;
use limb\app\base\control as Control;
	/**
	 *
	 */
	class Visible
	{

		function __construct()
		{
			// code...
		}


		//вспомогательная функция для вывода массива
		public static function PrintRouteArray($route_array)
		{
			foreach ($route_array as $key) {
				echo $key."<br />";
			}
		}
		#функция дозаменяет последние элементы шаблона и выводит страницу в браузер
		public static function PrintPage($code, $form, $name_page = "")
		{
			// подключение style
			$style = parse_ini_file(__DIR__."/../../view/include_style.ini");
			if(isset($style[$name_page."_top"]) && isset($style[$name_page."_bottom"]))
			{
				$style_top = $style[$name_page."_top"];
				$style_bottom = $style[$name_page."_bottom"];

				$style_bottom_html = self::ParseStyleString($style_bottom);
				$style_top_html = self::ParseStyleString($style_top);
			}
			else
			{
				$style_bottom_html = "";
				$style_top_html = "";
			}
			$code =  Control\Necessary::standartReplace(["%script_top%", "%script_bottom%"], [$style_top_html, $style_bottom_html], $code);
			if(isset($_SESSION["csrf"]))
				unset($_SESSION["csrf"]);
			$ini = parse_ini_file(__DIR__."/../../setting.ini");
			if($form === true)
			{
				$code = Control\Necessary::standartReplace(["%name_site%", "%csrf%"], [$ini["name_site"], self::csrf()], $code);
			}
			else
			{
				$code = Control\Necessary::standartReplace(["%name_site%"], [$ini["name_site"]], $code);
			}
			echo $code;
		}
		private static function ParseStyleString($string)
		{
			$result = "";
			$css_tm = file_get_contents(__DIR__."/../../datastore/include_style/css.tm");
			$js_tm = file_get_contents(__DIR__."/../../datastore/include_style/js.tm");
			if($string)
			{
				$position_arr = explode("&", $string);
				for($i = 0; $i < count($position_arr); $i++)
				{
					$exp = pathinfo($position_arr[$i], PATHINFO_EXTENSION);
					if($exp == "")
					{
						$result .= "\n".file_get_contents(__DIR__."/../../datastore/include_style/".$position_arr[$i].".tm");
					}
					else
					{
						$result .= "\n".str_replace("%".$exp."%", $position_arr[$i], file_get_contents(__DIR__."/../../datastore/include_style/".$exp.".tm"));
					}
				}
			}
			else
			{
				$result = "";
			}
			return $result;
		}
		private static function csrf()
		{
			if(!isset($_SESSION))
			{
				session_start();
			}
			if(!isset($_SESSION["count"]))
				$_SESSION["count"] = 1;
			else
				$_SESSION["count"] += 1;
			$code = Control\Generate::codegenerate(30);

			$_SESSION["csrfv"] = $code;

			return "<input type = 'text' value = '".$code."' name = 'code' style='display:none;'/>";
		}



	}
?>