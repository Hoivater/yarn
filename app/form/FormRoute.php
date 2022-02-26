<?php


namespace limb\app\form;

use limb\app\worker as Worker;
use limb\app\base\control as Control;
use limb\app\modules\auth as Auth;

require "../../autoload.php";

	if(!isset($_SESSION))
	{
		session_start();
	}

	class FormRoute extends FormBase
	{

		private $result;
		private $csrf;

		function __construct($name_form, $data)
		{
			if(isset($_SESSION["csrfv"])) $this -> csrf = $_SESSION["csrfv"];
			
			parent::__construct($data);

			if($this -> controlHtml == 2){
				if(isset($data['code'])){
					$csrf_site = $this -> data['code'];
					if($this -> csrf == $csrf_site)
					{
						$this -> routeF($name_form);
					}
				}
			}
			else
			{
				$this -> routeF($name_form);
			}

			
		}

		private function routeF($name_form)
		{
			#################### ######### ####  #######  ####      ################################
			#################### ######### #### # ##### # #### ##### ###############################
			#################### ######### #### ## ### ## #### #   #################################
			#################### ######### #### ### # ### #### ###### ##############################
			####################      #### #### #### #### ####       ###############################
			
			if($name_form == "connect")
			{
				
				$this -> result = $this -> tab_newIni();// перезаписывает db.ini возвращает либо true Либо false
				
			}
			elseif($name_form == 'importBD')
			{
				$this -> result = $this -> ImportBD();
			}
			elseif($name_form == 'newFields')
			{
				$this -> newRandomFields();
			}
			elseif($name_form == 'newTable')
			{
				$worker_i = new Worker\LogicTable($this -> data);
				$this -> result .= $worker_i -> CreateTable();//создаем таблицу

				if( $worker_i -> getResult() === true)
				{
					$parametr = $worker_i -> getParametr();//получаем массив данных [table_name, tmplt, replace]
					$masterClass = new Worker\MasterClass($parametr[0], $parametr[1], $parametr[2], $parametr[3], $parametr[4]);
					$this -> result .= $masterClass -> addTablePageCl();#возвращает успех или нет
				}
			}
			elseif($name_form == 'redTable')
			{
				$worker_i = new Worker\LogicTable($this -> data);
				

				Control\Necessary::delete_table($this -> data["name_table"]);
				
				$this -> result .= $worker_i -> CreateTable();//создаем таблицу
				
				if( $worker_i -> getResult() === true)
				{
					$parametr = $worker_i -> getParametr();//получаем массив данных [table_name, tmplt, replace]
					$masterClass = new Worker\MasterClass($parametr[0], $parametr[1], $parametr[2], $parametr[3], $parametr[4]);
					$this -> result .= $masterClass -> addTablePageCl();#возвращает успех или нет
				}
			}
			elseif($name_form == 'reg')
			{
				$reg = new Auth\AuthPage(false);
				$this -> result = $reg -> newUser($this -> data);
			}
			elseif($name_form == 'auth')
			{
				$reg = new Auth\AuthPage(false);
				$this -> result = $reg -> AuthUser($this -> data);
			}
			elseif($name_form == 'newpassword')
			{
				$reg = new Auth\AuthPage(false);
				$this -> result = $reg -> NewPasswordOnPost($this -> data);
			}
			#################### ######### ####  #######  ####000000################################
			#################### ######### #### # ##### # #### #####0###############################
			#################### ######### #### ## ### ## #### #   #################################
			#################### ######### #### ### # ### #### ###### ##############################
			####################      #### #### #### #### ####       ###############################
		
		}


		public function result()
		{
			return $this -> result;
		}
	}

	
	if(isset($_POST["nameForm"]))
	{
		if(count($_FILES) !== 0)
		{
			$ff = new FormFile($_FILES);
			$names = $ff -> getNames();
			$post_files = array_merge($names, $_POST);
		}
		else{
			$post_files = $_POST;
		}
		$fRoute = new FormRoute($_POST["nameForm"], $post_files);//вход данных и их обработка

		$_SESSION["message"] = $fRoute -> result();

		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit();
	}
?>