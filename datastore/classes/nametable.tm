<?
	namespace limb\code\site;
	use limb\app\base as Base;#для работы с валидатором и бд
	use limb\app\base\control as Control;
	use limb\app\worker as Worker;#для шаблонизатора
	#use limb\app\form as Form;
	#use limb\app\modules\commentary as Comm;
	/**
	 * работа с данными таблицы
	 *
	 */
	class _NAME_Table
	{
		public $tmplt_NAME_ = [_TMPLT_];//массив из таблиц
		public $result_NAME_;//финишная сборка для шаблона для возврата в _Page
		public $name = '_TABLENAME_';//имя таблицы которое используется по умолчанию
		public $table_key = "_FORTABLEKEY_";
		protected $language;
		#public $replace = [_REPLACE_];


		public function __construct()
		{
			if(isset($_COOKIE['language'])) $this -> language = $_COOKIE['language'];
			else 
			{
				$this -> language = "ru_";
			}
			#code...
		}

		//метод достаюший все поля из таблицы
		public function searchFieldCom()
		{
			#$si = new Base\SearchInq($name);
			#$si -> select(); 
			#$si ->  where($key, $value, $operator);
			#$si -> limit();
			#$result = $si -> res();

			#code...

		}
		#метод добавляющий данные в таблицу, value - строка следующего вида
		#NULL, '".$this -> title."', '".$this -> keywords."', '".$this -> description."'
		#функция для автозаполнения созданной таблицы, можно корретировать функции, например выбрать fotogenerate /в будущем =)
		public static function insertFieldLimb($num)
		{
			$name77656756 = '_TABLENAME_';
			$table_key757658 = "_FORTABLEKEY_";
			for($i = 0; $i <= $num-1; $i++)
			{
_NEWFIELDSCODE_
			$ri = new Base\RedactionInq($name77656756, $table_key757658);
			$result = $ri -> insert($value);
			}
			#code...
		}


		protected function Limb($auth = "noauth")#сборщик страницы
		{
			$limb = new Worker\Limb();

			$si = new Base\SearchInq($this -> name);
			$si -> selectQ();
			$si -> whereQ("link", $link, "=");
			$si -> orderDescQ();
			$result = $si -> resQ();  //массив со всеми записями
			if(isset($result[0]["id"])){

				$template = [
					"norepeat" => ["%title%", "%description%"],
					"replace_standart" => "no",
					"replace_internal" => [["name" => "left_content", "folder" => "article"]]
				];
				$data = [
					"norepeat" => ["title" => $result[0]["name"], "description" => $result[0]["description"]],
					"replace_standart" => "no",
					"replace_internal" => [$result]
				];


				$render = $limb -> TemplateMaster($template, $data, $auth, $this -> html);
			}
			else
			{
				header("Location:/");
				exit();
			}
			return $render;
		}
	}
?>
