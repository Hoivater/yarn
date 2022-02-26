<?php
namespace limb\app\modules\auth;
use limb\app\base as Base;

/**
 *
 */
class AuthTable
{
	public $tmpltUser = ['%id%', '%name%', '%email%', '%password%', '%access_user%', '%code_email%', '%code%', '%date%'];//массив из таблиц
	public $resultUser;//финишная сборка для шаблона для возврата в _Page
	public $name;//имя таблицы которое используется по умолчанию
	public $table_key = "`id`, `name`, `email`, `password`, `access_user`, `code_email`, `code`, `date`";
	#public $replace = [$id, $name, $email, $password, $access_user, $code_email, $code, $date];


	public $auth_tm;
	public $reg_tm;

	public function __construct()
	{
		$ini = parse_ini_file(__DIR__."/../../../app/base/db.ini");
		$this -> name = $ini["fornameDB"]."user";
		$this -> auth_tm = file_get_contents(__DIR__."/tem/auth.tm");	
		$this -> reg_tm = file_get_contents(__DIR__."/tem/reg.tm");	
		$this -> password_page_tm = file_get_contents(__DIR__."/tem/newpassword.tm");	
	}

	protected function RegistrationPage()
	{
		return $this -> reg_tm;
	}
	protected function AuthPage()
	{
		return $this -> auth_tm;
	}

	protected function NewPasswordPage()
	{
		return $this -> password_page_tm;
	}

	protected function addNewUser($post)
	{	
		$email = mb_strtolower(htmlspecialchars($post["email"]));
		$data = $this -> searchFieldCom($email);//проверка на наличие email
		if(isset($data[0]["id"]))
		{	
			return "Выбранный вами email: ".$email." уже зарегистрирован. Возможно вам необходимо <a href = '/refresh_password'>восстановить пароль</a>.";
		}
		$access_user = "user";
		$code_email = "no";
		$code = $this -> generateCodeUser();
		$name = htmlspecialchars($post["name"]);
		$pass = htmlspecialchars($post["pass1"]);
		//[$id, $name, $email, $password, $access_user, $code_email, $code, $date];
		$value = "NULL, '".$name."', '".$email."', '".md5($pass)."', '".$access_user."', '".$code_email."', '".$code."', '".time()."'";

		$result = $this -> insertFieldCom($value);
		if($result)
		{
			return "Регистрация успешно завершена, зайдите под своим email и паролем: <a href = '/auth'>Войти</a>";
		}
		else
			return "При регистрации произошла ошибка";
	}
	protected function writingPasswordPost($post)
	{	
		$email = mb_strtolower(htmlspecialchars($post["email"]));
		$data = $this -> searchFieldCom($email);//проверка на наличие email
		if(isset($data[0]["id"]))
		{	
			#меняем code_email на true - пока не разблокируется на false, пользователя по новому паролю не впускать!!!!!
			#записываем code, для отправки ссылки пользователю
			#отправляем ссылку пользователю
			return "Завершите обновление пароля перейдя по ссылке из письма. Удостовертесь, что письмо не попало в спам";
		}
		else
		{
			return "К сожалению на этот email невозможно поменять пароль оттого, что он незарегистрирован.";
		}
	}
	protected function AuthFirstControl($email, $password)
	{
		$data = $this -> searchFieldCom($email);
		$result = false;
		if(isset($data[0]["id"]))
		{
			if(md5($password) == $data[0]["password"])
				{
					$result = true;
					$code = $data[0]["code"];
					$email = $data[0]["email"];
				}
			else
			{
				$result = "Проверьте корректность ввода пароля.";
				$code = "";
				$email = "";		
			}
		}
		else
		{
			$result = "Такого email: ".$email." не найдено.";
			$code = "";
			$email = "";
		}

		return [$result, $code, $email];
		
	}
	public function AuthControl($code, $email)
	{
		$data = $this -> searchFieldCom($email);
		$result = false;
		if(isset($data[0]["id"]))
		{
			if(md5($data[0]["code"]) == $code)
				$result = true;
			$name = $data[0]["name"];
			$access_user = $data[0]["access_user"];
		}
		else
		{
			$name = "";
			$access_user = "";
		}

		return [$result, $name, $access_user];
	}
	public function AuthRules($code, $email)
	{
		$data = $this -> searchFieldCom($email);
		$result = false;
		if(isset($data[0]["id"]))
		{
			if(md5($data[0]["code"]) == $code)
				$result = true;
		}

		return [$result, $data[0]["access_user"]];
	}
	private function generateCodeUser()
	{
		$arrayTranscription = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "A", "b", "B", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "v", "w", "x", "y", "z", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		$num = count($arrayTranscription)-1;
		$code = "";
		for ($i=0; $i <= 32 ; $i++) { 
			$code .= $arrayTranscription[random_int(0, $num)];
		}
		return $code;
	}
	//метод достаюший все поля из таблицы
	private function searchFieldCom($email)
	{
		$si = new Base\SearchInq($this -> name);
		$si -> selectQ(); 
		$si ->  whereQ("email", $email, "=");
		$result = $si -> resQ();

		return $result;
	}
	#метод добавляющий данные в таблицу, value - строка следующего вида
	#NULL, '".$this -> title."', '".$this -> keywords."', '".$this -> description."'
	protected function insertFieldCom($value)
	{
		$ri = new Base\RedactionInq($this -> name, $this -> table_key);
		$result = $ri -> insert($value);
		return $result;
	}
}

?>