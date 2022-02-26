<h4 class="text-start">Таблица: %name_db% <a href = '/delete_table/%name_db%'>Удалить таблицу</a></h4>
<div style = 'border:1px solid #DEE2E6; padding:10px; border-radius:10px;'>
<h5>Заполнить таблицу, количество ячеек: 
	<form name = 'newfields' action="/app/form/FormRoute.php" method = 'post'>
		<input type = 'text' value = '%name_db%' name = 'name_db' class='hidden'> <input type = 'number' style='width:70px;border-radius:5px;border:1px solid #DEE2E6;' class = 'p-1 mt-1' name = 'count_fields'> <button type = 'submit' name = 'nameForm' value = 'newFields' class = 'btn btn-success pt-1'>Добавить произвольные поля</button></form></h5>
		<p>Настройка заполнения таблиц производится в code\site\%name_db%Table.php</p></div>
<div class="table_template">
<table class="table table-bordered">
	<tr>
		%tr1%
	</tr>
	<tr>
		%tr2%
	</tr>
	<tr>
		<td colspan = "%count_array%"><h5 class = 'para'>Код для DTBS <a href='' class="copy" id = "code_dtbs">Копировать</a></h5></td>
	</tr>
	<tr>
		<td colspan = "%count_array%">
			<p>При изменении таблицы меняются классы, рекомендуется их переименовать, либо перенести в другое место перед изменением таблицы.</p>
			<form name = 'redaction_table' action = '/app/form/FormRoute.php' method = 'post'>
				<textarea class="commandline" rows="10" id = 't_code_dtbs' name = 'code_user'>%code_dtbs%</textarea>
				<input type="text" class="hidden" value = "%name_db%" name = "name_table">
			<button type = 'submit' value = 'redTable' name = 'nameForm' class='m-3 btn btn-primary'>Сохранить внесенные изменения</button>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan = "%count_array%"><h5 class = 'para'>Для шаблонов <a href = ""  class="copy" id = "code_tmplt">Копировать</a></h5></td>
	</tr>
	<tr>
		<td colspan = "%count_array%"><textarea class="commandline" id = 't_code_tmplt'>%code_tmplt%</textarea></td>
	</tr>
	<tr>
		<td colspan = "%count_array%"><h5 class = 'para'>Для кода <a href = ""  class="copy" id = "code_php">Копировать</a></h5></td>
	</tr>
	<tr>
		<td colspan = "%count_array%"><textarea class="commandline" id = 't_code_php'>%code_for_code_tmplt%</textarea></td>
	</tr>
</table>
</div>