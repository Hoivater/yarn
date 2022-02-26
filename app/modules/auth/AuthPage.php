<?php
namespace limb\app\modules\auth;
use limb\app\base\control as Cont;
use limb\app\form as Form;


/**
 *
 */
class AuthPage extends AuthTable
{
	public $page;
	public $html;//основная заготовка сайта
	
	public $email;

	function __construct($email)
	{
		parent::__construct();
		
		$this -> email = $email;	

		#сборка регистрации без подтверждения  email = false
		#сборка регистрации с подтверждением по электронной почте email =  true
	}

	public function newUser($post)
	{
		$control = Cont\Control::RegistrationSecurity($post);
		if($control === true)
		{
			$control = $this -> addNewUser($post);
		}
		if(!isset($_SESSION))
		{
			session_start();
		}
		$_SESSION['message'] = $control;

		header('Location: /registration');
		exit();
	}
	public function NewPasswordOnPost($post)
	{
		$control = Cont\Control::RegistrationSecurity($post);
		if($control === true)
		{
			$control = $this -> writingPasswordPost($post);
		}
		if(!isset($_SESSION))
			{
				session_start();
			}
		$_SESSION['message'] = $control;
		header('Location: /auth');
		exit();
	}
	public function AuthUser($post)
	{
		session_start();
		$email = mb_strtolower(htmlspecialchars($post["email"]));
		$password = htmlspecialchars($post["password"]);
		if(isset($post["memory"]))	
			$memory = (int)htmlspecialchars($post["memory"]);
		else
			$memory = 86400;

		$auth = $this -> AuthFirstControl($email, $password);//первоначальная проверка на верность ввода пароля и майла
		//["true", code, email]
		if($auth[0] === true)
		{
			
			setcookie("code", md5($auth[1]), time()+$memory, '/');
			setcookie("email", $auth[2], time()+$memory, '/');
			$_SESSION["message"] = "Вы авторизированы!";
			header('Location: /');
			exit();
			
		}
		else
		{
			$_SESSION["message"] = $auth[0];
			header('Location: /auth');
			exit();
		}

	}
	
	public function Registration()
	{

		if($this -> email == false)
		{
			if(isset($_SESSION["message"])){
				$message = $_SESSION["message"];
				unset($_SESSION["message"]);	
			}
			else
				$message = "";
			$result = Cont\Necessary::standartReplace(["%message%"], [$message], $this -> RegistrationPage());
			$this -> page = $result;
		}
	}


	public function Auth()
	{
		if(isset($_SESSION["message"])){
				$message = $_SESSION["message"];
				unset($_SESSION["message"]);	
		}
		else
			$message = "";
		$result = Cont\Necessary::standartReplace(["%message%"], [$message], $this -> AuthPage());
		$this -> page = $result;

	}
	public function NewPassword()
	{
		if(isset($_SESSION["message"])){
				$message = $_SESSION["message"];
				unset($_SESSION["message"]);	
		}
		else
			$message = "";
		$result = Cont\Necessary::standartReplace(["%message%"], [$message], $this -> NewPasswordPage());
		$this -> page = $result;
	}
}

?>