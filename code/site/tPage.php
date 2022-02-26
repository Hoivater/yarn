<?php

namespace limb\code\site;

	trait tPage{
		public $html;//основная заготовка сайта
		public $page;//результат работы класса
		public $tmplt  = ["%site%", "%lang_ru%", "%lang_eng%"];//массив для замены staticPage
	}
?>