<?php
	namespace limb\app\modules\aj;
	use limb\app\modules\commentary as Com;


	require "../../../autoload.php";

	class Jon
	{
		private $nameAj;
		private $ex = ["nameAj"];#исключения, которые не проходят через htmlspecialchars
		private $post_nohtml;#массив post пропуущенный через htmls...

		function __construct($post)
		{
			$this -> nameAj = htmlspecialchars($post["nameAj"]);


			foreach ($post as $key => $value) {
				$res = $this -> parseHtml($key, $value);
				$this -> post_nohtml[$res[0]] = $res[1];
			}
			$result = $this -> {$this -> nameAj}();

		}

		private function parseHtml($key, $value)
		{
			for($i = 0; $i < count($this -> ex); $i++)
			{
				if($key == $this -> ex[$i] )
				{
					return [$key, $value];
				}
			}
			$key = htmlspecialchars($key, ENT_QUOTES);
			$value = htmlspecialchars($value, ENT_QUOTES);
			return [$key, $value];
		}

		########################################################################
		##########################FUNCTION######################################
		########################################################################
		public function loadCommentary()
		{
			$ct = new Com\CommentaryTable();

			$result = $ct -> LoadCommentary($this -> post_nohtml["id"]);

			echo $result;
		}

		########################################################################
		########################################################################
		########################################################################
	}

	#объект класса Jon создается лишь в том случае, когда строка post["nameAj"] соответствует названию функции в классе Jon
	if(isset($_POST["nameAj"]))
	{
		$res = method_exists(Jon::class, $_POST["nameAj"]);
		if($res === true)
		{
			$jon = new Jon($_POST);
		}
	}

?>