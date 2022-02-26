<?php
namespace limb\app\base;
use limb\app\modules as Modules;

	class SearchInq extends DataBase{
		private $db;//объект базы данных
		private $query;
		private $name;
		private $table;

		public function __construct($name){
			$this -> db = DataBase::getDB();
			$this -> name = $name;

		}


		//по принципу LARAVEL?? ГДЕ КАЖДАЯ ФУНКЦИЯ ДОБАВЛЯЕТ КУСОЧЕК КОДА В КОД
		public function selectQ()
		{
			//создает SELECT * FROM `".$table_name."`
			$this -> query = "SELECT * FROM `".$this -> name."`";
		}
		public function whereQ($key, $value, $operator)
		{
			if($operator == "LIKE")
				$value = "%".$value."%";
			$this -> query .= " WHERE ".$key.$operator."'".$value."'";
		}
		public function andQ($key, $value, $operator)
		{
			if($operator == "LIKE")
				$value = "%".$value."%";
			$this -> query .= " AND ".$key.$operator."'".$value."'";
		}
		public function orderDescQ($field = "")
		{
			if($field == "")
				$field = "id";
			$this -> query .= " ORDER BY `".$field."` DESC";
		}
		public function orderAscQ($field = "")
		{
			if($field == "")
				$field = "id";
			$this -> query .= " ORDER BY `".$field."` ASC";
		}
		public function limitQ($start, $length)
		{
			$this -> query .= " LIMIT ".$startFrom.", ".$length;
		}
		public function resQ()
		{
			$this -> table = $this -> db -> select($this -> query);

			return $this -> table;
		}
		public function getQuery()
		{
			return $this -> query;
		}

		#функция возвращает лишь активный модуль Paginate, и table с обрезанным количеством статей
		public function paginateQ($count)//count - количество записей на странице, $num - текущая страница
		{
			if(isset($_SESSION["numpage"]))
			{
				$count_table = count($this -> table);//количество строк в результирующем запросе
				$count_page = ceil($count_table/$count);//количество страниц
				$num_page = $_SESSION['numpage'];//номер страницы
				$paginate = new Modules\pagination\Pagination($count_table, $count_page, $num_page, $count);

				#Исходные данные: страаница 1, записей всего 10, на странице три записи
				$table = [];
				$end = $num_page*$count-1;
				$start = $end - $count + 1;
				if($end > count($this -> table) - 1)
				{
					$end = count($this -> table)-1;
				}
				for ($i=$start; $i <= $end ; $i++) {
					$table[] = $this -> table[$i];
				}


				return  [$table, $paginate -> getActiveModule()];
			}
			else
			{
				return [$this -> table, ""];
			}
		}
	}
?>