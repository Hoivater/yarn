<?php
namespace limb\app\web;

use limb\code\limb as Limb;
use limb\code\site as LimbSite;

use limb\app\base\control as Control;

use limb\app\modules as Modules;

class Route
{
	private $html;
	private $route_array;//содержит массив имен пути
	private $get;//содержит массив полученный методом GET
	private $auth;//содержит авторизацию
	private $forms;

	function __construct($requestUri)
	{
		$requestUri = strtok($requestUri, '?');
		$this -> auth = Control\Control::IsRules();
		
		#возможно поменять на возможность считывания из базы данных, если на сайте присутствует регистрация
		
		if(!isset($_COOKIE["language"]))
			setcookie("language", "ru_", time() + 172800, '/');

		if(isset($_GET))
		{
			$this -> get = $_GET;
		}
		$arr = explode('/', $requestUri);
		$new_arr = array_diff($arr, array(''));
		$this -> route_array = array_values($new_arr);
		
		if(!isset($_SESSION))
			{
				session_start();
			}

		if(isset($_SESSION["numpage"])){
			unset($_SESSION["numpage"]);
			if(isset($this -> get["page"]))
				$_SESSION['numpage'] = $this -> get["page"];
			else{
				$_SESSION['numpage'] = 1;
			}
		}

		else
		{
			if(isset($this -> get["page"]))
				{
					$_SESSION['numpage'] = $this -> get["page"];
				}
			else{
				$_SESSION['numpage'] = 1;
			}
		}
		$this -> forms = false;
		// $this -> routeLimb();					#Limb работа с таблицами#######################
		$this -> routePublicLimb(); 			#ваш проект####################################
	}

	private function routePublicLimb()
	{
		$route_arr = $this -> route_array;

		if(count($route_arr) >= 1)
		{
				if($route_arr[0] == "article")
				{
					$this -> forms = true;
					if(isset($route_arr[1]))
					{
						$html = new LimbSite\ArticlePage();
						$html -> Page($route_arr[1]);
					}

				}

			#модуль языковой########################################################################
			#################### ######### ####  #######  ####      ################################
			#################### ######### #### # ##### # #### ##### ###############################
			#################### ######### #### ## ### ## #### #   #################################
			#################### ######### #### ### # ### #### ###### ##############################
			####################      #### #### #### #### ####       ###############################
			
				elseif($route_arr[0] == "lang")
				{
					$this -> forms = true;
					if(isset($route_arr[1]))
					{
						$ini = parse_ini_file(__DIR__."/../../setting.ini");
						$lang_group = explode(", ", $ini["language"]);
						if($this -> langGroup($lang_group, $route_arr[1]))
						{
							setcookie("language", $route_arr[1], time() + 172800, '/');
							header("Location: ".$_SERVER["HTTP_REFERER"]);
							exit();
						}
						else
						{
							header("Location: ".$_SERVER["HTTP_REFERER"]);
							exit();
						}
					}

				}


			#модуль регистрации#####################################################################
			#################### ######### ####  #######  ####      ################################
			#################### ######### #### # ##### # #### ##### ###############################
			#################### ######### #### ## ### ## #### #   #################################
			#################### ######### #### ### # ### #### ###### ##############################
			####################      #### #### #### #### ####       ###############################
			
				elseif($route_arr[0] == "destructauth")
				{
					setcookie("code", '', -1);
					setcookie("email", '', -1);
					session_destroy();
					header("Location: ".$_SERVER['HTTP_REFERER']);
					exit();
				}
				elseif($route_arr[0] == "registration")
				{
					$this -> forms = true;
					$html = new Modules\auth\AuthPage(false);
					$html -> Registration();				
				}
				elseif($route_arr[0] == "auth")
				{
					$this -> forms = true;
					if(isset($route_arr[1])){
						if($route_arr[1] == "newpassword"){
							$html = new Modules\auth\AuthPage(false);
							$html -> NewPassword();	
						}
						else
						{
							$html = new Modules\auth\AuthPage(false);
							$html -> Auth();					
						}
					}
					else
					{
						$html = new Modules\auth\AuthPage(false);
						$html -> Auth();					
					}	
				}
			#модуль регистрации
			#################### ######### ####  #######  ####      ################################
			#################### ######### #### # ##### # #### ##### ###############################
			#################### ######### #### ## ### ## #### #   #################################
			#################### ######### #### ### # ### #### ###### ##############################
			####################      #### #### #### #### ####       ###############################
			

				else
				{

					$ini = parse_ini_file(__DIR__."/../../setting.ini");
					header("Location: ".$ini["name_site"]."/view/error/404.php");
				}
		}
		else{
			$this -> forms = true;
			$html = new LimbSite\MainPage();
		}

		$this -> html = $html -> page;
	}
	public function langGroup($arr, $key)
	{
		$result = false;
		for($i = 0; $i < count($arr); $i++)
		{
			if($arr[$i] == $key)
				$result = true;
		}
		return $result;
	}
	private function routeLimb()
	{
		$route_arr = $this -> route_array;

		if(count($route_arr) >= 1)
		{
			if($route_arr[0] == "import")
			{
				$html = new Limb\ImportPage();
			}
			elseif($route_arr[0] == "export")
			{
				$html = new Limb\ExportPage();
			}
			elseif($route_arr[0] == "template")
			{
				$html = new Limb\TemplatePage();
			}
			elseif($route_arr[0] == "setting")
			{
				$html = new Limb\SettingPage();
			}
			elseif($route_arr[0] == "table")
			{
				if(isset($route_arr[1])) $html = new Limb\TablePage($route_arr[1]);//открываем заданную
				else $html = new Limb\TablePage(0);//открываем первую статью
			}
			elseif($route_arr[0] == "delete_table")
			{
				if(isset($route_arr[1]))
				{
					Control\Necessary::delete_table($route_arr[1]);
				}
				header('Location: '.$_SERVER['HTTP_REFERER']);//возвращаем наместо
				exit();
			}
			else
			{
				if(!isset($_SESSION))
				{
					session_start();
				}
				$_SESSION['message'] = "Страница не найдена";
				if(isset($route_arr[0])) $html = new Limb\MainPage($route_arr[0]);//открываем заданную
				else $html = new Limb\MainPage(0);//открываем первую статью
			}
		}
		else
		{
			if(isset($route_arr[1])) $html = new Limb\MainPage($route_arr[1]);//открываем заданную
			else $html = new Limb\MainPage(0);//открываем первую статью
		}
		$this -> html = $html -> page;
	}


	public function getHtml()
	{
		return $this -> html;
	}
	public function getNamePage()
	{
		if(isset($this -> route_array[0])) $result = $this -> route_array[0];
		else $result = "main";
		return $result;
	}
	public function getForm()
	{
		return $this -> forms;
	}
	public function getRouteArray()
	{
		return $this -> route_array;
	}
}

?>