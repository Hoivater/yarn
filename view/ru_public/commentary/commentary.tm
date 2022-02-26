^start_repeat_commentary^
%id% %name% %date_creation% %text% %code% %answer% `
<div class="commentary">
	<div class="infoblock">
		<p class="name">%name%</p>
		<p class="date">%date_creation%</p>

		<input type = "text" value = "%name%" id = "name_n%code%" style = "display:none;">
	</div>

	<p class="text">%text%</p>
	<div class="clean"></div>
	<button class="click_answ btn btn-success" id = "n%code%">Ответить</button>
	%answer%
</div>
<div class="two" id = "two_%code%">

</div>
^end_repeat_commentary^