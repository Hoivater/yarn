<?php
namespace limb\app\base\control;
use limb\app\base as Base;

	// Класс для генерации различных типов значений
	// Набор статических классов

	class Generate
	{
		public static function this_idgenerate()
		{
			return 'NULL';
		}
		public static function this_dategenerate()
		{
			$year = 32140800;
			$clock_start = time()-$year;#год назад
			return rand($clock_start, time());
		}


		#максимальная длина int - 11, генерируется от 3 до 8; в автоматическом режиме
		public static function intgenerate($num = 0)
		{
			if($num == 0)
			{
				$min = 10;
				$count = rand(3, 9);
				$max = pow(10, $count);
			}
			else
			{
				$min = pow(10, $num-2);
				$max = $min + $min*9-1;
			}
			return rand($min, $max);
		}
		#генерируется заданная длинна из слов
		public static function varchargenerate($num)
		{
			$min = $num - 20;
			$max = $num - 10;
			$dictionary = file_get_contents(__DIR__."/../../../datastore/word/russian.tm");
			$dictionary_arr = explode(", ", $dictionary);
			shuffle($dictionary_arr);
			$result = "";
			for($i = 0; $i <= count($dictionary_arr)-1; $i++)
			{
				$result .= " ".$dictionary_arr[$i];
				$length = mb_strlen($result);
				if($length >= $min && $length <= $max)
				{
					break;
				}
				if($length > $num)
				{
					$result = mb_substr($result, 0, $num-4);
					break;
				}
			}

			return  self::mb_ucfirst(trim($result));
		}
		public static function mb_ucfirst($text) {
		    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
		}
		#генерируется текст размером от 200 до 900 символов из слов
		public static function textgenerate($num = "no")
		{
			if($num == "no")
			{
				$num = rand(200, 900);
			}
			$min = $num - 20;
			$max = $num + 20;
			$dictionary = file_get_contents(__DIR__."/../../../datastore/word/russian.tm");
			$dictionary_arr = explode(", ", $dictionary);
			shuffle($dictionary_arr);
			$result = "";
			for($i = 0; $i <= count($dictionary_arr)-1; $i++)
			{
				if($i % 10 == 0 && $i != 0)
				{
					$result .= '. '.self::mb_ucfirst($dictionary_arr[$i]);

				}
				else
				{
					$result .= " ".$dictionary_arr[$i];
				}
				$length = mb_strlen($result);

				if($length >= $min && $length <= $max)
				{
					break;
				}
				if($length >= $max)
				{
					$result = mb_substr($result, 0, $max-4);
					break;
				}
			}
			return  self::mb_ucfirst(trim($result));
		}
		#генерация числа 32.3243
		public static function floatgenerate()
		{
			$start = 0;
			$end = 9999999;
			$s = rand($start, $end);
			$result = $s / 10000;
			return $result;
		}
		#false || true
		public static function booleangenerate()
		{
			$ar = [1, 0];
			shuffle($ar);
			$result = $ar[0];
			return $result;
		}



		#текст через запятую/ специальное заполнение
		public static function tagsgenerate()
		{

			return $result;
		}

		public static function imagegenerate()
		{
			return "limb.jpg";
		}
		#генерируется ссылка латиницей
		public static function linkgenerate($name) {

        $arrayTranscription = array("а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "sh", "з" => "z", "и" => "i", "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "shh", "ъ" => "", "ы" => "y", "ь" => "", "э" => "a", "ю" => "ua", "я" => "ya", "," => "_", "." => "_", ":" => "_", ";" => "_", ";" => "_", " " => "_", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "a" => "a", "b" => "b", "c" => "c", "d" => "d", "e" => "e", "f" => "f", "g" => "g", "h" => "h", "i" => "i", "j" => "j", "k" => "k", "l" => "l", "m" => "m", "n" => "n", "o" => "o", "p" => "p", "r" => "r", "s" => "s", "t" => "t", "u" => "u", "v" => "v", "w" => "w", "x" => "x", "y" => "y", "z" => "z");
            $newname = "";
            $name = mb_strtolower(trim($name));
            $newarray = preg_split('//u',$name,-1,PREG_SPLIT_NO_EMPTY);//разбиение строки кириллицы в массив символов

            for($i = 0; $i <= count($newarray)-1; $i++)
            {
                foreach($arrayTranscription as $key => $value)
                {
                    if($newarray[$i] == $key)
                    {
                        $newname = $newname.$value;
                        continue;
                    }
                }
            }
        	return $newname;
    	}
		#пароль и оборачивается в md(5) 
		public static function passgenerate()
		{
			$arr = ["11111", "22222", "33333", "44444"];
			shuffle($arr);

			return md5($arr[0]);
		}
		
		public static function emailgenerate()
		{
			$array_end = ["@yndex.ru", "@gmail.com", "@bk.org", "@mail.ru"];
			$dictionary = file_get_contents(__DIR__."/../../../datastore/word/russian.tm");
			$dictionary_arr = explode(", ", $dictionary);
			shuffle($array_end);
			shuffle($dictionary_arr);

			$start = trim(self::linkgenerate($dictionary_arr[0]));

			return $start.$array_end[0];
		}
		public static function codegenerate($num)
		{
			$code = "";
			$arraysymbol = ["q","w","e","r","t","y","u","i","o","p","l","k","j","h","g","f","d","s","a","z","x","c","v","b","n","m","Q","W","E","R","T","Y","U","I","O","P","L","K","J","H","G","F","D","S","A","Z","X","C","V","B","N","M","!","@","%","^","&","*"];
			shuffle($arraysymbol);
			for ($i=0; $i < $num; $i++) { 
				$code .= $arraysymbol[$i];
			}

			return $code;
		}
		public static function nameLatinGenerate($num)
		{
			$code = "";
			$arraysymbol = ["q","w","e","r","t","y","u","i","o","p","l","k","j","h","g","f","d","s","a","z","x","c","v","b","n","m","Q","W","E","R","T","Y","U","I","O","P","L","K","J","H","G","F","D","S","A","Z","X","C","V","B","N","M"];
			shuffle($arraysymbol);
			for ($i=0; $i < $num; $i++) {
				$code .= $arraysymbol[$i];
			}

			return $code;
		}

	}
?>