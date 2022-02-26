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


    <script src="https://kit.fontawesome.com/de9f65bcf0.js" crossorigin="anonymous"></script>
		<script src="/style/limb/js/copy.js"></script>
		<script src="/style/limb/js/redaction.js"></script>
    <link rel="shortcut icon" href="/favicon.svg" type="image/x-icon">
	<title>LIMB</title>

</head>
<body>
	<div class="container-fluid main_navi">
		<div class="row">
			<div class="col-12">
				<h3 class="text-center p-3" id = "header">LIMB %version% <small><a href = '%link%' target="_blank">github</a></small></h3>
			</div>
		</div>
		<div class="row mt-2 pb-2 pt-2 mb-2 st">
			%menu_left%
			%menu_right%
		</div>
		<div class="main container-fluid">
		<div class="row">
			<div class="col-md-3 left_panel p-2">
				%main_left%
			</div>
			<div class="col-md-9 right_panel">

				<div class="card mb-2 mt-2 p-1">
					%connect%
					%message%
					%main_right%
				</div>
			</div>
		</div>
	</div>
	</div>


</body>
<script>
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
return new bootstrap.Tooltip(tooltipTriggerEl)
});
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
});
</script>
</html>
