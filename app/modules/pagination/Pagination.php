<?php 
	namespace limb\app\modules\pagination;
	use limb\app\base\control as Control;
	/**
	 * 
	 */
	class Pagination
	{
		private $tmplt = ["%active%", "%link%", "%num_page%"];
		private $tm_all;
		private $tm_li;
		private $result;

		private $count_table;
		private $count_page;
		private $num_page;
		private $count;

		#количество записей в таблице 10, count_page - количество страниц всего 4 , num_page - номер текущей страницы, count количество записей на странице..

		function __construct($count_table, $count_page, $num_page, $count)
		{
			$this -> count_table = $count_table;
			$this -> count_page = $count_page;
			$this -> num_page = $num_page;
			$this -> count = $count;
			$this -> tm_all = file_get_contents(__DIR__."/tem/pagination.tm");
			$this -> tm_li = file_get_contents(__DIR__."/tem/li.tm");
			if($count_page == 1)
			{
				$this -> result = "";
			}
			elseif($count_page == 2 || $count_page == 3 || $count_page == 4)
			{
				$this -> buildTwo();
			}
			elseif($count_page > 4)
			{
				$this -> buildAll();
			}
			else
			{

			}
		}
		public function buildTwo(){
			$array_replace = [];
			for($i = 1; $i <= $this->count_page; $i++)
			{
				if($i == $this -> num_page)
				{
					$array_replace[] = ["active", "href='?page=".$i."'", $i];
				}
				else
					$array_replace[] = ["", "href='?page=".$i."'", $i];
			}
			$res = Control\Necessary::ReplaceRepeat($this -> tmplt, $array_replace, $this->tm_li);

			$result = Control\Necessary::standartReplace('%li_all%', $res, $this->tm_all);



			$this -> result = $result;
		}

		public function buildAll(){
			$array_replace = [];
			if($this -> num_page > 1)
			{
				$href = "href='?page=".($this -> num_page-1)."'";
				$array_replace[] = ["", $href, 'Назад'];
			}
			else
				$array_replace[] = ["disabled", "", 'Назад'];

			for($i = $this -> num_page-1; $i <= $this -> num_page+3; $i++)
			{
				if($i > 0 && $i <= $this -> count_page){
					if($i == $this -> num_page+2)
					{
						$array_replace[] = ["disabled", "", "..."];
					}
					elseif($i == $this -> num_page+3)
					{
						$array_replace[] = ["", "href='?page=".$this -> count_page."'", $this -> count_page];
					}
					elseif($i == $this -> num_page)
					{
						$array_replace[] = ["active", "href='?page=".$i."'", $i];
					}
					else
						$array_replace[] = ["", "href='?page=".$i."'", $i];
				}
			}
			if($this -> num_page <= $this -> count_page-1)
			{
				$href = "href='?page=".($this -> num_page+1)."'";
				$array_replace[] = ["", $href, 'Вперед'];
			}
			else
				$array_replace[] = ["disabled", "", 'Вперед'];

			$res = Control\Necessary::ReplaceRepeat($this -> tmplt, $array_replace, $this->tm_li);

			$result = Control\Necessary::standartReplace('%li_all%', $res, $this->tm_all);

			$this -> result = $result;

		}

		public function getActiveModule()
		{
			return $this -> result;
		}
	}
?>