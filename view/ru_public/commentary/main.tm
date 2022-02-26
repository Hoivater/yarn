<div class="commentary_block">
	%commentary%

%module_paginate%

%startall%
	<div class="addcom">
		<form name="commentarynew" action="%name_site%/app/form/FormRoute.php" method="post">
			%csrf%
			<input type = "text" value = "%id%" name = "id_article" style = "display:none;">
			<input type = "text" value = "0" name = "level" id = "comment_level" style = "display:none;">
			<textarea class= "textarea form-control" id = "comment_text" name = "text"></textarea>
			<button type="submit" class="btn btn-success mt-2" id="submit" name="nameForm" value="commentarynew">Добавить комментарий</button>
		</form>
	</div>
%endall%

%startnoauth%
<h2>Для комментирования зарегистрируйтесь</h2>
%endnoauth%

</div>