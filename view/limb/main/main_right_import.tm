<div class="card-body">
<h5 class="text-center">Импорт своей базы данных</h5>
<form action='/app/form/FormRoute.php' method ='post'>
<textarea class="form-control importSql" rows="10" name = "file_sql" placeholder = 'Вставьте сюда ваш SQL'></textarea><br />
	<button type="submit" name = 'nameForm' value = 'importBD' class="btn btn-primary m-3">Импортировать</button>
</form>

</div>