<div class="card-body">
<h4 class="text-center mb-3">Cоздание таблицы </h4>
<form name = 'newTable' action = '/app/form/FormRoute.php' method = 'post'>
<textarea class="commandline" name = 'code_user' rows="10" id = "comLine">%standart%</textarea>
	<div class="btn-group" role="group" aria-label="Basic outlined example">
	<a type="button" class="comInt btn btn-outline-primary">INT</a>
	<a type="button" class="comFloat btn btn-outline-primary">FLOAT</a>
	<a type="button" class="comDouble btn btn-outline-primary">DOUBLE</a>
	<a type="button" class="comBoolean btn btn-outline-primary">BOOLEAN</a>
	<a type="button" class="comChar btn btn-outline-primary">CHAR</a>
	<a type="button" class="comVarchar btn btn-outline-primary">VARCHAR</a>
	<a type="button" class="comText btn btn-outline-primary">TEXT</a>
	</div><br />
	<button type="submit" class="btn btn-primary m-3" name = 'nameForm' value = 'newTable'>Создать таблицу</button>
</form>

</div>