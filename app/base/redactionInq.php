<?php
namespace limb\app\base;


	class RedactionInq extends DataBase{
		private $db;//объект базы данных
		private $query;
		private $name;
		private $key;

		public function __construct($name, $key){
			$this -> db = DataBase::getDB();
			$this -> name = $name;
			$this -> key = $key;
		}

		#обновление поля
		public function update($key_red, $value_red, $key, $value){
			$query = "UPDATE `".$this -> name."` SET `".$key_red."` = '".$value_red."' WHERE ".$key."='".$value."'";
			$table = $this -> db -> query($query);
			return $table;
		}
		#вставка строки
		public function insert($value_string)
		{
			$query = "INSERT INTO `".$this -> name."` (".$this -> key.") VALUES (".$value_string.")";

			$result = $this -> db -> query($query);
			return $result;
		}
		//`id`, `title`, `keywords`, `description` - key
		//NULL, '".$this -> title."', '".$this -> keywords."', '".$this -> description."' - value

		#удаление строки
		public function delete($key, $value){//удаление строки из таблицы $table_name где $name_column = $name_cat
			$query = "DELETE FROM `".$this -> name."` WHERE ".$key."='".$value."'";
			$table = $this -> db -> query($query);
			return $table;
		}
		#копирование строки из текущей таблицы в таблицу table_old
		public function copy($table_old, $key, $value){
			$query = "INSERT INTO `".$this -> name."` SELECT * FROM `".$table_old."` WHERE ".$key."='".$value."'";
			$table = $this -> db -> query($query);
			return $table;
		}

	}
?>