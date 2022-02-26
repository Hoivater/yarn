<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Jquery -->
    <script src="/style/limb/js/jq_v_3_6_0.js"></script>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="/style/limb/css/dtbs.css">

    <link rel="shortcut icon" href="/favicon.svg" type="image/x-icon">
	<title>LIMB Страница авторизации</title>

</head>
<body>
	
		<div class="main container-fluid mt-3">
			<div class="row">
					<div class="table_auth">
						<h3 class="text-center mt-1">Авторизация LIMB</h3>
						<form name = "auth" action = "%name_site%/app/form/FormRoute.php" method = "post">
							%csrf%
							<label for="email">Ваш Email:</label>
							<input type = "email" id = "email" name = "email" class="form-control mt-1" />
							<label for="password">Введите пароль:</label>
							<input type = "password" id = "password" name = "password" class="form-control mt-1" />
							<div class="form-check mt-2">
							  <input class="form-check-input" type="checkbox" name = "memory" value="2678400" id="defaultCheck1">
							  <label class="form-check-label" for="defaultCheck1">
							    Запомнить
							  </label>
							</div>
							<button type="submit" class = "btn btn-success mt-2" id = "submit" name = "nameForm" value = "auth">Войти</button>
							<a href = "/auth/newpassword" class="btn btn-success mt-2">Восстановить пароль</a> <a href = "/" class="btn btn-success mt-2 ml-2">На главную</a>
							<div class="message m-3">%message%</div>
						</form>
					</div>
			</div>
	</div>


</body>
<script>
$('#submit').click(function(){
	var errors = 'Исправить:<br />';
	var pass = $("input#pass1").val();
	var email = $("input#email").val();
	if(pass < 4)
		errors += "Пароль должен быть длиннее четырех символов.<br />";
	if(email.length < 3)
		errors += "Email не может быть короче 3 символов. <br />";
	if(errors != 'Исправить:<br />')
		{
			$(".message").html(errors);
			return false;
		}
	else
	{
		return true;
	}

});
</script>
</html>
