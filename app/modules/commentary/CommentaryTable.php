<?
	namespace limb\app\modules\commentary;
	use limb\app\base as Base;#для работы с валидатором и бд
	use limb\app\base\control as Control;
	use limb\app\worker as Worker;#для шаблонизатора
	/**
	 * работа с данными таблицы
	 *
	 */
	class CommentaryTable
	{
		public $tmpltCommentary = ['%id%', '%id_article%', '%name%', '%text%', '%levels%', '%nesting%', '%code%', '%date_creation%'];//массив из таблиц
		public $resultCommentary;//финишная сборка для шаблона для возврата в _Page
		public $name = '39t_commentary';//имя таблицы которое используется по умолчанию
		public $table_key = "`id`, `id_article`, `name`, `text`, `levels`, `nesting`, `code`, `date_creation`";
		public $ini;
		#public $replace = [$id, $id_article, $name, $text, $level, $code, $date_creation];
		protected $language;

		public function __construct()
		{
			$this -> ini = parse_ini_file(__DIR__."/../../../setting.ini");

			if(isset($_COOKIE['language'])) $this -> language = $_COOKIE['language'];
			else 
			{
				$this -> language = "ru_";
			}
		}

		public function LoadCommentary($id)
		{
			$si = new Base\SearchInq($this -> name);
			$si -> selectQ();
			$si -> whereQ("levels", $id, "=");
			$si -> orderAscQ();
			$result = $si -> resQ();

			for($i = 0; $i < count($result); $i++)
			{
				$result[$i]["date_creation"] = Control\Necessary::ConvertTime($result[$i]["date_creation"]);
				$result[$i]["answer"] = $this -> controlAnswer($result[$i]["code"]);

				$result[$i]["level_comment"] = $this -> Nesting($result[$i]["nesting"], $result[$i]["code"]);
			}


			$limb = new Worker\Limb();
			$template = [
				"internal" => [["name" => "commentary_two", "folder" => "commentary"]]
			];

			$data = [
				"internal" => [$result]
			];
			return $limb -> TemplateMaster($template, $data, $auth = "no", "%commentary_two%");

		}
		public static function addCommentary($data)
		{
			$name77656756 = '39t_commentary';
			$table_key757658 = "`id`, `id_article`, `name`, `text`, `levels`, `nesting`, `code`, `date_creation`";

			$id = Control\Generate::this_idgenerate();
			$id_article = $data["id_article"];
			$name = Control\Control::NameUser();
			$level = $data["level"];
			if($level != 0) $level = mb_substr($level, 1, strlen($level));
			$text = $data["text"];

			$code = Control\Generate::nameLatinGenerate(10);
			$date_creation = time();

			$nesting = self::getNesting($level);

			$value = $id.", '".$id_article."', '".$name."', '".$text."', '".$level."', '".$nesting."', '".$code."', '".$date_creation."'";
			$ri = new Base\RedactionInq($name77656756, $table_key757658);
			$result = $ri -> insert($value);
		}

		public function renderCommentary($id, $auth = "noauth"){
			$com_paginate = $this -> ini["comm_paginate"];
			$limb = new Worker\Limb();
			$si2 = new Base\SearchInq("39t_commentary");
			$si2 -> selectQ();
			$si2 -> whereQ("id_article", $id, "=");
			$si2 -> andQ("levels", 0, "=");
			$si2 -> orderAscQ();
			$si2 -> resQ();
			$res = $si2 -> paginateQ($com_paginate);  //все комментарии к статье
			$all_commentary_for_article = $res[0];
			$mod_pag = $res[1];

			for($i = 0; $i < count($all_commentary_for_article); $i++)
			{
				$all_commentary_for_article[$i]["date_creation"] = Control\Necessary::ConvertTime($all_commentary_for_article[$i]["date_creation"]);

				$all_commentary_for_article[$i]["answer"] = $this -> controlAnswer($all_commentary_for_article[$i]["code"]);
			}
			$template = [
				"norepeat" => ["%id%", "%module_paginate%"],
				"internal" => [["name" => "commentary", "folder" => "commentary"]]
			];

			$data = [
				"norepeat" => ["id" => $id, "module_paginate" => $mod_pag],
				"internal" => [$all_commentary_for_article]
			];

			return $limb -> TemplateMaster($template, $data, $auth, file_get_contents(__DIR__."/../../../view/".$this -> language."public/commentary/main.tm"));

		}
		#на основании code текущего сообщения проверяет наличие ответов на данное сообщение
		public function controlAnswer($code)
		{
			$si2 = new Base\SearchInq($this -> name);
			$si2 -> selectQ();
			$si2 -> whereQ("levels", $code, "=");
			$si2 -> orderAscQ();
			$result = $si2 -> resQ();
			if(count($result) == 0)
			{
				// $tm = file_get_contents(__DIR__."/../../../view/public/commentary/false_view_answ.tm");
				$tm = "";
			}
			else
			{
				$tm = file_get_contents(__DIR__."/../../../view/".$this -> language."public/commentary/true_view_answ.tm");
				$tm = str_replace(["%count%", "%code%"], [count($result), $code], $tm);
			}
			return $tm;
		}

		public function Nesting($level_comment, $code)
		{
			$nesting = $this -> ini["nesting"];
			if($level_comment < $nesting)
			{
				$tm = file_get_contents(__DIR__."/../../../view/".$this -> language."public/commentary/level_comment.tm");
				$tm = str_replace("%code%", $code, $tm);
			}
			else
			{
				$tm = "";
			}
			return $tm;
		}
		public static function getNesting($level)
		{
			$nesting = 0;
			if($level == 0) $nesting = 0;
			else
			{
				$si2 = new Base\SearchInq("39t_commentary");
				while($level !== 0)
				{
					$si2 -> selectQ();
					$si2 -> whereQ("code", $level, "=");
					$si2 -> orderAscQ();
					$result = $si2 -> resQ();
					if(isset($result[0]["id"]))
					{
						$level = $result[0]["levels"];
						$nesting += 1;
					}
					else
					{
						break;
					}
				}
			}
			return $nesting;
		}
	}
?>
