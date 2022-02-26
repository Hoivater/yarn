<?php
	namespace limb\app\base\control;
	use limb\app\base as Base;
	use limb\app\modules\auth as Auth;
	/**
	 * Класс который задействуется в различных проверках
	 *
	 */
	class Control
	{

		function __construct()
		{
			// code...
		}

		public static function NameUser()
		{
			$result = false;
			$au = new Auth\AuthPage(false);
			if(isset($_COOKIE['code']) && isset($_COOKIE['email']))
			{
				$code = htmlspecialchars($_COOKIE['code']);
				$email = htmlspecialchars($_COOKIE['email']);
				$auth = $au -> AuthControl($code, $email);
				$result = $auth[1];
			}
			return $result;//auth[1] - имя пользователя
		}


		public static function IsAuth()
		{
			$result = false;
			$au = new Auth\AuthPage(false);
			if(isset($_COOKIE['code']) && isset($_COOKIE['email']))
			{
				$code = htmlspecialchars($_COOKIE['code']);
				$email = htmlspecialchars($_COOKIE['email']);
				$auth = $au -> AuthControl($code, $email);
				$result = $auth[0];
			}
			return $result;//auth[1] - имя пользователя
		}
		
		public static function IsRules()
		{
			$result = false;
			$au = new Auth\AuthPage(false);
			if(isset($_COOKIE['code']) && isset($_COOKIE['email']))
			{
				$code = htmlspecialchars($_COOKIE['code']);
				$email = htmlspecialchars($_COOKIE['email']);
				$auth = $au -> AuthControl($code, $email);
				if($auth[0] === true) $result = $auth[2];
				else
					$result = false;
			}
			return $result;//auth[1] - имя пользователя auth[2] - допуск
		}

		// проверка на корректное соединение с бд
		public function ConnectDB()
		{
			$errors = "";
			// сразу проверяем на заполненность db.ini
			$ini = parse_ini_file('app/base/db.ini');

			foreach($ini as $key => $value)
			{
				if($key == 'host')
				{
					if(!$value)
					{
						$errors .= "<br />Не заполнено имя Хоста;";
					}
				}
				elseif($key == 'name_db')
				{
					if(!$value)
					{
						$errors .= "<br />Не заполнено имя название базы данных;";
					}
				}
				elseif($key == 'user')
				{
					if(!$value)
					{
						$errors .= "<br />Не заполнено имя пользователя базы данных;";
					}
				}
			}
			if($errors == "")
			{
				// затем проверяем на возможность фактического подключения
				$connects = Base\DataBase::getDB();
				$result_connect = $connects -> connect();
				return $result_connect;
			}
			else
			{
				return $errors;
			}
		}

		#проверка регистрационных данных
		public static function RegistrationSecurity($post)
		{
			return true;
		}

		public static function SecurityFields($fields)
		{
			$result = "";
			$new_fields = self::newArraySF($fields);
			
			$ini = parse_ini_file(__DIR__."/../../../datastore/command/fields_control.ini");
			
			$value = $new_fields[1];//varchar
			$key = $new_fields[0];//no
			

			for ($i=0; $i <= count($value)-1; $i++) { 

				if(isset($ini[$value[$i]]))
				{
					if($key[$i] == $ini[$value[$i]])
					{
						if($result == "" || $result === true)
							{
								$result = true;

							}
					}
					else
					{
						$result .= "Не совпадение: ".$value[$i]." = ".$ini[$value[$i]]."<br />";
					}
				}
				else
				{
					$result .= "Не найден заданный столбцу параметр.";
				}
			}
			

			if($result === true)
			{
				$key_result = [];
				for($i = 0; $i <= count($fields)-1; $i++)
				{
					$key_result[] = str_replace($value[$i], "", $fields[$i]);
				}
			}
			else
			{
				$key_result = "";
			}
			return [$result, $value, $key_result];
		}

		private static function newArraySF($fields)
		{
			$na = [];
			$nna = [];
			foreach ($fields as $key) {
				if(strstr($key, '(', true) === false){
					$na[] = 'no';
					$nna[] = $key;
				}
				else
				{
					$na[] = 'yes';
					$nna[] = strstr($key, '(', true);
				}
			}

			return [$na, $nna];
		}

	}
?>