<form name = "connect" action = "/app/form/FormRoute.php" method = "post">

	 <div class="mb-3">
	    <label for="hostDB" class="form-label">Введите host</label>
	    <input type="text" class="form-control" id="hostDB" name = "host" aria-describedby="hostDbInfo" value = "%host%">
	    <div id="hostDbInfo" class="form-text">Возможно: localhost</div>
	  </div>
	 <div class="mb-3">
	    <label for="nameDB" class="form-label">Имя базы данных</label>
	    <input type="text" class="form-control" id="nameDB" name="name_db"  aria-describedby="nameDbInfo" value = "%name_db%">
	    <div id="nameDbInfo" class="form-text">Введите имя базы данных</div>
	  </div>
	 <div class="mb-3">
	    <label for="userDB" class="form-label">Пользователь базы данных</label>
	    <input type="text" class="form-control" id="userDB" name="user" aria-describedby="userDbInfo" value = "%user%">
	    <div id="userDbInfo" class="form-text">Введите имя пользователя(root)</div>
	  </div>
	  <div class="mb-3">
	    <label for="exampleInputPassword1" class="form-label">Пароль пользователя БД</label>
	    <input type="password" class="form-control" name="password" id="exampleInputPassword1" value = "%password%">
	  </div>

	   <div class="mb-3">
	    <label for="fornameDB" class="form-label">Введите приставку для имен таблиц</label>
	    <input type="text" class="form-control" id="fornameDB" name = "fornameDB" aria-describedby="fornameDbInfo" value = "%fornameDB%">
	    <div id="fornameDbInfo" class="form-text">Необходимо для безопасности, в дальнейшем неизменяемо (Например: my32423i_)</div>
	  </div>
	  <p>- Кодировка, принимаемая по умолчанию: utf8_general_ci</p>
	  <p>- По умолчанию создаются классы для работы с таблицами.</p>

	  <button type="submit" class="btn btn-primary" name = "nameForm" value = "connect">Подключиться и сохранить настройки</button>
</form>