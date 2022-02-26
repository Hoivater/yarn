<?php
namespace limb\app\base;

	class TableInq extends DataBase{
		private $db;//объект базы данных
		private $query;

		public function __construct(){
			$this -> db = DataBase::getDB();
		}

		#получить названия присутсвующих таблиц
		public function showTable()
		{
			$query = "SHOW TABLES";
			$table = $this -> db -> select($query);

			return $table;
		}
		#получить названия присутсвующих таблиц
		public function dropTable($table_name)
		{
			$query = "DROP TABLE `".$table_name."`";
			$table = $this -> db -> querySqlDb($query);

			return $table;
		}
		#выполнение прямого SELECT
		public function querySql($query)
		{
			$table = $this -> db -> querySqlDb($query);
			return $table;
		}
		#получить названия присутсвующих колонок
		public function structureTable($table_name, $name_db)
		{
			$query = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='".$table_name."' AND table_schema='".$name_db."' ORDER BY `ORDINAL_POSITION`";
			$table = $this -> db -> select($query);
			return $table;
		}
		public function LengthTable($table_name){//возвращает количество записей в таблице
			$count_table = $this -> db -> CountTable($table_name);
			return $count_table;
		}
		public function ChoiceTableContinue($table_name, $length, $startFrom){//выборка из таблицы нужного количество статей в обратной последовательности 
			$query = "SELECT * FROM `".$table_name."` ORDER BY `id` DESC LIMIT ".$startFrom.", ".$length;
			$table = $this -> db -> select($query);
			return $table;
		}
		//функция предназначена для импорта базы данных
		//необходимо сформировать query:
		//- заменить " на \"
		//- заменить перенос строки на \n
		public function ImportBDU($query)
		{
			$table = $this -> db -> multi($query);
			return $table;
		}
	}
?>