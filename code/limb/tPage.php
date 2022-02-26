<?php
	namespace limb\code\limb;
	
	trait tPage{
		public $html;//заготовка
		public $page;//результат работы класса
		public $tmplt  = ["%main_left%", "%message%", "%main_right%"];//массив для замены
	}
?>